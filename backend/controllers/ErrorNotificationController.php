<?php

namespace backend\controllers;

use Yii;
use backend\models\ErrorNotification;
use backend\models\ErrorNotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Data;
use frontend\modules\walmart\components\TableConstant;
use frontend\modules\walmart\components\Product\ProductHelper;
/**
 * ErrorNotificationController implements the CRUD actions for ErrorNotification model.
 */
class ErrorNotificationController extends MainController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ErrorNotification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ErrorNotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ErrorNotification model.
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
     * Creates a new ErrorNotification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ErrorNotification();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ErrorNotification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        die('Permission Deny');
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
     * Deletes an existing ErrorNotification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        // print_r($_POST);die("Zdfg");
        $merchant_id = isset($_POST['id'])?$_POST['id'] : '';
        $error_type = isset($_POST['error_type'])?$_POST['error_type'] : '';
       
        if($merchant_id!='' && $error_type!=''){
            $query = "DELETE FROM `".TableConstant::WALMART_ERROR_NOTIFICATION."` WHERE `merchant_id`='".$merchant_id."' AND `error_type` ='".$error_type."'";
            $abc = Data::sqlRecords($query, 'all', 'delete');
           

        }
        $error='';
        if($error_type == ProductHelper::PRODUCT_CREATE){
            $error = 'product_create';
        }elseif ($error_type == ProductHelper::PRODUCT_UPDATE) {
            $error = 'product_update';
        }
        elseif ($error_type == ProductHelper::PRODUCT_DELETE) {
            $error = 'product_delete';
        }
        elseif ($error_type == ProductHelper::PRICE_UPDATE_ERROR) {
            $error = 'price_update';
        }
        elseif ($error_type == ProductHelper::INVENTORY_UPDATE_ERROR) {
            $error = 'inventory';
        }
        elseif ($error_type == ProductHelper::SKU_UPDATE_ERROR) {
            $error = 'sku_update';
        }
        elseif ($error_type == ProductHelper::PRODUCT_DELETE_ERROR) {
            $error = 'marketplace_delete_error';
        }
        elseif ($error_type == ProductHelper::ORDER_SHIPMENT) {
            $error = 'order_shipment';
        }

         return $this->redirect(['index?'.$error]);
    }

    /**
     * Finds the ErrorNotification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ErrorNotification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ErrorNotification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
