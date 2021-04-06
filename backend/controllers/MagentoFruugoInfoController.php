<?php

namespace backend\controllers;

use Yii;
use frontend\components\Data;
use backend\models\MagentoFruugoInfo;
use backend\models\MagentoFruugoInfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Extensionapi;

/**
 * MagentoFruugoInfoController implements the CRUD actions for MagentoFruugoInfo model.
 */
class MagentoFruugoInfoController extends MainController
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
     * Lists all MagentoFruugoInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new MagentoFruugoInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MagentoFruugoInfo model.
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
     * Creates a new MagentoFruugoInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($data=false)
    {
        //$data =  array('domain'=>'abcXyz.com','email'=>'abc@gmail.com','framework'=>'m1');
        $data=$_GET;
        $sql = "SELECT `id`,`domain`,`framework` FROM `magento_fruugo_info` WHERE domain ='".$data['domain']."'";
        $details = Data::sqlRecords($sql,'one','select'); 
        if(isset($details['domain']) && !empty($details['domain'])){
            return true;
        }
        
        $model = new MagentoFruugoInfo();
        $model->domain = $data['domain'];
        $model->email = $data['email'];
        $model->framework = $data['framework'];
        if ($model->save(false)) {
            return true;
        } /*else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }*/
        return false;
    }

    /**
     * Updates an existing MagentoFruugoInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSyncinfo()
    {
        $details = [];
        $session = Yii::$app->session;
        $sql = "SELECT `id`,`domain`,`framework`,`last_synced`,`total_revenue` FROM `magento_fruugo_info` ";
        $details = Data::sqlRecords($sql,'all','select');
        $chunkStatusArray=array_chunk($details, 1);
        foreach ($chunkStatusArray as $ind=> $value)
        {
            $session->set('orderstatus-'.$ind, $value);               
        }
        return $this->render('ajaxbulkupdate', [
            'totalcount' => count($chunkStatusArray),
            'pages' => count($chunkStatusArray),
            'action' => "Orders",
        ]);    
        
        /*Yii::$app->session->setFlash('success',$count." rows updated successfully");
        return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/magento-fruugo-info/index');*/
    }
    
    public function actionUpdateOrderInfo(){
        
        $session = Yii::$app->session;
        $index = Yii::$app->request->post('index');
        $userData = isset($session['orderstatus-'.$index]) ? $session['orderstatus-'.$index] : [];
        
        //$last_updated = "2017-12-01 00:00:00";
        if (empty($userData)) {
            $return_msg = ['error' => 'No data to update'];
            //$session->remove('orderstatus-'.$index);
        } else 
        { 
            $storeUrl = $userData[0]['domain'];
            $plateform = $userData[0]['framework'];
            $cleintId = $userData[0]['id'];
            $last_updated = $userData[0]['last_synced'];
            //$last_updated = "2017-12-01 00:00:00";
            $response = Extensionapi::getFruugoClientsDetails($storeUrl,$plateform,$last_updated);
            
            $updationDetails = array();
            $updationDetails = json_decode($response,true);
            if (isset($updationDetails['liveSkus'])) {
                
                if(is_array($updationDetails['revenueTotal']))
                foreach ($updationDetails['revenueTotal'] as $key => $value) {
                    if(!isset($value['fruugo_order_id'])){
                        continue;
                    }
                    $sql = "SELECT `id`, `merchant_info_id`, `fruugo_order_id`, `order_total`, `order_date`, `created_at` FROM `magento_fruugo_orders` WHERE `fruugo_order_id`=".$value['fruugo_order_id'];
                    $order_data = Data::sqlRecords($sql,'one','select'); 
                    if (!$order_data) {
                        $query = "INSERT INTO `magento_fruugo_orders`(`merchant_info_id`, `fruugo_order_id`, `order_total`, `order_date`) VALUES ('".$cleintId."','".$value['fruugo_order_id']."','".$value['total_paid']."','".$value['order_place_date']."')";
                        Data::sqlRecords($query,null,'insert');
                    }
                }
                  
            
            $updateQuery = "UPDATE `magento_fruugo_info` SET `live_sku`='".$updationDetails['liveSkus']."',`uploaded_sku`='".$updationDetails['uploadedSkus']."' WHERE  `domain` ='".$storeUrl."'";
            Data::sqlRecords($updateQuery,null,'update'); 
            $return_msg['success']= 'order data successfully updated';
            
           }
           else{
            $return_msg = ['error' => 'No data to update'];
           }
        }       
       return json_encode($return_msg); 
    }
    /**
     * Updates an existing MagentoFruugoInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSyncorderinfo()
    {
        $details = [];
        $session = Yii::$app->session;
        $sql = "SELECT `id`,`fruugo_password`,`fruugo_username`,`last_synced`,`total_revenue` FROM `magento_fruugo_info` WHERE `fruugo_username`!='' ";
        $details = Data::sqlRecords($sql,'all','select');
        $chunkStatusArray=array_chunk($details, 1);
        foreach ($chunkStatusArray as $ind=> $value)
        {
            $session->set('orderinfo-'.$ind, $value);               
        }
        return $this->render('ajaxbulkupdate', [
            'totalcount' => count($chunkStatusArray),
            'pages' => count($chunkStatusArray),
            'action' => "OrderApi",
        ]);    
        
        /*Yii::$app->session->setFlash('success',$count." rows updated successfully");
        return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/magento-fruugo-info/index');*/
    }
    public function actionUpdateOrderData(){        
        $this->enableCsrfValidation = false;
        $session = Yii::$app->session;
        $index = Yii::$app->request->post('index');
        $userData = isset($session['orderinfo-'.$index]) ? $session['orderinfo-'.$index] : [];
        //$last_updated = "2017-12-01 00:00:00";
        if (empty($userData)) {
            $return_msg = ['error' => 'No data to update'];
            //$session->remove('orderstatus-'.$index);
        } else 
        { 
            $fruugo_username = $userData[0]['fruugo_username'];
            $fruugo_password = $userData[0]['fruugo_password'];
            $cleintId = $userData[0]['id'];
            $last_updated = $userData[0]['last_synced'];
            //$last_updated = "2017-12-01 00:00:00";
            $response = Extensionapi::getOrders($fruugo_username,$fruugo_password,$cleintId);
            $return_msg['success']= $response.'order data successfully updated';
            //$updationDetails = array();
            
        }       
       return json_encode($return_msg); 
    }
    
   /*public function actionSyncinfo()
    {
        $details = [];
        $count = 0;
        $sql = "SELECT `id`,`domain`,`framework`,`last_synced`,`total_revenue` FROM `magento_fruugo_info` ";
        $details = Data::sqlRecords($sql,'all','select');   
        if(!empty($details))
        {
            foreach ($details as $key => $value) 
            {
                $count++;
                $storeUrl = $value['domain'];
                $plateform = $value['framework'];
                $cleintId = $value['id'];
                $last_updated = $value['last_synced'];
                $last_updated = "2017-12-01 00:00:00";
                $response = Extensionapi::getFruugoClientsDetails($storeUrl,$plateform,$last_updated);
                
                $updationDetails = array();
                $updationDetails = json_decode($response,true);
               if (isset($updationDetails['liveSkus'])) {
                
                $total_revenue = json_decode($value['total_revenue'],true);
                if (is_array($total_revenue['revenueTotal']) && !empty($total_revenue['revenueTotal'])) {
                    $total_revenue_arr = array_merge($total_revenue['revenueTotal'],$updationDetails['revenueTotal']);
                }
                else{
                    $total_revenue_arr = $updationDetails['revenueTotal'];
                }
                
                
                $updateQuery = "UPDATE `magento_fruugo_info` SET `live_sku`='".$updationDetails['liveSkus']."',`uploaded_sku`='".$updationDetails['uploadedSkus']."',`total_revenue`='".json_encode($total_revenue_arr)."' WHERE  `domain` ='".$storeUrl."'";
                Data::sqlRecords($updateQuery,null,'update'); 

                
               }
                
            }            
        }
        Yii::$app->session->setFlash('success',$count." rows updated successfully");
        return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/magento-fruugo-info/index');
    }*/
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
     * Deletes an existing MagentoFruugoInfo model.
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
     * Finds the MagentoFruugoInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MagentoFruugoInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MagentoFruugoInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
