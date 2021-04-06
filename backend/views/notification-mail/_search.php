<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\NotificationMailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notification-mail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'mail_type') ?>

    <?= $form->field($model, 'days') ?>

    <?= $form->field($model, 'send_mail') ?>

    <?= $form->field($model, 'marketplace') ?>

    <?php // echo $form->field($model, 'email_template') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
