<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ConditionalChargeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conditional-charge-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'charge_name') ?>

    <?= $form->field($model, 'charge_condition') ?>

    <?= $form->field($model, 'charge_range') ?>

    <?= $form->field($model, 'merchant_base') ?>

    <?php // echo $form->field($model, 'charge_type') ?>

    <?php // echo $form->field($model, 'apply') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
