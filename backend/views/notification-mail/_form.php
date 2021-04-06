<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\NotificationMail */
/* @var $form yii\widgets\ActiveForm */
$send_mail=[
        '1'=>'Enable',
        '0'=>'Disable'
];
$marketplace = [
        'jet'=>'jet',
        'walmart'=>'walmart',
        'newegg'=>'newegg'
];
$days = [
        '1' => '1 Day',
        '3' => '3 Days',
        '5' => '5 Days',
        '7' => '7 Days',
        '15'=> '15 Days'
];
$mailType = [
        'trial_expire' => 'Trial Expire',
        'license_expire' => 'License Expire',
        /*'purchased' => 'Purchased',*/
        'not_registered' => 'Not Registered',
        'not_configured' => 'Not Configured',
        'product_upload' => 'Product Upload'
];
?>

<div class="notification-mail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mail_type',['options'=>[]])->dropDownList($mailType , ['prompt' => 'Choose...']) ?>

    <?= $form->field($model, 'days',['options'=>[]])->checkboxList($days, ['prompt' => 'Choose...', 'multiple' => true, 'selected' => 'selected']);?>

    <?= $form->field($model, 'send_mail',['options'=>[]])->dropDownList($send_mail, ['prompt' => 'Choose...']);  ?>
    <?= $form->field($model, 'subject')->textInput() ?>

    <?= $form->field($model, 'marketplace',['options'=>[]])->checkboxList($marketplace);?>

    <?= $form->field($model, 'email_template')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
