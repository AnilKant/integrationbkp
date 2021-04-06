<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\JetMerchantsHelpSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jet-merchants-help-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'merchant_name') ?>

    <?= $form->field($model, 'merchant_store_name') ?>

    <?= $form->field($model, 'merchant_email_id') ?>

    <?= $form->field($model, 'subject') ?>

    <?php // echo $form->field($model, 'query') ?>

    <?php // echo $form->field($model, 'solution') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
