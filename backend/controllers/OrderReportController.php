<?php

namespace backend\controllers;

use backend\components\Data;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use frontend\modules\jet\components\Dashboard\Earninginfo;

class OrderReportController extends MainController
{
    public function actionIndex()
    {
        //get records of failed orders created by today
        $countFailedOrders = $jetReadyOrders  = [];
        $param="";
        $value="";
        $isDate=false;
        $sql = " SELECT `cron_data` FROM `jet_cron_schedule` WHERE `cron_name`='check_ready_order'";
        $jetReadyOrders = Data::sqlRecords($sql,'one','select');
        $param = Yii::$app->request->post('param');
        $marketplace = Yii::$app->request->post('marketplace');
        if(!$marketplace)
            $marketplace = Yii::$app->request->get('marketplace');
        if(Yii::$app->request->post('param'))
        {
            if(Yii::$app->request->post('param')=="duration")
            {
                $value=Yii::$app->request->post('value');
                //failed order count
                $jet_query='SELECT count(*) as `count` FROM `jet_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $walmart_query='SELECT count(*) as `count` FROM `walmart_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $newegg_query='SELECT count(*) as `count` FROM `newegg_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $newegg_ca_query='SELECT count(*) as `count` FROM `newegg_can_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $sears_query='SELECT count(*) as `count` FROM `sears_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $bonanza_query='SELECT count(*) as `count` FROM `bonanza_order_details` WHERE `status`="Sync Failed" AND `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
	            $etsy_query='SELECT count(*) as `count` FROM `etsy_orders` WHERE `error` IS NOT NULL AND `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
	            $wish_query='SELECT count(*) as `count` FROM `wish_failed_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $bestbuyca_query='SELECT count(*) as `count` FROM `bestbuyca_failed_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $walmartca_query='SELECT count(*) as `count` FROM `walmartca_orders` WHERE `order_status`=4 AND `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $pricefalls_query='SELECT count(*) as `count` FROM `pricefalls_failed_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $tophatter_query='SELECT count(*) as `count` FROM `tophatter_failed_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $fruugo_query='SELECT count(*) as `count` FROM `fruugo_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $rakutenus_query = 'SELECT count(*) as `count` FROM `rakutenus_order` WHERE (`fetch_status`=0 OR order_status=5) AND `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';


                //sales order
                $jet_sales_query='SELECT count(*) as `count` FROM `jet_order_detail` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $walmart_sales_query='SELECT count(*) as `count` FROM `walmart_order_details` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $newegg_sales_query='SELECT count(*) as `count` FROM `newegg_order_detail` WHERE DATE(`order_date`) = "'.$value.'"';
                $newegg_ca_sales_query='SELECT count(*) as `count` FROM `newegg_can_order_detail` WHERE DATE(`order_date`) = "'.$value.'"';
                $sears_sales_query='SELECT count(*) as `count` FROM `sears_order_details` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $bonanza_sales_query='SELECT count(*) as `count` FROM `bonanza_order_details` WHERE  `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $wish_sales_query='SELECT count(*) as `count` FROM `wish_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $etsy_sales_query='SELECT count(*) as `count` FROM `etsy_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $bestbuyca_sales_query='SELECT count(*) as `count` FROM `bestbuyca_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $walmartca_sales_query='SELECT count(*) as `count` FROM `walmartca_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $pricefalls_sales_query='SELECT count(*) as `count` FROM `pricefalls_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $tophatter_sales_query='SELECT count(*) as `count` FROM `tophatter_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $fruugo_sales_query='SELECT count(*) as `count` FROM `fruugo_order_details` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $rakutenus_sales_query='SELECT count(*) as `count` FROM `rakutenus_order` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
            }
            elseif(Yii::$app->request->post('param')=="date")
            {
                $isDate=true;
                $value=date('Y-m-d',strtotime(Yii::$app->request->post('value')));
            }
        }     
        else
        {
            $isDate=true;
            $value=date('Y-m-d');
        }
        
        if($isDate)
        {
            $jet_query='SELECT count(*) as `count` FROM `jet_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
            $walmart_query='SELECT count(*) as `count` FROM `walmart_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
            $newegg_query='SELECT count(*) as `count` FROM `newegg_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
            $newegg_ca_query='SELECT count(*) as `count` FROM `newegg_can_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
            $sears_query='SELECT count(*) as `count` FROM `sears_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
            $bonanza_query = 'SELECT count(*) `count` FROM `bonanza_order_details` WHERE `status`="Sync Failed" AND  DATE(`created_at`) = "'.$value.'"';
	        $etsy_query = 'SELECT count(*) `count` FROM `etsy_orders` WHERE DATE(`created_at`) = "'.$value.'" AND `error` IS NOT NULL  ';
	        $wish_query = 'SELECT count(*) `count` FROM `wish_failed_orders` WHERE DATE(`created_at`) = "'.$value.'"';
            $bestbuyca_query = 'SELECT count(*) `count` FROM `bestbuyca_failed_orders` WHERE DATE(`created_at`) = "'.$value.'"';
            $walmartca_query = 'SELECT count(*) `count` FROM `walmartca_orders` WHERE `order_status`=4 AND DATE(`created_at`) = "'.$value.'"';
            $tophatter_query = 'SELECT count(*) `count` FROM `tophatter_failed_orders` WHERE DATE(`created_at`) = "'.$value.'"';
            $fruugo_query = 'SELECT count(*) `count` FROM `fruugo_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
            $rakutenus_query = "SELECT count(*) as `count` FROM `rakutenus_order` WHERE DATE(`created_at`) = '".$value."'";

            $jet_sales_query='SELECT count(*) as `count` FROM `jet_order_detail` WHERE DATE(`created_at`) = "'.$value.'"';
            $walmart_sales_query='SELECT count(*) as `count` FROM `walmart_order_details` WHERE DATE(`created_at`) = "'.$value.'"';
            $newegg_sales_query='SELECT count(*) as `count` FROM `newegg_order_detail` WHERE DATE(`order_date`) = "'.$value.'"';
            $newegg_ca_sales_query='SELECT count(*) as `count` FROM `newegg_can_order_detail` WHERE DATE(`order_date`) = "'.$value.'"';
            $sears_sales_query='SELECT count(*) as `count` FROM `sears_order_details` WHERE DATE(`created_at`) = "'.$value.'"';
            $bonanza_sales_query='SELECT count(*) as `count` FROM `bonanza_order_details` WHERE  DATE(`created_at`) = "'.$value.'"';
            $wish_sales_query='SELECT count(*) as `count` FROM `wish_orders` WHERE DATE(`created_at`) = "'.$value.'"';
            $etsy_sales_query='SELECT count(*) as `count` FROM `etsy_orders` WHERE DATE(`created_at`) = "'.$value.'"';
            $bestbuyca_sales_query='SELECT count(*) as `count` FROM `bestbuyca_orders` WHERE DATE(`created_at`) = "'.$value.'"';
            $walmartca_sales_query='SELECT count(*) as `count` FROM `walmartca_orders` WHERE DATE(`created_at`) = "'.$value.'"';
            $tophatter_sales_query='SELECT count(*) as `count` FROM `tophatter_orders` WHERE DATE(`created_at`) = "'.$value.'"';
            $fruugo_sales_query='SELECT count(*) as `count` FROM `fruugo_order_details` WHERE DATE(`created_at`) = "'.$value.'"';
            $rakutenus_sales_query='SELECT count(*) as `count` FROM `rakutenus_order` WHERE DATE(`created_at`) = "'.$value.'"';

        }

        if($marketplace=='all' || $marketplace=='jet'){
            $countFailedOrders['jet'] = Data::sqlRecords($jet_query,'one');
            $countFailedOrders['jet_ready_order'] = $jetReadyOrders['cron_data'];
            $countOrders['jet'] = Data::sqlRecords($jet_sales_query,'one');
        }
        if($marketplace=='all' || $marketplace=='walmart'){
            $countOrders['walmart'] = Data::sqlRecords($walmart_sales_query,'one');
            $countFailedOrders['walmart'] = Data::sqlRecords($walmart_query,'one');
        }
        if($marketplace=='all' || $marketplace=='newegg'){
            $countOrders['newegg'] = Data::sqlRecords($newegg_sales_query,'one');
            $countFailedOrders['newegg'] = Data::sqlRecords($newegg_query,'one');
        }
        if($marketplace=='all' || $marketplace=='newegg_ca'){
            $countOrders['newegg_ca'] = Data::sqlRecords($newegg_ca_sales_query,'one');
            $countFailedOrders['newegg_ca'] = Data::sqlRecords($newegg_ca_query,'one');
        }
        if($marketplace=='all' || $marketplace=='sears'){
            $countOrders['sears'] = Data::sqlRecords($sears_sales_query,'one');
            $countFailedOrders['sears'] = Data::sqlRecords($sears_query,'one');
        }
        if($marketplace=='all' || $marketplace=='bonanza'){
            $countOrders['bonanza'] = Data::integrationSqlRecords($bonanza_sales_query,'one');
            $countFailedOrders['bonanza'] = Data::integrationSqlRecords($bonanza_query,'one');
        }
        if($marketplace=='all' || $marketplace=='etsy'){

            $countOrders['etsy'] = Data::integrationSqlRecords($etsy_sales_query,'one');
            $countFailedOrders['etsy'] = Data::integrationSqlRecords($etsy_query,'one');
        }
        if($marketplace=='all' || $marketplace=='wish'){
            $countOrders['wish'] = Data::integrationSqlRecords($wish_sales_query,'one');
            $countFailedOrders['wish'] = Data::integrationSqlRecords($wish_query,'one');
        }
        if($marketplace=='all' || $marketplace=='bestbuyca'){
            $countOrders['bestbuyca'] = Data::integrationSqlRecords($bestbuyca_sales_query,'one');
            $countFailedOrders['bestbuyca'] = Data::integrationSqlRecords($bestbuyca_query,'one');
        }
        if($marketplace=='all' || $marketplace=='walmart-canada'){
            $countOrders['walmart-canada'] = Data::integrationSqlRecords($walmartca_sales_query,'one');
            $countFailedOrders['walmart-canada'] = Data::integrationSqlRecords($walmartca_query,'one');
        }
        if($marketplace=='all' || $marketplace=='tophatter'){
            $countOrders['tophatter'] = Data::integrationSqlRecords($tophatter_sales_query,'one');
            $countFailedOrders['tophatter'] = Data::integrationSqlRecords($tophatter_query,'one');
        }
        if($marketplace=='all' || $marketplace=='fruugo'){
            $countOrders['fruugo'] = Data::integrationSqlRecords($fruugo_sales_query,'one');
            $countFailedOrders['fruugo'] = Data::integrationSqlRecords($fruugo_query,'one');
        }
        if($marketplace=='all' || $marketplace=='rakutenus'){
            $countOrders['rakutenus'] = Data::integrationSqlRecords($rakutenus_sales_query,'one');
            $countFailedOrders['rakutenus'] = Data::integrationSqlRecords($rakutenus_query,'one');
        }

        if(!$param){
            $param='date';
            $value=date('Y-m-d');
        }

        if(count($countFailedOrders) && count($countOrders))
        {
            if(Yii::$app->request->post('isAjax'))
            {
                $response = ['data'=>$countFailedOrders,'order_total'=>$countOrders,'param'=>$param,'value'=>$value];
                return json_encode($response);
            }else{
                return $this->render('index',['data'=>$countFailedOrders,'order_total'=>$countOrders,'param'=>$param,'value'=>$value]);
            }
        }else{
            echo "Please use param like marketplace=walmart or marketplace=etsy";die;
        }
    }

    public function actionView()
    {
        $data = Yii::$app->request->get();
        $countFailedOrders='';
        if( isset($data['marketplace'],$data['param']))
        {
            if($data['param']=="date")
            {   
                $value=date('Y-m-d',strtotime($data['value']));
                $jet_query='SELECT id,merchant_id,reference_order_id as order_id,reason,created_at,"jet" as marketplace FROM `jet_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
                $walmart_query='SELECT id,merchant_id,purchase_order_id as order_id,reason,created_at,"walmart" as marketplace FROM `walmart_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
                $newegg_query='SELECT id,merchant_id,order_number as order_id,error_reason as reason,created_at,"newegg" as marketplace FROM `newegg_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
                $newegg_ca_query='SELECT id,merchant_id,order_number as order_id,error_reason as reason,created_at,"newegg_ca" as marketplace FROM `newegg_can_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
                $sears_query = 'SELECT id,merchant_id,purchase_order_id as order_id,reason,created_at,"sears" as marketplace FROM `sears_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
                $bonanza_query = 'SELECT id,merchant_id,purchase_order_id as order_id,error  as reason,created_at,"bonanza" as marketplace FROM `bonanza_order_details` WHERE `status`="Sync Failed" AND  DATE(`created_at`) = "'.$value.'"';
                $wish_query = 'SELECT id,merchant_id,wish_order_id as order_id,reason,created_at,"wish" as marketplace FROM `wish_failed_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $etsy_query = 'SELECT id,merchant_id,receipt_id as order_id,error as reason,created_at_app,JSON_VALUE(order_data, "$.creation_tsz") as created_at,"etsy" as marketplace FROM `etsy_orders` WHERE `error` IS NOT NULL AND DATE(`created_at`) = "'.$value.'"';
                $bestbuyca_query = 'SELECT id,merchant_id,bestbuyca_order_id as order_id,error as reason,created_at,"bestbuyca" as marketplace FROM `bestbuyca_failed_orders` WHERE DATE(`created_at`) = "'.$value.'"';

                $walmartca_query = 'SELECT id,merchant_id,purchase_order_id as order_id,error_message,created_at,"walmart-canada" as marketplace FROM `walmartca_orders` WHERE `order_status`=4 AND DATE(`created_at`) = "'.$value.'"';

                $pricefalls_query = 'SELECT id,merchant_id,pricefalls_order_id as order_id,reason,created_at,"pricefalls" as marketplace FROM `pricefalls_failed_orders` WHERE DATE(`created_at`) = "'.$value.'"';

                $tophatter_query = 'SELECT id,merchant_id,tophatter_order_id as order_id,reason,created_at,"tophatter" as marketplace FROM `tophatter_failed_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $fruugo_query = 'SELECT id,merchant_id,purchase_order_id as order_id,reason,created_at,"fruugo" as marketplace FROM `fruugo_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
                $rakutenus_query = 'SELECT id,merchant_id,order_number as order_id,error_message,created_at,"rakutenus" as marketplace FROM `rakutenus_order` WHERE DATE(`created_at`) = "'.$value.'"';
            }
            elseif($data['param']=="duration")
            {
                $value=$data['value'];
                $jet_query='SELECT id,merchant_id,reference_order_id as order_id,reason,created_at,"jet" as marketplace FROM `jet_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $walmart_query='SELECT id,merchant_id,purchase_order_id as order_id,reason,created_at,"walmart" as marketplace FROM `walmart_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $newegg_query='SELECT id,merchant_id,order_number as order_id,error_reason as reason,created_at,"newegg" as marketplace FROM `newegg_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $newegg_ca_query='SELECT id,merchant_id,order_number as order_id,error_reason as reason,created_at,"newegg_ca" as marketplace FROM `newegg_can_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $sears_query='SELECT id,merchant_id,purchase_order_id as order_id,reason ,created_at,"sears" as marketplace FROM `sears_order_import_error` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $bonanza_query='SELECT id,merchant_id,purchase_order_id as order_id,error as reason ,created_at,"bonanza" as marketplace FROM `bonanza_order_details` WHERE `status`="Sync Failed" AND  `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $wish_query = 'SELECT id,merchant_id,wish_order_id as order_id,reason,created_at,"wish" as marketplace FROM `wish_failed_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $etsy_query = 'SELECT id,merchant_id,receipt_id as order_id,error as reason,created_at_app,JSON_VALUE(order_data, "$.creation_tsz") as created_at,"etsy" as marketplace FROM `etsy_orders` WHERE `error` IS NOT NULL AND `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $bestbuyca_query = 'SELECT id,merchant_id,bestbuyca_order_id as order_id,error as reason,created_at,"bestbuyca" as marketplace FROM `bestbuyca_failed_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $walmartca_query = 'SELECT id,merchant_id,purchase_order_id as order_id,error_message,created_at,"walmart-canada" as marketplace FROM `walmartca_orders` WHERE `order_status`=4 AND DATE(`created_at`) = "'.$value.'"';
                $pricefalls_query = 'SELECT id,merchant_id,pricefalls_order_id as order_id,reason,created_at,"pricefalls" as marketplace FROM `pricefalls_failed_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $tophatter_query = 'SELECT id,merchant_id,tophatter_order_id as order_id,created_at,"tophatter" as marketplace FROM `tophatter_failed_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $fruugo_query = 'SELECT id,merchant_id,purchase_order_id as order_id,reason,created_at,"fruugo" as marketplace FROM `fruugo_order_import_error` WHERE DATE(`created_at`) = "'.$value.'"';
                $rakutenus_query = 'SELECT id,merchant_id,order_number as order_id,error_message,created_at,"rakutenus" as marketplace FROM `rakutenus_order` WHERE DATE(`created_at`) = "'.$value.'"';
            }
            if($data['marketplace']=="jet")
                $countFailedOrders = Data::sqlRecords($jet_query,'all');
            elseif($data['marketplace']=="walmart")
                $countFailedOrders = Data::sqlRecords($walmart_query,'all');
            elseif($data['marketplace']=="newegg")
                $countFailedOrders = Data::sqlRecords($newegg_query,'all');
            elseif($data['marketplace']=="newegg_ca")
                $countFailedOrders = Data::sqlRecords($newegg_ca_query,'all');
            elseif($data['marketplace']=="sears")
                $countFailedOrders = Data::sqlRecords($sears_query,'all');
            elseif($data['marketplace']=="bonanza")
                $countFailedOrders = Data::integrationSqlRecords($bonanza_query,'all'); 
            elseif($data['marketplace']=="wish")
                $countFailedOrders = Data::integrationSqlRecords($wish_query,'all');   
            elseif($data['marketplace']=="etsy")
                $countFailedOrders = Data::integrationSqlRecords($etsy_query,'all');
            elseif($data['marketplace']=="bestbuyca")
                $countFailedOrders = Data::integrationSqlRecords($bestbuyca_query,'all');
            elseif($data['marketplace']=="walmart-canada")
                $countFailedOrders = Data::integrationSqlRecords($walmartca_query,'all');
            elseif($data['marketplace']=="pricefalls")
                $countFailedOrders = Data::integrationSqlRecords($pricefalls_query,'all');
            elseif($data['marketplace']=="tophatter")
                $countFailedOrders = Data::integrationSqlRecords($tophatter_query,'all');  
            elseif($data['marketplace']=="fruugo")
                $countFailedOrders = Data::integrationSqlRecords($fruugo_query,'all'); 
            elseif($data['marketplace']=="rakutenus")
                $countFailedOrders = Data::integrationSqlRecords($rakutenus_query,'all'); 
        }
      
            $dataProvider = new ArrayDataProvider([
                'allModels' => $countFailedOrders,
                'sort' => [
                    'attributes' => ['id', 'merchant_id','created_at','order_id'],
                ],
                'pagination' => [
                    'pageSize' => 30,
                ],
                'key' => 'id',
            ]);
            //var_dump($dataProvider->getModels());die;
            return $this->render('view', [
                'dataProvider' => $dataProvider,
                'marketplace'=>$data['marketplace']
            ]);
    
    }
    public function actionTotalorder()
    {
        $data = Yii::$app->request->get();
        date_default_timezone_set(Yii::$app->datetime->getTimezone());
        $countFailedOrders='';
        if($data && isset($data['marketplace'],$data['param']))
        {
            if($data['param']=="date")
            {   
                $value=date('Y-m-d',strtotime($data['value']));
                $jet_query='SELECT id,merchant_id,reference_order_id as order_id,created_at,"jet" as marketplace FROM `jet_order_detail` WHERE DATE(`created_at`) = "'.$value.'"';
                $walmart_query='SELECT id,merchant_id,purchase_order_id as order_id,created_at,"walmart" as marketplace FROM `walmart_order_details` WHERE DATE(`created_at`) = "'.$value.'"';
                $sears_query='SELECT id,merchant_id,purchase_order_id as order_id, created_at,"sears" as marketplace FROM `sears_order_details` WHERE DATE(`created_at`) = "'.$value.'"';
                $newegg_query='SELECT id,merchant_id,order_number as order_id,order_date as created_at,"newegg" as marketplace FROM `newegg_order_detail` WHERE DATE(`order_date`) = "'.$value.'"';
                $newegg_ca_query='SELECT id,merchant_id,order_number as order_id,order_date as created_at,"newegg" as marketplace FROM `newegg_can_order_detail` WHERE DATE(`order_date`) = "'.$value.'"';
                $bonanza_query='SELECT id,merchant_id,purchase_order_id as order_id, created_at,"bonanza" as marketplace FROM `bonanza_order_details` WHERE DATE(`created_at`) = "'.$value.'"';
                $wish_query='SELECT id,merchant_id,wish_order_id as order_id, created_at,"wish" as marketplace FROM `wish_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $etsy_query='SELECT id,merchant_id,receipt_id as order_id,created_at_app,JSON_VALUE(order_data, "$.creation_tsz") as created_at,"etsy" as marketplace,error FROM `etsy_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $bestbuyca_query='SELECT id,merchant_id,bestbuyca_order_id as order_id, created_at,"bestbuyca" as marketplace FROM `bestbuyca_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $walmartca_query='SELECT id,merchant_id,purchase_order_id as order_id, created_at,"walmart-canada" as marketplace FROM `walmartca_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $pricefalls_query='SELECT id,merchant_id,pricefalls_order_id as order_id, created_at,"pricefalls" as marketplace FROM `pricefalls_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $tophatter_query='SELECT id,merchant_id,tophatter_order_id as order_id, created_at,"tophatter" as marketplace FROM `tophatter_orders` WHERE DATE(`created_at`) = "'.$value.'"';
                $fruugo_query='SELECT id,merchant_id,purchase_order_id as order_id, created_at,"fruugo" as marketplace FROM `fruugo_order_details` WHERE DATE(`created_at`) = "'.$value.'"';
                $rakutenus_query='SELECT id,merchant_id,order_number as order_id, created_at,"rakutenus" as marketplace FROM `rakutenus_order` WHERE DATE(`created_at`) = "'.$value.'"';

            }
            elseif($data['param']=="duration")
            {
                $value=$data['value'];
                $jet_query='SELECT id,merchant_id,reference_order_id as order_id,created_at,"jet" as marketplace FROM `jet_order_detail` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $walmart_query='SELECT id,merchant_id,purchase_order_id as order_id,created_at,"walmart" as marketplace FROM `walmart_order_details` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $newegg_query='SELECT id,merchant_id,order_number as order_id,order_date as created_at,"newegg" as marketplace FROM `newegg_order_detail` WHERE `order_date`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $newegg_ca_query='SELECT id,merchant_id,order_number as order_id,order_date as created_at,"newegg" as marketplace FROM `newegg_can_order_detail` WHERE `order_date`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $sears_query='SELECT id,merchant_id,purchase_order_id as order_id, created_at,"sears" as marketplace FROM `sears_order_details` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $bonanza_query='SELECT id,merchant_id,purchase_order_id as order_id, created_at,"bonanza" as marketplace FROM `bonanza_order_details` WHERE  `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $wish_query='SELECT id,merchant_id,wish_order_id as order_id, created_at,"wish" as marketplace FROM `wish_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $etsy_query='SELECT id,merchant_id,receipt_id as order_id,created_at_app ,JSON_VALUE(order_data, "$.creation_tsz") as created_at,"etsy" as marketplace FROM `etsy_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $bestbuyca_query='SELECT id,merchant_id,bestbuyca_order_id as order_id, created_at,"bestbuyca" as marketplace FROM `bestbuyca_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $walmartca_query='SELECT id,merchant_id,purchase_order_id as order_id, created_at,"walmart-canada" as marketplace FROM `walmartca_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $pricefalls_query='SELECT id,merchant_id,pricefalls_order_id as order_id, created_at,"pricefalls" as marketplace FROM `pricefalls_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $tophatter_query='SELECT id,merchant_id,tophatter_order_id as order_id, created_at,"tophatter" as marketplace FROM `tophatter_orders` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $fruugo_query='SELECT id,merchant_id,purchase_order_id as order_id, created_at,"fruugo" as marketplace FROM `fruugo_order_details` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';
                $rakutenus_query='SELECT id,merchant_id,order_number as order_id, created_at,"rakutenus" as marketplace FROM `rakutenus_order` WHERE `created_at`> DATE_SUB(NOW(), INTERVAL '.$value.')';

           
            }
            if($data['marketplace']=="jet")
                $countFailedOrders = Data::sqlRecords($jet_query,'all');
            elseif($data['marketplace']=="walmart")
                $countFailedOrders = Data::sqlRecords($walmart_query,'all');
             elseif($data['marketplace']=="sears")
                $countFailedOrders = Data::sqlRecords($sears_query,'all');
            elseif($data['marketplace']=="newegg")
                $countFailedOrders = Data::sqlRecords($newegg_query,'all');
            elseif($data['marketplace']=="newegg_ca")
                $countFailedOrders = Data::sqlRecords($newegg_ca_query,'all');
            elseif($data['marketplace']=="sears")
                $countFailedOrders = Data::sqlRecords($sears_query,'all');
            elseif($data['marketplace']=="bonanza")
                $countFailedOrders = Data::integrationSqlRecords($bonanza_query,'all');
            elseif($data['marketplace']=="wish")
                $countFailedOrders = Data::integrationSqlRecords($wish_query,'all');  
            elseif($data['marketplace']=="etsy")
                $countFailedOrders = Data::integrationSqlRecords($etsy_query,'all');
            elseif($data['marketplace']=="bestbuyca")
                $countFailedOrders = Data::integrationSqlRecords($bestbuyca_query,'all'); 
            elseif($data['marketplace']=="walmart-canada")
                $countFailedOrders = Data::integrationSqlRecords($walmartca_query,'all');
            elseif($data['marketplace']=="pricefalls")
                $countFailedOrders = Data::integrationSqlRecords($pricefalls_query,'all');
            elseif($data['marketplace']=="tophatter")
                $countFailedOrders = Data::integrationSqlRecords($tophatter_query,'all');
            elseif($data['marketplace']=="fruugo")
                $countFailedOrders = Data::integrationSqlRecords($fruugo_query,'all');
            elseif($data['marketplace']=="rakutenus")
                $countFailedOrders = Data::integrationSqlRecords($rakutenus_query,'all');
        }
        
            $dataProvider = new ArrayDataProvider([
                'allModels' => $countFailedOrders,
                'sort' => [
                    'attributes' => ['id', 'merchant_id','created_at','order_id','reason'],
                ],
                'pagination' => [
                    'pageSize' => $data['per-page']??30,
                ],
                'key' => 'id',
            ]);
            //var_dump($dataProvider->getModels());die;
            return $this->render('view', [
                'dataProvider' => $dataProvider,
                'marketplace'=>$data['marketplace']
            ]);
        }

    public function actionTotalrevenue(){
        $date1 = $_GET['from'];
        $date2 = $_GET['to'];
        $jet = Earninginfo::getJetEarning($date1,$date2);
        $walmart = Earninginfo::getWalmartEarning($date1,$date2);
        echo "<pre> date1 - ".$date1." == date2 - ".$date2;
        echo "<br> walmart => ".$walmart;
        echo "<br> jet => ".$jet;
        
    }
}    