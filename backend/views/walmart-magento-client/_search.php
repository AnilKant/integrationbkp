<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartClientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="walmart-client-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'firstname') ?>

    <?= $form->field($model, 'lastname') ?>

    <?= $form->field($model, 'seller_store_name') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'annual_revenue') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'shipping_source') ?>

    <?php // echo $form->field($model, 'total_skus') ?>

    <?php // echo $form->field($model, 'company_address') ?>

    <?php // echo $form->field($model, 'valid_tax_w9') ?>

    <?php // echo $form->field($model, 'warehouse_in_usa') ?>

    <?php // echo $form->field($model, 'type_product') ?>

    <?php // echo $form->field($model, 'selling_marketplace') ?>

    <?php // echo $form->field($model, 'different_channel_partner') ?>

    <?php // echo $form->field($model, 'others') ?>

    <?php // echo $form->field($model, 'walmart_contact_before') ?>

    <?php // echo $form->field($model, 'walmart_approved') ?>

    <?php // echo $form->field($model, 'amazon_sellerurl') ?>

    <?php // echo $form->field($model, 'is_activated') ?>

    <?php // echo $form->field($model, 'company_name') ?>

    <?php // echo $form->field($model, 'other_framework') ?>

    <?php // echo $form->field($model, 'integration_framework') ?>

    <?php // echo $form->field($model, 'position') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
