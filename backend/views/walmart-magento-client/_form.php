<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartClient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="walmart-client-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'firstname')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'lastname')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'seller_store_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'email')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'phone')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'country')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'code')->textInput() ?>

    <?= $form->field($model, 'annual_revenue')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'website')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'shipping_source')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'total_skus')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'company_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'valid_tax_w9')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'warehouse_in_usa')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type_product')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'selling_marketplace')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'different_channel_partner')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'others')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'walmart_contact_before')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'walmart_approved')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'amazon_sellerurl')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_activated')->textInput() ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_framework')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'integration_framework')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
