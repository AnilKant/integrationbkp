<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\sears\models\SearsRegistration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sears-registration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reference')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'agreement')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_reference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'selling_on_sears')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'selling_on_sears_source')->textInput(['maxlength' => true]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
