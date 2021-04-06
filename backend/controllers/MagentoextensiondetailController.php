<?php

namespace backend\controllers;

use Yii;
use backend\models\MagentoExtensionDetail;
use backend\models\MagentoExtensionDetailSearch;
use frontend\components\Data;
use backend\components\Extensionapi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MagentoextensiondetailController implements the CRUD actions for MagentoExtensionDetail model.
 */
class MagentoextensiondetailController extends MainController
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
     * Lists all MagentoExtensionDetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new MagentoExtensionDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MagentoExtensionDetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dataCount = "SELECT count(*) from `magento_extension_grid_details` WHERE `client_id`=8";
        $detailsCount = Data::sqlRecords($dataCount,'one','select');
        if($detailsCount['count(*)']>7){
            self::removeEntry($id);
        }
        $sql = "SELECT `details`,`last_updated` from `magento_extension_grid_details` WHERE `client_id`='{$id}' ";
        $details = Data::sqlRecords($sql,'all','select');  
              

        return $this->render('view', [
            'model' => $details,
        ]);
    }

    /**
     * Creates a new MagentoExtensionDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MagentoExtensionDetail();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MagentoExtensionDetail model.
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
     * Deletes an existing MagentoExtensionDetail model.
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
     * Finds the MagentoExtensionDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MagentoExtensionDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MagentoExtensionDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSyncinfo()
    {
        $details = [];
        $count = 0;
        $sql = "SELECT `id`,`store_url`,`plateform` FROM `magento_extension_detail` ";
        $details = Data::sqlRecords($sql,'all','select');   
        if(!empty($details))
        {
            foreach ($details as $key => $value) 
            {
                $count++;
                $storeUrl = $value['store_url'];
                $plateform = $value['plateform'];
                $cleintId = $value['id'];
                $response = Extensionapi::getClientsDetails($storeUrl,$plateform);
                
                $updationDetails = array();
                $updationDetails = json_decode($response,true);
                $updateQuery = "UPDATE `magento_extension_detail` SET `total_product`='{$updationDetails['totalProducts']}',`published`='{$updationDetails['totalPublishedProducts']}',`unpublished`='{$updationDetails['totalUnpublishedProducts']}',`total_order`='{$updationDetails['totalOrderCount']}',`complete_orders`='{$updationDetails['orderCountComplete']}',`last_response`='".addslashes($response)."',`totalRevenue`='{$updationDetails['totalRevenue']}' ,`config_set`='{$updationDetails['consumerId']}' WHERE `store_url` ='{$storeUrl}'";
                Data::sqlRecords($updateQuery,null,'update'); 

                $checkExist = "SELECT `id` FROM `magento_extension_grid_details` WHERE `client_id`='{$cleintId}' AND `last_updated`='".date("Y-m-d")."' ";
                $alreadyExist = Data::sqlRecords($checkExist,"one",'select');
                if (empty($alreadyExist)) {
                    $sqlInsert = "INSERT INTO `magento_extension_grid_details` (`client_id`, `details`, `last_updated`) VALUES ( {$cleintId}, '".addslashes($response)."', '".date("Y-m-d")."')";
                    Data::sqlRecords($sqlInsert,null,'insert');
                }
                else
                {
                    $sqlUpdate = "UPDATE `magento_extension_grid_details` SET `details` = '".addslashes($response)."' WHERE `client_id` = {$cleintId} AND `last_updated`= '".date("Y-m-d")."' ";                   
                    Data::sqlRecords($sqlUpdate,null,'update');
                }
            }            
        }
        Yii::$app->session->setFlash('success',$count." rows updated successfully");
        return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/magentoextensiondetail/index');
    }
    public static function removeEntry($clientId='')
    {
        $sql = "DELETE FROM `magento_extension_grid_details` WHERE `id` NOT IN (SELECT `id` FROM (SELECT `id` FROM `magento_extension_grid_details` where `client_id`={$clientId} order by `id` desc limit 7) AS x) and client_id = {$clientId} ";       
        Data::sqlRecords($sql,null,'delete');
        return true;
    }
}
