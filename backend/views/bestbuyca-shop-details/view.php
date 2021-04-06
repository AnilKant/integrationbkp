<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\BestbuycaShopDetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bestbuyca Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bestbuyca-shop-details-view">

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
            'purchase_status',
            'expire_date',
            'created_at',
            'updated_at',
            'last_login_ip',
            'last_login_time',
        ],
    ]) ?>

</div>
