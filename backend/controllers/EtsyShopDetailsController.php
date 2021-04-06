<?php

namespace backend\controllers;

use Yii;
use backend\models\EtsyShopDetails;
use backend\models\EtsyShopDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Data;
use frontend\components\ShopifyClientHelper;

/**
 * EtsyShopDetailsController implements the CRUD actions for EtsyShopDetails model.
 */
class EtsyShopDetailsController extends MainController
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
     * Lists all EtsyShopDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new EtsyShopDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EtsyShopDetails model.
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
     * Creates a new EtsyShopDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EtsyShopDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EtsyShopDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /*if($post=Yii::$app->request->post()){
            $model->load(Yii::$app->request->post());
          print_r($model);print_r($post);die;  
        }*/

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            $merchant_id=Yii::$app->request->post('EtsyShopDetails')['merchant_id'];

            $shop=Data::integrationSqlRecords("SELECT `shopurl` FROM `merchant` WHERE merchant_id=$merchant_id","one");
            $updateConfig = [];
            $file_dir = __DIR__.'/../../../marketplace-integration/frontend/modules/etsy/config/'.$shop["shopurl"].'';
           
            $updateConfig = include($file_dir.'/config.php');
            $updateConfig['expire_date']=Yii::$app->request->post('EtsyShopDetails')['expire_date'];
            $updateConfig['purchase_status']=Yii::$app->request->post('EtsyShopDetails')['purchase_status'];
            $updateConfig['product_count']=Yii::$app->request->post('EtsyShopDetails')['product_count'];
            $updateConfig['order_count']=Yii::$app->request->post('EtsyShopDetails')['order_count'];
            if (!file_exists($file_dir)) {
                mkdir($file_dir, 0775, true);
            }

            $filenameOrig = $file_dir . '/config.php';
            $fileOrig = fopen($filenameOrig, 'w');
            file_put_contents($filenameOrig, '<?php return $arr = ' . var_export($updateConfig, true) . ';?>');
            fclose($fileOrig);

            $sql="INSERT IGNORE INTO `etsy_config` (`merchant_id`,`config_path`,`value`) VALUES ('".$merchant_id."','config_updated_on_app','1'),
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
     * Deletes an existing EtsyShopDetails model.
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
     * Finds the EtsyShopDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EtsyShopDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EtsyShopDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMerchantView()
    {
        $merchant_id=Yii::$app->request->post('merchant_id');
        $shop_details = ['shop_owner','city','province','country_name','currency','timezone','plan_display_name'];
        $trHtml="";
        if($merchant_id)
        {

            $sql="SELECT `token`,`shopurl`,verified_mobile,shop_json FROM `etsy_shop_details` `esd` 

              LEFT JOIN `merchant` `m` ON `m`.`merchant_id`=`esd`.`merchant_id` WHERE esd.merchant_id=".$merchant_id;
            $result=Data::integrationSqlRecords($sql,"one","select");

            if(isset($result['token'],$result['shopurl']))
            {

                $sc=new ShopifyClientHelper($result['shopurl'],$result['token'],ETSY_APP_KEY,ETSY_APP_SECRET);
                $shop_data = $sc->call('GET','/admin/shop.json');

                if(isset($shop_data['errors']))
                {

                    $trHtml.='
                        <tr>
                            <td class="value_label" width="33%">
                                <span>Errors</span>
                            </td>
                            <td class="value form-group " width="100%">
                                <span>'.json_encode($shop_data['errors']).'</span>
                            </td>
                        </tr>';
                    $shop_data = json_decode($result['shop_json'],true);
                }

                foreach($shop_data as $key=>$value)
                {
                    if(in_array($key,$shop_details))
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

                $trHtml.='
                        <tr>
                            <td class="value_label" width="33%">
                                <span>mobile</span>
                            </td>
                            <td class="value form-group " width="100%">
                                <span>'.$result['verified_mobile'].'</span>
                            </td>
                        </tr>';
            }
        }

        $html='
            <div class="container">
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align: center;">'.$merchant_id.'</h4>
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

    public function actionGetCount(){
        $html='';
        if($merchant_id=Yii::$app->request->post('merchant_id')){
            $count=0;
           /* $query="
            SELECT `token`,`shopurl` FROM `etsy_shop_details`
             INNER JOIN `merchant`
                 ON   `etsy_shop_details`.`merchant_id`=`merchant`.`merchant_id`
              WHERE `etsy_shop_details`.merchant_id='".$merchant_id."' ";

            $registrationData=Data::integrationSqlRecords($query,"one","select");

            if(isset($registrationData['token']) && isset($registrationData['shopurl'])){
                $sc=new ShopifyClientHelper($registrationData['shopurl'],$registrationData['token'],ETSY_APP_KEY,ETSY_APP_SECRET);
                $count=$sc->call('GET','admin/products/count.json');
            }*/

            $query="
            SELECT COUNT(*) as count FROM `etsy_product_variants` 
              WHERE merchant_id='".$merchant_id."' AND `status`!='Deleted' ";

            $data=Data::integrationSqlRecords($query,"one","select");
            if(isset($data['count'])){
                $count=$data['count'];
            }
            $html='
            <div class="container">
                    <div class="modal fade" id="myModalcount" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" style="text-align: center;">'.$merchant_id.': Products</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="sku_details jet_details_heading">

                                        <table class="table table-striped table-bordered">
                                            <tbody> 
                                                <tr>
                                                    <td>
                                                        <span>Total Products</span>
                                                    </td> 
                                                     
                                                    <td>
                                                        <span>
                                                        '.$count.'
                                                        </span>
                                                    </td>
                                                </tr>
                                           </tbody>       
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        }
        return $html;

    }

    public function actionValidateShop(){
        $valid_count = $error_count = $expire_count =$recurring_count = $uninstall_count = 0;
        $sql="SELECT `expire_date`,`token`,`shopurl`,`purchase_status`,`esd`.`merchant_id` FROM `etsy_shop_details` `esd` 
              INNER JOIN `merchant` `m` ON `m`.`merchant_id`=`esd`.`merchant_id`";
        $result=Data::integrationSqlRecords($sql,"all","select");
        foreach ($result as $key => $value){

            $merchant_id=$value['merchant_id'];
            $sc=new ShopifyClientHelper($value['shopurl'],$value['token'],ETSY_APP_KEY,ETSY_APP_SECRET);

            if($value['expire_date']!='0000-00-00 00:00:00' && time()>strtotime($value['expire_date']) && !is_null($value['expire_date']))
            {
                if($value['purchase_status']!=1)
                {
                    $query = "SELECT id from etsy_payment where merchant_id = ".$merchant_id;
                    $payment_data = Data::integrationSqlRecords($query,"all","select");
                    if(is_array($payment_data) && count($payment_data))
                    {
                        $expire_status=4;
                    }
                    else
                    {
                        $expire_status=3;
                    }

                    $query="UPDATE `etsy_shop_details` SET `purchase_status`=$expire_status WHERE `merchant_id`='".$merchant_id."'";
                    Data::integrationSqlRecords($query,null,"update");
                }
            }

            $shopjson=$sc->call('GET','/admin/shop.json');

            if(isset($shopjson['errors'])){
                if($shopjson['errors']=='[API] Invalid API key or access token (unrecognized login or wrong password)'){
                    $query="UPDATE `etsy_shop_details` SET `install_status`=0,`store_status`='active'  WHERE merchant_id=$merchant_id";
                     Data::integrationSqlRecords($query,null,"update");
                    $uninstall_count++;
                }else{
                    $error=$shopjson['errors'];
                    $query="UPDATE `etsy_shop_details` SET `store_status`='inactive'  WHERE `merchant_id`=".$merchant_id;
                     Data::integrationSqlRecords($query,null,"update");
                    $error_count++;

                }

            }else{
                if(isset($shopjson['plan_name']) && $shopjson['plan_name']=='affiliate'){
                    $query="UPDATE `etsy_shop_details` SET `store_status`='inactive'  WHERE `merchant_id`=".$merchant_id;
                    Data::integrationSqlRecords($query,null,"update");
                }
                $valid_count++;

            }
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


   /* public static function checkRecurringPayment($merchant_id,$sc){
        //get all recurring details


        if(is_array($recurringCharges) && count($recurringCharges))
        {
            foreach ($recurringCharges as $key => $value)
            {
                if(isset($value['id']))
                {
                    $recurringPayment=$sc->call("GET","/admin/recurring_application_charges/".$value['id'].".json");
                    if(isset($recurringPayment['id']) && $recurringPayment['status']=="active")
                    {
                        $recurringDeletePayment=$sc->call("DELETE","/admin/recurring_application_charges/".$recurringPayment['id'].".json");
                        if(!isset($recurringDeletePayment['errors']))
                        {
                            //update charge status on db'

                             Data::integrationSqlRecords("UPDATE `etsy_payment` SET `status`='cancelled' WHERE id=".$recurringPayment['id'],null,'update');
                        }
                    }
                }
            }
        }

    }*/

    public static function checkRecurringStatusOnShopify($sc, $merchant_id)
    {
        $recurringCharges = Data::integrationSqlRecords('SELECT `id` FROM `etsy_payment` WHERE merchant_id="'.$merchant_id.'" AND `plan_type`="Recurring Plan (Monthly)"','one','select');

        if(isset($recurringCharges['id']))
        {
            $paymentResponse = $sc->call('GET','/admin/recurring_application_charges/'.$recurringCharges['id'].'.json',['fields'=>'id,status']);
            if(!isset($paymentResponse['errors']) && $paymentResponse['status']=="active")
            {
                return true;
            }else{
                return false;
            }
        }else{
            if(!Data::integrationSqlRecords('SELECT `id` FROM `etsy_payment` WHERE merchant_id="'.$merchant_id.'"')){
              $expire_status=3;  
          }else{
            $expire_status=4;
          }
             
            $query="UPDATE `etsy_shop_details` SET `purchase_status`= '".$expire_status."' WHERE `merchant_id`='".$merchant_id."'";
            Data::integrationSqlRecords($query,null,"update");
        }
    }

    public function actionExport(){
        $sql="SELECT `merchant`.`merchant_id`,`merchant`.`shopurl`,`email`,`install_date`,`expire_date`,`purchase_status` FROM `merchant` INNER JOIN `etsy_shop_details` `esd` ON `esd`.`merchant_id`=`merchant`.`merchant_id`";
        $result=Data::integrationSqlRecords($sql);
        if($result){
             $header=true;
             $address=Yii::getAlias('@rootdir').'/var/user';
            $file_name='etsy-user.csv';
            $file_address=$address.'/'.$file_name;
            if(file_exists($file_address)){
                unlink($file_address);
            }

            if (!is_dir($address)) {
                mkdir($address, 0775, true);

            }

            $input = fopen($file_address, "w");
            foreach ($result as $key => $value) {
               if($header){

                    $headerArray=array_keys($value);
                    fputcsv($input, $headerArray);
                    $header=false;
                }
                fputcsv($input, $value);
            }

            fclose($input);

            header('Content-Type: application/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $file_name);

            $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
            $content = $encode . file_get_contents($file_address);
            echo $content;
            exit();
        }
    }

    public function actionGetPayment()
    {
        $merchant_id = Yii::$app->request->post('merchant_id');
        $payment_response = Data::integrationSqlRecords('SELECT payment_data,payment_type,token,shopurl FROM etsy_payment ep 
        LEFT JOIN `merchant` `m` ON `m`.`merchant_id`=`ep`.`merchant_id` 
        LEFT JOIN `etsy_shop_details` `esd` ON `esd`.`merchant_id`=`ep`.`merchant_id`
        WHERE ep.merchant_id='.$merchant_id,'all');
        $html = '';
        if(is_array($payment_response) && count($payment_response))
        {

            foreach ($payment_response as $payment)
            {
                $html.= '<table class="table table-striped table-bordered"><tbody>';
                if(isset($payment['payment_type'],$payment['payment_data'],$payment['token'],$payment['shopurl']))
                {
                    $payment_data = json_decode($payment['payment_data'],true);
                    $status = $payment_data['status'];
                    if(isset($payment_data['id']))
                    {
                        if($payment['payment_type'] =='recurring')
                        {
                            $sc=new ShopifyClientHelper($payment['shopurl'],$payment['token'],ETSY_APP_KEY,ETSY_APP_SECRET);
                            $payment_current_data = $sc->call('GET','/admin/recurring_application_charges/'.$payment_data['id'].'.json');
                            if(isset($payment_current_data['errors']))
                            {
                                $html.='<tr><td>Error</td><td>'.json_encode($payment_current_data['errors']).'</td></tr>';
                                $status = '';
                            }
                            else
                            {
                                $status = $payment_current_data['status'];
                            }
                        }

                        $html.='<tr><td>plan name</td><td>'.$payment_data['name'].'</td></tr>';
                        $html.='<tr><td>price</td><td>'.$payment_data['price'].'</td></tr>';
                        $html.='<tr><td>created_at</td><td>'.$payment_data['created_at'].'</td></tr>';
                        $is_test = 'no';
                        if($payment_data['test']==1)
                            $is_test = 'yes';
                        $html.='<tr><td>is test</td><td>'.$is_test.'</td></tr>';
                        $html.='<tr><td>status</td><td>'.$status.'</td></tr>';

                    }

                }

                $html.='</tbody></table>';
            }

        }

        if(!$html)
        {
            $html = 'no payment data found in db';
        }

        $modal_html='<div class="container">
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align: center;">'.$merchant_id.'</h4>
                            </div>
                            <div class="modal-body">
                                <div class="sku_details jet_details_heading">
                                    '.$html.'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        return $modal_html;
    }
}
