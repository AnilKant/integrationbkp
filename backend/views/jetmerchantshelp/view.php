<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\JetMerchantsHelp */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jet Merchants Helps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-merchants-help-view">

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
            'merchant_name',
            'merchant_store_name',
            'merchant_email_id:email',
            'subject',
            'query:ntext',
            'solution:ntext',
            'status',
            'time',
        ],
    ]) ?>

</div>
