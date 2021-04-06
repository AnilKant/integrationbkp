<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PricingPlanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pricing-plan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'plan_name') ?>

    <?= $form->field($model, 'plan_type') ?>

    <?= $form->field($model, 'duration') ?>

    <?= $form->field($model, 'plan_status') ?>

    <?php // echo $form->field($model, 'base_price') ?>

    <?php // echo $form->field($model, 'special_price') ?>

    <?php // echo $form->field($model, 'apply_on') ?>

    <?php // echo $form->field($model, 'additional_condition') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
