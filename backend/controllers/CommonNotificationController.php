<?php

namespace backend\controllers;

use Yii;
use backend\models\CommonNotification;
use backend\models\CommonNotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\controllers\MainController;

/**
 * CommonNotificationController implements the CRUD actions for CommonNotification model.
 */
class CommonNotificationController extends MainController
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
    /*public function beforeAction($action){
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        return true;
    }*/
    /**
     * Lists all CommonNotification models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new CommonNotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CommonNotification model.
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
     * Creates a new CommonNotification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CommonNotification();
        if(Yii::$app->request->post()){
            $data = Yii::$app->request->post();
            
            $data['CommonNotification']['marketplace'] = implode(',', $data['CommonNotification']['marketplace']);
            if ($model->load($data) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
    }

    /**
     * Updates an existing CommonNotification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->post()){
            $data = Yii::$app->request->post();
            $data['CommonNotification']['marketplace'] = implode(',', $data['CommonNotification']['marketplace']);
            if ($model->load($data) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
    }

    /**
     * Deletes an existing CommonNotification model.
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
     * Finds the CommonNotification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CommonNotification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CommonNotification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
