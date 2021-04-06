<?php

namespace backend\controllers;

use Yii;
use backend\models\JetTestApi;
use backend\models\JetTestApiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\JetRegistration;

/**
 * JetTestApiController implements the CRUD actions for JetTestApi model.
 */
class JetTestApiController extends MainController
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
     * Lists all JetTestApi models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new JetTestApiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JetTestApi model.
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
     * Creates a new JetTestApi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JetTestApi();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing JetTestApi model.
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
    public function actionExport(){
        
        $base_path=\Yii::getAlias('@webroot').'/'.date('YmdGis').'.csv';
        $file = fopen($base_path,"a+");
        $headers = array('M-Id','Name','Email','Mobile');
        $head = array();
        
        foreach($headers as $header) {
            $head[] = $header;
        }

        fputcsv($file,$head);
        $csvdata=array();
        $i=0;
     
            $collection = JetRegistration::find()->All();
            
            foreach($collection as $model){
                   
                $csvdata[$i]['M-Id'] =$model->merchant_id;
                $csvdata[$i]['Name'] =$model->name;
                $csvdata[$i]['Email'] =$model->email;
                $csvdata[$i]['Mobile'] =$model->mobile;
                
                $i++;
              
            }
             //var_dump($row);echo "<hr>" ;// var_dump($csvdata); die;    
       
        foreach($csvdata as $v)
        { 
            $row = array(); 
            $row[] =$v['M-Id'];        
            $row[] =$v['Name'];
            $row[] =$v['Email'];
            $row[] =$v['Mobile'];

            fputcsv($file,$row); 
            
        }  
        fclose($file);
       
        return  Yii::$app->response->sendFile($base_path);
    }

    /**
     * Deletes an existing JetTestApi model.
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
     * Finds the JetTestApi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JetTestApi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JetTestApi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
