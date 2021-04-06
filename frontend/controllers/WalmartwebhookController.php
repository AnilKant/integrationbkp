<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\MerchantDb;
use frontend\components\Webhook;
use frontend\modules\walmart\models\WalmartShopDetails;
use frontend\modules\walmart\models\WalmartExtensionDetail;
use frontend\modules\walmart\components\Sendmail;
use frontend\modules\walmart\components\Data;

class WalmartwebhookController extends Controller
{
	
	public function beforeAction($action)
	{
		if ($this->action->id == 'isinstall') {
			Yii::$app->controller->enableCsrfValidation = false;
		}
		return true;
	}

	
	public function actionIsinstall()
	{
		$file_name = \Yii::getAlias('@webroot').'/var/uninstall/'.date('d-m-Y').'/uninstall-'.time().'.txt';
		$path = dirname($file_name);
		if(!file_exists($path)){
			mkdir($path, 0777, true);
		}
		$myfile = fopen($file_name, "a+");
		try {
			$webhook_content = '';
			$webhook = fopen('php://input' , 'rb');
			while(!feof($webhook)) { //loop through the input stream while the end of file is not reached
				$webhook_content .= fread($webhook, 4096); //append the content on the current iteration
			}
			fclose($webhook); //close the resource

			$data = json_decode($webhook_content,true); //convert the json to array
			
			fwrite($myfile, "\n[".date('d-m-Y H:i:s')."]\n");
			fwrite($myfile, print_r($data,true));

			if($data && isset($data['id'])) 
			{
				$data['log_file_name'] = $file_name;
				$url = Yii::getAlias('@webbaseurl')."/walmartwebhook/processinstall";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $data ));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($ch, CURLOPT_TIMEOUT,1);
				$result = curl_exec($ch);
				curl_close($ch);
				exit(0);
			}
		}
		catch(Exception $e) {
			fwrite($myfile,$e->getMessage());
			exit(0);
		}
	}

	public function actionProcessinstall()
	{
		$data = $_POST;
		if(isset($data['myshopify_domain']) && isset($data['id']))
		{
			try 
			{
				$file_name = $data['log_file_name'];
				$path = dirname($file_name);
				if(!file_exists($path)){
					mkdir($path, 0777, true);
				}
				$myfile = fopen($file_name, "a+");

				$shop = $data['myshopify_domain'];
				$model = User::find()->where(['username'=>$shop])->one();
				if($model) 
				{
					$walmartShopDetails = WalmartShopDetails::find()->where(['shop_url'=>$shop])->one();	
					if($walmartShopDetails)
					{
						$shopUrl = $walmartShopDetails->shop_url;
						$token = $walmartShopDetails->token;

						$install_status = false;//Data::isAppInstalled($shopUrl, $token);
						if(!$install_status) {
							$email_id = $walmartShopDetails->email;
							$walmartShopDetails->status = 0;
							//$walmartShopDetails->save(false);
							Sendmail::uninstallmail($email_id);

							$extensionModel = WalmartExtensionDetail::find()->where(['merchant_id'=>$walmartShopDetails->merchant_id])->one();
							if($extensionModel) {
								$extensionModel->app_status="uninstall";
								$extensionModel->uninstall_date=date('Y-m-d H:i:s');
								//$extensionModel->save(false);
							}

							$merchantDbModel = MerchantDb::find()->where(['merchant_id'=>$walmartShopDetails->merchant_id])->one();
							if($merchantDbModel) {
								$app_name = $merchantDbModel->app_name;
								if(strpos($app_name, Data::APP_NAME_WALMART) !== false){
									$apps = explode(',', $app_name);
									if(($index = array_search(Data::APP_NAME_WALMART, $apps))!==false) {
										unset($apps[$index]);

										if(count($apps)) {
											$merchantDbModel->app_name = implode(',', $apps);
										} else {
											$merchantDbModel->app_name = '';
										}

										//$merchantDbModel->save(false);
									}
								}
							}
						}

						fwrite($myfile, "\nToken : ".$token);
					}

					fwrite($myfile, "\nShop : ".$shop);

					Webhook::createWebhooks($shop);
				}
			}
			catch(Exception $e){
				fwrite($myfile,$e->getMessage());
			}
		}
	}
}