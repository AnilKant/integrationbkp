<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\fruugo\models\Pricefallsshopdetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pricefallsshopdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pricefallsshopdetails-view">

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
            'token',
            'install_status',
            'install_date',
            'uninstall_date',
            
            'expire_date',
            'purchase_status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
