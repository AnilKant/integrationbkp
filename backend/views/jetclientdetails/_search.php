<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\jet\models\JetRegistrationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jet-registration-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'merchant_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'shipping_source') ?>

    <?= $form->field($model, 'other_shipping_source') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'reference') ?>

    <?php // echo $form->field($model, 'already_selling') ?>

    <?php // echo $form->field($model, 'previous_api_provider_name') ?>

    <?php // echo $form->field($model, 'is_uninstalled_previous') ?>

    <?php // echo $form->field($model, 'agreement') ?>

    <?php // echo $form->field($model, 'other_reference') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
