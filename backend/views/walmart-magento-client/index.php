<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WalmartClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Walmart Clients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="walmart-client-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Walmart Client', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'firstname:ntext',
            'lastname:ntext',
            'seller_store_name:ntext',
            'email:ntext',
            // 'phone:ntext',
            // 'country:ntext',
            // 'code',
            // 'annual_revenue:ntext',
            // 'website:ntext',
            // 'shipping_source:ntext',
            // 'total_skus:ntext',
            // 'company_address:ntext',
            // 'valid_tax_w9:ntext',
            // 'warehouse_in_usa:ntext',
            // 'type_product:ntext',
            // 'selling_marketplace:ntext',
            // 'different_channel_partner:ntext',
            // 'others:ntext',
            // 'walmart_contact_before:ntext',
            // 'walmart_approved:ntext',
            // 'amazon_sellerurl:ntext',
            // 'is_activated',
            // 'company_name',
            // 'other_framework',
            // 'integration_framework',
            // 'position',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
