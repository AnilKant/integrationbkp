<?php
namespace maintenance\controllers;

use Yii;
use app\models\AppStatus;
use app\models\JetConfiguration;
use app\models\JetOrderDetail;
use app\models\JetOrderImportError;
use app\models\JetProduct;
use app\models\JetProductVariants;
use frontend\models\JetShipmentDetails;
use common\models\JetExtensionDetail;
use common\models\User;
use frontend\components\Jetapimerchant;
use frontend\components\Jetproductinfo;
use frontend\components\Sendmail;
use frontend\components\Data;
use frontend\components\ShopifyClientHelper;
use frontend\components\Shopifyinfo;
use frontend\models\JetConfig;

use yii\web\Controller;

class ShopifywebhookController extends Controller
{
	public function beforeAction($action)
	{
		
		$path = $action->controller->id.'/'.$action->id;
		
		$webhook_content = '';
		$webhook = fopen('php://input' , 'rb');
		while(!feof($webhook)){
			$webhook_content .= fread($webhook, 4096);
		}

		$serverData = addslashes((json_encode($_SERVER)));

		$query="INSERT INTO `jet_maintenance_log`(`action`,`data`,`server_data`) VALUES('{$path}','{$webhook_content}','{$serverData}')";
		Data::sqlRecords($query,"one","insert");
		die;
		
	}

	public function callCurl($url,$data,$json=true,$returnTransfer = true){

        $data_string = json_encode($data); 
        // Initialize cURL
        $ch = curl_init();
        // Set URL on which you want to post the Form and/or data
        curl_setopt($ch, CURLOPT_URL, $url);
        // Data+Files to be posted
        if($json){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data_string))                                                                       
            );   
        }
        else
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            // Data+Files to be posted

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));   
        }      
        // Pass TRUE or 1 if you want to wait for and catch the response against the request made
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returnTransfer);
        curl_setopt($ch, CURLOPT_HEADER, false);


        // For Debug mode; shows up any error encountered during the operation
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        
        // Execute the request
        $response = curl_exec($ch);
        
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            return ['responseCode'=>$httpcode,'errorMsg'=>curl_error($ch)];  
        }
        
        curl_close($ch);
        if($httpcode!=200)
        {
           
           // preg_match_all('/<div class=\"header(.*?)\">(.*?)<\/div>/s',$response,$output,PREG_SET_ORDER);
            //preg_match_all('/<div class="header">(.*?)</div>/s', $response, $output);
            
            return ['responseCode'=>$httpcode,'errorMsg'=>$response];
            //var_dump($output);
            
        }
        else
        {
            return ['responseCode'=>$httpcode,'response'=>$response];
        }
        // Just for debug: to see response
        
    }

	public function actionProductcreate()
	{
		
    }	
	public function actionCreateshipment()
	{
		
	}
		
	function actionProductupdate()
	{
	}
	
	public function actionProductdelete()
	{
	}

	public function actionIsinstall()
	{
	}

	public function actionCurlprocessforuninstall()
	{
		
	}
	public function actionCancelled()
	{
	}
	public function checkcancelQty($sku,$order_items)
	{
	}

	public function actionCreatewalmartshipment()
	{
		
	}

	public function actionCreateorder()
	{
	}


}
