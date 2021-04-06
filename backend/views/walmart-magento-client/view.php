<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartClient */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Walmart Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="walmart-client-view">

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
            'firstname:ntext',
            'lastname:ntext',
            'seller_store_name:ntext',
            'email:ntext',
            'phone:ntext',
            'country:ntext',
            'code',
            'annual_revenue:ntext',
            'website:ntext',
            'shipping_source:ntext',
            'total_skus:ntext',
            'company_address:ntext',
            'valid_tax_w9:ntext',
            'warehouse_in_usa:ntext',
            'type_product:ntext',
            'selling_marketplace:ntext',
            'different_channel_partner:ntext',
            'others:ntext',
            'walmart_contact_before:ntext',
            'walmart_approved:ntext',
            'amazon_sellerurl:ntext',
            'is_activated',
            'company_name',
            'other_framework',
            'integration_framework',
            'position',
        ],
    ]) ?>

</div>
