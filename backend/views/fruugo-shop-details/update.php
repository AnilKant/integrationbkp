<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartShopDetails */

$this->title = 'Update Fruugo Shop Details: ' . ' ' . $model->merchant_id;
$this->params['breadcrumbs'][] = ['label' => 'Walmart Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="walmart-shop-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
