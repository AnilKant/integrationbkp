<?php

namespace backend\controllers;

use Yii;
use backend\models\WishShopDetails;
use backend\models\WishShopDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Data;
use backend\components\wish\Shophelper;

/**
 * WishshopdetailsController implements the CRUD actions for WishShopDetails model.
 */
class WishshopdetailsController extends MainController
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
     * Lists all WishShopDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new WishShopDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WishShopDetails model.
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
     * Creates a new WishShopDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WishShopDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WishShopDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $shop=Data::integrationSqlRecords("SELECT `shopurl` FROM `merchant` WHERE merchant_id='".Yii::$app->request->post('WishShopDetails')['merchant_id']."'","one");
            $updateConfig = [];
            $file_dir = __DIR__.'/../../../marketplace-integration/frontend/modules/wish/config/'.$shop["shopurl"].'';
            $updateConfig = include($file_dir.'/config.php');
            $updateConfig['expire_date']=Yii::$app->request->post('WishShopDetails')['expire_date'];
            $updateConfig['purchase_status']=Yii::$app->request->post('WishShopDetails')['purchase_status'];
            if (!file_exists($file_dir)) {
                mkdir($file_dir, 0775, true);
            }

            $filenameOrig = $file_dir . '/config.php';
            $fileOrig = fopen($filenameOrig, 'w');
            file_put_contents($filenameOrig, '<?php return $arr = ' . var_export($updateConfig, true) . ';?>');
            fclose($fileOrig);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WishShopDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the WishShopDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WishShopDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WishShopDetails::findOne($id)) !== null) {
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
                $merchantData=Data::integrationSqlRecords("SELECT fname,lname,mobile,email,country,time_zone,time_slot FROM `wish_registration` WHERE merchant_id=".$merchant_id,"all");
            }
            elseif($param=="Payment")
            {
                $merchantData=Data::integrationSqlRecords("SELECT id,activated_on,plan_type,status,payment_data,billing_on FROM `wish_payment` WHERE merchant_id=".$merchant_id,"all");
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

    public function actionExport(){

        $columns = [
            "`me`.`merchant_id`",
            "`me`.`shopurl`",
            "`me`.`email`",
            "`me`.`currency`",
            "`wsd`.`purchase_status`",
            "`wsd`.`install_date`",
            "`wsd`.`install_status`",
            "`wsd`.`expire_date`",
            "`wsd`.`uninstall_dates`",
            "`wi`.`status` as config",
            "`wp`.`plan_type`",
            "`me`.`shop_json` as shopify_plan_display_name",
        ];

        //$sql="SELECT ".implode(',',$columns)." FROM `merchant` `me` INNER JOIN `wish_shop_details` `wsd` ON `wsd`
        //.`merchant_id`=`me`.`merchant_id` INNER JOIN `wish_installation` `wi` ON `wsd`.`merchant_id`=`wi`.`merchant_id` LEFT JOIN `wish_payment` `wp` ON `wp`.`merchant_id`=`wsd`.`merchant_id`";
        $sql="SELECT ".implode(',',$columns)." FROM `wish_shop_details` `wsd` INNER JOIN `merchant` `me` ON `me`.`merchant_id`=`wsd`.`merchant_id` LEFT JOIN `wish_installation` `wi` ON `wsd`.`merchant_id`=`wi`.`merchant_id` LEFT JOIN `wish_payment` `wp` ON `wp`.`merchant_id`=`wsd`.`merchant_id`";
        $result=Data::integrationSqlRecords($sql);

        if($result){
            $header=true;
            $address=Yii::getAlias('@rootdir').'/var/user';
            $file_name='wish-user.csv';
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
                    $headerArray[] = 'Country';
                    fputcsv($input, $headerArray);
                    $header=false;
                }

                if(isset($value['purchase_status'])){
                    $value['purchase_status'] = Shophelper::getStatusLabel($value['purchase_status']);
                }

                if($value['config'] == 1){
                    $value['config'] = 'yes';
                }else{
                    $value['config'] = 'no';
                }
                if(isset($value['shopify_plan_display_name'])){
                    $data = json_decode($value['shopify_plan_display_name'],true);
                    $shopify_plan = $data['plan_display_name'] ?? '';
                    $value['shopify_plan_display_name'] = $shopify_plan;

                    if(isset($data['country'])){
                        $value['Country'] = $data['country'];
                    }

                }

//if(isset($value['Country']) && $value['Country'] == 'CA'){

                fputcsv($input, $value);
//}
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
}
