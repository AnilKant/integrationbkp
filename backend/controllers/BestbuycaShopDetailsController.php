<?php

namespace backend\controllers;

use Yii;
use backend\models\BestbuycaShopDetails;
use backend\models\BestbuycaShopDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Data;
use backend\controllers\MainController;
use frontend\components\ShopifyClientHelper;

/**
 * BestbuycaShopDetailsController implements the CRUD actions for BestbuycaShopDetails model.
 */
class BestbuycaShopDetailsController extends MainController
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

    /*public function beforeAction($action){
        if (Yii::$app->user->isGuest) {

            return $this->redirect(['site/login']);
        }
        return true;
    }*/


    /**
     * Lists all BestbuycaShopDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new BestbuycaShopDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BestbuycaShopDetails model.
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
     * Creates a new BestbuycaShopDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BestbuycaShopDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BestbuycaShopDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            $sql="INSERT IGNORE INTO `bestbuyca_config` (`merchant_id`,`config_path`,`value`) VALUES ('".$merchant_id."','config_updated_on_app','1'),
                 ('".$merchant_id."','config_updated_on_queue','1')";
            Data::integrationSqlRecords($sql,null,'insert');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BestbuycaShopDetails model.
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
     * Finds the BestbuycaShopDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BestbuycaShopDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BestbuycaShopDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionMerchantView(){
        $merchant_id=Yii::$app->request->post('merchant_id');
       // $model=$this->findModel($id);
        /*print_r($model);die;*/
        $trHtml="";
        if($merchant_id)
        {
            $query="
            SELECT `bestbuyca_registration`.fname as first_name,`bestbuyca_registration`.lname as last_name,`merchant`.verified_mobile,`bestbuyca_registration`.time_zone,`bestbuyca_registration`.time_slot,`merchant`.shop_json
            FROM `bestbuyca_registration` 
            INNER JOIN `merchant` 
            ON   `bestbuyca_registration`.`merchant_id`=`merchant`.`merchant_id`
            WHERE `bestbuyca_registration`.merchant_id='".$merchant_id."' ";
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

        public function actionValidateShop(){
        $valid_count = $error_count = $expire_count =$recurring_count = $uninstall_count = 0;
        $sql="SELECT `expire_date`,`token`,`shopurl`,`purchase_status`,`bsd`.`merchant_id` FROM `bestbuyca_shop_details` `bsd` 
              INNER JOIN `merchant` `m` ON `m`.`merchant_id`=`bsd`.`merchant_id`";
        $result=Data::integrationSqlRecords($sql,"all","select");
        foreach ($result as $key => $value){

            $merchant_id=$value['merchant_id'];
            $sc=new ShopifyClientHelper($value['shopurl'],$value['token'],BESTBUYCA_APP_KEY,BESTBUYCA_APP_SECRET);

            if($value['expire_date']!='0000-00-00 00:00:00' && time()>strtotime($value['expire_date']) && !is_null($value['expire_date'])){

                if($value['purchase_status']!=3 && $value['purchase_status']!=4){

                    if(!self::checkRecurringStatusOnShopify($sc, $merchant_id)){
                        /*   if($value['purchase_status']==1){
                               $expire_status=3;
                           }else{
                               $expire_status=4;
                           }
                           $query="UPDATE `bestbuyca_shop_details` SET `purchase_status`=$expire_status WHERE `merchant_id`='".$value['merchant_id']."'";
                           Data::integrationSqlRecords($query,null,"update");*/
                        $recurring_count++;
                    }else{
                        $days = 5;
                        $expire_date = date('Y-m-d H:i:s', strtotime('+'.$days.' days', strtotime(date('Y-m-d H:i:s'))));

                        $query="UPDATE `bestbuyca_shop_details` SET `expire_date`='".$expire_date."',`purchase_status`=2  WHERE merchant_id=$merchant_id";
                        Data::integrationSqlRecords($query,null,"update");
                        $expire_count++;

                    }

                }

            }
            $shopjson=$sc->call('GET','/admin/shop.json');

            /*if(isset($shopjson['errors'])){
                if($shopjson['errors']=='[API] Invalid API key or access token (unrecognized login or wrong password)'){
                    $query="UPDATE `bestbuyca_shop_details` SET `install_status`=0,`store_status`='active'  WHERE merchant_id=$merchant_id";
                    Data::integrationSqlRecords($query,null,"update");
                    $uninstall_count++;
                }else{
                    $error=$shopjson['errors'];
                    $query="UPDATE `bestbuyca_shop_details` SET `store_status`='inactive'  WHERE `merchant_id`=".$merchant_id;
                    Data::integrationSqlRecords($query,null,"update");
                    $error_count++;

                }

            }else{
                if(isset($shopjson['plan_name']) && $shopjson['plan_name']=='affiliate'){
                    $query="UPDATE `bestbuyca_shop_details` SET `store_status`='inactive'  WHERE `merchant_id`=".$merchant_id;
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
        $recurringCharges = Data::integrationSqlRecords('SELECT `id` FROM `bestbuyca_payment` WHERE merchant_id="'.$merchant_id.'" AND `plan_type`="Recurring Plan (Monthly)"','one','select');

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
