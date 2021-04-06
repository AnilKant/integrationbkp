<?php

namespace backend\controllers;

use Yii;
use common\models\PricingPlan;
use common\models\PricingPlanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ConditionalRange;
use common\models\ConditionalCharge;
/**
 * PricingplanController implements the CRUD actions for PricingPlan model.
 */
class PricingplanController extends MainController
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
     * Lists all PricingPlan models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new PricingPlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PricingPlan model.
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
     * Creates a new PricingPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PricingPlan();
        if ($model->load(Yii::$app->request->post())) {        	        	
            $model->duration = $_POST['PricingPlan']['duration'][1]." ".$_POST['PricingPlan']['duration'][0];
            $model->apply_on = implode(',', $_POST['PricingPlan']['apply_on']);
            $model->additional_condition = json_encode($_POST['PricingPlan']['additional_condition']);
           if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
           }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PricingPlan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionViewcondition()
    {
        $this->layout = "main2";
        //print_r($_POST['id']);die();
        $connection = Yii::$app->getDb();
        $charge_id = $connection->createCommand("SELECT `additional_condition` FROM `pricing_plan` WHERE `id`='".$_POST['id']."'")->queryAll();
        $charge_id = explode(",",$charge_id[0]['additional_condition']);
        
        $model = ConditionalCharge::find()->where(['id'=> $charge_id])->all();
        foreach ($model as $value) {
            $value->conditional_range = ConditionalRange::find()->where(['charge_id'=> $value['id']])->all();
            
            $additional_condition[] = $value;
        }
        //$model->conditional_range = ConditionalRange::find()->where(['charge_id'=> $id])->all();
        //print_r($additional_condition);die();
        return $this->render('additional', [
                'model' => $additional_condition,
            ]);

    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
         if ($model->load(Yii::$app->request->post())) {
            var_dump(Yii::$app->request->post());
            $model->capped_amount = $_POST['PricingPlan']['capped_amount'];
            $model->duration = $_POST['PricingPlan']['duration'][1]." ".$_POST['PricingPlan']['duration'][0];
            $model->apply_on = implode(',',$_POST['PricingPlan']['apply_on']);
            $model->additional_condition = implode(',',$_POST['PricingPlan']['additional_condition']);

           if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
           }
            else {
            return $this->render('update', [
                'model' => $model,
            ]);
            }
        } else {
            $model->apply_on = explode(',',$model->apply_on);
            $model->additional_condition = explode(',',$model->additional_condition);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PricingPlan model.
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
     * Finds the PricingPlan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PricingPlan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PricingPlan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
