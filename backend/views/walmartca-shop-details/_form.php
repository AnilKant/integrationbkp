<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartcaShopDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="walmartca-shop-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'install_date')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'install_status')->textInput() ?>

    <?= $form->field($model, 'uninstall_dates')->textInput() ?>

    <?= $form->field($model, 'purchase_status')->dropDownList(["1"=>"Trial","2"=>"Trial Expired","3"=>"Purchased","4"=>"License Expired"]) ?>

    <?= $form->field($model, 'expire_date')->textInput() ?>

    <?= $form->field($model, 'seller_username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seller_password')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>