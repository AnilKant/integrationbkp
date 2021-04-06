<?php

namespace backend\controllers;

use Yii;
use common\models\ConditionalCharge;
use common\models\ConditionalChargeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ConditionalRange;
use backend\controllers\MainController;

/**
 * ConditionalChargeController implements the CRUD actions for ConditionalCharge model.
 */
class ConditionalChargeController extends MainController
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
     * Lists all ConditionalCharge models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new ConditionalChargeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConditionalCharge model.
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
     * Creates a new ConditionalCharge model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ConditionalCharge();
        $model->conditional_range = new ConditionalRange();
        //print_r($_POST);die();
        if ($model->load(Yii::$app->request->post()) && $model->conditional_range->load(Yii::$app->request->post())) 
        {   
            if($model->save(false))
            {
                $connection = Yii::$app->getDb();
                $charge_id = $model->id;
                $condition[0] = $_POST['ConditionalRange'];
                $i = 0;
                foreach ($condition as $value) {
                    $count = count($value['amount']);

                    for ($j=0;$j<$count;$j++) 
                    { 
                        if (isset($value['from_range'][$i])) {
                            $from_range = $value['from_range'][$i];
                            $to_range = $value['to_range'][$i];
                            $fixed_range = NULL;
                        }
                        else{
                            $from_range = NULL;
                            $to_range = NULL;
                            $fixed_range = $value['fixed_range'][$i];
                        }
                        $amount_type = $value['amount_type'][$i];
                        $amount = $value['amount'][$i];
                        $query = 'INSERT INTO `conditional_range`(`charge_id`, `fixed_range`, `from_range`, `to_range`, `amount_type`, `amount`) VALUES ("'.$charge_id.'","'.$fixed_range.'","'.$from_range.'","'.$to_range.'","'.$amount_type.'","'.$amount.'")';
                            $connection->createCommand($query)->execute();
                        $i++;
                    }
                }
                
                    Yii::$app->session->setFlash('success','Details is saved successfully');
                    return $this->redirect(['view', 'id' => $model->id]);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ConditionalCharge model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        //print_r($_POST);die();
        $model = ConditionalCharge::find()->where(['id'=> $id])->one();
        $model->conditional_range = ConditionalRange::find()->where(['charge_id'=> $id])->all();

        if ($model->load(Yii::$app->request->post())) 
        {
            if($model->save(false)){
                $connection = Yii::$app->getDb();
                     $charge_id = $model->id;
                     $condition[0] = $_POST['ConditionalRange'];
                     $i = 0;
                     ConditionalRange::deleteAll('charge_id='.$id);
                     foreach ($condition as $value) {
                        $count = count($value['amount']);
                        //print_r($count);die;
                        for ($j=0;$j<$count;$j++) { 
                            if (isset($value['from_range'][$i])) {
                                $from_range = $value['from_range'][$i];
                                $to_range = $value['to_range'][$i];
                                $fixed_range = NULL;
                            }
                            else{
                                $from_range = NULL;
                                $to_range = NULL;
                                $fixed_range = $value['fixed_range'][$i];
                            }
                            $amount_type = $value['amount_type'][$i];
                            $amount = $value['amount'][$i];
                            $query = 'INSERT INTO `conditional_range`(`charge_id`, `fixed_range`, `from_range`, `to_range`, `amount_type`, `amount`) VALUES ("'.$charge_id.'","'.$fixed_range.'","'.$from_range.'","'.$to_range.'","'.$amount_type.'","'.$amount.'")';
                                $connection->createCommand($query)->execute();
                            $i++;
                    }
                 }
                
                    Yii::$app->session->setFlash('success','Details is saved successfully');
                    return $this->redirect(['view', 'id' => $model->id]);
        	}
    	}
        else {
            return $this->render('update', [
                'model' => $model,

        ]);
    }
  }

    /**
     * Deletes an existing ConditionalCharge model.
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
     * Finds the ConditionalCharge model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConditionalCharge the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConditionalCharge::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
