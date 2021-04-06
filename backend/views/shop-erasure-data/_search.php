<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\etsy\models\ShopErasureDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-erasure-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'merchant_id') ?>

    <?= $form->field($model, 'shop_url') ?>

    <?= $form->field($model, 'mobile') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'marketplace') ?>

    <?php // echo $form->field($model, 'total_products') ?>

    <?php // echo $form->field($model, 'total_orders') ?>

    <?php // echo $form->field($model, 'total_revenue') ?>

    <?php // echo $form->field($model, 'config_set') ?>

    <?php // echo $form->field($model, 'purchased_status') ?>

    <?php // echo $form->field($model, 'install_date') ?>

    <?php // echo $form->field($model, 'uninstall_date') ?>

    <?php // echo $form->field($model, 'last_purchased_plan') ?>

    <?php // echo $form->field($model, 'shopify_plan_name') ?>

    <?php // echo $form->field($model, 'shop_data') ?>

    <?php // echo $form->field($model, 'marketplace_configuration') ?>

    <?php // echo $form->field($model, 'token') ?>

    <?php // echo $form->field($model, 'expiry_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
