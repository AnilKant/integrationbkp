<?php

namespace backend\controllers;

use Yii;
use backend\models\Pricefallsshopdetails;
use backend\models\PricefallsshopdetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Data;


/**
 * PricefallsshopdetailsController implements the CRUD actions for Pricefallsshopdetails model.
 */
class PricefallsshopdetailsController extends MainController
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
     * Lists all Pricefallsshopdetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
    		return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
    	}
        $searchModel = new PricefallsshopdetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pricefallsshopdetails model.
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
     * Creates a new Pricefallsshopdetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pricefallsshopdetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Pricefallsshopdetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {  
       /**/
       
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
     * Deletes an existing Pricefallsshopdetails model.
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
     * Finds the Pricefallsshopdetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pricefallsshopdetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        
        if (($model = Pricefallsshopdetails::findOne($id)) !== null) {
            return $model;
        } else {
       
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


     public function actionMerchantView(){
        $merchant_id=Yii::$app->request->post('merchant_id');
        $trHtml="";
        if($merchant_id)
        {
            $query="
            SELECT `pricefalls_registration`.fname as first_name,`pricefalls_registration`.lname as last_name,`pricefalls_registration`.mobile,`pricefalls_registration`.time_zone,`pricefalls_registration`.time_slot,`merchant`.shop_json
             FROM `pricefalls_registration` 
             INNER JOIN `merchant` 
                 ON   `pricefalls_registration`.`merchant_id`=`merchant`.`merchant_id`
              WHERE `pricefalls_registration`.merchant_id='".$merchant_id."' ";
            // print_r($query);die;
            $registrationData=Data::integrationSqlRecords($query,"one","select");


            if(isset($registrationData['first_name']))
            {

                foreach ($registrationData as $key => $value) {
                    if($key=='shop_json'){
                        $data=json_decode($registrationData['shop_json']);
                        foreach($data as $key=>$value){
                            if($key=='country'||$key=='province' ||$key=='currency'||$key=='plan_name' ){
                                if($key=='plan_name'){
                                    $key='plan_display_name';
                                }
                                $trHtml.='
                        <tr>
                            <td class="value_label" width="33%">
                                <span>'.$key.'</span>
                            </td>
                            <td class="value form-group " width="100%">
                                <span>'.$value.'</span>
                            </td>
                        </tr>';
                            }
                        }
                    }else{
                        $trHtml.='
                <tr>
                    <td class="value_label" width="33%">
                        <span>'.$key.'</span>
                    </td>
                    <td class="value form-group " width="100%">
                        <span>'.$value.'</span>
                    </td>
                </tr>';
                    }
                }
            }
        }
        $html='
            <div class="container">
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" style="text-align: center;">'.$merchant_id.': Registration</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="sku_details jet_details_heading">

                                        <table class="table table-striped table-bordered">
                                            <tbody>'
            .$trHtml.
            '</tbody>       
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

        return $html;
       // return json_encode(['html' => $html]);

    }


   

}
