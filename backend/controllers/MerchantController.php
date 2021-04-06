<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Merchant;

/**
 * WalmartshopdetailsController implements the CRUD actions for WalmartShopDetails model.
 */
class MerchantController extends MainController
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
            /* print_r($model);die;*/

        if ($model) 
        {
            $model->status = Merchant::STATUS_DELETED;

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
       /* $model = $this->findModel(['merchant_id' => $id]);*/
        $model = $this->findModel($id);
        if ($model) 
        {
            $model->status = Merchant::STATUS_ACTIVE;

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
        if ((Merchant::find()->where(['merchant_id' => $id])->one() !== null)) {
            return Merchant::find()->where(['merchant_id' => $id])->one();
        } else {
            return false;
        }
    }
}
