<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartcaShopDetails */

$this->title = 'Update Walmartca Shop Details: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Walmartca Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="walmartca-shop-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
