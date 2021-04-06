<?php
namespace frontend\controllers;

use frontend\components\Data;
use frontend\components\ShopErasureHelper;
use frontend\components\Webhook;
use common\models\MerchantDb;
use Yii;
use yii\base\Exception;
use yii\web\Controller;

class ShopifyShopErasureController extends Controller
{
	public function beforeAction($action)
	{
		Yii::$app->request->enableCsrfValidation = false;
		return true;
	}
	public function actionWalmartShopDataErasure()
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
			
			$data['marketplace'] = ShopErasureHelper::WALMART_APP;
			$url = Yii::getAlias('@webbaseurl') . "/shopify-shop-erasure/curlforshopdata?maintenanceprocess=1";
			Data::sendCurlRequest($data, $url);
			
			exit(0);
		} catch (\Exception $e) {
			$this->createExceptionLog('actionWalmartShopDataErasure', $e->getMessage(), $shopName);
			exit(0);
		}
	}
	
	public function actionJetShopDataErasure()
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
			
			$data['marketplace'] = ShopErasureHelper::JET_APP;
			$url = Yii::getAlias('@webbaseurl') . "/shopify-shop-erasure/curlforshopdata?maintenanceprocess=1";
			Data::sendCurlRequest($data, $url);
			
			exit(0);
		} catch (\Exception $e) {
			$this->createExceptionLog('actionJetShopDataErasure', $e->getMessage(), $shopName);
			exit(0);
		}
	}
	
	public function actionNeweggShopDataErasure()
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
			
			$data['marketplace'] = ShopErasureHelper::NEWEGG_APP;
			$url = Yii::getAlias('@webbaseurl') . "/shopify-shop-erasure/curlforshopdata?maintenanceprocess=1";
			Data::sendCurlRequest($data, $url);
			
			exit(0);
		} catch (\Exception $e) {
			$this->createExceptionLog('actionNeweggShopDataErasure', $e->getMessage(), $shopName);
			exit(0);
		}
	}
	
	public function actionNeweggcaShopDataErasure()
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
			
			$data['marketplace'] = ShopErasureHelper::NEWEGGCA_APP;
			$url = Yii::getAlias('@webbaseurl') . "/shopify-shop-erasure/curlforshopdata?maintenanceprocess=1";
			Data::sendCurlRequest($data, $url);
			
			exit(0);
		} catch (\Exception $e) {
			$this->createExceptionLog('actionNeweggcaShopDataErasure', $e->getMessage(), $shopName);
			exit(0);
		}
	}
	
	public function actionSearsShopDataErasure()
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
			
			$data['marketplace'] = ShopErasureHelper::SEARS_APP;
			$url = Yii::getAlias('@webbaseurl') . "/shopify-shop-erasure/curlforshopdata?maintenanceprocess=1";
			Data::sendCurlRequest($data, $url);
			
			exit(0);
		} catch (\Exception $e) {
			$this->createExceptionLog('actionSearsShopDataErasure', $e->getMessage(), $shopName);
			exit(0);
		}
	}
	
	public function actionCurlforshopdata()
	{
		$data = $_POST;
		 //$data = json_decode('{"shop_id":"6211502198","shop_domain":"dev-seller.myshopify.com","shopName":"dev-seller.myshopify.com","marketplace":"jet"}', true);
		
		if (is_array($data) && count($data)){
			$file_dir = \Yii::getAlias('@webroot') . '/var/gdpr/shop';
			if (!file_exists($file_dir)) {
				mkdir($file_dir, 0775, true);
			}
			$filenameOrig = $file_dir . '/' . $data['shop_domain'] . '.log';
			$fileOrig = fopen($filenameOrig, 'w');
			fwrite($fileOrig, "\n" . date('d-m-Y H:i:s') . "\n" . json_encode($data));
			fclose($fileOrig);
			
			if (isset($data['shop_domain'],$data['shop_id'],$data['marketplace'])) {
				try {
                    $shopErasureData = new ShopErasureHelper($data['shop_domain'],$data['marketplace']);
					$marketplace = $data['marketplace'];
					$merchant_id = "";
					$isDeleted = false;
					switch ($data['marketplace']) {
						case ShopErasureHelper::WALMART_APP:
							
							$update_data = $shopErasureData->getWalmartData();
							if ($update_data && !isset($update_data['error'])) {
								$insert_data = $shopErasureData->insertData($update_data);
								if ($insert_data == 1) {
									$merchant_id = $update_data['merchant_id'];
									$isDeleted = $shopErasureData->deleteData($update_data['marketplace'], $update_data['merchant_id']);
								}
							}
							break;
						case ShopErasureHelper::JET_APP:
							
							$update_data = $shopErasureData->getJetData();
							
							if ($update_data && !isset($update_data['error'])) {
								$insert_data = $shopErasureData->insertData($update_data);
								if ($insert_data == 1) {
									$merchant_id = $update_data['merchant_id'];
									$isDeleted = $shopErasureData->deleteData($update_data['marketplace'], $update_data['merchant_id']);
								}
							}
							break;
						case ShopErasureHelper::NEWEGG_APP:
							
							$update_data = $shopErasureData->getNeweggData();
							
							if ($update_data && !isset($update_data['error'])) {
								$insert_data = $shopErasureData->insertData($update_data);
								if ($insert_data == 1) {
									$merchant_id = $update_data['merchant_id'];
									$isDeleted = $shopErasureData->deleteData($update_data['marketplace'], $update_data['merchant_id']);
								}
							}
							
							break;
						case ShopErasureHelper::NEWEGGCA_APP:
							$update_data = $shopErasureData->getNeweggcaData();
							
							if ($update_data && !isset($update_data['error'])) {
								$insert_data = $shopErasureData->insertData($update_data);
								if ($insert_data == 1) {
									$merchant_id = $update_data['merchant_id'];
									$isDeleted = $shopErasureData->deleteData($update_data['marketplace'], $update_data['merchant_id']);
								}
							}
							break;
						case ShopErasureHelper::SEARS_APP:
							$update_data = $shopErasureData->getSearsData();
							
							if ($update_data && !isset($update_data['error'])) {
								$insert_data = $shopErasureData->insertData($update_data);
								if ($insert_data == 1) {
									$merchant_id = $update_data['merchant_id'];
									$isDeleted = $shopErasureData->deleteData($update_data['marketplace'], $update_data['merchant_id']);
								}
							}
							break;
						
					}
					if($isDeleted && $marketplace && $merchant_id){
						$merchantDbModel = MerchantDb::find()->where(['merchant_id' => $merchant_id])->one();
						if ($merchantDbModel) {
							$app_name = $merchantDbModel->app_name;
							if (strpos($app_name, $marketplace) !== false) {
								$apps = explode(',', $app_name);
								if (($index = array_search($marketplace, $apps)) !== false) {
									unset($apps[$index]);
									
									if (count($apps)) {
										$merchantDbModel->app_name = implode(',', $apps);
									} else {
										$merchantDbModel->app_name = '';
									}
									$merchantDbModel->save(false);
								}
							}
						}
					}
					
				} catch (\Exception $exception) {
					echo $exception;
					$this->createExceptionLog('actionCurlforshopdata', $exception->getMessage(), $data['shop_domain']);
					exit(0);
				}
			}
		}
	}
	
	public function createExceptionLog($functionName, $msg, $shopName = 'common')
	{
		$dir = \Yii::getAlias('@webroot') . '/var/exceptions/' . $functionName . '/' . $shopName;
		if (!file_exists($dir)) {
			mkdir($dir, 0775, true);
		}
		try {
			throw new \Exception($msg);
		} catch (\Exception $e) {
			$filenameOrig = $dir . '/' . time() . '.txt';
			$handle = fopen($filenameOrig, 'a');
			$msg = date('d-m-Y H:i:s') . "\n" . $msg . "\n" . $e->getTraceAsString();
			fwrite($handle, $msg);
			fclose($handle);
			$this->sendEmail($filenameOrig, $msg);
		}
	}
	
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
	
}