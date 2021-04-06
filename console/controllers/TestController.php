<?php
namespace console\controllers;

use frontend\modules\Base\components\Framework\App;
use frontend\modules\rabbitmq\components\RQueueHelper;
use Yii;
use yii\console\Controller;
use frontend\modules\walmart\models\WalmartCronSchedule;
use frontend\modules\walmart\components\Walmartappdetails;
use frontend\modules\walmart\controllers\WalmartorderdetailController;

/**
* Test controller
*/
class TestController extends Controller 
{
    public function actionIndex() 
    {
    	var_dump(Yii::getAlias('@webroot'));
    	var_dump(Yii::getAlias('@rootdir'));die;
        echo "cron service runnning";
    }
    
    public function actionWalmartorder()
	{
		//ob_start ();
		$obj = new WalmartorderdetailController(Yii::$app->controller->id,'');
		$cron_array = array();
		$connection = Yii::$app->getDb();
		$cronData = WalmartCronSchedule::find()->where(['cron_name'=>'fetch_order'])->one();
		if($cronData && $cronData['cron_data'] != "") {
		  	$cron_array = json_decode($cronData['cron_data'],true);
		}
		else {
		  	$cron_array = Walmartappdetails::getConfig();
		}

		$processedMerchantCount = 0;
		$size = 100;

		if(is_array($cron_array) && count($cron_array)>0)
		{
		  	foreach($cron_array as $key=>$Config)
		  	{
		    	try
		    	{
		        	$merchant_id = $key;
		        	$walmartApiData = Walmartappdetails::getWalmartAPiDetails($merchant_id);
		        	if(isset($walmartApiData['consumer_id']) && isset($walmartApiData['secret_key']))
		        	{
		        		$Config['merchant_id'] = $merchant_id;
		        		$Config['consumer_id'] = $walmartApiData['consumer_id'];
		        		$Config['secret_key'] = $walmartApiData['secret_key'];
		        		$obj->actionCreate($Config);
		        	}
		       
		        	unset($cron_array[$key]);
		    	}
		    	catch (Exception $e)
		    	{
				    Data::createLog("order fetch exception ".$e->getTraceAsString(),'walmartOrderCron/exception.log','a',true);
				    unset($cron_array[$key]);
				    continue;
		    	}
		    	$processedMerchantCount++;
		    	if($processedMerchantCount == $size)
		      		break;
		  	}
		}

		if(count($cron_array)==0)
		  	$cronData->cron_data = "";
		else
		  	$cronData->cron_data = json_encode($cron_array);

		$cronData->save(false);
		unset($cronData);
		//$html = ob_get_clean();
	}
}