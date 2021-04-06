<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\models\JetShopDetails;
use backend\models\JetShopDetailsSearch;

use backend\components\Sendmail;
use frontend\modules\jet\components\Data;
use common\models\JetProduct;
use common\components\Xmlapi;

/**
 * JetshopdetailsController implements the CRUD actions for JetShopDetails model.
 */
class JetshopdetailsController extends MainController
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
     * Lists all JetShopDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if(Yii::$app->user->isGuest) {
    		return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
    	}
        $searchModel = new JetShopDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JetShopDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
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
                $merchantData=Data::sqlRecords("SELECT name,mobile,time_zone,time_slot,already_selling,previous_api_provider_name,is_uninstalled_previous FROM `jet_registration` WHERE merchant_id=".$merchant_id,"all");
            }
            elseif($param=="Payment")
            {
                $merchantData=Data::sqlRecords("SELECT id,activated_on,plan_type,status FROM `jet_recurring_payment` WHERE merchant_id=".$merchant_id,"all");
            }
            
            if(is_array($merchantData) && count($merchantData))
            {
                foreach ($merchantData as $m_value) 
                {
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

    /**
     * Creates a new JetShopDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JetShopDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing JetShopDetails model.
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

    /**
     * Deletes an existing JetShopDetails model.
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
     * Finds the JetShopDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JetShopDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JetShopDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBulk()
    {
        $selection = Yii::$app->request->post('selection');
        $bulk_option = Yii::$app->request->post('bulk_name');
        return $this->redirect([$bulk_option,'selection'=>$selection]);
    }
        public function actionExport()
    {
        $query = "SELECT * FROM `jet_shop_details`";
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

        $csvdata=array();
        $i=0;
        foreach($shopData as $value)
        {
            $row = array();
            $row[]=$value['merchant_id'];
            $row[]=$value['shop_url'];
            $row[]=$value['shop_name'];
            $row[]=$value['email'];
            $row[]=$value['country_code'];
            $row[]=$value['install_status'];
            $row[]=$value['purchase_status'];
            $row[]=$value['installed_on'];
            $row[]=$value['expired_on'];
            $row[]=$value['uninstall_date'];
            fputcsv($file,$row);
        }

        fclose($file);
        //$link=Yii::$app->request->baseUrl.'/var/product_csv-'.$merchant_id.'/products.csv';
        $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
        $content = $encode . file_get_contents($base_path);
        return \Yii::$app->response->sendFile($base_path);
    }
    /**
     * create staff acount for new customer with cedcommerce email.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JetExtensionDetail the loaded model
     * 
     */

    public function actionStaffAccount()
    {
        if(isset($_GET['selection']))
        {
            $selection=(array)$_GET['selection'];
            $count=0;
            $isAvailable=0;
            if(is_array($selection) && count($selection)>0)
            {
                foreach ($selection as $key => $value) 
                {
                    $ip = JetExtensionDetail::IP;           // should be server IP address or 127.0.0.1 if local server
                    $account = JetExtensionDetail::USER;        // cpanel user account name
                    $passwd =JetExtensionDetail::PASSWORD;        // cpanel user password
                    $port = JetExtensionDetail::PORT;                 // cpanel secure authentication port unsecure port# 2082
                    $email_domain = JetExtensionDetail::DOMAIN; // email domain (usually same as cPanel domain)
                    $email_quota = JetExtensionDetail::EMAIL_QUOTA; // default amount of space in megabytes  

                    // check if overrides passed
                    $email_user = "jet-merchant-".$value;
                    $email_pass = "cedcoss007";

                    //create user account
                    $xmlapi = new Xmlapi($ip);
                    $xmlapi->set_port($port);
                    $xmlapi->password_auth($account, $passwd);
                    $call = array('domain'=>$email_domain, 'email'=>$email_user, 'password'=>$email_pass, 'quota'=>$email_quota);
                    $xmlapi->set_debug(0);      //output to error file  set to 1 to see error_log.

                    $result = $xmlapi->api2_query($account, "Email", "addpop", $call); 
                    if(isset($result->data->result) && $result->data->result==1)
                    {
                        $email_id=$email_user.'@'.$email_domain;
                        $query="SELECT id FROM `staff_account_members` WHERE merchant_id=".$value." LIMIT 0,1";
                        $emailColl=Data::sqlRecords($query,'one','select');
                        if(!$emailColl){
                            //insert new staff account
                            $count++;
                            $query="INSERT INTO `staff_account_members`(`merchant_id`, `email`, `password`) VALUES ('".$value."','".$email_id."','".$email_pass."')";
                            Data::sqlRecords($query,null,'insert');
                        }else{
                            $isAvailable++;
                        }
                    }
                }
                if($count>0)
                {
                    Yii::$app->session->setFlash('success',$count. "staff account created successfully");
                    return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/staff-merchant/index');
                }elseif($isAvailable>0){
                    Yii::$app->session->setFlash('success',"staff account already available");
                    return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/staff-merchant/index');
                }
                return $this->redirect(['index']);
            }
        }
    }

    public function actionProductvalidation()
    {
        $Res = $resArr = [];
        
        //$Res = Data::sqlRecords("SHOW TABLES FROM cedcom5_sPy11F","all","select");
        $Res = Data::sqlRecords("SHOW TABLES FROM shopif7_sPy11F","all","select");
       
        $resArr[''] = "--Choose column--";
        foreach ($Res as $key => $value) 
        {            
           //$resArr[$value['Tables_in_cedcom5_sPy11F']] = $value['Tables_in_cedcom5_sPy11F'];
           $resArr[$value['Tables_in_shopif7_sPy11F']] = $value['Tables_in_shopif7_sPy11F'];
        }
        return $this->render('validator', [
            'tablename' => $resArr,
        ]);
    }
    public function actionValidationresponse()
    {
        $columnData = $columnNames = [];
        $sql=trim(Yii::$app->request->post('sql'));
        $columnData = Data::sqlRecords($sql,'all','select');
        
        $ColumnNameDetails =  '<td id="column_details">
                <select id="column_name" name="column_name" class="form-control">';                    
                    foreach($columnData as $key => $val)
                    {
                        $ColumnNameDetails .= '<option value="'.$val["Field"].'">'.$val["Field"].'</option>'; 
                    }                    
                $ColumnNameDetails .='</select>                        
            </td><td><input type="text" name="search_name" id="search_name"></td>';     
        return $ColumnNameDetails;
    }
    public function actionValidationresult()
    {
        $merchant_id=trim(Yii::$app->request->post('merchant_id'));
        $table_name=trim(Yii::$app->request->post('table_name'));
        $column_name=trim(Yii::$app->request->post('column_name'));
        $search_field=trim(Yii::$app->request->post('search_field'));
        $query = "SELECT * FROM {$table_name} WHERE `merchant_id`='{$merchant_id}' AND {$column_name}='{$search_field}' ";
        $ResultData = Data::sqlRecords($query,'all','select');
        echo "<pre>";
        print_r($ResultData);
        die("<hr>Details End");
    }
}
