<?php

namespace frontend\components;

use Yii;
use yii\base\Component;

class Webhook extends Component
{
    public static $appList = null;

    public static function initialise()
    {
        self::$appList = [
            [
                'app_name' => 'walmart',
                'api_key' => WALMART_APP_KEY,
                'api_secret' => WALMART_APP_SECRET,
            ],
            [
                'app_name' => 'jet',
                'api_key' => PUBLIC_KEY,
                'api_secret' => PRIVATE_KEY,
            ],
            [
                'app_name' => 'newegg',
                'api_key' => NEWEGG_APP_KEY,
                'api_secret' => NEWEGG_APP_SECRET,
            ],
            [
                'app_name' => 'neweggcanada',
                'api_key' => NEWEGGCANADA_APP_KEY,
                'api_secret' => NEWEGGCANADA_APP_SECRET,
            ],
            [
                'app_name' => 'sears',
                'api_key' => SEARS_APP_KEY,
                'api_secret' => SEARS_APP_SECRET,
            ]
        ];
    }

    public static function createWebhooks($shop)
    {
        self::initialise();

        $secureWebUrl = Yii::getAlias("@webbaseurl") . "/shopifywebhook/";

        /*$urls = [
            $secureWebUrl . "productcreate",
            $secureWebUrl . "productupdate",
            $secureWebUrl . "productdelete",
        ];*/
        $urls = [
            "https://queue.apps.cedcommerce.com/integration/rabbitmq/shopify-webhook/product-create",
            "https://queue.apps.cedcommerce.com/integration/rabbitmq/shopify-webhook/product-update",
            "https://queue.apps.cedcommerce.com/integration/rabbitmq/shopify-webhook/product-delete",
            $secureWebUrl . "createorder",
            $secureWebUrl . "createshipment",
            $secureWebUrl . "createshipment",
            $secureWebUrl . "createshipment",
            $secureWebUrl . "cancelled",
        ];

        /*$topics = [
            "products/create",
            "products/update",
            "products/delete",
            "orders/create",
            "orders/partially_fulfilled",
            "orders/fulfilled",
            "orders/updated",
            "orders/cancelled",
        ];*/
        $topics = [];

        $otherWebhooks = self::getAllWebhooks($shop);

        $flag = false;
        $productWebhook = false;
        $orderWebhook = false;
        $created = [];
        /*print_r(self::$appList);die;*/
        foreach (self::$appList as $key => $appKeys) {
            if (isset($appKeys['install']) && $appKeys['install']) {
                $token = $appKeys['token'];
                $app = $appKeys['app_name'];

                if (!$productWebhook) {
                    if (isset($appKeys['product']) && $appKeys['product']) {
                        $productTopics = [
                            "products/create",
                            "products/update",
                            "products/delete",
                        ];
                        $topics = array_merge($topics,$productTopics);
                        $productWebhook = true;
                    }
                }

                if (!$orderWebhook) {
                    if (isset($appKeys['order']) && $appKeys['order']) {
                        $orderTopics = [
                            "orders/create",
                            "orders/partially_fulfilled",
                            "orders/fulfilled",
                            "orders/updated",
                            "orders/cancelled",
                        ];
                        $topics = array_merge($topics,$orderTopics);
                        $orderWebhook = true;
                    }
                }

                $sc = new ShopifyClientHelper($shop, $token, $appKeys['api_key'], $appKeys['api_secret']);

                if (!$flag) {
                    $flag = true;
                    if (!empty($topics)) {
                        foreach ($topics as $key => $topic) {

                            if (!in_array($topic, $otherWebhooks['topic'])) {
                                $charge = ['webhook' => ['topic' => $topic, 'address' => $urls[$key]]];
                                $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                            }
                        }
                    }
                }

                switch ($app) {
                    case 'walmart':
                        $walmartUnInstallUrl = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/isinstall";
                        $walmartUnInstallTopic = "app/uninstalled";
                        if (!in_array($walmartUnInstallUrl, $otherWebhooks['address'])) {
                            $charge = ['webhook' => ['address' => $walmartUnInstallUrl, 'topic' => $walmartUnInstallTopic]];
                            $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                        }
                        /*if($productWebhook)
                        {
                            $walmartInventoryUrl = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/inventory-level-update";
                            $walmartinventoryTopic = "inventory_levels/update";
                            if (!in_array($walmartInventoryUrl, $otherWebhooks['address'])) {
                                $charge = ['webhook' => ['address' => $walmartInventoryUrl, 'topic' => $walmartinventoryTopic]];
                                $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                            }
                        }*/
                        break;

                    case 'jet':
                        $jetUnInstallUrl = Yii::getAlias('@webjeturl') . "/jetwebhook/isinstall";
                        $jetUnInstallTopic = "app/uninstalled";
                        if (!in_array($jetUnInstallUrl, $otherWebhooks['address'])) {
                            $charge = ['webhook' => ['address' => $jetUnInstallUrl, 'topic' => $jetUnInstallTopic]];
                            $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                        }
                        /*if($productWebhook)
                        {
                            $jetInventoryUrl = Yii::getAlias('@webjeturl') . "/jetwebhook/inventory-level-update";
                            $jetinventoryTopic = "inventory_levels/update";
                            if (!in_array($jetInventoryUrl, $otherWebhooks['address'])) {
                                $charge = ['webhook' => ['address' => $jetInventoryUrl, 'topic' => $jetinventoryTopic]];
                                $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                            }
                        }*/
                        break;

                    case 'newegg':
                        $neweggUnInstallUrl = Yii::getAlias('@webneweggurl') . "/newegg-webhook/isinstall";
                        $neweggUnInstallTopic = "app/uninstalled";
                        if (!in_array($neweggUnInstallUrl, $otherWebhooks['address'])) {
                            $charge = ['webhook' => ['address' => $neweggUnInstallUrl, 'topic' => $neweggUnInstallTopic]];
                            $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                        }
                        /*if($productWebhook)
                        {
                            $neweggInventoryUrl = Yii::getAlias('@webneweggurl') . "/newegg-webhook/inventory-level-update";
                            $neweggInventoryTopic = "inventory_levels/update";
                            if (!in_array($neweggInventoryUrl, $otherWebhooks['address'])) {
                                $charge = ['webhook' => ['address' => $neweggInventoryUrl, 'topic' => $neweggInventoryTopic]];
                                $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                            }
                        }*/
                        break;

                    case 'neweggcanada':
                        $neweggcaUnInstallUrl = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/isinstall";
                        $neweggcaUnInstallTopic = "app/uninstalled";
                        if (!in_array($neweggcaUnInstallUrl, $otherWebhooks['address'])) {
                            $charge = ['webhook' => ['address' => $neweggcaUnInstallUrl, 'topic' => $neweggcaUnInstallTopic]];
                            $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                        }
                        /*if($productWebhook)
                        {
                            $neweggcaInventoryUrl = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/inventory-level-update";
                            $neweggcaInventoryTopic = "inventory_levels/update";
                            if (!in_array($neweggcaInventoryUrl, $otherWebhooks['address'])) {
                                $charge = ['webhook' => ['address' => $neweggcaInventoryUrl, 'topic' => $neweggcaInventoryTopic]];
                                $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                            }
                        }*/
                        break;

                    case 'sears':
                        $searsUnInstallUrl = Yii::getAlias('@websearsurl') . "/searswebhook/isinstall";
                        $searsUnInstallTopic = "app/uninstalled";
                        if (!in_array($searsUnInstallUrl, $otherWebhooks['address'])) {
                            $charge = ['webhook' => ['address' => $searsUnInstallUrl, 'topic' => $searsUnInstallTopic]];
                            $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                        }
                        /*if($productWebhook)
                        {
                            $searsInventoryUrl = Yii::getAlias('@websearsurl') . "/searswebhook/inventory-level-update";
                            $searsInventoryTopic = "inventory_levels/update";
                            if (!in_array($searsInventoryUrl, $otherWebhooks['address'])) {
                                $charge = ['webhook' => ['address' => $searsInventoryUrl, 'topic' => $searsInventoryTopic]];
                                $created[] = $sc->call('POST', '/admin/webhooks.json', $charge);
                            }
                        }*/
                        break;
                }
            }
        }

        if(!empty($created)){
            $query = "UPDATE `merchant_db` SET ";
            $is_update=false;
            if($productWebhook){
                $query .= ' product_webhook=1,';
                $is_update = true;
            }
            if($orderWebhook){
                $query .= ' order_webhook=1,';
                $is_update = true;
            }

            if($is_update){
                $query = trim($query,',');
                $query .= ' WHERE `shop_name`="'.$shop.'"';

                Data::sqlRecords($query,null,'update');
            }
        }

        return ['created' => $created, 'otherWebhooks' => $otherWebhooks];
    }

