<?php
namespace frontend\controllers;
use common\models\User;
use frontend\components\Data;
use frontend\components\Log;
use frontend\components\Webhook;
use frontend\modules\jet\components\Jetproductinfo;

use Yii;
use yii\web\Controller;

class ShopifywebhookController extends Controller
{
    public function beforeAction($action)
    {
        Yii::$app->controller->enableCsrfValidation = false;
        return true;
    }

    public function actionProductcreate()
    {
        $shopName = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : 'common';
        try {
            $webhook_content = '';
            $webhook = fopen('php://input', 'rb');
            while (!feof($webhook)) {
                $webhook_content .= fread($webhook, 4096);
            }
            $realdata = "";
            $data = [];
            fclose($webhook);

            $realdata = $webhook_content;
            if ($webhook_content == '' || empty(json_decode($realdata, true))) {
                return;
            }

            $hmac = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';//for live site
            if (!Webhook::validateWebhook($webhook_content, $hmac)) {
                return;
            }

            $data = json_decode($realdata, true);// array of webhook data

            if (isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']))
                $data['shopName'] = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
            $url = Yii::getAlias('@webbaseurl') . "/shopifywebhook/curlproductcreate?maintenanceprocess=1";
            Data::sendCurlRequest($data, $url);

            //send walmart url
            //$url = Yii::getAlias('@webwalmarturl')."/walmart-webhook/productcreate?maintenanceprocess=1";
            //Data::sendCurlRequest($data,$url);

            exit(0);
        } catch (Exception $e) {
            $this->createExceptionLog('actionProductcreate', $e->getMessage(), $shopName);
            exit(0);
        }
    }

    public function actionCurlproductcreate()
    {
        $data = $_POST;

        if (isset($data['shopName']) && isset($data['id'])) {
            try {
                $file_dir = \Yii::getAlias('@webroot') . '/var/product/create/' . $data['shopName'];
                if (!file_exists($file_dir)) {
                    mkdir($file_dir, 0775, true);
                }
                $filenameOrig = "";
                $filenameOrig = $file_dir . '/' . $data['id'] . '.log';
                $fileOrig = "";
                $fileOrig = fopen($filenameOrig, 'w');
                fwrite($fileOrig, "\n" . date('d-m-Y H:i:s') . "\n" . json_encode($data));
                fclose($fileOrig);
                $sql = "SELECT `id` FROM `user` WHERE username='" . $data['shopName'] . "' LIMIT 0,1";
                $userData = Data::sqlRecords($sql, "one", "select");
                $query = "SELECT `id` FROM `jet_product` WHERE id='" . $data['id'] . "' LIMIT 0,1";
                $proresult = Data::sqlRecords($query);
                if (!$proresult && isset($userData['id'])) {

                    //send jet url
                    $url = Yii::getAlias('@webjeturl') . "/jetwebhook/curlproductcreate?maintenanceprocess=1";
                    Data::sendCurlRequest($data, $url);

                    //send walmart url
                    $url = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/productcreate?maintenanceprocess=1";
                    Data::sendCurlRequest($data, $url);
                    $url = Yii::getAlias('@webneweggurl') . "/newegg-webhook/productcreate?maintenanceprocess=1";
                    Data::sendCurlRequest($data, $url);
                    $url = Yii::getAlias('@websearsurl') . "/searswebhook/productcreate?maintenanceprocess=1";
                    Data::sendCurlRequest($data, $url);
                    $url = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/productcreate?maintenanceprocess=1";
                    Data::sendCurlRequest($data, $url);
                    //send newegg url
                }
                unset($data, $query);
            } catch (\yii\db\Exception $e) {
                $this->createExceptionLog('actionCurlproductcreate', $e->getMessage(), $data['shopName']);
                exit(0);
            } catch (Exception $e) {
                $this->createExceptionLog('actionCurlproductcreate', $e->getMessage(), $data['shopName']);
                exit(0);
            }
        }
    }

    public function actionTestproduct()
    {
        $data = '{"id":"8874517772","title":"New shirt blue","body_html":"New shirt blue","vendor":"Fashion Apparel ","product_type":"tshirt","created_at":"2016-11-09T07:49:30-05:00","handle":"new-shirt-blue","updated_at":"2016-11-09T07:49:30-05:00","published_at":"2016-11-09T07:43:00-05:00","published_scope":"global","tags":"","variants":[{"id":"30642204172","product_id":"8874517772","title":"l","price":"100.00","sku":"tetete","position":"1","grams":"0","inventory_policy":"deny","fulfillment_service":"manual","inventory_management":"shopify","option1":"l","created_at":"2016-11-09T07:49:30-05:00","updated_at":"2016-11-09T07:49:30-05:00","taxable":"0","barcode":"435656565656","inventory_quantity":"20","weight":"0","weight_unit":"kg","old_inventory_quantity":"20","requires_shipping":"1"},{"id":"30642204236","product_id":"8874517772","title":"s","price":"100.00","sku":"new","position":"2","grams":"0","inventory_policy":"deny","fulfillment_service":"manual","inventory_management":"shopify","option1":"s","created_at":"2016-11-09T07:49:30-05:00","updated_at":"2016-11-09T07:49:30-05:00","taxable":"0","barcode":"435656565656","inventory_quantity":"20","weight":"0","weight_unit":"kg","old_inventory_quantity":"20","requires_shipping":"1"},{"id":"30642204300","product_id":"8874517772","title":"m","price":"100.00","sku":"check321","position":"3","grams":"0","inventory_policy":"deny","fulfillment_service":"manual","inventory_management":"shopify","option1":"m","created_at":"2016-11-09T07:49:30-05:00","updated_at":"2016-11-09T07:49:30-05:00","taxable":"0","barcode":"435656565656","inventory_quantity":"30","weight":"0","weight_unit":"kg","old_inventory_quantity":"20","requires_shipping":"1"}],"options":[{"id":"10712096012","product_id":"8874517772","name":"Size","position":"1","values":["l","s","m"]}],"shopName":"ced-jet.myshopify.com"}';
        var_dump(Jetproductinfo::saveNewRecords(json_decode($data, true), 14, 'published'));
    }

    public function actionCheckLog()
    {
        $this->createExceptionLog('test', 'messagess');
        die('done');
    }

    /*
     * function for creating log
     */
    public function createExceptionLog($functionName, $msg, $shopName = 'common')
    {
        $dir = \Yii::getAlias('@webroot') . '/var/exceptions/' . $functionName . '/' . $shopName;
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }
        try {
            throw new Exception($msg);
        } catch (Exception $e) {
            $filenameOrig = $dir . '/' . time() . '.txt';
            $handle = fopen($filenameOrig, 'a');
            $msg = date('d-m-Y H:i:s') . "\n" . $msg . "\n" . $e->getTraceAsString();
            fwrite($handle, $msg);
            fclose($handle);
            $this->sendEmail($filenameOrig, $msg);
        }

    }

