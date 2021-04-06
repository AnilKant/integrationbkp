<?php

namespace backend\controllers;

use Yii;
use backend\models\ShopErasureData;
use backend\models\ShopErasureDataSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Data;


/**
 * ShopErasureDataController implements the CRUD actions for ShopErasureData model.
 */
class ShopErasureDataController extends Controller
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

    public function beforeAction($action){
        if (Yii::$app->user->isGuest) {

            return $this->redirect(['site/login']);
        }
        return true;
    }

    /**
     * Lists all ShopErasureData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopErasureDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopErasureData model.
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
     * Creates a new ShopErasureData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopErasureData();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ShopErasureData model.
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


    public function actionMerchantView(){
        $merchant_id=Yii::$app->request->post('merchant_id');
        // $model=$this->findModel($id);

        $trHtml="";
        if(!empty($merchant_id))
        {
            $sql="SELECT `shop_data` FROM `shop_erasure_data` WHERE `merchant_id`=$merchant_id";
            $DeletedData=Data::integrationSqlRecords($sql,"one","select");

            if(isset($DeletedData['shop_data']))
            {
                $data=json_decode($DeletedData['shop_data'],true);
                $Shopif_data['country_name']=isset($data['country_name'])?$data['country_name']:'';
                $Shopif_data['plan_name']=isset($data['plan_display_name'])?$data['plan_display_name']:'';
                $Shopif_data['shop_owner']=isset($data['shop_owner'])?$data['shop_owner']:'';
                $Shopif_data['phone']=isset($data['phone'])?$data['phone']:'';

                foreach ($Shopif_data as $key => $value) {


                 $trHtml.='<tr>
                                <td class="value_label" width="33%">
                                    <span>'.$key.'</span>
                                </td>
                                <td class="value form-group " width="100%">
                                    <span>'.$value.'</span>
                                </td>
                            </tr>';


                    /*else{
                        $trHtml.='
                <tr>
                    <td class="value_label" width="33%">
                        <span>'.$key.'</span>
                    </td>
                    <td class="value form-group " width="100%">
                        <span>'.$value.'</span>
                    </td>
                </tr>';
                    }*/
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
                                    <h4 class="modal-title" style="text-align: center;">'.$merchant_id.': Shopify Data</h4>
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

        //return $html;
         return json_encode(['html' => $html]);

    }

    /**
     * Deletes an existing ShopErasureData model.
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
     * Finds the ShopErasureData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopErasureData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopErasureData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