    public static function getAllWebhooks($shop)
    {
        $webhooks = ["topic" => [], "address" => []];
        $productWebhook = false;
        $orderWebhook = false;

        foreach (self::$appList as $key => $appData) {
            $app = $appData['app_name'];

            /*if($app == 'walmart') {
                continue;
            }*/

            switch ($app) {
                case 'walmart':
                    $query = "SELECT `token`, `status`,`merchant_id` FROM `walmart_shop_details` WHERE `shop_url` LIKE '{$shop}'";
                    $result = Data::sqlRecords($query, 'one');
                    if (is_array($result) && $result['status']) {
                        self::$appList[$key]['token'] = $result['token'];

                        if(!$productWebhook)
                        {
                            $productData = self::getTableData($result['merchant_id'], 'jet_product');
                            if ($productData) {
                                self::$appList[$key]['product'] = true;
                                $productWebhook = true;
                            } else {
                                self::$appList[$key]['product'] = false;
                            }
                        }

                        if(!$orderWebhook)
                        {
                            $orderData = self::getTableData($result['merchant_id'], 'walmart_order_details');

                            if ($orderData) {
                                self::$appList[$key]['order'] = true;
                                $orderWebhook = true;

                            } else {
                                self::$appList[$key]['order'] = false;
                            }
                        }

                        $created = self::getCreatedWehooks($shop, $result['token'], $appData['api_key'], $appData['api_secret'], $key);
                        $webhooks["topic"] = array_merge($webhooks["topic"], $created["topic"]);
                        $webhooks["address"] = array_merge($webhooks["address"], $created["address"]);
                    }
                    break;

                case 'jet':
                    $query = "SELECT `user`.`auth_key`, `jsd`.`install_status`, `jsd`.`purchase_status`,`jsd`.merchant_id FROM `user` INNER JOIN (SELECT `install_status`, `purchase_status`, `shop_url`,`merchant_id` FROM `jet_shop_details` WHERE `shop_url` LIKE '{$shop}') `jsd` ON `user`.`username`=`jsd`.`shop_url` WHERE `username` LIKE '{$shop}'";
                    $result = Data::sqlRecords($query, 'one');
                    if (is_array($result) && $result['install_status']) {
                        self::$appList[$key]['token'] = $result['auth_key'];

                        if(!$productWebhook)
                        {
                            $productData = self::getTableData($result['merchant_id'], 'jet_product');
                            if ($productData) {
                                self::$appList[$key]['product'] = true;
                                $productWebhook = true;
                            } else {
                                self::$appList[$key]['product'] = false;
                            }
                        }

                        if(!$orderWebhook)
                        {
                            $orderData = self::getTableData($result['merchant_id'], 'jet_order_detail');

                            if ($orderData) {
                                self::$appList[$key]['order'] = true;
                                $orderWebhook = true;

                            } else {
                                self::$appList[$key]['order'] = false;
                            }
                        }

                        $created = self::getCreatedWehooks($shop, $result['auth_key'], $appData['api_key'], $appData['api_secret'], $key);
                        $webhooks["topic"] = array_merge($webhooks["topic"], $created["topic"]);
                        $webhooks["address"] = array_merge($webhooks["address"], $created["address"]);
                    }
                    break;

                case 'newegg':
                    $query = "SELECT `token`, `install_status`,`merchant_id` FROM `newegg_shop_detail` WHERE `shop_url` LIKE '{$shop}'";
                    $result = Data::sqlRecords($query, 'one');
                    if (is_array($result) && $result['install_status']) {
                        self::$appList[$key]['token'] = $result['token'];

                        if(!$productWebhook)
                        {
                            $productData = self::getTableData($result['merchant_id'], 'jet_product');
                            if ($productData) {
                                self::$appList[$key]['product'] = true;
                                $productWebhook = true;
                            } else {
                                self::$appList[$key]['product'] = false;
                            }
                        }

                        if(!$orderWebhook)
                        {
                            $orderData = self::getTableData($result['merchant_id'], 'newegg_order_detail');

                            if ($orderData) {
                                self::$appList[$key]['order'] = true;
                                $orderWebhook = true;

                            } else {
                                self::$appList[$key]['order'] = false;
                            }
                        }

                        $created = self::getCreatedWehooks($shop, $result['token'], $appData['api_key'], $appData['api_secret'], $key);
                        $webhooks["topic"] = array_merge($webhooks["topic"], $created["topic"]);
                        $webhooks["address"] = array_merge($webhooks["address"], $created["address"]);
                    }
                    break;

                case 'neweggcanada':
                    $query = "SELECT `token`, `install_status`,`merchant_id` FROM `newegg_can_shop_detail` WHERE `shop_url` LIKE '{$shop}'";
                    $result = Data::sqlRecords($query, 'one');
                    if (is_array($result) && $result['install_status']) {
                        self::$appList[$key]['token'] = $result['token'];

                        if(!$productWebhook)
                        {
                            $productData = self::getTableData($result['merchant_id'], 'jet_product');
                            if ($productData) {
                                self::$appList[$key]['product'] = true;
                                $productWebhook = true;
                            } else {
                                self::$appList[$key]['product'] = false;
                            }
                        }

                        if(!$orderWebhook)
                        {

                            $orderData = self::getTableData($result['merchant_id'], 'newegg_can_order_detail');
                            if ($orderData) {
                                self::$appList[$key]['order'] = true;
                                $orderWebhook = true;
                            } else {
                                self::$appList[$key]['order'] = false;
                            }

                        }

                        $created = self::getCreatedWehooks($shop, $result['token'], $appData['api_key'], $appData['api_secret'], $key);
                        $webhooks["topic"] = array_merge($webhooks["topic"], $created["topic"]);
                        $webhooks["address"] = array_merge($webhooks["address"], $created["address"]);
                    }
                    break;

                case 'sears':
                    $query = "SELECT `token`, `status`,`merchant_id` FROM `sears_shop_details` WHERE `shop_url` LIKE '{$shop}'";
                    $result = Data::sqlRecords($query, 'one');
                    if (is_array($result) && $result['status']) {
                        self::$appList[$key]['token'] = $result['token'];

                        if(!$productWebhook)
                        {
                            $productData = self::getTableData($result['merchant_id'], 'sears_product');
                            if ($productData) {
                                self::$appList[$key]['product'] = true;
                                $productWebhook = true;
                            } else {
                                self::$appList[$key]['product'] = false;
                            }
                        }

                        if(!$orderWebhook)
                        {
                            $orderData = self::getTableData($result['merchant_id'], 'sears_order_details');

                            if ($orderData) {
                                self::$appList[$key]['order'] = true;
                                $orderWebhook = true;

                            } else {
                                self::$appList[$key]['order'] = false;
                            }
                        }   

                        $created = self::getCreatedWehooks($shop, $result['token'], $appData['api_key'], $appData['api_secret'], $key);
                        $webhooks["topic"] = array_merge($webhooks["topic"], $created["topic"]);
                        $webhooks["address"] = array_merge($webhooks["address"], $created["address"]);
                    }
                    break;
            }
        }

        return $webhooks;
    }