    /**
     * function for sending mail with attachment
     */
    public function sendEmail($file, $msg, $email = 'shivamverma@cedcoss.com')
    {
        try {
            $name = 'shopify webhook controllers';
            $EmailTo = $email . ',amitkumar@cedcoss.com,kshitijverma@cedcoss.com,shivamverma@cedcoss.com';
            $EmailFrom = $email;
            $EmailSubject = "shopify webhook controllers";
            $from = 'shopify@cedcommerce.com';
            $message = $msg;
            $separator = md5(time());

            // carriage return type (we use a PHP end of line constant)
            $eol = PHP_EOL;

            // attachment name
            $filename = 'exception';//store that zip file in ur root directory
            $attachment = chunk_split(base64_encode(file_get_contents($file)));

            // main header
            $headers = "From: " . $from . $eol;
            $headers .= "MIME-Version: 1.0" . $eol;
            $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"";

            // no more headers after this, we start the body! //

            $body = "--" . $separator . $eol;
            $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol . $eol;
            $body .= $message . $eol;

            // message
            $body .= "--" . $separator . $eol;
            /*  $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
             $body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
             $body .= $message.$eol; */

            // attachment
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;
            $body .= $attachment . $eol;
            $body .= "--" . $separator . "--";

            // send message
            if (mail($EmailTo, $EmailSubject, $body, $headers)) {
                $mail_sent = true;
            } else {
                $mail_sent = false;
            }
        } catch (Exception $e) {

        }
    }

    public function actionCreateshipment()
    {
        $shopName = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : 'common';
        try {
            $webhook_content = '';
            $webhook = fopen('php://input', 'rb');
            while (!feof($webhook)) {
                $webhook_content .= fread($webhook, 4096);
            }

            fclose($webhook);
            $realdata = $webhook_content;
            if ($webhook_content == '' || empty(json_decode($realdata, true))) {
                return;
            }

            $hmac = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';//for live site
            if (!Webhook::validateWebhook($webhook_content, $hmac)) {
                return;
            }

            $data = json_decode($realdata, true);

            if (isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']))
                $data['shopName'] = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];

            if (isset($data['note']) && $data['note'] == "Walmart Marketplace-Integration") {
                $url = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/ordershipment?maintenanceprocess=1";
            } elseif (isset($data['note']) && $data['note'] == "Newegg-Integration(newegg.com)") {
                $url = Yii::getAlias('@webneweggurl') . "/newegg-webhook/shipment?maintenanceprocess=1";
            } elseif (isset($data['note']) && $data['note'] == "Sears Marketplace - Integration(Cedcommerce)") {
                $url = Yii::getAlias('@websearsurl') . "/searswebhook/ordershipment";
            } elseif (isset($data['note']) && $data['note'] == "Newegg-Canada-Integration(newegg.ca)") {
                $url = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/shipment?maintenanceprocess=1";
            } else {
                $url = Yii::getAlias('@webjeturl') . "/jetwebhook/curlprocessfororder?maintenanceprocess=1";
            }
            Data::sendCurlRequest($data, $url);
            exit(0);
        } catch (Exception $e) {
            $this->createExceptionLog('actionCreateshipment', $e->getMessage(), $shopName);
            exit(0);
        }
    }

    public function getShipementStatus($items)
    {
        $items = isset($items[0]) ? $items : [$items];
        foreach ($items as $item) {
            if ($item['fulfillment_status'] != 'fulfilled') {
                return 'inprogress';
            }
        }
        return 'complete';
    }

