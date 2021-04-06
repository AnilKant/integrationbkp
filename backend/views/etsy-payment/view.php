<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\EtsyPayment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Etsy Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="etsy-payment-view">

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
            'charge_id',
            'merchant_id',
            'billing_on',
            'activated_on',
            'plan_type',
            'status',
            'recurring_data:ntext',
        ],
    ]) ?>

</div>
