<?php
  namespace backend\controllers;
  
  use frontend\modules\walmart\components\QueryHelper;
  use Yii;
  use backend\models\Bonanzashopdetails;
  use backend\models\BonanzashopdetailsSearch;
  use backend\components\Data;
  use yii\web\Controller;
  use yii\web\NotFoundHttpException;
  use yii\filters\VerbFilter;
  use backend\controllers\MainController;

  /**
   * bonanzashopdetailsController implements the CRUD actions for bonanzashopdetails model.
   */
  class BonanzashopdetailsController extends MainController
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
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        return true;
    }*/
    
    /**
     * Lists all bonanzashopdetails models.
     * @return mixed
     */
    public function actionIndex()
    {
      if(Yii::$app->user->isGuest) {
        return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
      }
      
      $searchModel = new BonanzashopdetailsSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      
      return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
      ]);
    }
    
    /**
     * Displays a single bonanzashopdetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
      if(Yii::$app->user->isGuest) {
        return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
      }
      return $this->render('view', [
        'model' => $this->findModel($id),
      ]);
    }
    
    /**
     * Creates a new bonanzashopdetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
      $model = new Bonanzashopdetails();
      
      if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
      } else {
        return $this->render('create', [
          'model' => $model,
        ]);
      }
    }
    
    /**
     * Updates an existing bonanzashopdetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      if(Yii::$app->user->isGuest) {
        return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
      }
      $model = $this->findModel($id);
      $postData = Yii::$app->request->post();
      if(isset($postData['merchant']['email'])){
          $sql = "UPDATE `merchant` SET `email` ='". htmlspecialchars($postData['merchant']['email'])."' WHERE `merchant_id` = $model->merchant_id";
          Data::integrationSqlRecords($sql,'one','update');
      }
      if ($model->load($postData) && $model->save(false)) {
                $url = Yii::getAlias('@webbonanzaurl')."/bonanzashopdetails/update-config";
                $data = [
                    'merchant_id'=> $model->merchant_id,
                    'shopname' => $model->shop_url,
                    ];
                Data::sendCurlRequest($data,$url);
        return $this->redirect(['view', 'id' => $model->id]);
      } else {
        return $this->render('update', [
          'model' => $model,
        ]);
      }
    }
    
    /**
     * Deletes an existing bonanzashopdetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
      if(Yii::$app->user->isGuest) {
        return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
      }
      $this->findModel($id)->delete();
      
      return $this->redirect(['index']);
    }
    
    /**
     * Finds the bonanzashopdetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return bonanzashopdetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
      if (($model = Bonanzashopdetails::findOne($id)) !== null) {
        return $model;
      } else {
        throw new NotFoundHttpException('The requested page does not exist.');
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
          $merchantData=Data::integrationSqlRecords("SELECT name, mobile,selling_on_bonanza,time_zone, time_slot FROM bonanza_registration WHERE merchant_id=".$merchant_id,"all");
          // print_r($merchantData);die("kk");
        }
        elseif($param=="Payment")
        {
          
          $merchantData=Data::integrationSqlRecords("SELECT id,charge_id,billing_on,activated_on,plan_type, status,recurring_data FROM `bonanza_payment` WHERE merchant_id=".$merchant_id,"all");
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
    
    public function actionViewclientdetails(){
      $this->layout="main2";
      $merchant_id=trim(Yii::$app->request->post('merchant_id'));
      
      $sql = "SELECT * FROM `bonanza_registration` WHERE `merchant_id`='{$merchant_id}'";
      $clientData =  Data::integrationSqlRecords($sql,'one','select');
      $error = !isset($clientData['name'])?true:false;
      
      $html=$this->render('viewclientdetails',['data'=>$clientData,'error'=>$error],true);
      
      return $html;
    }


  }
