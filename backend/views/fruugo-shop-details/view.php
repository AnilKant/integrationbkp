<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartShopDetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fruugo Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="walmart-shop-details-view">
    <h1><?= Html::encode($this->title) ?></h1>
        <div class="grid-view">
         <div class="btn-left-set">
   <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </div class="btn-left-set">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'merchant_id',
            'shop_url:url',
            'shop_name',
            'email:email',
            'allowed_sku',
            'install_date',
            'expire_date',
            'purchase_status',          
        ],
    ]) ?>
    </div>

</div>
