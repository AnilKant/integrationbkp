<?php
namespace console\components\walmart;

use Yii;
use frontend\modules\walmart\components\Data;
use frontend\modules\walmart\models\WalmartProduct;
use frontend\modules\walmart\components\Inventory\InventoryUpdate as Inventory;

class InventoryUpdate extends \yii\base\Component
{
    public $dbconnection = null;
    public $inventoryThresholdValue = [];

    public function __construct($dbname = null)
    {
        if (is_null($dbname)) {
            $this->dbconnection = Yii::$app->get(Yii::$app->getBaseDb());
        } else {
            $dbList = Yii::$app->getDbList();
            if (in_array($dbname, $dbList)) {
                $this->dbconnection = Yii::$app->get($dbname);
            }
        }
    }

    public function sqlRecords($query, $type = null, $queryType = null)
    {
        $connection = $this->dbconnection;
        $response = [];
        if ($queryType == "update" || $queryType == "delete" || $queryType == "insert" || ($queryType == null && $type == null)) {
            $response = $connection->createCommand($query)->execute();
        } elseif ($type == 'one') {
            $response = $connection->createCommand($query)->queryOne();
        } elseif ($type == 'column') {
            $response = $connection->createCommand($query)->queryColumn();
        } else {
            $response = $connection->createCommand($query)->queryAll();
        }

        return $response;
    }

