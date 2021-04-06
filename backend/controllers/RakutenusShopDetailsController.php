<?php

namespace backend\controllers;

use Yii;
use backend\models\RakutenusShopDetails;
use backend\components\Data;
use backend\models\RakutenusShopDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RakutenusShopDetailsController implements the CRUD actions for RakutenusShopDetails model.
 */
class RakutenusShopDetailsController extends MainController
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

    /**
     * Lists all RakutenusShopDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RakutenusShopDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
// echo "<pre>";print_r($dataProvider);die;
        // $installation_data = Data::sqlRecords('SELECT * FROM `rakutenus_installation`','one','select','admin');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RakutenusShopDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $data = Data::sqlRecords('SELECT * FROM `merchant` WHERE `merchant_id`='.$model['merchant_id'],'one','select','admin');
        $model['shopurl'] = $data['shopurl'];
        $model['verified_mobile'] = $data['verified_mobile'];
        $model['email'] = $data['email'];
        $model['seller_username'] = $data['seller_username'];
        $model['seller_password'] = $data['seller_password'];
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new RakutenusShopDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RakutenusShopDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RakutenusShopDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $data = Data::sqlRecords('SELECT * FROM `merchant` WHERE `merchant_id`='.$model['merchant_id'],'one','select','admin');
        $model['shopurl'] = $data['shopurl'];
        $model['verified_mobile'] = $data['verified_mobile'];
        $model['email'] = $data['email'];
        $model['seller_username'] = $data['seller_username'];
        $model['seller_password'] = $data['seller_password'];
        $post_data = Yii::$app->request->post();

        if ($model->load($post_data) && $model->save()) {
            Data::sqlRecords('UPDATE `merchant` SET `seller_username`="'.$post_data["RakutenusShopDetails"]["seller_username"].'",`seller_password`="'.$post_data["RakutenusShopDetails"]["seller_password"].'" WHERE `merchant_id`='.$model['merchant_id'],null,'update','admin');
            Data::removeconfigfile($model->shopurl,'rakuten/us','new');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RakutenusShopDetails model.
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
     * Finds the RakutenusShopDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RakutenusShopDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RakutenusShopDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExportCustom()
    {
        $query = "SELECT * FROM `rakutenus_payment` as `np` INNER JOIN `merchant` as nsd ON nsd.merchant_id = np.merchant_id where np.`activated_on`>='2019-07-01 00:00:00'";
        $shopData = Data::sqlRecords($query,'all','select','admin');
// echo "<pre>";print_r($shopData);die;
        if (!file_exists(\Yii::getAlias('@webroot').'/var/custom_email_csv-'.date('Y-m-d'))){
            mkdir(\Yii::getAlias('@webroot').'/var/custom_email_csv-'.date('Y-m-d'),0775, true);
        }
        $base_path=\Yii::getAlias('@webroot').'/var/custom_email_csv-'.date('Y-m-d').'/'.time().'.csv';
        $file = fopen($base_path,"w");
        $headers = array('Merchant id','Shop URL','Email');
        $row = array();
        foreach($headers as $header) {
            $row[] = $header;
        }
        fputcsv($file,$row);

        $csvdata=array();
        $i=0;
        foreach($shopData as $value)
        {
            $row = array();
            $row[]=$value['merchant_id'];
            $row[]=$value['shopurl'];
            $row[]=$value['email'];
            fputcsv($file,$row);
        }

        fclose($file);
        //$link=Yii::$app->request->baseUrl.'/var/product_csv-'.$merchant_id.'/products.csv';
        $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
        $content = $encode . file_get_contents($base_path);
        return \Yii::$app->response->sendFile($base_path);
    }
}
