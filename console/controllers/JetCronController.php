<?php

namespace console\controllers;

use frontend\modules\jet\components\Data;
use frontend\modules\jet\components\Jetapimerchant;
use frontend\modules\jet\controllers\JetcronController as Shopifyjet;
use frontend\modules\jet\controllers\NewCronController as newCron;
use frontend\modules\jet\controllers\JetinvsyncController;
use Yii;
use yii\console\Controller;

/**
 * Cron controller
 */
class JetCronController extends Controller
{
    public function actionIndex()
    {
        // echo "cron service runnning";
        echo getcwd();
    }

    public function actionRefundstatus()
    {
        $obj = new Shopifyjet(Yii::$app->controller->id, '');
        $obj->actionUpdaterefundstatus(true);
    }

    public function actionCreatereturn()
    {
        $obj = new Shopifyjet(Yii::$app->controller->id, '');
        $obj->actionCreatejetreturn(true);
    }

    public function getErrorArray($errorsArray, $array)
    {
        if (isset($errorsArray[$array['merchant_order_id']])) {
            $array['reason'] = $errorsArray[$array['merchant_order_id']]['reason'] . '<br>' . $array['reason'];
            $errorsArray[$array['merchant_order_id']] = $array;
        } else {
            $errorsArray[$array['merchant_order_id']] = $array;
        }
        return $errorsArray;
    }

    public function actionProductstatus()
    {
        $obj = new Shopifyjet(Yii::$app->controller->id, '');
        $obj->actionUpdatejetproductstatus(true);
    }

    public function actionUpdatepaymentstatus()
    {
        $obj = new Shopifyjet(Yii::$app->controller->id, '');
        $obj->actionUpdaterecurringpaymentstatus(true);
    }// RecurringPaymentUpdate end

    public function actionInventoryUpdate()
    {
        return true;
    }

    /*public function actionCancelfailedorder(){
      $obj = new Shopifyjet(Yii::$app->controller->id,'');
      $obj->actionRemovefailedorders(true);
    }*/
    public function actionUpdatedynamicprice()
    {
        $obj = new Shopifyjet(Yii::$app->controller->id, '');
        $obj->actionDynamicprice(true);
    }

    public function actionFetchjetorder()
    {
        $obj = new Shopifyjet(Yii::$app->controller->id, '');
        $obj->actionFetchorder(true);
    }

    public function actionFetchjetordertest()
    {
        $obj = new Shopifyjet(Yii::$app->controller->id, '');
        $obj->actionOrderFetchTest(true);
    }

    public function actionSyncshopifyorder()
    {
        $obj = new Shopifyjet(Yii::$app->controller->id, '');
        $obj->actionSyncorder(true);
    }

    public function actionGetshipment()
    {
        $obj = new Shopifyjet(Yii::$app->controller->id, '');
        $obj->actionShiporder(true);
    }

    public function actionCheckApiStatus()
    {

        $jetApiScheduleData = Data::sqlRecords("SELECT `api_data`, `api_url`, `api_request_type`, `is_enabled`,`merchant_id` FROM `jet_api_schedule` WHERE `id`=1", "one", "select");

        if (isset($jetApiScheduleData['is_enabled']) && $jetApiScheduleData['is_enabled'] == 1) {
            $merchant_id = $jetApiScheduleData['merchant_id'];
            $jetConfiguration = Data::sqlRecords("SELECT `api_user`,`api_password` FROM `jet_configuration` WHERE merchant_id='" . $merchant_id . "' LIMIT 0,1", "one", "select");
            if (isset($jetConfiguration['api_user'])) {
                $jetHelper = new Jetapimerchant(API_HOST, $jetConfiguration['api_user'], $jetConfiguration['api_password']);
                $status = false;
                if ($jetApiScheduleData['api_request_type'] == "GET") {

                    $jetHelper->CGetRequest($jetApiScheduleData['api_request_type'], $merchant_id, $status);

                } elseif ($jetApiScheduleData['api_request_type'] == "POST") {

                    $jetHelper->CPostRequest($jetApiScheduleData['api_request_type'], $jetApiScheduleData['api_data'], $merchant_id, $status);

                } else {

                    $jetHelper->CPutRequest($jetApiScheduleData['api_request_type'], $jetApiScheduleData['api_data'], $merchant_id, $status);

                }
                if ($status && $status != 503) {

                    Data::sqlRecords("DELETE FROM `jet_api_schedule` WHERE id=1");

                }
            }
        }
    }

    /*
     * Update all inventory on jet within 24 hours
     * */
    public function actionInventoryupdatejson()
    {
        //$startTime = microtime(true);
        $obj = new JetinvsyncController(Yii::$app->controller->id, '');
        $obj->actionUploadinventory(true);
        /* $endTime = microtime(true);
        echo $startTime.'-'.$endTime."=".($endTime-$startTime)/60;
        die("<hr>");  */
    }

    /*
     * Process uploaded inventory json file
     *  */
    public function actionProcessinvjson()
    {
        $startTime = microtime(true);
        $obj = new JetinvsyncController(Yii::$app->controller->id, '');
        $res = $obj->actionProcessuploadedfiles(true);
        $endTime = microtime(true);
        echo $startTime . '-' . $endTime . "=" . ($endTime - $startTime) / 60;
        echo "<hr><pre>";
        print_r($res);
        die("<hr>Test");
    }

    /*
    * Acknowledge and verify all upload json files
    *  */
    public function actionProcessAndVerifyAllJsonFiles()
    {
        $obj = new JetinvsyncController(Yii::$app->controller->id, '');
        $processOnlyInventory = "no";
        $obj->actionProcessuploadedfiles($processOnlyInventory);
    }

    /*
     * Check all the orders on ready state (from Jet)
     * */
    public function actionCheckreadyorder()
    {
        $startTime = microtime(true);
        $obj = new Shopifyjet(Yii::$app->controller->id, '');
        $obj->actionReadyorder(true);
        $endTime = microtime(true);
        echo $startTime . '-' . $endTime . "=" . ($endTime - $startTime) / 60;
        die("<hr>");
    }
}
