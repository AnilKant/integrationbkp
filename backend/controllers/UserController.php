<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;

/**
 * WalmartshopdetailsController implements the CRUD actions for WalmartShopDetails model.
 */
class UserController extends MainController
{
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
     * Disable user from accessing the app.
     * @param integer $id
     */
    public function actionDisable($id)
    {
        $model = $this->findModel($id);

        if ($model) 
        {
            $model->status = User::STATUS_DELETED;

            if($model->save(false)){
                Yii::$app->session->setFlash('success','User Disabled Successfully');
            }
            else{
                Yii::$app->session->setFlash('error','User Can\'t be Disabled');
            }
        }
        else {
            Yii::$app->session->setFlash('error','Merchant id '. $id .' do not exits.');
        }   

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Enable user to access the app.
     * @param integer $id
     */
    public function actionEnable($id)
    {
        $model = $this->findModel($id);

        if ($model) 
        {
            $model->status = User::STATUS_ACTIVE;

            if($model->save(false)){
                Yii::$app->session->setFlash('success','User Enabled Successfully');
            }
            else{
                Yii::$app->session->setFlash('error','User Can\'t be Enabled');
            }
        }
        else {
            Yii::$app->session->setFlash('error','Merchant id '. $id .' do not exits.');
        }   

        return $this->redirect(Yii::$app->request->referrer);
    }
 
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, false is return
     * @param integer $id
     * @return Object User | Bool
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }
}
