<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RakutenfrShopDetails */

$this->title = 'Update Rakutenfr Shop Details: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rakutenfr Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rakutenfr-shop-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
