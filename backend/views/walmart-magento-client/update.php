<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartClient */

$this->title = 'Update Walmart Client: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Walmart Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="walmart-client-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
