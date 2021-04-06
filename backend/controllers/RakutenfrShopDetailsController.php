<?php

namespace backend\controllers;

use Yii;
use backend\models\RakutenfrShopDetails;
use backend\components\Data;
use backend\models\RakutenfrShopDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RakutenfrShopDetailsController implements the CRUD actions for RakutenfrShopDetails model.
 */
class RakutenfrShopDetailsController extends MainController
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
     * Lists all RakutenfrShopDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RakutenfrShopDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RakutenfrShopDetails model.
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
     * Creates a new RakutenfrShopDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RakutenfrShopDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RakutenfrShopDetails model.
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
            Data::sqlRecords('UPDATE `merchant` SET `seller_username`="'.$post_data["RakutenfrShopDetails"]["seller_username"].'",`seller_password`="'.$post_data["RakutenfrShopDetails"]["seller_password"].'" WHERE `merchant_id`='.$model['merchant_id'],null,'update','admin');
            Data::removeconfigfile($model->shopurl,'rakuten/fr','new');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RakutenfrShopDetails model.
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
     * Finds the RakutenfrShopDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RakutenfrShopDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RakutenfrShopDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExportCustom()
    {
        $query = "SELECT * FROM `rakutenfr_payment` as `np` INNER JOIN `merchant` as nsd ON nsd.merchant_id = np.merchant_id where np.`activated_on`>='2019-07-01 00:00:00'";
        $shopData = Data::sqlRecords($query,'all','select','admin');
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
