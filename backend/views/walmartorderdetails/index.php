<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use yii\widgets\Pjax;
use frontend\modules\walmart\components\Data;

$walmartOrderItemsRefundContent = \yii\helpers\Url::toRoute(['walmartorderdetail/refund-data']);
$walmartOrderShippingContent = \yii\helpers\Url::toRoute(['walmartorderdetail/curlprocessfororder']);
$viewWalmartOrderDetails = \yii\helpers\Url::toRoute(['walmartorderdetail/vieworderdetails']);

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\walmart\models\WalmartOrderDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Walmart Order Details';
$this->params['breadcrumbs'][] = $this->title;
$merchant_id = Yii::$app->user->identity->id;
// print_r($merchant_id);die("dfg");
?>

<!--<div class="walmart-order-detail-form">
    <form action="<?/*= Yii::$app->request->baseUrl; */?>/walmartorderdetails/index>" method="post">
        <input type="text" name="days"/>
        <input type="submit" name="submit" value="submit">

    </form>
</div>-->
    <div class="walmart-order-details-index content-section">
        <div class="form new-section">
            <div class="jet-pages-heading">
                <div class="title-need-help">
                    <h1 class="Jet_Products_style"><?= Html::encode($this->title) ?></h1>
                    <a class="help_jet" title="Need Help" target="_blank"
                       href="<?= Yii::$app->request->baseUrl; ?>/walmart/sell-on-walmart#sec6"></a>
                </div>

                <div class="clear"></div>
            </div>

            <div class="responsive-table-wrap">
                <?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
                <?= GridView::widget([
                    'options' => ['class' => 'grid-view table-responsive'],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        'merchant_id',
                        'order_total',
                       
                        'purchase_order_id'

                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
