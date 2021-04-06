<?php

namespace backend\controllers;
use backend\models\JetRecurringPayment;
use backend\models\JetRecurringPaymentSearch;
use common\models\Post;
use common\models\User;
use frontend\components\ShopifyClientHelper;
use frontend\components\Shopifyinfo;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;


/**
 * JetRecurringPaymentController implements the CRUD actions for JetRecurringPayment model.
 */
class JetRecurringPaymentController extends MainController
{
	protected $connection;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all JetRecurringPayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JetRecurringPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /*
    cancel recurring monthly payment 
    */
    public function actionCancelpayment(){
        //print_r($_POST);die;
        $connection = Yii::$app->getDb();
        
        $merchant_id=$_POST['merchant_id'];
        $payment_id=$_POST['id'];
        $planType=$_POST['type'];
        $selectShop = $connection->createCommand("SELECT `username`,`auth_key` from user WHERE id ='".$merchant_id."' ")->queryOne();
        $shop=$selectShop['username'];
        $token=$selectShop['auth_key'];
        
        
        $sc = new ShopifyClientHelper($shop, $token, PUBLIC_KEY, PRIVATE_KEY);
                
        $response=[];
        if ($planType=='Recurring Plan (Monthly)'){
            $cancelresponse = $sc->call('DELETE','/admin/recurring_application_charges/'.$payment_id.'.json');
        }
        $response = $sc->call('GET','/admin/recurring_application_charges/'.$payment_id.'.json');

        if ($response['status'] == "cancelled") {
            $message = "Recurring charge has been cancelled ! Thank you";
        }
        else{
            $message = "Recurring charge is not cancelled";
        }
        return $message;
    }
    /**
     * Displays a single JetRecurringPayment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {
    	
    	$connection = Yii::$app->getDb();
    	
    	$merchant_id=$_GET['mid'];
    	$payment_id=$_GET['id'];
    	$planType=$_GET['type'];
    	$selectShop = $connection->createCommand("SELECT `username`,`auth_key` from user WHERE id ='".$merchant_id."' ")->queryOne();
    	$shop=$selectShop['username'];
    	$token=$selectShop['auth_key'];
    	
    	
    	$sc = new ShopifyClientHelper($shop, $token, PUBLIC_KEY, PRIVATE_KEY);
    	    	
    	$response="";
    	if ($planType=='Recurring Plan (Monthly)'){
    		$response=$sc->call('GET','/admin/recurring_application_charges/'.$payment_id.'.json');
    	}elseif ($planType=='Recurring Plan (Yearly)'){
    		$response=$sc->call('GET','/admin/application_charges/'.$payment_id.'.json');
    	}

    	return $this->render('view', [
            'model' => $response,
        ]);
    }
    public function actionCheckpayment()
    {
    	$connection = Yii::$app->getDb();
    	$allPayment = $connection->createCommand("select jet_recurring_payment.id,jet_recurring_payment.plan_type,user.username,user.auth_key FROM jet_recurring_payment INNER JOIN user ON jet_recurring_payment.merchant_id=user.id")->queryAll();
    	 /* echo "<pre>";
    	print_r($allPayment);
    	die;  */
    	
    	
    	$sc = new ShopifyClientHelper($allPayment['username'], $allPayment['auth_key'], PUBLIC_KEY, PRIVATE_KEY);
    	/* echo "<pre>";
    	print_r($sc);
    	die; */
        $response = "";
    	foreach ($allPayment as $key=>$val){
    		/*  echo "<pre>";
    		print_r($val);
    		die;  */
    		$value=$val["id"];
	    	if ($val['plan_type']=="Recurring Plan (Monthly)"){
	    		$response=$sc->call('GET','/admin/recurring_application_charges/'.$value.'.json');
	    		echo "Recurring Plan (Monthly) <hr><pre>";
	    		print_r($response);
	    		echo "<hr>";
	    		die;
	    	}elseif ($val['plan_type']=='Recurring Plan (Yearly)'){
	    		$response=$sc->call('GET','/admin/application_charges/'.$val['id'].'.json');
	    		echo "Recurring Plan (Yearly) <hr><pre>";
	    		print_r($response);
	    		echo "<hr>";
	    		die;
	    	}
	    	
    	}
    	echo "<pre>";
    	print_r($response);
    	echo "<hr>";
    	die;
    }
    public function actionViewpayment(){
    	$this->layout="main2";
    	$payment_id=$_POST['id'];
    	$merchant_id=$_POST['merchant_id'];
    	$planType=$_POST['type'];
    	
    	$connection=Yii::$app->getDb();
    		
    	$selectShop = $connection->createCommand("SELECT `username`,`auth_key` from user WHERE id ='".$merchant_id."' ")->queryOne();
    	$shop=$selectShop['username'];
    	$token=$selectShop['auth_key'];
    	
    	
    	$sc = new ShopifyClientHelper($shop, $token, PUBLIC_KEY, PRIVATE_KEY);
    	    	
    	$response=array();
    	if ($planType=='Recurring Plan (Monthly)'){
    		$response=$sc->call('GET','/admin/recurring_application_charges/'.$payment_id.'.json');
    	}elseif ($planType=='Recurring Plan (Yearly)'){
    		$response=$sc->call('GET','/admin/application_charges/'.$payment_id.'.json');
    	}

    	if($response && !isset($response['errors']))
    	{
    		$html=$this->render('viewpayment',array('data'=>$response),true);
    	}
    	return $html;
    	 
    }
    public function actionUpdatepaymentstatus(){
    	date_default_timezone_set('Asia/Kolkata');
    	
    	$CurDate= date("Y-m-d H:i:s");
    	$nextDate =date('Y-m-d H:i:s',strtotime('+3 days', strtotime(date('Y-m-d H:i:s'))));
    	$finalExpiredate =date('Y-m-d H:i:s',strtotime('+32 days', strtotime(date('Y-m-d H:i:s'))));    	
    	
    	if (!file_exists('/home/cedcom5/public_html/shopify/jet/var/RecurringPaymentUpdate/cron/'.date('d-m-Y')))
    	{
			mkdir('/home/cedcom5/public_html/shopify/jet/var/RecurringPaymentUpdate/cron/'.date('d-m-Y'),0775, true);
		} 
		$connection=Yii::$app->getDb();
    	
    	
    	$api_key=PUBLIC_KEY;
    	$secret_key=PRIVATE_KEY;
    	$response=array();
    	
    	$sql="SELECT `jet_extension_detail`.`merchant_id`,`jet_extension_detail`.`app_status` FROM `jet_extension_detail` INNER JOIN `jet_recurring_payment` ON `jet_extension_detail`.`merchant_id`=`jet_recurring_payment`.`merchant_id` WHERE `jet_extension_detail`.`expire_date` BETWEEN '".$CurDate."' AND '$nextDate'";
    	$shopdata=$connection->createCommand($sql)->queryAll();
    	$shop='';$token='';$payment_id='';$planType='';
    	foreach ($shopdata as $data)
    	{
    		$merchant_id=$data['merchant_id'];
    		$install_status=$data['app_status'];
    		    
    		
    		$sqlrecurring="SELECT `jet_recurring_payment`.id,`jet_recurring_payment`.status,`jet_recurring_payment`.`merchant_id`, `jet_recurring_payment`.plan_type , `user`.username ,`user`.auth_key  FROM `jet_recurring_payment` INNER JOIN `user` ON `jet_recurring_payment`.`merchant_id`=  `user`.`id` WHERE `jet_recurring_payment`.`merchant_id`='".$merchant_id."'";
    		$recurringData=$connection->createCommand($sqlrecurring)->queryOne();
   		
    		$shop=$recurringData['username'];
    		$token=$recurringData['auth_key'];
    		$payment_id=$recurringData['id'];
    		$planType=$recurringData['plan_type'];  
    		
    		if ($install_status=='uninstall')
    		{
    			$updatePaymentQuery="UPDATE `jet_recurring_payment` SET `status` = 'cancelled' WHERE `jet_recurring_payment`.`id` ='".$payment_id."' AND `merchant_id`='".$merchant_id."'";
    			$updateStatus=$connection->createCommand($updatePaymentQuery)->execute();
    		}
    		$sc = new ShopifyClientHelper($shop, $token,$api_key, $secret_key);	    		 
    		 
    		if ($planType=='Recurring Plan (Monthly)'){
    			$response=$sc->call('GET','/admin/recurring_application_charges/'.$payment_id.'.json');
    		}elseif ($planType=='Recurring Plan (Yearly)'){
    			$response=$sc->call('GET','/admin/application_charges/'.$payment_id.'.json');
    		}
    		$filenameOrig="";
    		$filenameOrig='/home/cedcom5/public_html/shopify/jet/var/RecurringPaymentUpdate/cron/'.date('d-m-Y').'/'.$merchant_id.'.log';
    		$fileOrig="";
    		$fileOrig=fopen($filenameOrig,'a+');
	    	$msg1='';
    		if (isset($response) && $response['status']=='cancelled')
    		{    			
    			 $updatePaymentQuery="UPDATE `jet_recurring_payment` SET `status` = '".$response['status']."' WHERE `jet_recurring_payment`.`id` ='".$payment_id."' AND `merchant_id`='".$merchant_id."'";
    		     $updateStatus=$connection->createCommand($updatePaymentQuery)->execute();
    		     $updatePlanQuery="UPDATE `jet_extension_detail` SET `status` = 'Not Purchase' WHERE `merchant_id` ='".$merchant_id."' ";
    		     $updateStatus=$connection->createCommand($updatePlanQuery)->execute();
    		     $msg1.= "<hr>";
    		     $msg1.= $merchant_id."---Status Cancelled <hr>";
    		     $msg1.= $updatePaymentQuery."<br>";
    		     $msg1.= $updatePlanQuery."<br>";
    		     $msg1.= "<hr>";
    		}elseif (isset($response) && $response['status']=='active')
    		{
    			
    			 $updatePlanQuery="UPDATE `jet_extension_detail` SET `expire_date`='".$finalExpiredate."' WHERE `merchant_id` ='".$merchant_id."' ";
    		     $updateStatus=$connection->createCommand($updatePlanQuery)->execute();
    		     $msg1.= "<hr>";
    		     $msg1.= $merchant_id."---Status Active <hr>";
    		     $msg1.= $updatePlanQuery."<br>";
    		     $msg1.= "<hr>";
    		}  
    		
    		fwrite($fileOrig,$msg1);
    		fclose($fileOrig);
    	}
    }
    
}
