<?php
	
	namespace backend\controllers;
	
	use Yii;
	use backend\models\EtsyPayment;
	use backend\models\EtsyPaymentSearch;
	use backend\components\Data;
	use frontend\components\ShopifyClientHelper;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	use yii\filters\VerbFilter;
	
	
	/**
	 * EtsyPaymentController implements the CRUD actions for EtsyPayment model.
	 */
	class EtsyPaymentController extends Controller
	{
		/**
		 * @inheritdoc
		 */
		public function behaviors()
		{
			return [
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'delete' => ['POST'],
					],
				],
			];
		}
		
		public function beforeAction($action)
		{
			if (Yii::$app->user->isGuest) {
				return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
				return false;
			}
			return true;
		}
		
		/**
		 * Lists all EtsyPayment models.
		 * @return mixed
		 */
		public function actionIndex()
		{
			$searchModel = new EtsyPaymentSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			
			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}
		
		/**
		 * Displays a single EtsyPayment model.
		 * @param integer $id
		 * @return mixed
		 */
		public function actionView($id)
		{
			return $this->render('view', [
				'model' => $this->findModel($id),
			]);
		}
		
		/**
		 * Creates a new EtsyPayment model.
		 * If creation is successful, the browser will be redirected to the 'view' page.
		 * @return mixed
		 */
		public function actionCreate()
		{
			$model = new EtsyPayment();
			
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				return $this->render('create', [
					'model' => $model,
				]);
			}
		}
		
		/**
		 * Updates an existing EtsyPayment model.
		 * If update is successful, the browser will be redirected to the 'view' page.
		 * @param integer $id
		 * @return mixed
		 */
		public function actionUpdate($id)
		{
			$model = $this->findModel($id);
			
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				return $this->render('update', [
					'model' => $model,
				]);
			}
		}
		
		/**
		 * Deletes an existing EtsyPayment model.
		 * If deletion is successful, the browser will be redirected to the 'index' page.
		 * @param integer $id
		 * @return mixed
		 */
		public function actionDelete($id)
		{
			$this->findModel($id)->delete();
			
			return $this->redirect(['index']);
		}
		
		/**
		 * Finds the EtsyPayment model based on its primary key value.
		 * If the model is not found, a 404 HTTP exception will be thrown.
		 * @param integer $id
		 * @return EtsyPayment the loaded model
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		protected function findModel($id)
		{
			if (($model = EtsyPayment::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
		
		
		/*
		cancel recurring monthly payment
		*/
		public function actionCancelpayment(){
			
			$merchant_id=$_POST['merchant_id'];
			$payment_id=$_POST['id'];
			$planType=$_POST['type'];
			
			$selectShop = Data::integrationSqlRecords("SELECT `shopurl` AS `shop_url`,`token` FROM `etsy_shop_details` `esd` INNER JOIN `merchant` ON `esd`.`merchant_id`=`merchant`.`merchant_id` WHERE `esd`.`merchant_id`='".$merchant_id."' ",'one','select');
			$shop=$selectShop['shop_url'];
			$token=$selectShop['token'];
			
			
			$sc = new ShopifyClientHelper($shop, $token, '','');
			
			if ($planType=='recurring'){
				$sc->call('DELETE','/admin/recurring_application_charges/'.$payment_id.'.json');
			}
			$response=$sc->call('GET','/admin/recurring_application_charges/'.$payment_id.'.json');
			
			if ($response['status'] == "cancelled") {
				$this->UpdateChargeStatus($merchant_id,$payment_id,$response['status']);
				$message = "Recurring charge has been cancelled ! Thank you";
			}
			else{
				$message = "Recurring charge is not cancelled";
			}
			return $message;
		}
		
		public function actionViewpayment(){
			$this->layout="main2";
			$payment_id=$_POST['id'];
			$merchant_id=$_POST['merchant_id'];
			$planType=$_POST['type'];
			$selectShop = Data::integrationSqlRecords("SELECT `shopurl` AS `shop_url`,`token` FROM `etsy_shop_details` `esd` INNER JOIN `merchant` ON `esd`.`merchant_id`=`merchant`.`merchant_id` WHERE `esd`.`merchant_id`='".$merchant_id."' ",'one','select');
			$shop=$selectShop['shop_url'];
			$token=$selectShop['token'];
			
			
			$sc = new ShopifyClientHelper($shop, $token, ETSY_APP_KEY, ETSY_APP_SECRET);
			
			if ($planType=='recurring'){
				$response=$sc->call('GET','/admin/recurring_application_charges/'.$payment_id.'.json');
			}else{
				$response=$sc->call('GET','/admin/application_charges/'.$payment_id.'.json');
			}
			
			if($response && !isset($response['errors']))
			{
				if(isset($response['status']) && $response['status'] =="cancelled") {
					$this->UpdateChargeStatus($merchant_id,$payment_id,$response['status']);
				}
				$html=$this->render('viewpayment',array('data'=>$response),true);
				return $html;
			}else{
				if (isset($response['errors']) && ($response['errors'] =='[API] Invalid API key or access token (unrecognized login or wrong password)'))
				{
					$this->UpdateChargeStatus($merchant_id,$payment_id,'cancelled');
				}
				echo "<hr><pre>";
				print_r($response);
				die("<hr>charge response");
			}
		}
		public function UpdateChargeStatus($merchant_id,$charge_id,$status){
			$sql = "UPDATE `etsy_payment` SET `status`='".$status."' WHERE `merchant_id`='{$merchant_id}' AND `id`='{$charge_id}'";
			Data::integrationSqlRecords($sql,null,'update');
		}
	}
