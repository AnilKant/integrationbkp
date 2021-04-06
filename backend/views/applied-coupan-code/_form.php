<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AppliedCoupanCode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="applied-coupan-code-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput() ?>

    <?= $form->field($model, 'used_on')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coupan_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'activated_date')->textInput() ?>

    <?= $form->field($model, 'coupan_code_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
