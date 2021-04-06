<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AppliedCoupanCode */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Applied Coupan Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applied-coupan-code-view">

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
            'used_on',
            'coupan_code',
            'activated_date',
            'coupan_code_id',
        ],
    ]) ?>

</div>
