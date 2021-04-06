<?php

namespace backend\controllers;

use frontend\modules\walmart\components\Dashboard\OrderInfo;
use frontend\modules\walmart\components\Dashboard\Productinfo;
use frontend\modules\walmart\components\Product\ProductHelper;
use frontend\modules\walmart\components\QueryHelper;
use frontend\modules\walmart\components\TableConstant;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\WalmartShopDetails;
use backend\models\WalmartExtensionDetail;
use backend\models\WalmartShopDetailsSearch;
use backend\components\Sms;
use backend\components\Data;
use frontend\modules\walmart\walmartsdk\src\Walmart\Item;
use frontend\modules\walmart\components\ConfigurationHelper;
use frontend\modules\walmart\components\Data as moduleData;


/**
 * WalmartshopdetailsController implements the CRUD actions for WalmartShopDetails model.
 */
class WalmartshopdetailsController extends MainController
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
     * Lists all WalmartShopDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new WalmartShopDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WalmartShopDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = WalmartShopDetails::find()->joinWith(['walmartExtensionDetail'])->where(['walmart_shop_details.merchant_id'=>$id])->One();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WalmartShopDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = WalmartShopDetails::find()->joinWith(['walmartExtensionDetail'])->where(['walmart_shop_details.merchant_id'=>$id])->One();
        // print_r($model);die('dcfgiesw');
        if ($model->load(Yii::$app->request->post()) && $model->walmartExtensionDetail->load(Yii::$app->request->post()))
        {

            if($model->save(false) && $model->walmartExtensionDetail->save(false)){
                Yii::$app->session->setFlash('success','Details is saved successfully');
            }
            else{
                Yii::$app->session->setFlash('error','Details is not saved successfully');
            }
            return $this->redirect(['view', 'id'=> $id]);
        }
        else {
            return $this->render('update', [
                'model' => $model,

        ]);
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
            if($param == "Registration")
            {
                $merchantData=Data::sqlRecords("SELECT `walmart_registration`.*, `merchant_subscription`.subscription_data FROM `walmart_registration` LEFT JOIN `merchant_subscription` ON `merchant_subscription`.merchant_id = `walmart_registration`.merchant_id WHERE `walmart_registration`.merchant_id = ".$merchant_id,"all");

            }
            elseif($param == "Payment")
            {
                $merchantData=Data::sqlRecords("SELECT id,billing_on,activated_on,plan_type, status,recurring_data FROM `walmart_recurring_payment` WHERE merchant_id=".$merchant_id."","all");
            }


           
            if(is_array($merchantData) && count($merchantData))
            {
              foreach ($merchantData as $m_value)
                {
                    foreach ($m_value as $key => $value)
                    {
                        if($key == 'subscription_data' && $value!= '')
                        {
                            $value = json_decode($value,true);
                            $trHtml.='
                            <tr>
                                <td class="value_label" width="33%">
                                    <span>'.$key.'</span>
                                </td>
                                <td class="value form-group " width="100%">
                                <ol>';
                            
                            foreach ($value as $subscription_key => $subscription_value) 
                            {
                                $trHtml.='<li>'.$subscription_key.'</li>';
                            }
                            
                            $trHtml.='</ol></td></tr>';
                        }
                        else{
                            if($value != '')
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
                                    <div class="sku_details jet_details_heading table-responsive">

                                        <table class="table table-striped table-bordered table-responsive">
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
    public function actionExport()
    {
        /*$query = "SELECT * FROM `walmart_shop_details` INNER JOIN `walmart_extension_detail` ON `walmart_shop_details`.merchant_id=`walmart_extension_detail`.merchant_id INNER JOIN `merchant_subscription` ON `walmart_shop_details`.merchant_id=`merchant_subscription`.merchant_id";*/
        $query = "SELECT * FROM `walmart_shop_details` INNER JOIN `walmart_extension_detail` ON `walmart_shop_details`.merchant_id=`walmart_extension_detail`.merchant_id";
        $shopData = Data::sqlRecords($query,'all');
        
        if (!file_exists(\Yii::getAlias('@webroot').'/var/email_csv-'.date('Y-m-d'))){
            mkdir(\Yii::getAlias('@webroot').'/var/email_csv-'.date('Y-m-d'),0775, true);
        }
        $base_path=\Yii::getAlias('@webroot').'/var/email_csv-'.date('Y-m-d').'/'.time().'.csv';
        $file = fopen($base_path,"w");
        $headers = array('Merchant id','Shop Url','Shop Name','Email id','Country Code','Install Status','Purchase Status','Install Date','Expire Date','Uninstall Date');
        $row = array();
        foreach($headers as $header) {
            $row[] = $header;
        }
        fputcsv($file,$row);

        $csvdata = array();
        $i = 0;
        foreach($shopData as $value)
        {
            $query = "SELECT `subscription_data` FROM merchant_subscription WHERE merchant_id = '".$value['merchant_id']."'";
            $subscription_data = Data::sqlRecords($query,'one');

            if(!($subscription_data) || isset($subscription_data['subscription_data']) && $subscription_data['subscription_data']!='' && $subscription_data['subscription_data'] != '[]')
            {
                $row = array();
                $row[] = $value['merchant_id'];
                $row[] = $value['shop_url'];
                $row[] = $value['shop_name'];
                $row[] = $value['email'];
                $row[] = $value['install_country'];
                $row[] = $value['app_status'];
                $row[] = $value['status'];
                $row[] = $value['install_date'];
                $row[] = $value['expire_date'];
                $row[] = $value['uninstall_date'];
                fputcsv($file,$row);
            }
        }

        fclose($file);
        $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
        $content = $encode . file_get_contents($base_path);
        return \Yii::$app->response->sendFile($base_path);
    }

    /**
     * Finds the WalmartShopDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WalmartShopDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WalmartShopDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionSendMessage()
    {
        $sms = new Sms();
        $data = $sms->sendSms($_POST['number'],$_POST['message']);
        if(isset($data['success']) && $data['success'])
        {
            $directory = \Yii::getAlias('@webroot') . '/var/sms/' . $_POST['mid'];
            if (!file_exists($directory)) {
                mkdir($directory, 0775, true);
            }
            $handle = fopen($directory . '/sms.log', 'a');
            fwrite($handle, 'Sent Sms : '.date("Y-m-d h:i:sa") . PHP_EOL . json_encode($_POST['message']) . PHP_EOL);
            fclose($handle);
            return json_encode(['success'=>true,'message'=>"Message sent successfully"]);

        }
        else{
            return json_encode(['success'=>false,'message'=>$data['message']]);
        }
    }
    public function actionViewPrevMsg()
    {
        $merchant_id = $_GET['mid'];
        $directory = \Yii::getAlias('@webroot') . '/var/sms/'.$merchant_id.'/sms.log';
        if (file_exists($directory)) {
           $data = file_get_contents($directory);

           return $data;
        }
        else
        {
            return "No Previous Message Available";
        }
    }
    public function actionRemoveconfigfile(){
        $shopurl=$_GET['shop'];
        $base_path = \Yii::getAlias('@frontend').'/modules/walmart/config/'.$shopurl;
        if (file_exists($base_path)) {
            unlink(\Yii::getAlias('@frontend').'/modules/walmart/config/'.$shopurl.'/'.'config.php');
        }
        if(is_dir($base_path)){
            rmdir($base_path);
        }
        return $this->redirect('index');
    }

    public function actionValidateMerchant()
    {
        if (isset($_GET['status']))
        {
            if (!file_exists(\Yii::getAlias('@webroot').'/var/api_validate_csv'))
            {
                mkdir(\Yii::getAlias('@webroot').'/var/api_validate_csv',0775, true);
            }
            $base_path = \Yii::getAlias('@webroot').'/var/api_validate_csv/data.csv';
            if (file_exists($base_path))
            {
                $file = fopen($base_path, "r");
                $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
                $content = $encode . file_get_contents($base_path);
                \Yii::$app->response->sendFile($base_path);
//                 return $this->render('index');
                return false;
            } else {
                 return $this->redirect(['index']);
            }
        }
        else {
                $base_path = \Yii::getAlias('@webroot').'/var/api_validate_csv';
                if (file_exists($base_path))
                {
                    if (file_exists(\Yii::getAlias('@webroot') . '/var/api_validate_csv/data.csv')) {
                        unlink(\Yii::getAlias('@webroot') . '/var/api_validate_csv/data.csv');
                    }
                    rmdir($base_path);
                }

                $session = Yii::$app->session;
                $query = "SELECT `merchant_id`,`shop_url`,`email` FROM `walmart_shop_details` WHERE status = 1";
                $shopData = Data::sqlRecords($query,'all');
                $total = count($shopData);
                $productfeedUrl = Data::getUrl('walmartshopdetails/validate-merchant?status=download');

                $validationData = $errored_data = [];
                if (!empty($shopData) && is_array($shopData))
                {
                    $limit = 100;
                    $pages = ceil(count($shopData) / $limit);
                    $chunkStatusArray = array_chunk($shopData, 100);

                    $session->set('merchantdata', $chunkStatusArray);

                    return $this->render('validate', ['totalcount' => $total,
                        'pages' => $pages,'csv_path' => $productfeedUrl]);

                }
            }
    }

    public function actionGetValidateData()
    {
        $index = Yii::$app->request->post('index');
        $totalrecord = Yii::$app->request->post('total');
        $session = Yii::$app->session;
        $returnArr = $errored_data = [];
        if (!file_exists(\Yii::getAlias('@webroot').'/var/api_validate_csv'))
        {
            mkdir(\Yii::getAlias('@webroot').'/var/api_validate_csv',0775, true);
            $base_path = \Yii::getAlias('@webroot').'/var/api_validate_csv/data.csv';
            $file = fopen($base_path,"w");
            $headers = array('Merchant ID','Shop Url','Email ID','Consumer ID','Secret Key');
            $row = array();
            foreach($headers as $header)
            {
                $row[] = $header;
            }

            fputcsv($file,$row);
        } else {
            $base_path = \Yii::getAlias('@webroot').'/var/api_validate_csv/data.csv';
            $file = fopen($base_path, "a");

            $row = array();
        }

        $shopData = isset($session['merchantdata'][$index]) ? $session['merchantdata'][$index] : [];
        if(is_array($shopData) && !empty($shopData))
        {
            $configHelper = new ConfigurationHelper();
            foreach ($shopData as $key => $value)
            {
                $validationData = $configHelper->getWalmartConfiguration($value['merchant_id']);
                if(is_array($validationData) && !empty($validationData))
                {
                    $itemObj = new Item($validationData);
                    $resultItems = $itemObj->getAllItem();

                    if(isset($resultItems['status']) && empty($resultItems['status']) && isset($resultItems['error'])){
                        $errored_data[] = array_merge($value,$validationData);
                    }
                }
            }

            $csvdata = array();

            foreach($errored_data as $error_key => $error_value)
            {
                $row = array();
                $row[] = $error_value['merchant_id'];
                $row[] = $error_value['shop_url'];
                $row[] = $error_value['email'];
                $row[] = isset($error_value['consumer_id'])?$error_value['consumer_id']:$error_value['client_id'];
                $row[] = isset($error_value['secret_key'])?$error_value['secret_key']:$error_value['client_secret'];
                fputcsv($file,$row);
            }

            fclose($file);
            //$link=Yii::$app->request->baseUrl.'/var/product_csv-'.$merchant_id.'/products.csv';
          /*  $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
            $content = $encode . file_get_contents($base_path);
            $path = \Yii::$app->response->sendFile($base_path);*/

            $returnArr = ['success' => true, 'success_count' => count($errored_data)];
            return json_encode($returnArr);
            // return \Yii::$app->response->sendFile($base_path);

        }else{
            return json_encode(['error' => "Data not found."]);
        }

    }

    public function actionGetProductOrderData()
    {
        $this->layout = 'main2';
        $merchant_id = isset($_POST['merchant_id'])?$_POST['merchant_id']:'';
        if($merchant_id)
        {
            $PublishedProducts = Productinfo::getPublishedProducts($merchant_id);
            $ProcessingProducts = Productinfo::getProcessingProducts($merchant_id);
            $UnpublishedProducts = Productinfo::getUnpublishedProducts($merchant_id);
            $NotUploadedProducts = Productinfo::getNotUploadedProducts($merchant_id);
            $StagedProducts = Productinfo::getStagedProducts($merchant_id);
            $TotalProducts = Productinfo::getTotalProducts($merchant_id);

            $data['published'] = $PublishedProducts;
            $data['item_processing'] = $ProcessingProducts;
            $data['unpublished'] = $UnpublishedProducts;
            $data['not_uploaded'] = $NotUploadedProducts;
            $data['stage'] = $StagedProducts;
            $data['total_products'] = $TotalProducts;

            $OtherProducts = intval($TotalProducts) - (intval($PublishedProducts) + intval($ProcessingProducts) + intval($UnpublishedProducts) + intval($NotUploadedProducts) + intval($StagedProducts)/*+intval($DeletedProducts)*/);
            if ($OtherProducts < 0) {
                $OtherProducts = 0;
            }
            $data['other_products'] = $OtherProducts;


            $CompletedOrders = OrderInfo::getCompletedOrdersCount($merchant_id);
            $AcknowledgedOrders = OrderInfo::getAcknowledgedOrdersCount($merchant_id);
            $CancelledOrders = OrderInfo::getCancelledOrdersCount($merchant_id);
            $FailedOrders = OrderInfo::getFailedOrdersCount($merchant_id);
            $TotalOrders = OrderInfo::getTotalOrdersCount($merchant_id);

            $data['completed'] = $CompletedOrders;
            $data['acknowledged'] = $AcknowledgedOrders;
            $data['cancelled'] = $CancelledOrders;
            $data['failed_order'] = $FailedOrders;
            $data['total_orders'] = $TotalOrders;

        }

        $html = $this->render('viewproductorderdata', ['data' => $data]);
        return $html;
    }

    public function actionGetsyncorderdata()
    {
        $trHtml = '';
        $query = "SELECT merchant_id FROM walmart_config WHERE data='order_sync_start' AND value=1";
        $merchants = Data::sqlRecords($query,'column');
        if($merchants)
        {

             $trHtml.='
                    <table class="table table-hover table-responsive" style="table-layout: fixed; text-align: center;">
                        <tr>
                            <th class="value_label" width="33%" style="text-align: center;">
                                <span>Merchant IDs</span>
                            </th>
                        </tr>';

            foreach ($merchants as $key => $merchant_id) {
                 $trHtml.='
                        <tr>
                            <td class="value form-group " width="100%">
                                <span>'.$merchant_id.'</span>
                            </td>
                        </tr>
                    ';
            }
            $trHtml.='</table>';
        }else{
                $trHtml.='
                    <table class="table table-hover table-responsive" style="table-layout: fixed; text-align: center;">
                        <tr>
                            <th class="value_label" width="33%" style="text-align: center;">
                                <span>No Merchants Available</span>
                            </th>
                        </tr>';
        }
         $html='
                <div class="container">
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="sku_details jet_details_heading table-responsive">
                                        <table class="table table-striped table-bordered table-responsive">
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

    public function actionUpdateOrderStart($id)
    {
        if($id){
            moduleData::saveConfigValue($id,'order_sync_start','0');
            Yii::$app->session->setFlash('error','Order Sync Start has been updated.');
        }
            return $this->redirect(['update', 'id'=> $id]);
    }
}
