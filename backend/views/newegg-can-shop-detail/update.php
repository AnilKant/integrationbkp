<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\NeweggShopDetail */

$this->title = 'Update Newegg Shop Detail: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Newegg Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="newegg-shop-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