    public function getStandardOffsetUTC()
    {
        $timezone = "";
        $timezone = date_default_timezone_get();
        if ($timezone == 'UTC') {
            return '';
        } else {
            $timezone = new \DateTimeZone($timezone);
            $transitions = "";
            $transitions = array_slice($timezone->getTransitions(), -3, null, true);

            foreach (array_reverse($transitions, true) as $transition) {
                if (isset($transition['isdst']) && $transition['isdst'] == 1) {
                    continue;
                }
                return sprintf('%+03d:%02u', $transition['offset'] / 3600, abs($transition['offset']) % 3600 / 60);
            }
            return false;
        }
    }

    public function actionProductupdate()
    {
        $shopName = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : 'common';
        try {
            $webhook = fopen('php://input', 'rb');
            $webhook_content = '';
            while (!feof($webhook)) {
                $webhook_content .= fread($webhook, 4096);
            }
            $realdata = "";
            $data = $orderData = [];
            fclose($webhook);
            $realdata = $webhook_content;
            if ($webhook_content == '' || empty(json_decode($realdata, true))) {
                //return;
                exit(0);
            }

            $hmac = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';//for live site
            if (!Webhook::validateWebhook($webhook_content, $hmac)) {
                //return;
                exit(0);
            }

            $data = json_decode($realdata, true);// ab ye data ka array hai

            if (isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']))
                $data['shopName'] = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];

            //$url = "https://826524d5.ngrok.io/Amit/shopify/marketplace-integration/frontend/web/integration/shopifywebhook/curlprocessforproductupdate?maintenanceprocess=1";
            $url = Yii::getAlias('@webbaseurl') . "/shopifywebhook/curlprocessforproductupdate?maintenanceprocess=1";
            Data::sendCurlRequest($data, $url);
            exit(0);
        } catch (Exception $e) {
            $this->createExceptionLog('actionProductupdate', $e->getMessage(), $shopName);
            exit(0);
        }

    }

