<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 12/7/18
 * Time: 3:54 PM
 */
namespace frontend\controllers;

use frontend\components\QueryHelper;
use frontend\components\SyncProduct\WalmartProductSync;
use frontend\modules\walmart\components\Data;
use frontend\modules\walmart\components\ShopifyClientHelper;
use frontend\modules\walmart\models\WalmartCronSchedule;
use frontend\modules\walmartnew\models\WalmartInstallation;
use yii\web\Controller;
use Yii;

class SyncWalmartProductController extends Controller
{
    public $marketplace = [];

    public function actionSyncWalmartInventory()
    {
        $processedMerchantCount = 0;
        $size = 1;

        $result = WalmartCronSchedule::find()->where(['cron_name' => 'sync_inventory'])->one();
        if ($result && $result['cron_data'] != "") {

            $cron_array = json_decode($result['cron_data'], true);

        } else {

            $query = "SELECT walmart_extension_detail.merchant_id,merchant_db.db_name FROM walmart_extension_detail INNER JOIN merchant_db ON walmart_extension_detail.merchant_id=merchant_db.merchant_id WHERE walmart_extension_detail.`status` NOT IN ('License Expired' , 'Trial Expired') AND walmart_extension_detail.`app_status` LIKE 'install'";

            $cron_array = QueryHelper::sqlRecords($query, null, 'all');
        }

        if (is_array($cron_array) && count($cron_array)) {
            foreach ($cron_array as $key => $merchant) {

                if ($processedMerchantCount == $size)
                    break;

                $processedMerchantCount++;

                $shopdata = Data::getWalmartShopDetails($merchant['merchant_id']);
                $sc = new ShopifyClientHelper($shopdata['shop_url'], $shopdata['token'], WALMART_APP_KEY, WALMART_APP_SECRET);

                $walmartProductSync = new WalmartProductSync($merchant['merchant_id'], $merchant['db_name'], $sc);

                $walmartProductSync->insertProcessedMerchant();
                $response = $walmartProductSync->execute();
                if(!$response)
                {
                    $walmartProductSync->unsetSyncMerchant();
                    unset($cron_array[$key]);
                }
            }
        }

        if (count($cron_array) == 0)
            $result->cron_data = "";
        else
            $result->cron_data = json_encode($cron_array);

        $result->save(false);

    }

}