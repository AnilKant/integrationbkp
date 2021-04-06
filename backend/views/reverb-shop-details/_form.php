<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ReverbShopDetails */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="reverb-shop-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput(['disabled'=>'disabled']) ?>

    <?= $form->field($model, 'shopurl')->textInput(['rows' => 6,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'install_status')->dropDownList(['1'=>'Install','0'=>'Uninstall'],['prompt'=>'Select Option','disabled'=>'disabled']) ?>

    <?= $form->field($model, 'install_date')->textInput() ?>

    <?= $form->field($model, 'uninstall_dates')->textInput() ?>

    <?= $form->field($model, 'purchase_status')->dropDownList(['1'=>'Trial','2'=>'Trial Expired','3'=>'Purchased','4'=>'License Expired'],['prompt'=>'Select Option'])  ?>

    <?= $form->field($model, 'app_handler')->textInput() ?>

    <?= $form->field($model, 'seller_username')->textInput() ?>

    <?= $form->field($model, 'seller_password')->textInput() ?>

    <?= $form->field($model, 'verified_mobile')->textInput(['disabled'=>'disabled']) ?>

    <?= $form->field($model, 'expire_date')->textInput() ?>

    <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_login')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
