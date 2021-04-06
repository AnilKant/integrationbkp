<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MagentoExtensionDetailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magento-extension-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'store_url') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'total_product') ?>

    <?= $form->field($model, 'published') ?>

    <?php // echo $form->field($model, 'unpublished') ?>

    <?php // echo $form->field($model, 'total_order') ?>

    <?php // echo $form->field($model, 'complete_orders') ?>

    <?php // echo $form->field($model, 'config_set') ?>

    <?php // echo $form->field($model, 'ac_details') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
