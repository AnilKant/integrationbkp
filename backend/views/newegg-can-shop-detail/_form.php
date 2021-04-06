<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\NeweggShopDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="newegg-can-shop-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput(['disabled'=>'disabled']) ?>

    <?= $form->field($model, 'shop_url')->textarea(['rows' => 6,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'shop_name')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'token')->textarea(['rows' => 6,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'country_code')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'install_status')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'install_date')->textInput() ?>

    <?= $form->field($model, 'expire_date')->textInput() ?>

    <?= $form->field($model, 'purchase_date')->textInput() ?>

    <?/*= $form->field($model, 'purchase_status')->textInput(['maxlength' => true]) */?>
    <?= $form->field($model, 'purchase_status')->dropDownList(['Trial'=>'Trial','Trail Expired'=>'Trail Expired','Purchased'=>'Purchased','Not Purchased'=>'Not Purchased','License Expired'=>'License Expired'],['prompt'=>'Select Option']) ?>


    <?= $form->field($model, 'client_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'uninstall_date')->textInput() ?>

    <?= $form->field($model, 'app_status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
