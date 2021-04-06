<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\bonanza\models\bonanzaRegistration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bonanza-registration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput() ?>

    <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'store_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shipping_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_shipping_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'annual_revenue')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reference')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'agreement')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_reference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'selling_on_bonanza')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'selling_on_bonanza_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_selling_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'approved_by_bonanza')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
