<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BonanzaPayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bonanza-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'charge_id')->textInput() ?>

    <?= $form->field($model, 'merchant_id')->textInput() ?>

    <?= $form->field($model, 'billing_on')->textInput() ?>

    <?= $form->field($model, 'activated_on')->textInput() ?>

    <?= $form->field($model, 'plan_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recurring_data')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
