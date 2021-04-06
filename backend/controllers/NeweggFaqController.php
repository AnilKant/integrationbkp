<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 8/3/18
 * Time: 5:28 PM
 */

namespace backend\controllers;

use Yii;
use backend\models\NeweggFaq;
use backend\models\NeweggFaqSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class NeweggFaqController extends MainController
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
     * Lists all WalmartFaq models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        
        $searchModel = new NeweggFaqSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WalmartFaq model.
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
     * Creates a new WalmartFaq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NeweggFaq();
        if(isset($_POST['NeweggFaq']['marketplace'])){
            $model->marketplace =implode(",",$_POST['NeweggFaq']['marketplace']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            /*print_r($_POST);die;*/
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WalmartFaq model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($post_data=Yii::$app->request->post() ) {
           
            $model->marketplace=implode(",",$post_data['NeweggFaq']['marketplace']);
            $model->query=$post_data['NeweggFaq']['query'];
            $model->description=$post_data['NeweggFaq']['description'];
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->marketplace = explode(',', $model->marketplace);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WalmartFaq model.
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
     * Finds the WalmartFaq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WalmartFaq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NeweggFaq::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}