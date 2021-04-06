<?php
namespace maintenance\controllers;

use Yii;
use app\models\AppStatus;
use app\models\JetConfiguration;
use app\models\JetOrderDetail;

use app\models\JetOrderImportError;
use app\models\JetProduct;
use app\models\JetProductVariants;
use app\models\JetShipmentDetails;
use common\models\JetExtensionDetail;
use common\models\User;
use frontend\components\Profiler;
use frontend\components\Jetapimerchant;
use frontend\components\Jetproductinfo;
use frontend\components\Sendmail;
use frontend\components\ShopifyClientHelper;
use frontend\components\Shopifyinfo;
use frontend\models\JetConfig;
use console\controllers\CronController;
use yii\web\Controller;
use frontend\components\Jetappdetails;

class TestWebhooksController extends Controller
{
	public function beforeAction($action)
	{
		die('before');
		Yii::$app->controller->enableCsrfValidation = false;
		return true;
	}

	public function getRandomFloatValue(){
		return rand(1, 1000) / 10;
	}

	public function getRandomIntValue(){
		return rand(1, 1000);
	}
	/**
	 * function for getting random data of product for test product update
	 */
	public function getProductForTest(){
			///file_get_contents(\Yii::getAlias('@webroot')."/var/8713189004.txt")
		$timeStamp = time();
		$data = Array(
			    'id' => '8713189004',
			    'title' => 'Apple-71'.$timeStamp,
			    'body_html' => 'Apple 7 mobile'.$timeStamp,
			    'vendor' => 'Fashion Apparel'.$timeStamp, 
			    'product_type' => 'ball',
			    'created_at' => '2016-10-07T03:32:31-04:00',
			    'handle' => 'apple-7',
			    'updated_at' => '2016-10-13T08:17:54-04:00',
			    'published_at' => '2016-10-07T03:26:00-04:00',
			    'published_scope' => 'global',
			    'tags' => '',
			    'variants' => Array
			        (
			            '0' => Array
			                (
			                    'id' => '29982617292',
			                    'product_id' => '8713189004',
			                    'title' => 'silver / 75'.$timeStamp,
			                    'price' => $this->getRandomFloatValue(),
			                    'sku' => '285642562',
			                    'position' => '1',
			                    'grams' => '500',
			                    'inventory_policy' => 'deny',
			                    'fulfillment_service' => 'manual',
			                    'inventory_management' => 'shopify',
			                    'option1' => 'silver'.$timeStamp,
			                    'option2' => '75'.$timeStamp,
			                    'created_at' => '2016-10-07T03:32:31-04:00',
			                    'updated_at' => '2016-10-07T03:32:31-04:00',
			                    'taxable' => '1',
			                    'barcode' => '',
			                    'inventory_quantity' => $this->getRandomIntValue(),
			                    'weight' =>$this->getRandomFloatValue(),
			                    'weight_unit' => 'kg',
			                    'old_inventory_quantity' => $this->getRandomIntValue(),
			                    'requires_shipping' => 1,
			                ),

			            '1' => Array
			                (
			                    'id' => '29982617356',
			                    'product_id' => '8713189004',
			                    'title' => 'silver / 65 '.$timeStamp,
			                    'price' => $this->getRandomFloatValue(),
			                    'sku' => '285642563',
			                    'position' => 2,
			                    'grams' => 500,
			                    'inventory_policy' => 'deny',
			                    'fulfillment_service' => 'manual',
			                    'inventory_management' => 'shopify',
			                    'option1' => 'silver'.$timeStamp,
			                    'option2' => '65'.$timeStamp,
			                    'created_at' => '2016-10-07T03:32:31-04:00',
			                    'updated_at' => '2016-10-07T03:32:31-04:00',
			                    'taxable' => 1,
			                    'barcode' => '',
			                    'inventory_quantity' => $this->getRandomIntValue(),
			                    'weight' => $this->getRandomFloatValue(),
			                    'weight_unit' => 'kg',
			                    'old_inventory_quantity' => $this->getRandomIntValue(),
			                    'requires_shipping' => 1,
			                ),

			            '2' => Array
			                (
			                    'id' => '29982617420',
			                    'product_id' => '8713189004',
			                    'title' => 'silver / 56'.$timeStamp,
			                    'price' => $this->getRandomFloatValue(),
			                    'sku' => '285642564',
			                    'position' => 3,
			                    'grams' => 500,
			                    'inventory_policy' => 'deny',
			                    'fulfillment_service' => 'manual',
			                    'inventory_management' => 'shopify',
			                    'option1' => 'silver'.$timeStamp,
			                    'option2' => '56'.$timeStamp,
			                    'created_at' => '2016-10-07T03:32:31-04:00',
			                    'updated_at' => '2016-10-07T03:32:31-04:00',
			                    'taxable' => 1,
			                    'barcode' => '',
			                    'inventory_quantity' => $this->getRandomIntValue(),
			                    'weight' => $this->getRandomFloatValue(),
			                    'weight_unit' => 'kg',
			                    'old_inventory_quantity' => $this->getRandomIntValue(),
			                    'requires_shipping' => 1,
			                ),

			            '3' => Array
			                (
			                    'id' => '29982617484',
			                    'product_id' => '8713189004',
			                    'title' => 'golden / 75 '.$timeStamp,
			                    'price' => $this->getRandomFloatValue(),
			                    'sku' => '285642565',
			                    'position' => 4,
			                    'grams' => 500,
			                    'inventory_policy' => 'deny',
			                    'fulfillment_service' => 'manual',
			                    'inventory_management' => 'shopify',
			                    'option1' => 'golden'.$timeStamp,
			                    'option2' => '75'.$timeStamp,
			                    'created_at' => '2016-10-07T03:32:31-04:00',
			                    'updated_at' => '2016-10-07T03:32:31-04:00',
			                    'taxable' => 1,
			                    'barcode' => '',
			                    'inventory_quantity' =>$this->getRandomIntValue(),
			                    'weight' => $this->getRandomFloatValue(),
			                    'weight_unit' => 'kg',
			                    'old_inventory_quantity' => $this->getRandomIntValue(),
			                    'requires_shipping' => 1,
			                ),

			            '4' => Array
			                (
			                    'id' => '29982617548',
			                    'product_id' => '8713189004',
			                    'title' => 'golden / 65'.$timeStamp,
			                    'price' => $this->getRandomFloatValue(),
			                    'sku' => '285642566',
			                    'position' => 5,
			                    'grams' => '500',
			                    'inventory_policy' => 'deny',
			                    'fulfillment_service' => 'manual',
			                    'inventory_management' => 'shopify',
			                    'option1' => 'golden'.$timeStamp,
			                    'option2' => '65'.$timeStamp,
			                    'created_at' => '2016-10-07T03:32:31-04:00',
			                    'updated_at' => '2016-10-07T03:32:31-04:00',
			                    'taxable' => 1,
			                    'barcode' => '',
			                    'inventory_quantity' => $this->getRandomIntValue(),
			                    'weight' => $this->getRandomFloatValue(),
			                    'weight_unit' => 'kg',
			                    'old_inventory_quantity' => $this->getRandomIntValue(),
			                    'requires_shipping' => 1,
			                ),

			            '5' => Array
			                (
			                    'id' => '29982617612',
			                    'product_id' => '8713189004',
			                    'title' => 'golden / 56'.$timeStamp,
			                    'price' => $this->getRandomFloatValue(),
			                    'sku' => '285642567',
			                    'position' => 6,
			                    'grams' => 500,
			                    'inventory_policy' => 'deny',
			                    'fulfillment_service' => 'manual',
			                    'inventory_management' => 'shopify',
			                    'option1' => 'golden'.$timeStamp,
			                    'option2' => '56'.$timeStamp,
			                    'created_at' => '2016-10-07T03:32:31-04:00',
			                    'updated_at' => '2016-10-07T03:32:31-04:00',
			                    'taxable' => 1,
			                    'barcode' => '',
			                    'inventory_quantity' => $this->getRandomIntValue(),
			                    'weight' => $this->getRandomFloatValue(),
			                    'weight_unit' => 'kg',
			                    'old_inventory_quantity' => $this->getRandomIntValue(),
			                    'requires_shipping' => 1,
			                ),

			        ),

			    'options' => Array
			        (
			            '0' => Array
			                (
			                    'id' => '10496543820',
			                    'product_id' => '8713189004',
			                    'name' => 'Color',
			                    'position' => 1,
			                    'values' => Array
			                        (
			                            '0' => 'silver'.$timeStamp,
			                            '1' => 'golden'.$timeStamp
			                        )

			                ),

			            '1' => Array
			                (
			                    'id' => '10496543884',
			                    'product_id' => '8713189004',
			                    'name' => 'Size',
			                    'position' => 2,
			                    'values' => Array
			                        (
			                            '0' => '75'.$timeStamp,
			                            '1' => '65'.$timeStamp,
			                            '2' => '56'.$timeStamp,
			                        )

			                ),

			        ),

			    'images' => Array
			        (
			            '0' => Array
			                (
			                    'id' => '19247501580',
			                    'product_id' => '8713189004',
			                    'position' => 1,
			                    'created_at' => '2016-10-07T03:32:34-04:00',
			                    'updated_at' => '2016-10-07T03:32:34-04:00',
			                    'src' => 'https://cdn.shopify.com/s/files/1/1009/2336/products/1.jpg?v=1475825554',
			                ),

			        ),

			    'image' => Array
			        (
			            'id' => '19247501580',
			            'product_id' => '8713189004',
			            'position' => 1,
			            'created_at' => '2016-10-07T03:32:34-04:00',
			            'updated_at' => '2016-10-07T03:32:34-04:00',
			            'src' => 'https://cdn.shopify.com/s/files/1/1009/2336/products/1.jpg?v=1475825554',
			        ),

			    'shopName' => 'ced-jet.myshopify.com',
			);
		return $data;
	}

