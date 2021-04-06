<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\components\Data;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MagentoFruugoInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Magento Fruugo Infos';
$this->params['breadcrumbs'][] = $this->title;

$sql = "SELECT `total_revenue` FROM `magento_fruugo_info`";
$total_revenue = Data::sqlRecords($sql,"all",'select'); 
$total_ced_revenue = 0;
$ced_month_revenue = 0;
$ced_last_month_revenue = 0;
$sql1 = "SELECT SUM(`order_total`) FROM `magento_fruugo_orders`";
$total_ced_revenue = Data::sqlRecords($sql1, "one", "select");
$sql2 = "SELECT SUM(`order_total`) FROM `magento_fruugo_orders` WHERE MONTH(`order_date`)=MONTH(CURRENT_DATE())";
$ced_month_revenue =  Data::sqlRecords($sql2, "one", "select");
$sql3 = "SELECT SUM(`order_total`) FROM `magento_fruugo_orders` WHERE MONTH(`order_date`)=MONTH(CURRENT_DATE()- INTERVAL 1 MONTH)";
$ced_last_month_revenue =  Data::sqlRecords($sql3, "one", "select");
?>

<div class="magento-fruugo-info-index">

    <div class="row">
        <div class="col-md-6">
            <h1><?= Html::encode($this->title) ?></h1>
            <p>
            <?= Html::a('Sync Client Detail', ['syncinfo'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="col-md-6">
            <table class="table table-striped table-bordered"  style="margin:20px;float: right;">
                <tr>
                    <th>This month Ced Revenue</th>
                    <th>Last month Ced Revenue</th>
                   <th>Total Ced Revenue</th>
                   <th>This month Order Revenue</th>
                    <th>Last month Order Revenue</th>
                   <th>Total Order Revenue</th>
                </tr>
                <tr>
                    <td><?= number_format((float)((1/100)*($ced_month_revenue['SUM(`order_total`)'])),2); ?></td>
                    <td><?= number_format((float)((1/100)*($ced_last_month_revenue['SUM(`order_total`)'])),2); ?></td>
                    <td><?= number_format((float)((1/100)*($total_ced_revenue['SUM(`order_total`)'])),2); ?></td>
                    <td><?= number_format($ced_month_revenue['SUM(`order_total`)'],2); ?></td>
                    <td><?= number_format($ced_last_month_revenue['SUM(`order_total`)'],2); ?></td>
                    <td><?= number_format($total_ced_revenue['SUM(`order_total`)'],2); ?></td>
                </tr>
            </table>
        </div>
    </div>
    
    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'domain',
            'email:email',
            'live_sku',
            'uploaded_sku',
            [
                'label'=>'Total Revenue',
                'value' => function($data){
                    
                    $query1 = "SELECT SUM(`order_total`) FROM `magento_fruugo_orders` WHERE `merchant_info_id`=".$data['id'];
                    $totalOrders = Data::sqlRecords($query1,"one",'select');

                    $totals= $totalOrders['SUM(`order_total`)'] ? $totalOrders['SUM(`order_total`)']:0;
                    return number_format($totals,2);
                },
                'format'=>'raw',
            ],
            [
                'label'=>' Current Month Revenue',
                'value' => function($data){
                    
                    $query = "SELECT SUM(`order_total`) FROM `magento_fruugo_orders` WHERE MONTH(`order_date`)=MONTH(CURRENT_DATE()) AND `merchant_info_id`=".$data['id'];
                    $totalOrder = Data::sqlRecords($query,"one",'select');
                    $total= $totalOrder['SUM(`order_total`)'] ? $totalOrder['SUM(`order_total`)']:0;
                    return number_format($total,2);
                },
                'format'=>'raw',
            ],
            'install_on',
            'framework',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>


<script type="text/javascript">
    $(document).ready(function(){
        $(".glyphicon-trash").hide();
    });
</script>