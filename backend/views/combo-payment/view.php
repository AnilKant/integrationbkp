<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ComboPayment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Combo Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="combo-payment-view">

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
            'shop_url:ntext',
            'selected_marketplace:ntext',
            'shopify_payment_info:ntext',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
