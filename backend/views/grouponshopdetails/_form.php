<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\GrouponShopDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="groupon-shop-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput() ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'install_status')->textInput() ?>

    <?= $form->field($model, 'install_date')->textInput() ?>

    <?= $form->field($model, 'uninstall_date')->textInput() ?>

    <?= $form->field($model, 'purchase_status')->textInput() ?>

    <?= $form->field($model, 'expire_date')->textInput() ?>

    <?php //echo $form->field($model, 'ip_address')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'last_login')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
