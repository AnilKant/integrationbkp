<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Callschedule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="callschedule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput(['disabled'=>'disabled']) ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'shop_url')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'marketplace')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'status')->dropDownList(['completed'=>'completed','inprogress'=>'inprogress','pending'=>'pending'],['prompt'=>'Select Option']) ?>

    <?= $form->field($model, 'time')->textInput(['disabled'=>'disabled']) ?>

    <?= $form->field($model, 'no_of_request')->textInput(['disabled'=>'disabled']) ?>

    <?= $form->field($model, 'time_zone')->textInput(['maxlength' => true,'placeholder'=>'UTC']) ?>

    <?= $form->field($model, 'preferred_timeslot')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'response')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
