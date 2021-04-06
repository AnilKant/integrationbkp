<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\etsy\models\ShopErasureData */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shop Erasure Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-erasure-data-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'shop_url:url',
            'mobile',
            'email:email',
            'marketplace',
            'total_products',
            'total_orders',
            'total_revenue',
            'config_set',
            'purchased_status',
            'install_date',
            'uninstall_date',
            'last_purchased_plan',
            'shopify_plan_name',
            'shop_data:ntext',
            'marketplace_configuration:ntext',
            'token',
            'expiry_date',
        ],
    ]) ?>

</div>
