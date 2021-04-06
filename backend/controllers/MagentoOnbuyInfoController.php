<?php

namespace backend\controllers;

use Yii;
use frontend\components\Data;
use backend\models\MagentoOnbuyInfo;
use backend\models\MagentoOnbuyInfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MagentoOnbuyInfoController implements the CRUD actions for MagentoOnbuyInfo model.
 */
class MagentoOnbuyInfoController extends MainController
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
     * Lists all MagentoOnbuyInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new MagentoOnbuyInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MagentoOnbuyInfo model.
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
     * Creates a new MagentoOnbuyInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($data=false)
    {
        //$data =  array('domain'=>'abcXyz.com','email'=>'abc@gmail.com','framework'=>'m1');
        $data=$_GET;
        $sql = "SELECT `id`,`domain`,`framework` FROM `magento_onbuy_info` WHERE domain ='".$data['domain']."'";
        $details = Data::sqlRecords($sql,'one','select'); 
        if(isset($details['domain']) && !empty($details['domain'])){
            return true;
        }
        
        $model = new MagentoOnbuyInfo();
        $model->domain = $data['domain'];
        $model->email = $data['email'];
        $model->framework = $data['framework'];
        if ($model->save(false)) {
            return true;
        } /*else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }*/
        return false;
    }

    /**
     * Updates an existing MagentoOnbuyInfo model.
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
     * Deletes an existing MagentoOnbuyInfo model.
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
     * Finds the MagentoOnbuyInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MagentoOnbuyInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MagentoOnbuyInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