    public static function getCreatedWehooks($shop_url, $token, $app_key, $app_secret, $key)
    {
        try {
            $webhooks = ["topic" => [], "address" => []];

            $sc = new ShopifyClientHelper($shop_url, $token, $app_key, $app_secret);

            $response = $sc->call('GET', '/admin/webhooks.json');

            if (!isset($response['errors'])) {
                self::$appList[$key]['install'] = true;

                foreach ($response as $k => $value) {
                    if (isset($value['topic'])) {
                        $webhooks['topic'][] = $value['topic'];
                        $webhooks['address'][] = $value['address'];
                    }
                }
            }
            return $webhooks;
        } catch (\Exception $e) {
            //$e->getMessage();
            return [];
        }
    }

    public static function validateWebhook($webhookData, $hmac)
    {
        //$hmac = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        self::initialise();

        foreach (self::$appList as $key => $appKeys) {
            $calculated_hmac = base64_encode(hash_hmac('sha256', $webhookData, $appKeys['api_secret'], true));

            if (hash_equals($hmac, $calculated_hmac)) {
                return true;
            }
        }

        return false;
    }

    public static function validatappreferalcode($appref_code)
    {
        // $url = Yii::getAlias('@webbaseurl')."/shopifywebhook/curlprocessforproductupdate?maintenanceprocess=1";
        $data = [];
        $url = 'http://192.168.0.161/yii-ref/index.php/api/validate?appref=' . $appref_code;
        $respone = Data::sendCurlRequest($data, $url);
        $res = json_decode($respone, true);
        if ($res && $res['success'] && $res['status']) {
            return true;
        } elseif ($res && $res['success'] && !$res['status']) {
            return false;
        } else {
            return false;
        }
    }

    public static function sendDatatoMobileapp($data)
    {
        $url = 'http://192.168.0.161/yii-ref/index.php/api/reffershop';
        $respone = Data::sendCurlRequest($data, $url);
        $res = json_decode($respone, true);

        Data::createLog(json_encode($data));

        return true;
    }

    /**
     * @param $merchant_id
     * @return array|bool|int
     */
    public static function getTableData($merchant_id, $table_name)
    {
        $query = "SELECT * FROM {$table_name} WHERE merchant_id='" . $merchant_id . "' LIMIT 0,1";

        $tableData = Data::sqlRecords($query, "one", "select");
        return $tableData;
    }
}