<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\EtsyPayment */

$this->title = 'Update Etsy Payment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Etsy Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="etsy-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
