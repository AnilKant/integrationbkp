<?php

namespace backend\controllers;

use Yii;
use backend\models\WalmartClientDetails;
use backend\models\WalmartClientDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JetTestApiController implements the CRUD actions for JetTestApi model.
 */
class WalmartClientController extends MainController
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

        $searchModel = new WalmartClientDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Display Walmart Client Details
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = WalmartClientDetails::find()->where(['merchant_id'=>$id])->one();
        if($model)
        {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
        else
        {
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Client Details.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = WalmartClientDetails::find()->where(['merchant_id'=>$id])->one();

        if($model)
        {
            if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
                //LatestUpdatesComponent::unsetLatestUpdatesSession();
                return $this->redirect(['view', 'id' => $model->merchant_id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            return $this->redirect(['index']);
        }
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
        if (($model = WalmartClientDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExport(){
        
        $base_path=\Yii::getAlias('@webroot').'/walmart-'.date('YmdGis').'.csv';
        $file = fopen($base_path,"a+");
        $headers = array('M-Id','Name','Email','Mobile');
        $head = array();
        
        foreach($headers as $header) {
            $head[] = $header;
        }

        fputcsv($file,$head);
        $csvdata=array();
        $i=0;
     
            $collection = WalmartClientDetails::find()->All();
            
            foreach($collection as $model){
                   
                $csvdata[$i]['M-Id'] =$model->merchant_id;
                $csvdata[$i]['Name'] =$model->fname." ".$model->lname ;
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
}
