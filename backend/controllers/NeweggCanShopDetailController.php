<?php

namespace backend\controllers;

use backend\components\Data;
use Yii;
use backend\models\NeweggCanShopDetail;
use backend\models\NeweggCanShopDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NeweggCanShopDetailController implements the CRUD actions for NeweggCanShopDetail model.
 */
class NeweggCanShopDetailController extends MainController
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
     * Lists all NeweggCanShopDetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        
        $searchModel = new NeweggCanShopDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NeweggShopDetail model.
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
     * Creates a new NeweggShopDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new NeweggShopDetail();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Updates an existing NeweggShopDetail model.
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
     * Deletes an existing NeweggShopDetail model.
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
     * Finds the NeweggShopDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NeweggShopDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NeweggCanShopDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExport()
    {
        $query = "SELECT * FROM `newegg_can_shop_detail` as `nsd` INNER JOIN `user` ON nsd.merchant_id = user.id";
        $shopData = Data::sqlRecords($query,'all');
// echo "<pre>";print_r($shopData);die;
        if (!file_exists(\Yii::getAlias('@webroot').'/var/email_csv-'.date('Y-m-d'))){
            mkdir(\Yii::getAlias('@webroot').'/var/email_csv-'.date('Y-m-d'),0775, true);
        }
        $base_path=\Yii::getAlias('@webroot').'/var/email_csv-'.date('Y-m-d').'/'.time().'.csv';
        $file = fopen($base_path,"w");

        $headers = array('Merchant id','Shop Url','Shop Name','Email id','Country Code','Contact no','Install Status','Purchase Status','Purchase Date','Install Date','Expire Date','Uninstall Date');
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
            $row[]=$value['mobile'];
            $row[]=$value['install_status'];
            $row[]=$value['purchase_status'];
            $row[]=$value['purchase_date'];
            $row[]=$value['install_date'];
            $row[]=$value['expire_date'];
            $row[]=$value['uninstall_date'];            
            fputcsv($file,$row);
        }

        fclose($file);
        //$link=Yii::$app->request->baseUrl.'/var/product_csv-'.$merchant_id.'/products.csv';
        $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
        $content = $encode . file_get_contents($base_path);
        return \Yii::$app->response->sendFile($base_path);
    }

    public function actionUpdateStatus()
    {
        $query = "SELECT `merchant_id`,`expire_date`,`purchase_status` FROM `newegg_can_shop_detail` WHERE `purchase_status` IN ('Trail' ,'Not Purchased','Purchased')";
        $data = Data::sqlRecords($query, 'all');

        foreach ($data as $model) {
            $merchant_id = $model['merchant_id'];
            if ($model && !empty($model['expire_date'])) {
                $expdate = strtotime($model['expire_date']);

                if (time() > $expdate) {
                    if ($model['purchase_status'] == 'Purchased') {
                        $sql = "UPDATE `newegg_can_shop_detail` SET `purchase_status`='License Expired' WHERE `merchant_id`='" . $merchant_id . "'";
                        $result = Data::sqlRecords($sql, null, 'update');
                    } elseif ($model['purchase_status'] == 'Trail') {
                        $sql = "UPDATE `newegg_can_shop_detail` SET `purchase_status`='Trail Expired' WHERE `merchant_id`='" . $merchant_id . "'";
                        $result = Data::sqlRecords($sql, null, 'update');
                    } elseif ($model['purchase_status'] == 'Trail') {
                        $sql = "UPDATE `newegg_can_shop_detail` SET `purchase_status`='Trail Expired' WHERE `merchant_id`='" . $merchant_id . "'";
                        $result = Data::sqlRecords($sql, null, 'update');
                    }
                }
            }
        }
    }

    public function actionViewMerchant()
    {

        $merchant_id=Yii::$app->request->post('id');
        $param=Yii::$app->request->post('param');
        // print_r($param);die("lll");
        $trHtml="";
        $merchantData=[];
        if($merchant_id)
        {
            if($param=="Registration")
            {
                $merchantData=Data::sqlRecords("SELECT name, email, mobile, shipping_source, reference FROM newegg_can_registration WHERE merchant_id=".$merchant_id,"all");
                // print_r($merchantData);die("kk");
            }
            elseif($param=="Payment")
            {
                $merchantData=Data::sqlRecords("SELECT id,billing_on,activated_on,plan_type, status,recurring_data FROM `newegg_can_payment` WHERE merchant_id=".$merchant_id."","all");
                // print_r($merchantData);die("ll");
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
}
