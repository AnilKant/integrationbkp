<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\NeweggClientDetail */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Newegg Client Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newegg-client-detail-view">

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
            'name',
            'shipping_source',
            'other_shipping_source',
            'mobile',
            'email:email',
            'reference:ntext',
            'agreement',
            'other_reference',
        ],
    ]) ?>

</div>
