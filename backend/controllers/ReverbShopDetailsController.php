<?php

namespace backend\controllers;

use Yii;
use backend\models\ReverbShopDetails;
use backend\components\Data;
use backend\models\ReverbShopDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\reverb\TableConstant;

/**
 * ReverbShopDetailsController implements the CRUD actions for ReverbShopDetails model.
 */
class ReverbShopDetailsController extends MainController
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
     * Lists all ReverbShopDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReverbShopDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
// echo "<pre>";print_r($dataProvider);die;
        // $installation_data = Data::sqlRecords('SELECT * FROM `Reverb_installation`','one','select','admin');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReverbShopDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $data = Data::sqlRecords('SELECT * FROM `merchant` WHERE `merchant_id`='.$model['merchant_id'],'one','select','admin');
        $model['shopurl'] = $data['shopurl'];
        $model['verified_mobile'] = $data['verified_mobile'];
        $model['email'] = $data['email'];
        $model['seller_username'] = $data['seller_username'];
        $model['seller_password'] = $data['seller_password'];
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ReverbShopDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReverbShopDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ReverbShopDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $data = Data::sqlRecords('SELECT * FROM `merchant` WHERE `merchant_id`='.$model['merchant_id'],'one','select','admin');
        $model['shopurl'] = $data['shopurl'];
        $model['verified_mobile'] = $data['verified_mobile'];
        $model['email'] = $data['email'];
        $model['seller_username'] = $data['seller_username'];
        $model['seller_password'] = $data['seller_password'];
        $post_data = Yii::$app->request->post();

        if ($model->load($post_data) && $model->save()) {
            Data::sqlRecords('UPDATE `merchant` SET `seller_username`="'.$post_data["ReverbShopDetails"]["seller_username"].'",`seller_password`="'.$post_data["ReverbShopDetails"]["seller_password"].'" WHERE `merchant_id`='.$model['merchant_id'],null,'update','admin');
            Data::removeconfigfile($model->shopurl,'reverb','new');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ReverbShopDetails model.
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
     * Finds the ReverbShopDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReverbShopDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReverbShopDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionViewMerchant()
    {

        $merchant_id=Yii::$app->request->post('id');
        $param=Yii::$app->request->post('param');
        $trHtml="";
        $merchantData=[];
        if($merchant_id)
        {
            if($param=="Registration")
            {
                $merchantData=Data::sqlRecords("SELECT shop_json FROM `".TableConstant::MERCHANT."` WHERE merchant_id=".$merchant_id,null,"one",'admin');
            }
            /*elseif($param=="Payment")
            {
                $merchantData=Data::sqlRecords("SELECT id,activated_on,plan_type,status FROM `jet_recurring_payment` WHERE merchant_id=".$merchant_id,"all");
            }*/
            
            if(is_array($merchantData) && count($merchantData))
            {
                foreach ($merchantData as $m_value) 
                {
                    $m_value = json_decode($m_value['shop_json'],true);
                    foreach ($m_value as $key => $value) 
                    {
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
            else{
                     $trHtml.='
                     <div>
                            <h3 class="text-center">No Data Available</h3>
                     </div>
                        ';
           }
           
        }
         $html='
                <div class="container">
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" style="text-align: center;">'.$merchant_id.': '.$param.'</h4>
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
    }
}
