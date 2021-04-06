<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MagentoFruugoInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magento-fruugo-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fruugo_username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fruugo_password')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'install_on')->textInput() ?>

    <?= $form->field($model, 'framework')->textInput(['maxlength' => true,'disabled' => 'disabled']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
