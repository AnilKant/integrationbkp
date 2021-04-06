<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CoupanCodeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupan-code-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'promo_code') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'amount_type') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'applied_on') ?>

    <?php // echo $form->field($model, 'expire_date') ?>

    <?php // echo $form->field($model, 'applied_merchant') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