    public function execute()
    {
        if ($this->dbconnection) {
            $merchantList = $this->getActiveMerchantList();

            //exclude test shops form this query
            $testShopList = ['14', '359', '6'];
            $merchantList = array_diff($merchantList, $testShopList);
            $allowedMerchantIds = "'" . implode("','", $merchantList) . "'";

            $allowedStatus = "'" . WalmartProduct::PRODUCT_STATUS_UPLOADED . "', '" . WalmartProduct::PRODUCT_STATUS_STAGE . "', '" . WalmartProduct::PRODUCT_STATUS_PROCESSING . "', '" . WalmartProduct::PRODUCT_STATUS_UNPUBLISHED . "', '" . WalmartProduct::PRODUCT_STATUS_READY_TO_PUBLISH . "'";

            $offset = 0;
            $limit = 5000;

            $cron_schedule = $this->sqlRecords("SELECT `cron_data` FROM `walmart_cron_schedule` WHERE `cron_name` = 'sync_inventory'", 'one');
            if (isset($cron_schedule['cron_data']) && !empty(trim($cron_schedule['cron_data']))) {
                $offset = $cron_schedule['cron_data'];
            }

            //add product status filter and get only uploaded products on walmart
            $query = "SELECT `wp`.`merchant_id`, `wp`.`product_id`, IF(`wpv`.`option_id` IS NOT NULL, `wpv`.`option_id`, `jp`.`variant_id`) AS `variant_id`, IF(`wpv`.`option_id` IS NULL, 'simple', 'variant') AS `type`, IF(`wpv`.`option_id` IS NOT NULL, `jpv`.`option_sku`, `jp`.`sku`) AS `sku`, IF((`wpv`.`option_id` IS NOT NULL) AND (`wpv`.`fulfillment_lag_time`), `wpv`.`fulfillment_lag_time`, `wp`.`fulfillment_lag_time`) AS `fulfillment_lag_time`, IF(`wpv`.`option_id` IS NULL, IF(`wp`.`product_qty` IS NULL, `jp`.`qty`, `wp`.`product_qty`), IF(`wpv`.`option_qtys` IS NULL, `jpv`.`option_qty`, `wpv`.`option_qtys`)) AS `qty`, IF(`wpv`.`option_id` IS NOT NULL, `wpv`.`status`, `wp`.`status`) AS `status` FROM `walmart_product` `wp` LEFT JOIN `walmart_product_variants` `wpv` ON `wp`.`product_id`=`wpv`.`product_id` INNER JOIN `jet_product` `jp` ON `jp`.`id`=`wp`.`product_id` LEFT JOIN `jet_product_variants` `jpv` ON `wpv`.`option_id`=`jpv`.`option_id` WHERE `wp`.`merchant_id` IN (" . $allowedMerchantIds . ") AND IF(`wpv`.`option_id` IS NOT NULL, `wpv`.`status`, `wp`.`status`) IN (" . $allowedStatus . ") LIMIT $offset, $limit";

            $products = $this->sqlRecords($query, 'all');

            if (empty($products) && $offset != 0) {
                $offset = 0;

                $query = "SELECT `wp`.`merchant_id`, `wp`.`product_id`, IF(`wpv`.`option_id` IS NOT NULL, `wpv`.`option_id`, `jp`.`variant_id`) AS `variant_id`, IF(`wpv`.`option_id` IS NULL, 'simple', 'variant') AS `type`, IF(`wpv`.`option_id` IS NOT NULL, `jpv`.`option_sku`, `jp`.`sku`) AS `sku`, IF((`wpv`.`option_id` IS NOT NULL) AND (`wpv`.`fulfillment_lag_time`), `wpv`.`fulfillment_lag_time`, `wp`.`fulfillment_lag_time`) AS `fulfillment_lag_time`, IF(`wpv`.`option_id` IS NULL, IF(`wp`.`product_qty` IS NULL, `jp`.`qty`, `wp`.`product_qty`), IF(`wpv`.`option_qtys` IS NULL, `jpv`.`option_qty`, `wpv`.`option_qtys`)) AS `qty`, IF(`wpv`.`option_id` IS NOT NULL, `wpv`.`status`, `wp`.`status`) AS `status` FROM `walmart_product` `wp` LEFT JOIN `walmart_product_variants` `wpv` ON `wp`.`product_id`=`wpv`.`product_id` INNER JOIN `jet_product` `jp` ON `jp`.`id`=`wp`.`product_id` LEFT JOIN `jet_product_variants` `jpv` ON `wpv`.`option_id`=`jpv`.`option_id` WHERE `wp`.`merchant_id` IN (" . $allowedMerchantIds . ") AND IF(`wpv`.`option_id` IS NOT NULL, `wpv`.`status`, `wp`.`status`) IN (" . $allowedStatus . ") LIMIT $offset, $limit";

                $products = $this->sqlRecords($query, 'all');
            }

            $offset = intval($offset) + $limit;
            $query = "UPDATE `walmart_cron_schedule` SET `cron_data` = '{$offset}' WHERE `cron_name` = 'sync_inventory'";
            $this->sqlRecords($query, null, 'update');

            $previousMerchantId = 0;
            $inventoryObj = null;
            foreach ($products as $product) {
                $merchant_id = $product['merchant_id'];
                $product_id = $product['product_id'];
                $variant_id = $product['variant_id'];
                $qty = $this->getProductQty($product['qty'], $merchant_id, $variant_id);
                $sku = $product['sku'];
                $fulfillment_lag_time = $product['fulfillment_lag_time'];

                $apiDetails = $this->getWalmartApiDetails($merchant_id);

                if (is_array($apiDetails) && isset($apiDetails['consumer_id']) && isset($apiDetails['secret_key'])) {
                    if ($merchant_id != $previousMerchantId) {
                        $inventoryObj = new Inventory($apiDetails['consumer_id'], $apiDetails['secret_key']);
                    }
                    $response = $inventoryObj->updateInventoryOnCron($sku, $qty, $fulfillment_lag_time);

                    $update_time = date('Y-m-d H:i:s');
                    if (isset($response['inventory']['sku'])) {
                        $message = $qty . ': Inventory uploaded on walmart';
                        if ($product['type'] == 'simple') {
                            $query = "UPDATE `walmart_product` SET `inventory_update_time` = '{$update_time}', `inventory_update_message` = '{$message}' WHERE `product_id` = '{$product_id}'";
                        } else {
                            $query = "UPDATE `walmart_product_variants` SET `inventory_update_time` = '{$update_time}', `inventory_update_message` = '{$message}' WHERE `option_id` = '{$variant_id}'";
                        }
                        $this->sqlRecords($query, null, 'update');
                    } elseif (isset($response['errors'])) {
                        if (isset($response['errors']['error']['code']) && $response['errors']['error']['code'] == 'UNAUTHORIZED.GMP_GATEWAY_API') {
                            $message = $qty . ': Inventory not uploaded due to Invalid Api Details';
                        } else {
                            $message = $qty . ': Inventory not uploaded due to ' . $response['errors']['error']['code'];
                        }

                        if ($product['type'] == 'simple') {
                            $query = "UPDATE `walmart_product` SET `inventory_update_time` = '{$update_time}', `inventory_update_message` = '{$message}' WHERE `product_id` = '{$product_id}'";
                        } else {
                            $query = "UPDATE `walmart_product_variants` SET `inventory_update_time` = '{$update_time}', `inventory_update_message` = '{$message}' WHERE `option_id` = '{$variant_id}'";
                        }

                        $this->sqlRecords($query, null, 'update');
                    } else {
                        $message = $qty . ': Inventory not uploaded';

                        if ($product['type'] == 'simple') {
                            $query = "UPDATE `walmart_product` SET `inventory_update_time` = '{$update_time}', `inventory_update_message` = '{$message}' WHERE `product_id` = '{$product_id}'";
                        } else {
                            $query = "UPDATE `walmart_product_variants` SET `inventory_update_time` = '{$update_time}', `inventory_update_message` = '{$message}' WHERE `option_id` = '{$variant_id}'";
                        }

                        $this->sqlRecords($query, null, 'update');
                    }
                }

                $previousMerchantId = $merchant_id;
                //1.) send inentory on walmart
                //2.) based on response update time in db when inventory is synced
            }
            return true;
        } else {
            return false;
        }
    }

