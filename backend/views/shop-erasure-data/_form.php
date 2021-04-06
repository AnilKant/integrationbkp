<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\etsy\models\ShopErasureData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-erasure-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput() ?>

    <?= $form->field($model, 'shop_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'marketplace')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_products')->textInput() ?>

    <?= $form->field($model, 'total_orders')->textInput() ?>

    <?= $form->field($model, 'total_revenue')->textInput() ?>

    <?= $form->field($model, 'config_set')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'purchased_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'install_date')->textInput() ?>

    <?= $form->field($model, 'uninstall_date')->textInput() ?>

    <?= $form->field($model, 'last_purchased_plan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shopify_plan_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'marketplace_configuration')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expiry_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
