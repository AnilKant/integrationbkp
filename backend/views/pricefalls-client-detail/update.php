<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\pricefalls\models\pricefallsRegistration */

$this->title = 'Update pricefalls Registration: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'pricefalls Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pricefalls-registration-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
