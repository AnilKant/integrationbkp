<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\JetShopDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jet-shop-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'merchant_id') ?>

    <?= $form->field($model, 'shop_url') ?>

    <?= $form->field($model, 'shop_name') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'country_code') ?>

    <?php // echo $form->field($model, 'currency') ?>

    <?php // echo $form->field($model, 'install_status') ?>

    <?php // echo $form->field($model, 'installed_on') ?>

    <?php // echo $form->field($model, 'expired_on') ?>

    <?php // echo $form->field($model, 'purchased_on') ?>

    <?php // echo $form->field($model, 'purchase_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
