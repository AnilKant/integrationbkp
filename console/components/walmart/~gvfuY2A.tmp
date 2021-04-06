<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 12/7/18
 * Time: 5:48 PM
 */

namespace console\components\walmart;

use console\components\QueryHelper;
use frontend\modules\walmart\components\Inventory\InventoryUpdate;
use frontend\modules\walmart\components\Jetproductinfo;
use frontend\modules\walmart\models\WalmartCronSchedule;
use yii\base\Component;
use yii\db\Query;

class WalmartProductSync extends Component
{

    public $merchant_id;
    public $dbname = null;
    public $sc;
    public $days;
    public $currentPage = 1;
    public $merchantData = null;
    public $syncFields = [];

    public function __construct($merchant_id, $dbname, $sc, $days = 2)
    {
        $this->merchant_id = $merchant_id;
        $this->dbname = $dbname;
        $this->sc = $sc;
        $this->days = $days;
    }

    public function execute()
    {
        $validateMerchant = $this->validateMerchant();

        if (!$validateMerchant) {
            return false;
        }

        if (is_null($this->merchantData)) {
            return false;
        }

        $current_page = $this->merchantData[$this->merchant_id]['current_page'];
        $totalPages = $this->merchantData[$this->merchant_id]['total_pages'];

        $pageLimit = $current_page + 5;
        if(empty($this->syncFields))
        {
            return false;
        }

        for ($i = $current_page; $i < $pageLimit; $i++) {
            $productIds = $this->getProduct($i);
            $products = $this->sc->call('GET', '/admin/products.json?ids=' . implode(',', $productIds), array());
            if(isset($products['errors']))
            {
                return false;
            }

            foreach ($products as $product) {

                Jetproductinfo::updateDetails($product,$this->syncFields,$this->merchant_id);
            }
        }

        if ($totalPages > $pageLimit) {
            $updateArray = [];
            $updateArray[$this->merchant_id]['total_pages'] = $totalPages;
            $updateArray[$this->merchant_id]['current_page'] = $pageLimit;

            $query = "UPDATE `walmart_cron_schedule` SET `cron_data`= :cron_data WHERE `cron_name`= :cron_name";
            QueryHelper::sqlRecords($query, [':cron_data' => json_encode($updateArray), ':cron_name' => 'sync_product_data'], 'update');

            return true;
        } else {
            /*$query = "UPDATE `walmart_cron_schedule` SET `cron_data`= :cron_data WHERE `cron_name`= :cron_name";
            QueryHelper::sqlRecords($query, [':cron_data' => '', ':cron_name' => 'sync_product_data'], 'update');*/
            $this->unsetSyncMerchant();
            return false;

        }

    }

    public function validateMerchant()
    {
        $merchantsList = InventoryUpdate::getInventorySyncDisabledMerchants();

        if (in_array($this->merchant_id, $merchantsList)) {
            return false;
        }

        $syncEnable = $this->getSyncData();
        if (!$syncEnable) {
            return false;
        }

        return true;

    }

    public function getProduct($page = null, $limit = 50)
    {
        if (!is_null($page) && !is_null($limit)) {
            $offset = ($page-1) * $limit;
            $query = "SELECT `product_id` FROM `walmart_product` WHERE `merchant_id`=:merchant_id LIMIT $offset,$limit";
        } else {
            $query = "SELECT `product_id` FROM `walmart_product` WHERE `merchant_id`=:merchant_id";
        }
//        $data = QueryHelper::sqlRecords($query, [':merchant_id' => $this->merchant_id], 'column',$this->dbname);
        $data = QueryHelper::sqlRecords($query, [':merchant_id' => $this->merchant_id], 'column');
        return $data;
    }

    public function insertProcessedMerchant()
    {
        $result = WalmartCronSchedule::find()->where(['cron_name' => 'sync_product_data'])->one();
        if ($result && $result->cron_data == "") {
            $products = $this->getProduct();
            $totalProducts = count($products);
            $totalPages = (int)(ceil($totalProducts / 50));

            $updateArray = [];
            $updateArray[$this->merchant_id]['total_pages'] = $totalPages;
            $updateArray[$this->merchant_id]['current_page'] = $this->currentPage;
//            $result->cron_data = json_encode($updateArray);
            $this->merchantData = $updateArray;
//            $result->save(false);
        } else {
            $this->merchantData = json_decode($result->cron_data, true);
        }

    }

    public function getSyncData()
    {
        $query = "SELECT `value` FROM  `walmart_config` WHERE `merchant_id`=:merchant_id AND `data`=:field_name";

        $enableSync = QueryHelper::sqlRecords($query, [':merchant_id' => $this->merchant_id, ':field_name' => 'sync_product_enable'], 'one',$this->dbname);

        if (isset($enableSync['value']) && $enableSync['value'] == 'disable') {
            return false;
        }

        $query = "SELECT `value` FROM  `walmart_config` WHERE `merchant_id`=:merchant_id AND `data`=:field_name";

        $syncConfigJson = QueryHelper::sqlRecords($query, [':merchant_id' => $this->merchant_id, ':field_name' => 'sync-fields'], 'one',$this->dbname);

        if (isset($syncConfigJson['value']) && !empty($syncConfigJson['value'])) {

            $syncFields = json_decode($syncConfigJson['value'], true);
            if (empty($syncFields)) {
                return false;
            }else{
                $this->syncFields = $syncFields;
                return true;
            }

        } else {
            $sync_fields = [
                'sku' => '1',
                'title' => '1',
                'image' => '1',
                'product_type' => '1',
                'qty' => '1',
                'weight' => '1',
                'price' => '1',
                'upc' => '1',
                'vendor' => '1',
                'description' => '1',
                'variant_options' => '1',
            ];
            $syncFields['sync-fields'] = $sync_fields;
            $this->syncFields = $syncFields;
            return true;
        }
    }

    public function unsetSyncMerchant()
    {
        $result = WalmartCronSchedule::find()->where(['cron_name' => 'sync_product_data'])->one();
        if ($result && $result->cron_data != "") {

            $merchant_data = json_decode($result->cron_data, true);
            if(isset($merchant_data[$this->merchant_id]))
            {
                $result->cron_data = '';
                $result->save(false);
            }
        }
    }


}