	public function productCheckColumns(){
		return [	['name'=>'title','column_name'=>'title'],
					['name'=>'inventory_quantity','column_name'=>'qty'],
					['name'=>'weight','column_name'=>'weight'],
					['name'=>'price','column_name'=>'price'],
					['name'=>'vendor','column_name'=>'vendor'],
					['name'=>'body_html','column_name'=>'description'],
					['name'=>'product_type','column_name'=>'product_type'],

				];
	}

	public function productVariantCheckColumns(){
		return [	['name'=>'title','column_name'=>'option_title'],
					['name'=>'inventory_quantity','column_name'=>'option_qty'],
					['name'=>'weight','column_name'=>'option_weight'],
					['name'=>'price','column_name'=>'option_price'],
					['name'=>'vendor','column_name'=>'vendor'],

				];
	}
	public function productCreate(){
		$string='{"id":"8874517772","title":"New shirt blue","body_html":"New shirt blue","vendor":"Fashion Apparel ","product_type":"","created_at":"2016-11-09T07:49:30-05:00","handle":"new-shirt-blue","updated_at":"2016-11-09T07:49:30-05:00","published_at":"2016-11-09T07:43:00-05:00","published_scope":"global","tags":"","variants":[{"id":"30642204172","product_id":"8874517772","title":"l","price":"20.00","sku":"new_123456","position":"1","grams":"0","inventory_policy":"deny","fulfillment_service":"manual","inventory_management":"shopify","option1":"l","created_at":"2016-11-09T07:49:30-05:00","updated_at":"2016-11-09T07:49:30-05:00","taxable":"0","barcode":"435656565656","inventory_quantity":"20","weight":"0","weight_unit":"kg","old_inventory_quantity":"20","requires_shipping":"1"},{"id":"30642204236","product_id":"8874517772","title":"s","price":"20.00","sku":"new_123457","position":"2","grams":"0","inventory_policy":"deny","fulfillment_service":"manual","inventory_management":"shopify","option1":"s","created_at":"2016-11-09T07:49:30-05:00","updated_at":"2016-11-09T07:49:30-05:00","taxable":"0","barcode":"435656565656","inventory_quantity":"20","weight":"0","weight_unit":"kg","old_inventory_quantity":"20","requires_shipping":"1"},{"id":"30642204300","product_id":"8874517772","title":"m","price":"20.00","sku":"new_123458","position":"3","grams":"0","inventory_policy":"deny","fulfillment_service":"manual","inventory_management":"shopify","option1":"m","created_at":"2016-11-09T07:49:30-05:00","updated_at":"2016-11-09T07:49:30-05:00","taxable":"0","barcode":"435656565656","inventory_quantity":"20","weight":"0","weight_unit":"kg","old_inventory_quantity":"20","requires_shipping":"1"}],"options":[{"id":"10712096012","product_id":"8874517772","name":"Size","position":"1","values":["l","s","m"]}],"shopName":"ced-jet.myshopify.com"}';
		return json_decode($string,true);
	}
	public function getAllWebHooks()
	{
		return  [ ['action'=>'shopifywebhook/productupdate','data'=>[]],
				  ['action'=>'shopifywebhook/productcreate','data'=>[]],
				  ['action'=>'shopifywebhook/curlproductcreate','data'=>$this->productCreate()],
				  ['action'=>'shopifywebhook/createshipment','data'=>[]],
				  ['action'=>'shopifywebhook/curlprocessfororder','data'=>[]],
				  ['action'=>'shopifywebhook/curlprocessforproductupdate','data'=>[]],
				  ['action'=>'shopifywebhook/productdelete','data'=>[]],
				  ['action'=> 'shopifywebhook/curlprocessforproductupdate','data'=>$this->getProductForTest()],

		        ];
	}
	public function getWebhookFunctions(){
		return  [ 'actionTestProductUpdate'
		        ];
	}

