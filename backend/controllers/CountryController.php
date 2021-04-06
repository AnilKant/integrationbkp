<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\controllers\MainController;

/**
 * Site controller
 */
class CountryController extends MainController
{

    /*public function beforeAction($action){
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        return true;
    }*/

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        $connection=Yii::$app->getDb();
        $model = $connection->createCommand('SELECT `ip_address` AS `ip`,`merchant_id` FROM `walmart_shop_details` WHERE `ip_address` IS NOT NULL AND `install_country` IS NULL')->queryAll();
        $ch = curl_init();
        foreach ($model as $value) {
            // set url
            $url = 'https://ipapi.co/'.$value['ip'].'/json/';
            curl_setopt($ch, CURLOPT_URL, $url);

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // $output contains the output string
            $output = curl_exec($ch);
            if($output){
                $data = json_decode($output,true);
                if(isset($data['country_name']))
                {
                    $command = $connection->createCommand('UPDATE `walmart_shop_details` SET `install_country` = "'.$data['country_name'].'" WHERE `merchant_id`="'.$value['merchant_id'].'"');
                    $command->execute();
                }
            }
        }
        // close curl resource to free up system resources
        curl_close($ch); 

    }
}