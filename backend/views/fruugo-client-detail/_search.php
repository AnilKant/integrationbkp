<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\fruugo\models\fruugoRegistrationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fruugo-registration-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'merchant_id') ?>

    <?= $form->field($model, 'fname') ?>

    <?= $form->field($model, 'lname') ?>

    <?= $form->field($model, 'store_name') ?>

    <?php // echo $form->field($model, 'shipping_source') ?>

    <?php // echo $form->field($model, 'other_shipping_source') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'annual_revenue') ?>

    <?php // echo $form->field($model, 'reference') ?>

    <?php // echo $form->field($model, 'agreement') ?>

    <?php // echo $form->field($model, 'other_reference') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'selling_on_fruugo') ?>

    <?php // echo $form->field($model, 'selling_on_fruugo_source') ?>

    <?php // echo $form->field($model, 'other_selling_source') ?>

    <?php // echo $form->field($model, 'approved_by_fruugo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
