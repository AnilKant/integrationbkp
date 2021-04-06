<?php

namespace backend\controllers;

use frontend\components\QueryHelper;
use Yii;
use backend\models\NewappsErrorNotification;
use backend\models\NewappsErrorNotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Data;

/**
 * NewappsErrorNotificationController implements the CRUD actions for ErrorNotification model.
 */
class NewappsErrorNotificationController extends MainController
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
        $searchModel = new NewappsErrorNotificationSearch();
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
        $model = new NewappsErrorNotification();

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
        $merchant_id = Yii::$app->request->post('m_id',false);
        $error_type = Yii::$app->request->post('error_type',false);
        $mp = Yii::$app->request->post('mp',false);

        if($mp && $merchant_id && $error_type){
            $query = "DELETE FROM `newapps_error_notification` WHERE `merchant_id`=:m_id AND `error_type` =:error_type AND `marketplace` LIKE :mp ";
            QueryHelper::sqlRecords($query, [':m_id'=>$merchant_id,':error_type'=>$error_type,':mp'=>$mp], 'delete');
        }
        $error=NewappsErrorNotification::getErrorTypeNameById($error_type);
         return $this->redirect(['index?'.$error]);
    }

    /**
     * Finds the ErrorNotification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NewappsErrorNotification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NewappsErrorNotification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
