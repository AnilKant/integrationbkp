<?php

namespace backend\controllers;

use Yii;
use backend\models\FruugoShopDetails;
use backend\components\Data;
use backend\models\FruugoShopDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FruugoShopDetailsController implements the CRUD actions for FruugoShopDetails model.
 */
class FruugoShopDetailsController extends MainController
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
     * Lists all FruugoShopDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
    		return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
    	}
        $searchModel = new FruugoShopDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FruugoShopDetails model.
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
     * Creates a new FruugoShopDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FruugoShopDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FruugoShopDetails model.
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
     * Deletes an existing FruugoShopDetails model.
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
     * Finds the FruugoShopDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FruugoShopDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FruugoShopDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetProductOrderData()
    {
        $this->layout = 'main2';

        $data = [];
        $query = "SELECT COUNT(*) as uploaded FROM `fruugo_product_upload` WHERE `merchant_id` ={$_POST['merchant_id']}";
        $result = Data::integrationSqlRecords($query, 'one');
        $data['uploaded'] = isset($result['uploaded'])?$result['uploaded']:0;

        $query = "SELECT count(*) as `instock` FROM  `fruugo_product_variants` WHERE `merchant_id`=" . $_POST['merchant_id'] . " AND `status`='INSTOCK'";
        $result = Data::integrationSqlRecords($query, 'one');
        $data['instock'] = isset($result['instock'])?$result['instock']:0;


        $query = "SELECT count(*) as `outstock` FROM  `fruugo_product_variants` WHERE `merchant_id`=" . $_POST['merchant_id'] . " AND `status`='OUTOFSTOCK'";
        $result = Data::integrationSqlRecords($query, 'one');
        $data['outstock'] = isset($result['outstock'])?$result['outstock']:0;

        $query = "SELECT count(*) as `not_uploaded` FROM  `fruugo_product_variants` WHERE `merchant_id`=" . $_POST['merchant_id'] . " AND `status`='Not Uploaded'";
        $result = Data::integrationSqlRecords($query, 'one');
        $data['not_uploaded'] = isset($result['not_uploaded'])?$result['not_uploaded']:0;

        $query = "SELECT COUNT(*) as `fetch_order` FROM `fruugo_order_details` WHERE `merchant_id` ={$_POST['merchant_id']}";
        $result = Data::integrationSqlRecords($query, 'one');
        $data['fetch_order'] = isset($result['fetch_order'])?$result['fetch_order']:0;

        $query = "SELECT COUNT(*) as `failed_order` FROM `fruugo_order_import_error` WHERE `merchant_id` ={$_POST['merchant_id']}";
        $result = Data::integrationSqlRecords($query, 'one');
        $data['failed_order'] = isset($result['failed_order'])?$result['failed_order']:0;

        $query = "SELECT `order_data` FROM `fruugo_order_details` WHERE `status` = 'shipped' AND `merchant_id`=".$_POST['merchant_id'];
        $result = Data::integrationSqlRecords($query, 'all','select');
        $total = 0;

        if(is_array($result) && count($result)>0)
        {
            foreach ($result as $val)
            {
                if(isset($val['order_data']))
                {
                    $val['order_data']=json_decode($val['order_data'],true);
                        foreach ($val['order_data']['o:orderLines']['o:orderLine'] as $value) {
                            if(isset($totalPriceInclVat['o:totalPriceInclVat']))
                            {
                                $total += $value['o:totalPriceInclVat'];
                            }
                        }
                }
            }
        }

        $data['revenue']= (float)$total;

        $html = $this->render('viewproductorderdata', ['data' => $data], true);

        return $html;


    }
    
    public function actionExport(){
        $sql="SELECT `merchant`.`merchant_id`,`merchant`.`shopurl`, `fsd`.`email`,`install_date`,`expire_date`,`purchase_status` FROM `merchant` INNER JOIN `fruugo_shop_details` `fsd` ON `fsd`.`merchant_id`=`merchant`.`merchant_id`";
        $result=Data::integrationSqlRecords($sql);
        if($result){
             $header=true;
             $address=Yii::getAlias('@rootdir').'/var/user';
            $file_name='fruugo-user.csv';
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
}
