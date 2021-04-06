<?php

namespace backend\controllers;

use Yii;
use backend\models\ReferrerUser;
use backend\models\ReferrerUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReferrerController implements the CRUD actions for ReferrerUser model.
 */
class ReferrerController extends MainController
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
     * Lists all ReferrerUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        
        $searchModel = new ReferrerUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReferrerUser model.
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
     * Finds the ReferrerUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReferrerUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReferrerUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionApprove($id)
    {
        if($this->findModel($id)->approve()) {
            Yii::$app->session->setFlash('success', 'Approved successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Cannot be approved.');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUnapprove($id)
    {
        if($this->findModel($id)->unapprove()) {
            Yii::$app->session->setFlash('success', 'Unapproved successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Cannot be unapproved.');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}
