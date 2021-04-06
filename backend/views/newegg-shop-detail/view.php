<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\NeweggShopDetail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Newegg Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newegg-shop-detail-view">
<h1><?= Html::encode($this->title) ?></h1>
    <div class="grid-view">
         <div class="btn-left-set">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </div class="btn-left-set">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'shop_url:ntext',
            'shop_name',
            'email:email',
            'token:ntext',
            'country_code',
            'currency',
            'install_status',
            'install_date',
            'expire_date',
            'purchase_date',
            'purchase_status',
            'client_data:ntext',
            'uninstall_date',
            'app_status',
        ],
    ]) ?>
    </div>

</div>
