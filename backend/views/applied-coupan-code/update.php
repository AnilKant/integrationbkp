<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AppliedCoupanCode */

$this->title = 'Update Applied Coupan Code: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Applied Coupan Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="applied-coupan-code-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