    public function actionCurlprocessforproductupdate()
    {
        $data = $_POST;
        //$data = json_decode('{"id":"9764525836","title":"Testing2600 qwerty","body_html":"<p>Testing2600<\/p>\n<p>kbhjk<\/p>","vendor":"cedcommerce1","product_type":"cat101","created_at":"2017-02-23T06:12:39-05:00","handle":"p1w0","updated_at":"2018-07-02T07:42:54-04:00","published_at":"2017-02-23T06:12:00-05:00","tags":"","published_scope":"global","variants":[{"id":"40415225548","product_id":"9764525836","title":"Default Title","price":"5.00","sku":"Testing2600","position":"1","inventory_policy":"deny","fulfillment_service":"manual","inventory_management":"shopify","option1":"Default Title","created_at":"2017-05-25T07:57:10-04:00","updated_at":"2018-07-02T07:42:54-04:00","taxable":"1","barcode":"887962541589","grams":"142","inventory_quantity":"11","weight":"5","weight_unit":"oz","inventory_item_id":"28840075788","old_inventory_quantity":"11","requires_shipping":"1"}],"options":[{"id":"11811226508","product_id":"9764525836","name":"Title","position":"1","values":["Default Title"]}],"images":[{"id":"25602166540","product_id":"9764525836","position":"1","created_at":"2017-06-05T05:50:24-04:00","updated_at":"2018-06-11T11:22:23-04:00","alt":"Testing2600 qwerty - Fashion Apparel ","width":"2560","height":"1440","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1009\/2336\/products\/women9.jpg?v=1528730543"}],"image":{"id":"25602166540","product_id":"9764525836","position":"1","created_at":"2017-06-05T05:50:24-04:00","updated_at":"2018-06-11T11:22:23-04:00","alt":"Testing2600 qwerty - Fashion Apparel ","width":"2560","height":"1440","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1009\/2336\/products\/women9.jpg?v=1528730543"},"shopName":"ced-jet.myshopify.com"}',true);

        if (isset($data['id']) && isset($data['shopName'])) {
            try {
                $file_dir = \Yii::getAlias('@webroot') . '/var/product/update/' . $data['shopName'];
                if (!is_dir($file_dir)) {
                    mkdir($file_dir, 0775, true);
                }
                $filenameOrig = $file_dir . '/' . $data['id'] . '.log';
                $fileOrig = fopen($filenameOrig, 'w');
                $handle = fopen($file_dir . '/' . $data['id'] . '-qty-price.log', 'a+');
                fwrite($fileOrig, "\n" . date('d-m-Y H:i:s') . "\n" . json_encode($data));
                fclose($fileOrig);
                //get merchant_id
                $query = "SELECT id FROM `user` WHERE username='" . $data['shopName'] . "' LIMIT 0,1";
                $userCollection = Data::sqlRecords($query, "one", "select");
                if (isset($userCollection['id'])) {
                    $merchant_id = $userCollection['id'];
                } else {
                    return false;
                }
                $prodExist = Data::sqlRecords("SELECT `id`,`variant_id`,`type`,`price`,`qty`,`sku` FROM `jet_product` WHERE id='" . $data['id'] . "' LIMIT 0,1", "one", "select");
                $configSetting = JetProductInfo::getConfigSettings($merchant_id);
                if (!$prodExist) {
                    $import_status = (isset($configSetting['import_status']) && $configSetting['import_status']) ? $configSetting['import_status'] : "";
                    Jetproductinfo::saveNewRecords($data, $merchant_id, $import_status);
                    //send walmart url
                    $url = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/productcreate?maintenanceprocess=1";
                    Data::sendCurlRequest($data, $url);
                    //send Sears url
                    $url = Yii::getAlias('@websearsurl') . "/searswebhook/productcreate?maintenanceprocess=1";
                    Data::sendCurlRequest($data, $url);
                    //send newegg url
                    $url = Yii::getAlias('@webneweggurl') . "/newegg-webhook/productcreate?maintenanceprocess=1";
                    Data::sendCurlRequest($data, $url);

                    $url = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/productcreate?maintenanceprocess=1";
                    Data::sendCurlRequest($data, $url);
                    unset($data, $query);
                    return true;
                } else {
                    //update price/inventory on base table
                    $isPrice = false;
                    $isChangedPrice = false;
                    $priceData = [];
                    $inventoryData = [];
                    $isChangedInventory = false;
                    /* if (isset($configSetting['fixed_price']) && $configSetting['fixed_price'] == 'yes') {
                         $isPrice = true;
                     }*/
                    if (isset($data['variants'])) {

                        foreach ($data['variants'] as $key => $value) {
                            if ($prodExist['type'] == "variants") {
                                $proVarExist = Data::sqlRecords("SELECT `option_id`,`option_price`,`option_qty`,`option_sku` FROM `jet_product_variants` WHERE option_id=" . $value['id'] . " LIMIT 0,1", "one", "select");
                                if ($proVarExist['option_id'] == $value['id'] && $proVarExist['option_sku'] == $value['sku']) {
                                    //echo $proVarExist['option_price']."--".$value['price']."--".$proVarExist['option_qty']."---".$value['inventory_quantity'];
                                    $pro_var_query = "";
                                    if (!$isPrice && $proVarExist['option_price'] != $value['price']) {
                                        //$priceData[$value['sku']]=$value['price'];
                                        $priceData[$value['id']] = ['product_id' => $data['id'], 'price' => $value['price'], 'sku' => $value['sku'], 'merchant_id' => $merchant_id];
                                        $pro_var_query .= 'option_price="' . $value['price'] . '",';
                                    }
                                    if ($proVarExist['option_qty'] != $value['inventory_quantity']) {
                                        //$inventoryData[$value['sku']]=$value['inventory_quantity'];
                                        $inventoryData[$value['id']] = ['product_id'=>$data['id'],'inventory' => $value['inventory_quantity'], 'sku' => $value['sku'], 'merchant_id' => $merchant_id];
                                        $pro_var_query .= 'option_qty="' . (int)$value['inventory_quantity'] . '",';
                                    }
                                    if ($pro_var_query) {
                                        $pro_var_query = rtrim($pro_var_query, ",");
                                        $query = "UPDATE `jet_product_variants` SET " . $pro_var_query . " WHERE option_id=" . $value['id'];
                                        Data::sqlRecords($query);
                                    }
                                }
                            }
                            if ($prodExist['variant_id'] == $value['id'] && $prodExist['sku'] == $value['sku']) {
                                $pro_query = "";
                                if (!$isPrice && $prodExist['price'] != $value['price']) {
                                    //$priceData[$value['sku']]=$value['price'];
                                    $priceData[$value['id']] = ['product_id' => $data['id'], 'price' => $value['price'], 'sku' => $value['sku'], 'merchant_id' => $merchant_id];
                                    $pro_query .= 'price="' . (float)$value['price'] . '",';
                                }
                                if ($prodExist['qty'] != $value['inventory_quantity']) {
                                    //$inventoryData[$value['sku']]=$value['inventory_quantity'];
                                    $inventoryData[$value['id']] = ['product_id'=>$data['id'],'inventory' => $value['inventory_quantity'], 'sku' => $value['sku'], 'merchant_id' => $merchant_id];
                                    $pro_query .= 'qty="' . (int)$value['inventory_quantity'] . '",';
                                }
                                if ($pro_query) {
                                    $pro_query = rtrim($pro_query, ",");
                                    Data::sqlRecords("UPDATE `jet_product` SET " . $pro_query . " WHERE id=" . $prodExist['id']);
                                }
                            }
                        }
                    }
                }
                //var_dump($inventoryData);var_dump($priceData);
                if (is_array($inventoryData) && count($inventoryData) > 0) {
                    $inventoryData['shopName'] = $data['shopName'];

                    //update inventory on jet
                    $url = Yii::getAlias('@webjeturl') . "/jetwebhook/curlprocessforinventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);

                    //update inventory on walmart
                    $url = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);

                    //update inventory on sears
                    /*$url = Yii::getAlias('@websearsurl') . "/searswebhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);*/

                    //update inventory on neweggmarketplace
                    $url = Yii::getAlias('@webneweggurl') . "/newegg-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);

                    //update inventory on neweggcanada
                    $url = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);

                }
                if (is_array($priceData) && count($priceData) > 0) {
                    $priceData['shopName'] = $data['shopName'];

                    //update price on jet
                    $url = Yii::getAlias('@webjeturl') . "/jetwebhook/curlprocessforpriceupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($priceData, $url);

                    //update price on walmart
                    $url = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/priceupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($priceData, $url);

                    //update price on sears
                    /*$url = Yii::getAlias('@websearsurl') . "/searswebhook/priceupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($priceData, $url);*/

                    //update inventory on neweggmarketplace
                    $url = Yii::getAlias('@webneweggurl') . "/newegg-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($priceData, $url);

                    //update inventory on neweggcanada
                    $url = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($priceData, $url);

                }

                //send curl request to process all data on jet/walmart
                $url = Yii::getAlias('@webjeturl') . "/jetwebhook/curlprocessforproductupdate?maintenanceprocess=1";
                Data::sendCurlRequest($data, $url);

                //walmart product update
                $url = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/productupdate?maintenanceprocess=1";
                Data::sendCurlRequest($data, $url);

                //newegg us product update
                $url = Yii::getAlias('@webneweggurl') . "/newegg-webhook/productupdate?maintenanceprocess=1";
                Data::sendCurlRequest($data, $url);

                //newegg canada product update
                $url = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/productupdate?maintenanceprocess=1";
                Data::sendCurlRequest($data, $url);

                //sears product update
                $url = Yii::getAlias('@websearsurl') . "/searswebhook/productupdate?maintenanceprocess=1";
                Data::sendCurlRequest($data, $url);
            } catch (Exception $e) {
                $this->createExceptionLog('actionCurlprocessforproductupdate', $e->getMessage(), $data['shopName']);
            }
        }
    }

    public function actionProductdelete()
    {
        $data = [];
        $data['shopName'] = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : "common";
        try {
            $webhook_content = '';
            $webhook = fopen('php://input', 'rb');
            while (!feof($webhook)) { //loop through the input stream while the end of file is not reached
                $webhook_content .= fread($webhook, 4096); //append the content on the current iteration
            }
            fclose($webhook); //close the resource
            //$webhook_content=$data='{"id":"8874517772","shopName":"ced-jet.myshopify.com"}';
            if ($webhook_content == '') {
                return;
            }

            $hmac = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';//for live site
            if (!Webhook::validateWebhook($webhook_content, $hmac)) {
                return;
            }

            $data = json_decode($webhook_content, true); //convert the json to array
            $data['shopName'] = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : "common";
            $url = Yii::getAlias('@webbaseurl') . "/shopifywebhook/curlprocessfordelete?maintenanceprocess=1";
            Data::sendCurlRequest($data, $url);
            //walmart product delete
            //$url = Yii::getAlias('@webwalmarturl')."/walmart-webhook/productdelete?maintenanceprocess=1";
            //Data::sendCurlRequest($data,$url);
            return true;
        } catch (Exception $e) {
            $this->createExceptionLog('actionProductdelete', $e->getMessage(), $data['shopName']);
            return;
        }
    }

    public function actionCurlprocessfordelete()
    {
        $data = $_POST;
        if (isset($data['shopName']) && isset($data['id'])) {
            $file_dir = \Yii::getAlias('@webroot') . '/var/jet/product/delete/' . $data['shopName'];
            if (!file_exists($file_dir)) {
                mkdir($file_dir, 0775, true);
            }
            $file_path = $file_dir . '/' . $data['id'] . '.log';
            $myfile = fopen($file_path, "w");
            fwrite($myfile, print_r($data, true));
            $errorstr = "";
            $deleted_variants = $count_variants = 0;
            $archiveSku = $configColl = [];
            $result = $isConfig = false;
            $product_id = $sqlresult = $query = $productmodel = $merchant_id = $jetHelper = "";

            $product_id = trim($data['id']);

            $query = "SELECT `id` FROM `user` WHERE username='" . $data['shopName'] . "' LIMIT 0,1";
            $configColl = Data::sqlRecords($query, 'one', 'select');

            if (isset($configColl['id'])) {
                //$isConfig=true;
                //$api_host="https://merchant-api.jet.com/api";
                $merchant_id = $configColl['id'];
                //$jetHelper = new Jetapimerchant("https://merchant-api.jet.com/api",$configColl['api_user'],$configColl['api_password']);
            }
            $query = "SELECT `product_id`,`option_sku` FROM `jet_product_variants` WHERE product_id='" . $product_id . "'";
            $sqlresult = Data::sqlRecords($query, 'all', 'select');
            $count_variants = count($sqlresult);

            if (!empty($sqlresult) && is_array($sqlresult)) {
                $result1 = false;
                //prepare skus for archive
                if (is_array($sqlresult) && count($sqlresult) > 0) {
                    foreach ($sqlresult as $value_sku) {
                        $archiveSku[] = $value_sku['option_sku'];
                    }
                    fwrite($myfile, "variant sku ready for archive:" . PHP_EOL . json_encode($archiveSku) . PHP_EOL);
                }
                $sql = "DELETE FROM `jet_product_variants` WHERE product_id='" . $product_id . "'";
                Data::sqlRecords($sql, null, 'delete');
            }

            // delete product data
            $deleted_product = 0;
            $query = "";
            $productmodel = "";
            $result = [];
            $query = "SELECT `id`,`merchant_id`,`sku`,`product_type` FROM `jet_product` WHERE id='" . $product_id . "' LIMIT 0,1";
            $result = Data::sqlRecords($query, 'one', 'select');

            if (isset($result['id'])) {
                fwrite($myfile, PHP_EOL . "product ready to delete" . PHP_EOL);
                $merchant_id = $result['merchant_id'];
                $result1 = false;
                //prepare skus for archive
                //if($isConfig){
                $archiveSku[] = $result['sku'];
                fwrite($myfile, "simple sku ready for archive:" . PHP_EOL . json_encode($archiveSku) . PHP_EOL);
                //}
                $product_type = $result['product_type'];
                $sql = "DELETE FROM `jet_product` WHERE id='" . $product_id . "'";
                Data::sqlRecords($sql, null, 'delete');
                //delete product type from product section
                if (isset($result['product_type'])) {
                    fwrite($myfile, PHP_EOL . "delete product type from product section" . PHP_EOL);
                    $delRes = Jetproductinfo::deleteProductType($product_type, $merchant_id);
                    fwrite($myfile, PHP_EOL . "delete product type response:" . PHP_EOL . $delRes . PHP_EOL);
                }
                $query = "SELECT `product_count` FROM `insert_product` WHERE merchant_id='" . $merchant_id . "' LIMIT 0,1";
                $insertData = Data::sqlRecords($query, 'one', 'select');
                if (isset($insertData['product_count'])) {
                    if ($insertData['product_count'] > 0)
                        $count = $insertData['product_count'] - 1;
                    $sqlresult1 = false;
                    $query1 = "UPDATE `insert_product` SET  product_count='" . $count . "' where merchant_id='" . $merchant_id . "'";
                    Data::sqlRecords($sql, null, 'update');
                }
            } else {
                $message1 = json_encode($result) . ' Either select result is false or deleted varients-' . $deleted_variants . ' not equal to count varients-' . $count_variants;
                fwrite($myfile, $message1 . PHP_EOL);
            }
            if (count($archiveSku) > 0/* && $isConfig*/) {
                //send curl request to archive/retire on jet/walmart
                $archive_data = ['archiveSku' => $archiveSku, 'merchant_id' => $merchant_id];
                $url = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/productdelete?maintenanceprocess=1";
                Data::sendCurlRequest($archive_data, $url);
                $url = Yii::getAlias('@webwalmarturl') . "/jetwebhook/curlprocessfordelete?maintenanceprocess=1";
                Data::sendCurlRequest($archive_data, $url);
                // newegg product detele
                $url = Yii::getAlias('@webneweggurl') . "/newegg-webhook/productdelete?maintenanceprocess=1";
                Data::sendCurlRequest($archive_data, $url);
                //newegg product detele
                $url = Yii::getAlias('@websearsurl') . "/searswebhook/productdelete?maintenanceprocess=1";
                Data::sendCurlRequest($archive_data, $url);
                //newegg canada product update
                $url = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/productdelete?maintenanceprocess=1";
                Data::sendCurlRequest($data, $url);
                //$message=Jetproductinfo::archiveProductOnJet($archiveSku,$jetHelper,$merchant_id);
                //fwrite($myfile,PHP_EOL."archive sku(s) response from jet:".PHP_EOL.$message.PHP_EOL);
            }
            fclose($myfile);
        }
    }

    public function actionIsinstall($appName='test')
    {
        $webhook_content = '';
        $webhook = fopen('php://input', 'rb');
        while (!feof($webhook)) { //loop through the input stream while the end of file is not reached
            $webhook_content .= fread($webhook, 4096); //append the content on the current iteration
        }
        fclose($webhook); //close the resource
        $data = "";
        $data = $webhook_content;
        if ($webhook_content == '' || empty(json_decode($data, true))) {
            return;
        }

        $hmac = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';//for live site
        /*if (!Webhook::validateWebhook($webhook_content, $hmac)) {
            return;
        }*/

        $data = json_decode($webhook_content, true); //convert the json to array
	    
        $data['appName'] = $appName;
        $data['shopName'] = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : "common";
        Log::createWebhookTrace('jet','uninstall',$data['shopName'],'test-'.time(),print_r($data,true));
        $url = Yii::getAlias('@webjeturl') . "/jetwebhook/curlprocessforuninstall?maintenanceprocess=1";
        Data::sendCurlRequest($data, $url);
        exit(0);
    }

    public function actionCancelled()
    {
        $webhook_content = '';

        $webhook = fopen('php://input', 'rb');
        while (!feof($webhook)) {
            $webhook_content .= fread($webhook, 4096);
        }
        $realdata = "";
        $data = $orderData = [];
        fclose($webhook);
        $realdata = $webhook_content;
        if ($webhook_content == '' || empty(json_decode($realdata, true))) {
            return;
        }

        $hmac = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';//for live site
        if (!Webhook::validateWebhook($webhook_content, $hmac)) {
            return;
        }

        $data = json_decode($realdata, true);
        if (isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'])) {
            $shopName = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
            $data['shopName'] = $shopName;
        }

        $url = Yii::getAlias('@webbaseurl') . "/shopifywebhook/curlprocessforordercancel?maintenanceprocess=1";
        Data::sendCurlRequest($data, $url);
        //update inventory on order cancel walmart
        //$url = Yii::getAlias('@webwalmarturl')."/walmart-webhook/ordercancel?maintenanceprocess=1";
        //Data::sendCurlRequest($data,$url);
        exit(0);
    }

    public function actionCurlprocessforordercancel()
    {
        $data = $_POST;
        if ($data && isset($data['id']) && isset($data['shopName'])) {
            $path = \Yii::getAlias('@webroot') . '/var/order/cancel/' . $data['shopName'];
            if (!file_exists($path)) {
                mkdir($path, 0775, true);
            }
            $file_path = $path . '/data.log';
            $myfile = fopen($file_path, "a+");
            fwrite($myfile, "\n[" . date('d-m-Y H:i:s') . "]\n");
            fwrite($myfile, json_encode($data));
            fclose($myfile);
            try {

                $shopName = $data['shopName'];
                $file_path = \Yii::getAlias('@webroot') . '/var/order/cancel/' . $shopName;

                if (!file_exists($file_path)) {
                    mkdir($file_path, 0775, true);
                }
                $orderId = $data['order_number'];

                $filename = $file_path . '/' . $orderId . '.log';
                $file = fopen($filename, 'a+');
                $Message = PHP_EOL . "[actionCurlprocessforordercancel][" . date('d-m-Y H:i:s') . "]\n";
                $Message .= "Process Qty After Order Process.";
                fwrite($file, $Message);
                $line_items = isset($data['line_items']) ? $data['line_items'] : [];
                if (count($line_items) == 0)
                    return;
                $inventoryData = [];
                foreach ($line_items as $key => $product) {
                    if (!isset($product['product_id'], $product['variant_id'])) {
                        continue;
                    }
                    $canceled_qty = 0;
                    $product_id = $product->product_id;
                    $variant_id = $product->variant_id;
                    $sku = $product->sku;
                    $ordered_qty = $product->quantity;
                    $fulfillable_quantity = $product->fulfillable_quantity;
                    $canceled_qty = $ordered_qty - $fulfillable_quantity;

                    $productColl = Data::sqlRecords("SELECT merchant_id,qty,id FROM `jet_product` WHERE variant_id='" . $variant_id . "' LIMIT 0,1", "one", "select");
                    if (isset($productColl['merchant_id'])) {
                        $updateQty = $productColl['qty'] + $canceled_qty;
                        $inventoryData[$variant_id] = ['product_id'=>$productColl['id'],'inventory' => $updateQty, 'sku' => $sku, 'merchant_id' => $productColl['merchant_id']];
                        $query = "UPDATE `jet_product` SET `qty`=" . $updateQty . "  WHERE `variant_id`=" . $variant_id;
                        Data::sqlRecords($query, null, "update");
                    }
                    $productVarColl = Data::sqlRecords("SELECT merchant_id,option_qty,product_id FROM `jet_product_variants` WHERE option_id='" . $variant_id . "' LIMIT 0,1", "one", "select");
                    if (isset($productVarColl['merchant_id'])) {
                        $updateQty = $productVarColl['option_qty'] + $canceled_qty;
                        $inventoryData[$variant_id] = ['product_id'=>$productVarColl['product_id'],'inventory' => $updateQty, 'sku' => $sku, 'merchant_id' => $productVarColl['merchant_id']];
                        $query = "UPDATE `jet_product_variants` SET `option_qty`=" . $updateQty . "  WHERE `option_id`=" . $variant_id;
                        Data::sqlRecords($query, null, "update");
                    }
                }
                //send curl request to update inventory on jet/walmart
                if (is_array($inventoryData) && count($inventoryData) > 0) {
                    $inventoryData['shopName'] = $data['shopName'];

                    //update inventory on walmart
                    $url = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);
                    //update inventory on jet
                    $url = Yii::getAlias('@webjeturl') . "/jetwebhook/curlprocessforinventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);

                    //update inventory on neweggmarketplace
                    $url = Yii::getAlias('@webneweggurl') . "/newegg-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);

                    //update inventory on neweggcanada
                    $url = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);
                }
                //send ordercanceldata on jet
                $url = Yii::getAlias('@webjeturl') . "/jetwebhook/curlprocessforordercancel?maintenanceprocess=1";
                Data::sendCurlRequest($data, $url);
                //update inventory on order cancel walmart
                $url = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/ordercancel?maintenanceprocess=1";
                Data::sendCurlRequest($data, $url);
                //update inventory on order cancel neweegg
                $url = Yii::getAlias('@webneweggurl') . "/newegg-webhook/ordercancel?maintenanceprocess=1";
                Data::sendCurlRequest($data, $url);
                //order cancel curl for newegg canada
                $url = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/ordercancel?maintenanceprocess=1";
                Data::sendCurlRequest($data, $url);
                fclose($file);
            } catch (\Exception $e) {
                $this->createExceptionLog('actionCurlprocessforordercancle', $e->getMessage());
                return;
            }
        }
    }

    public function checkcancelQty($sku, $order_items)
    {
        $cancel = 0;
        foreach ($order_items as $value) {
            if ($value['merchant_sku'] == $sku) {
                $cancel = $value['request_order_cancel_qty'];
                break;
            }
        }
        return $cancel;
    }

    public function actionCreateorder()
    {
        try {
            $webhook = fopen('php://input', 'rb');
            $webhook_content = "";
            while (!feof($webhook)) {
                $webhook_content .= fread($webhook, 4096);
            }
            fclose($webhook);
            $realdata = $webhook_content;
            if ($webhook_content == '' || empty(json_decode($realdata, true))) {
                return;
            }

            $hmac = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';//for live site
            if (!Webhook::validateWebhook($webhook_content, $hmac)) {
                return;
            }

            $data = json_decode($realdata, true);
            if (isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'])) {
                $shopName = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
                $data['shopName'] = $shopName;
            } else {
                return;
            }
            $url = Yii::getAlias('@webbaseurl') . "/shopifywebhook/curlprocessforordercreate?maintenanceprocess=1";
            Data::sendCurlRequest($data, $url);

            //update inventory on order create walmart
            /*$url = Yii::getAlias('@webwalmarturl')."/walmart-webhook/ordercreate?maintenanceprocess=1";
            Data::sendCurlRequest($data,$url);*/
            exit(0);
        } catch (Exception $e) {
            $this->createExceptionLog('actionCreateorder', $e->getMessage(), $shopName);
            return;
        }
    }

    public function actionCurlprocessforordercreate()
    {
        $data = $_POST;

        if ($data && isset($data['id']) && isset($data['shopName'])) {
            try {
                $shopName = $data['shopName'];
                $file_path = \Yii::getAlias('@webroot') . '/var/order/create/' . $shopName;

                $merchant_id = Data::getMerchantIdFromShop($shopName);

                if (!file_exists($file_path)) {
                    mkdir($file_path, 0775, true);
                }
                $orderId = $data['order_number'];

                $filename = $file_path . '/' . $orderId . '.log';

                $file = fopen($filename, 'a+');
                $Message = PHP_EOL . "[actionCurlprocessforordercreate][" . date('d-m-Y H:i:s') . "]\n";
                $Message .= "Entered In actionCurlprocessforordercreate";
                fwrite($file, $Message);

                fwrite($file, json_encode($data));

                $line_items = isset($data['line_items']) ? $data['line_items'] : [];
                $inventoryData = [];

                if (!$line_items || count($line_items) == 0)
                    return;

                foreach ($line_items as $key => $product) {

                    if (!isset($product['product_id'], $product['variant_id'])) {
                        continue;
                    }

                    $product_id = $product['product_id'];
                    $variant_id = $product['variant_id'];
                    $sku = $product['sku'];
                    $ordered_qty = $product['quantity'];
                    $fulfillable_quantity = $product['fulfillable_quantity'];

                    $productColl = Data::sqlRecords("SELECT id,merchant_id,qty FROM `jet_product` WHERE variant_id='" . $variant_id . "' LIMIT 0,1", "one", "select");

                    if (isset($productColl['merchant_id'])) {

                        $updateQty = $productColl['qty'] - $ordered_qty;
                        $inventoryData[$variant_id] = ['product_id' => $productColl['id'], 'inventory' => $updateQty, 'sku' => $sku, 'merchant_id' => $productColl['merchant_id']];

                        $query = "UPDATE `jet_product` SET `qty`=" . $updateQty . "  WHERE `variant_id`=" . $variant_id;
                        Data::sqlRecords($query, null, "update");
                    }

                    $productVarColl = Data::sqlRecords("SELECT merchant_id,option_qty,product_id FROM `jet_product_variants` WHERE option_id='" . $variant_id . "' LIMIT 0,1", "one", "select");
                    $checkVarUpdateInventory = false;

                    if (isset($productVarColl['merchant_id'])) {
                        $updateQty = $productVarColl['option_qty'] - $ordered_qty;

                        $inventoryData[$variant_id] = ['product_id' => $productVarColl['product_id'], 'inventory' => $updateQty, 'sku' => $sku, 'merchant_id' => $productVarColl['merchant_id']];

                        $query = "UPDATE `jet_product_variants` SET `option_qty`=" . $updateQty . "  WHERE `option_id`=" . $variant_id;
                        Data::sqlRecords($query, null, "update");
                    }
                }
                if (is_array($inventoryData) && count($inventoryData) > 0) {
                    $inventoryData['shopName'] = $data['shopName'];

                    //update inventory on jet
                    $url = Yii::getAlias('@webjeturl') . "/jetwebhook/curlprocessforinventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);

                    //update inventory on walmart
                    $url = Yii::getAlias('@webwalmarturl') . "/walmart-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);

                    //update inventory on neweggmarketplace
                    $url = Yii::getAlias('@webneweggurl') . "/newegg-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);

                    //update inventory on neweggcanada
                    $url = Yii::getAlias('@webneweggcanadaurl') . "/newegg-webhook/inventoryupdate?maintenanceprocess=1";
                    Data::sendCurlRequest($inventoryData, $url);
                }

                fclose($file);
            } catch (\Exception $e) {
                $this->createExceptionLog('actionCurlprocessforordercreate', $e->getMessage(), $shopName);
                return;
            }
        }
        return;
    }

    public static function log($data = "Himanshu", $file_path = "cancel.log")
    {
        if (!file_exists($file_path)) {
            mkdir($file_path, 0775, true);
        }
        $filename = $file_path . '/cancel.log';
        $file = fopen($filename, 'a+');

        if (is_array($data))
            fwrite($file, print_r($data, true));
        else
            fwrite($file, $data);

        fclose($file);
    }

    public function matchShipmentSku($lineItems, $orderItems)
    {
        $matchFlag = "";
        foreach ($orderItems as $key => $value) {
            if ($lineItems['sku'] == $value['merchant_sku']) {
                $matchFlag = "ced";
            }
        }
        return $matchFlag;
    }
}
