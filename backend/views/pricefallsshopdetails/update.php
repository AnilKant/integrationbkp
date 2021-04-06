<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\fruugo\models\Pricefallsshopdetails */

$this->title = 'Update Pricefallsshopdetails: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pricefallsshopdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pricefallsshopdetails-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