	public function testSyntaxErrors(){
		Profiler::addCounter('syntaxIssues','Syntax Issues');
		Profiler::pause();
		ob_start ();
		$syntaxIssues = 0;
		foreach($this->getAllWebHooks() as $webhook){
			$response = $this->callCurl($webhook['action'],$webhook['data'],false);
			if($response['responseCode']==200)
				echo 'Action : '.$webhook['action'].' Response Code:'.$response['responseCode'].'<hr>'.$response['response'].'<br/>';
			else{
				echo 'Action : '.$webhook['action'].' Response Code:'.$response['responseCode'].'<br/>';
				echo $response['errorMsg'];
				$syntaxIssues++;
			}
		}
		$html = ob_get_clean();
		Profiler::resume();
		$this->pushData('testSyntaxErrors',['syntaxIssues'=>$syntaxIssues]);
		return ['html'=>$html,'issues'=>$syntaxIssues];
	}
	public function actionTestSyntaxErrors(){
		$result = $this->testSyntaxErrors();
		echo addslashes(json_encode($result));
		die;
	}

	public function actionTestWalmartWebhooks(){
		
		// URL on which we have to post data
		$url = Yii::getAlias('@hostname').'/integration/walmart/webhookupdate/productupdate';
		$data = ['merchant_id'=>16,'type'=>"price",'sku'=>"100661",'price'=>"20"]; 
		// Initialize cURL
		$ch = curl_init();
		// Set URL on which you want to post the Form and/or data
		curl_setopt($ch, CURLOPT_URL, $url);
		// Data+Files to be posted
		
		curl_setopt($ch, CURLOPT_POST, 1);
		// Data+Files to be posted

		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));   
		     
		// Pass TRUE or 1 if you want to wait for and catch the response against the request made
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// For Debug mode; shows up any error encountered during the operation
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		// Execute the request
		$response = curl_exec($ch);
		
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		var_dump($httpcode);
		var_dump($response);die;
		if (curl_errno($ch)) {
			return ['responseCode'=>$httpcode,'errorMsg'=>curl_error($ch)];  
		}
		
		
	}
	public function actionConcurrentRequestResponseTest(){
		for($i=0;$i<50;$i++){
			$data = $this->callCurl('test-webhooks/test-syntax-errors',[]);
				if(isset($data['response'])){
				$data = json_decode($data['response'],true);
				if($data['issues']==0){
					print_r($data);
				}
			}
			else
			{
				var_dump($data);
			}

		}
		
	}
	/*
     * function for testing all webhook functions 
	 */
	public function actionTestAllWebhooks(){
		$this->testSyntaxErrors();
		
		$methods = $this->getWebhookFunctions();
		$result = array();
		try{
			
			foreach($methods as $method){
				Profiler::start($method);
				$result = $this->checkFunction($method);
				$this->pushData($method,$result);
				
				Profiler::stop($method);
			}
			print_r(Profiler::$counters);
			echo Profiler::getHtml();
			
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	}

	function pushData($method,$data){
		foreach($data as $key=>$value){
			Profiler::pushData($method,$key,$value);	
		}
	}
	
	public function checkFunction($method){
		return $response = $this->$method();
	}

	public function compareData($data,$product,$dbData,$type="product"){
		$updateIssues = 0;
		$html = '';
		$html .= 'Sku : '.$product['sku'].'<br>';
		$jetApp = new Jetappdetails();
		$product['weight'] = round($jetApp->convertWeight($product['weight'],$product['weight_unit']),2);
		
		if($type == 'product'){
			$columns = $this->productCheckColumns();
		}
		else
		{
			$columns = $this->productVariantCheckColumns();
		}
		foreach($columns as $keyData){
			if(isset($product[$keyData['name']])){
				/*
				if($keyData['name']=='weight'){
					var_dump($product[$keyData['name']]);
					var_dump($dbData[$keyData['column_name']]);
					var_dump($product[$keyData['name']]==$dbData[$keyData['column_name']]);
				}
				*/
				if($product[$keyData['name']]==$dbData[$keyData['column_name']]){
					$html .= $keyData['name'].' : Updated <br>';
				}
				else
				{
					$html .= $keyData['name'].' : <strong>Not Updated </strong> RequestedValue: '.$product[$keyData['name']].' SavedValue:'.$dbData[$keyData['column_name']].'<br>';
					$updateIssues++;
				}
			}
			elseif(isset($data[$keyData['name']])){
				if($data[$keyData['name']]==$dbData[$keyData['column_name']]){
					$html .= $keyData['name'].' : Updated <br>';
				}
				else
				{
					$html .= $keyData['name'].' : <strong>Not Updated</strong>RequestedValue:'.$data[$keyData['name']].'  SavedValue:'.$dbData[$keyData['column_name']].' <br>';
					$updateIssues++;
				}
			}
			
		}
		$this->pushData('actionTestProductUpdate',['updateIssues'=>$updateIssues]);
		return $html.'<hr>';
	}
	/* Check product data is updated or not*/
	public function checkProductUpdates($data){
		Profiler::addCounter('updateIssues','Columns not updated');
		$product = $data['variants'][0]; /*Main Product*/
		$productId = $product['id'];
		$db = Yii::$app->getDb();
		$query = 'select * from `jet_product` where sku="'.$product['sku'].'"';
		$dbData = $db->createCommand($query)->queryOne();
		$html = '';
		$html .= $this->compareData($data,$product,$dbData);

		foreach($data['variants'] as $key=>$product){
			if($key!=0){/*Only for variants (skipping main product)*/
				$query = 'select * from `jet_product_variants` where option_sku="'.$product['sku'].'"';
				$dbData = $db->createCommand($query)->queryOne();
				$html .= $this->compareData($data,$product,$dbData,'variants');
			}
		}
		
		$this->pushData('actionTestProductUpdate',['extraData'=>$html]);
		
		
	}
	/*
	 * function for testing product update webhook function
	 */
	public function actionTestProductUpdate(){
			$path = 'shopifywebhook/productupdate';
		//echo 'Started:'.$path.'<br>';
			$result = [];
			$data = $this->getProductForTest();
			$response = $this->callCurl($path,$data);
			$httpCode  = $response['responseCode'];
			if($httpCode==200){
				$result['success'] = 1;
				$result['msg'] = 'No syntax error';
			}else
			{
				$result['success'] = 0;
				$result['error_code'] = $httpCode;
				
				echo '-- error code : '.$httpCode.'<br>';
				if(isset($response['errorMsg']))
					$result['msg'] = $response['errorMsg'];
			}
			$this->pushData('curl-call/'.$path,$result);
		//	echo 'Ended:'.$path.'<hr>';
			$path = 'jetproduct/updateonjet';
		//echo 'Started:'.$path.'<br>';
			Profiler::start($path);
			$result = [];
			try{

				$obj = new CronController(Yii::$app->controller->id,'');
				$productId = $data['id'];
				$db = Yii::$app->getDb();
				
				$updated = false;
				$count = 0;
				//var_dump(Yii::$app->getDb());die;
				
				
				while($updated!=true){
					$curDate = date('Y-m-d H:i:s');
					$query = 'select `product_id` from `jet_product_tmp` where product_id='.$productId .' and created_at<="'.$curDate.'" order by merchant_id';
					$productUpdateData = $db->createCommand($query)->queryAll();
					if(count($productUpdateData)>0){
						$updated = true;
					}
					elseif($count++<60){
						sleep(2);
					}
					else
					{
						throw new \Exception('Some issue in product update no data found in table : jet_product_tmp'); 
					}
				}
				$obj->actionUpdateonjet();
				$this->checkProductUpdates($data);
				//Yii::$app->runAction($path);
				$result['success'] = 1;
				$result['msg'] = 'Success';
			}
			catch(\Exception $e){
				$result['success'] = 0;

				$result['msg'] = $e->getMessage();
				$result['trace'] = $e->getTraceAsString();
			}
			Profiler::stop($path);
			return $result;
		//echo 'Ended:'.$path.'<hr>';
	}

	public function callCurl($path,$data,$json=true,$returnTransfer = true){
		Profiler::start('curl-call/'.$path);
		// URL on which we have to post data
		$baseUrl = Yii::getAlias('@weburl').'/';
		$url =  $baseUrl.$path;
		
		// Any other field you might want to catch
		
		
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
		// For Debug mode; shows up any error encountered during the operation
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		
		// Execute the request
		$response = curl_exec($ch);
		
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (curl_errno($ch)) {
			return ['responseCode'=>$httpcode,'errorMsg'=>curl_error($ch)];  
		}
		
		curl_close($ch);
		Profiler::stop('curl-call/'.$path);
		if($httpcode!=200)
		{
			var_dump($response);die;
			preg_match_all('/<div class=\"header(.*?)\">(.*?)<\/div>/s',$response,$output,PREG_SET_ORDER);
			//preg_match_all('/<div class="header">(.*?)</div>/s', $response, $output);
			
			return ['responseCode'=>$httpcode,'errorMsg'=>isset($output[0][0])?$output[0][0]:''];
			//var_dump($output);
			
		}
		else
		{
			return ['responseCode'=>$httpcode,'response'=>$response];
		}
		// Just for debug: to see response
		
	}
	public function actionCheckPro(){
		$webhook = fopen('php://input' , 'rb');
		$webhook_content = '';
		while(!feof($webhook)){
			$webhook_content .= fread($webhook, 4096);
		}
		fclose($webhook);
		$realdata=$webhook_content;
		$data = json_decode($realdata,true);
		print_r($data);die;
	}

}