    public function getProductQty($qty, $merchant_id, $variant_id)
    {
        //can get inventory from shopify as well only for those merchants who hasn't disabled inventory sync from shopify
        $invThreshold = -1;

        if (!isset($this->inventoryThresholdValue[$merchant_id])) {
            $threshold_value = Data::getConfigValue($merchant_id, 'inventory');
            if ($threshold_value) {
                $invThreshold = $this->inventoryThresholdValue[$merchant_id] = is_numeric($threshold_value) ? intval($threshold_value) : -1;
            } else {
                $invThreshold = $this->inventoryThresholdValue[$merchant_id] = -1;
            }
        } else {
            $invThreshold = $this->inventoryThresholdValue[$merchant_id];
        }

        if ($invThreshold !== -1 && intval($qty) <= $invThreshold) {
            $qty = 0;
        }

        return $qty;
    }

    public function getActiveMerchantList()
    {
        $params[':status1'] = 'License Expired';
        $params[':status2'] = 'Trial Expired';
        $params[':app_status'] = 'install';

		$count = 0;
		$idList = [];
		$inventorySyncDisabledMerchants = Inventory::getInventorySyncDisabledMerchants();
		foreach ($inventorySyncDisabledMerchants as $merchantId) {
			$idList[] = ":merchant_id$count";
			$params[":merchant_id$count"] = $merchantId;
			$count++;
		}

        $connection = $this->dbconnection;

        if($idList) {
            $merchantList  = $connection->createCommand('SELECT `merchant_id` FROM `walmart_extension_detail` WHERE `status` NOT IN (:status1 , :status2) AND `app_status` LIKE :app_status AND `merchant_id` NOT IN ('. implode(',', $idList) .')')
           ->bindValues($params)
           ->queryColumn();
        }
        else
        {
            $merchantList  = $connection->createCommand('SELECT `merchant_id` FROM `walmart_extension_detail` WHERE `status` NOT IN (:status1 , :status2) AND `app_status` LIKE :app_status')
           ->bindValues($params)
           ->queryColumn();
        }

        return $merchantList;
    }

    public function getWalmartApiDetails($merchant_id)
    {
        $currentDirPath = __DIR__;
        $fileName = $currentDirPath . '/filestorage/apiDetails.php';

        $merchantsApiDetail = [];

        if (file_exists($fileName)) {
            $merchantsApiDetail = require $fileName;
        }

        if (isset($merchantsApiDetail[$merchant_id])) {
            return $merchantsApiDetail[$merchant_id];
        } else {
            $this->generateApiDetailsFile();
            if (file_exists($fileName)) {
                $merchantsApiDetail = require $fileName;
            }

            if (isset($merchantsApiDetail[$merchant_id])) {
                return $merchantsApiDetail[$merchant_id];
            }
        }

        return false;
    }

    private function generateApiDetailsFile()
    {
        $currentDirPath = __DIR__;
        $dirPath = $currentDirPath . '/filestorage/';
        $fileName = 'apiDetails.php';

        Data::createDirectory($dirPath, 0777);

        if (file_exists($dirPath . $fileName)) {
            unlink($dirPath . $fileName);
        }

        $apiDetailsArray = [];
        $query = "SELECT `merchant_id`, `consumer_id`, `secret_key` FROM `walmart_configuration`";
        $result = $this->sqlRecords($query, 'all');

        if ($result) {
            $apiDetailsArray = array_column($result, null, 'merchant_id');
        }

        $fileHandle = fopen($dirPath . $fileName, 'w');
        fwrite($fileHandle, '<?php return $arr = ' . var_export($apiDetailsArray, true) . ';  ?>');
        fclose($fileHandle);
    }
}


