<?php

namespace backend\controllers;

use frontend\modules\merchant\QueryHelper;
use Yii;
use backend\models\OnbuyShopDetails;
use backend\components\Data;
use backend\components\onbuy\TableConstant;
use backend\models\OnbuyShopDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\components\ShopifyClientHelper;

/**
 * OnbuyShopDetailsController implements the CRUD actions for OnbuyShopDetails model.
 */
class OnbuyShopDetailsController extends MainController
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
     * Lists all CatchShopDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OnbuyShopDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
// echo "<pre>";print_r($dataProvider);die;
        // $installation_data = Data::sqlRecords('SELECT * FROM `rakutenus_installation`','one','select','admin');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CatchShopDetails model.
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
     * Creates a new CatchShopDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OnbuyShopDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CatchShopDetails model.
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

        $sql="SELECT `value` FROM `onbuy_config` WHERE `merchant_id`=:merchant_id AND `config_path`=:config_path";
        $result = QueryHelper::sqlRecords($sql,[':config_path'=>'barcode_exempted',':merchant_id'=>$model['merchant_id']],'one','admin');

        $model['barcode_exempted'] = !empty($result['value'])?$result['value']:0;
        if ($model->load($post_data) && $model->save()) {
            //echo "<pre>";print_r($post_data);die;
            Data::sqlRecords('UPDATE `merchant` SET `seller_username`="'.$post_data["OnbuyShopDetails"]["seller_username"].'",`seller_password`="'.$post_data["OnbuyShopDetails"]["seller_password"].'" WHERE `merchant_id`='.$model['merchant_id'],null,'update','admin');
            Data::sqlRecords('INSERT IGNORE INTO `onbuy_config`(`merchant_id`,`config_path`,`value`) VALUES ("'.$model['merchant_id'].'","barcode_exempted", "'.$post_data["OnbuyShopDetails"]["barcode_exempted"].'")ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)',null,'update','admin');
            Data::removeconfigfile($model->shopurl,'onbuy','newapp');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing OnbuyShopDetails model.
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
     * Finds the CatchShopDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatchShopDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OnbuyShopDetails::findOne($id)) !== null) {
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

    public function actionValidateShop(){
        $valid_count = $error_count = $expire_count =$recurring_count = $uninstall_count = 0;
        $sql="SELECT `expire_date`,`token`,`shopurl`,`purchase_status`,`esd`.`merchant_id` FROM `onbuy_shop_details` `esd` 
              INNER JOIN `merchant` `m` ON `m`.`merchant_id`=`esd`.`merchant_id`";
        $result=Data::integrationSqlRecords($sql,"all","select");
        
        foreach ($result as $key => $value){

            $merchant_id=$value['merchant_id'];
            $sc=new ShopifyClientHelper($value['shopurl'],$value['token'],TableConstant::ONBUY_APP_KEY,TableConstant::ONBUY_APP_SECRET);

            if($value['expire_date']!='0000-00-00 00:00:00' && time()>strtotime($value['expire_date']) && !is_null($value['expire_date'])){

                if($value['purchase_status']!=2 && $value['purchase_status']!=4){

                    if(!self::checkRecurringStatusOnShopify($sc, $merchant_id)){
                    
                        $recurring_count++;
                    }else{
                        $days = 5;
                        $expire_date = date('Y-m-d H:i:s', strtotime('+'.$days.' days', strtotime(date('Y-m-d H:i:s'))));

                        $query="UPDATE `onbuy_shop_details` SET `expire_date`='".$expire_date."',`purchase_status`=2  WHERE merchant_id=$merchant_id";
                        Data::integrationSqlRecords($query,null,"update");
                        $expire_count++;

                    }

                }

            }
            $shopjson=$sc->call('GET','/admin/shop.json');

            if(isset($shopjson['errors'])){
                if($shopjson['errors']=='[API] Invalid API key or access token (unrecognized login or wrong password)'){
                    $query="UPDATE `onbuy_shop_details` SET `install_status`=0  WHERE merchant_id=$merchant_id";
                    Data::integrationSqlRecords($query,null,"update");
                    $uninstall_count++;
                }/*else{
                    $error=$shopjson['errors'];
                    $query="UPDATE `catchmp_shop_details` SET `store_status`='inactive'  WHERE `merchant_id`=".$merchant_id;
                    Data::integrationSqlRecords($query,null,"update");
                    $error_count++;

                }*/

            }/*else{
                if(isset($shopjson['plan_name']) && $shopjson['plan_name']=='affiliate'){
                    $query="UPDATE `catchmp_shop_details` SET `store_status`='inactive'  WHERE `merchant_id`=".$merchant_id;
                    Data::integrationSqlRecords($query,null,"update");
                }
                $valid_count++;

            }*/
            //echo "<pre>";print_r($value['shop']);print_r($shopjson);die;


        }
        $result['error_count']=$error_count;
        $result['valid_count']=$valid_count;
        $result['expire_count']=$expire_count;
        $result['uninstall_count']=$uninstall_count;
        $result['recurring_count']=$recurring_count;
        Yii::$app->session->setFlash('success', json_encode(['status_updated'=>$result]));
        return $this->redirect(['index']);
        //echo "<pre>";print_r($result);die;
    }

    public static function checkRecurringStatusOnShopify($sc, $merchant_id)
    {
        $recurringCharges = Data::integrationSqlRecords('SELECT `id` FROM `onbuy_payment` WHERE merchant_id="'.$merchant_id.'" AND `plan_type`="Recurring Plan (Monthly)"','one','select');

        if(isset($recurringCharges['id']))
        {
            $paymentResponse = $sc->call('GET','/admin/recurring_application_charges/'.$recurringCharges['id'].'.json',['fields'=>'id,status']);
            if(!isset($paymentResponse['errors']) && $paymentResponse['status']=="active")
            {
                return true;
            }
        }
        return false;
    }
}
