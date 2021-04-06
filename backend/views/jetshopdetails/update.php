<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\JetShopDetails */

$this->title = 'Update Jet Shop Details: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jet Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jet-shop-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
