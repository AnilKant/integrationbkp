<?php

namespace backend\controllers;

use Yii;
use backend\models\NotificationMail;
use backend\models\NotificationMailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NotificationMailController implements the CRUD actions for NotificationMail model.
 */
class NotificationMailController extends MainController
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
     * Lists all NotificationMail models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new NotificationMailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NotificationMail model.
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
     * Creates a new NotificationMail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NotificationMail();

        if (Yii::$app->request->post() /*&& $model->save()*/) {
            $post_data = Yii::$app->request->post();
            if(isset($post_data))
            {
                $post_data['NotificationMail']['days']= json_encode($post_data['NotificationMail']['days']);
                $post_data['NotificationMail']['marketplace'] = json_encode($post_data['NotificationMail']['marketplace']);
            }
            $model->load($post_data);
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing NotificationMail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $post_data = Yii::$app->request->post();
            if(isset($post_data))
            {
                $post_data['NotificationMail']['days']= json_encode($post_data['NotificationMail']['days']);
                $post_data['NotificationMail']['marketplace'] = json_encode($post_data['NotificationMail']['marketplace']);
            }
            $model->load($post_data);
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->days = json_decode($model->days,true);
            $model->marketplace = json_decode($model->marketplace,true);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing NotificationMail model.
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
     * Finds the NotificationMail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NotificationMail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NotificationMail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
