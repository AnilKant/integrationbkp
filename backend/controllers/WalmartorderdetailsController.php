<?php

namespace backend\controllers;

use backend\components\Data;
use backend\models\WalmartOrderDetailSearch;
use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;


/**
 * Walmart Order detail Controller
 */
class WalmartorderdetailsController extends MainController
{

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $days='';
        $where='';
        if(isset($_GET['export']) && !empty($_GET['export'])){
            $days = $_GET['export'];
            $action = '-'.$days.' days';

            $date=date('Y-m-d');
            $date1=date('Y-m-d',strtotime($action, strtotime(date('Y-m-d'))));
            $where = "WHERE `status` = 'completed' AND `created_at` BETWEEN '{$date1}' AND '{$date}'";
        }

        if($where){
            $query = "SELECT * FROM (SELECT `shop_url`,`merchant_id` FROM `walmart_shop_details`) as `wsd` INNER JOIN (SELECT `merchant_id`,COUNT(`purchase_order_id`) as `counter`,SUM(order_total) as revenue FROM `walmart_order_details` $where group by merchant_id ) as `order_table` ON `order_table`.`merchant_id` = `wsd`.`merchant_id` order by `order_table`.revenue desc ";

        }else{
            $query = "SELECT * FROM (SELECT `shop_url`,`merchant_id` FROM `walmart_shop_details`) as `wsd` INNER JOIN (SELECT `merchant_id`,COUNT(`purchase_order_id`) as `counter`,SUM(order_total) as revenue FROM `walmart_order_details` WHERE `status` = 'completed' group by merchant_id ) as `order_table` ON `order_table`.`merchant_id` = `wsd`.`merchant_id` order by `order_table`.revenue desc ";

        }

        $order = Data::sqlRecords($query,'all',null);
        $header = ['SHOP URL', 'MERCHANT_ID','Total No of Orders', 'REVENUE'];

        $order_data = array();
        foreach ($order as $row) {
            $order_data[] = array_combine($header, $row);
        }
        $searchOrderAttributes = ['SHOP URL', 'MERCHANT_ID','Total No of Orders', 'REVENUE'];
        $searchOrderModel = [];
        $searchOrderColumns = [];

        foreach ($searchOrderAttributes as $searchOrderAttribute) {
            $filterName1 = $searchOrderAttribute;

            $filterValue1 = Yii::$app->request->getQueryParam(str_replace(' ', '_', $filterName1), '');
            $searchOrderModel[$searchOrderAttribute] = $filterValue1;

            $searchOrderColumns[] = [
                'attribute' => $searchOrderAttribute,
                'filter' => '<input class="form-control" name="' . str_replace(' ', '_', $filterName1) . '" value="' . $filterValue1 . '" type="text">',
                'value' => $searchOrderAttribute,
            ];

            $order_data = array_filter($order_data, function ($item) use (&$filterValue1, &$searchOrderAttribute) {

                return strlen($filterValue1) > 0 ? stripos('/^' . strtolower($item[$searchOrderAttribute]) . '/', strtolower($filterValue1)) : true;
            });

        }

        return $this->render('index-new', ['order' => $order_data, 'searchOrderAttributes' => $searchOrderAttributes, 'searchOrderModel' => $searchOrderModel, 'searchOrderColumns' => $searchOrderColumns,'days'=>$days]);

    }

    /*public function actionIndex()
    {
        $searchModel = new WalmartOrderDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }*/

}