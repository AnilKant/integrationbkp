<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CallscheduleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="callschedule-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'merchant_id') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'shop_url') ?>

    <?= $form->field($model, 'marketplace') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'time') ?>

    <?php // echo $form->field($model, 'no_of_request') ?>

    <?php // echo $form->field($model, 'time_zone') ?>

    <?php // echo $form->field($model, 'preferred_timeslot') ?>

    <?php // echo $form->field($model, 'response') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
