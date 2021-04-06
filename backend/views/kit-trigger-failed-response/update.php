<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KitTriggerFailedResponse */

$this->title = 'Update Kit Trigger Failed Response: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kit Trigger Failed Responses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kit-trigger-failed-response-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
