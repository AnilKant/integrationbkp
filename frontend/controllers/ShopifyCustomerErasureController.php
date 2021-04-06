<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 11/6/18
 * Time: 12:53 PM
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class ShopifyCustomerErasureController extends Controller
{

    public function actionWalmartCustomerDataErasure()
    {
        exit(0);
    }

    public function actionJetCustomerDataErasure()
    {
        exit(0);
    }

    public function actionNeweggCustomerDataErasure()
    {
        exit(0);
    }

    public function actionNeweggcaCustomerDataErasure()
    {
        exit(0);
    }

    public function actionSearsCustomerDataErasure()
    {
        $shopName = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : 'common';
        try {
            $webhook_content = '';
            $webhook = fopen('php://input', 'rb');
            while (!feof($webhook)) {
                $webhook_content .= fread($webhook, 4096);
            }
            fclose($webhook);

            if ($webhook_content == '' ) {
                return true;
            }

            $file_dir = \Yii::getAlias('@webroot') . '/var/gdpr/customer';
            if (!file_exists($file_dir)) {
                mkdir($file_dir, 0775, true);
            }
            $filenameOrig = $file_dir . '/' . $shopName . '.log';
            $fileOrig = fopen($filenameOrig, 'a+');
            fwrite($fileOrig, "\n" . date('d-m-Y H:i:s') . "\n" . print_r($webhook_content,true));
            fclose($fileOrig);

            return true;
        } catch (\Exception $e) {
            $this->createExceptionLog('actionSearsCustomerDataErasure', $e->getMessage(), $shopName);
            exit(0);
        }
    }

}