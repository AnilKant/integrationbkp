<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AppliedCoupanCodeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="applied-coupan-code-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'merchant_id') ?>

    <?= $form->field($model, 'used_on') ?>

    <?= $form->field($model, 'coupan_code') ?>

    <?= $form->field($model, 'activated_date') ?>

    <?php // echo $form->field($model, 'coupan_code_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
