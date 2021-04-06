<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\JetMerchantsHelp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jet-merchants-help-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'merchant_name')->textInput(['maxlength' => true,'readonly' => true]) ?>
	
    <?= $form->field($model, 'merchant_store_name')->textInput(['maxlength' => true,'readonly' => true]) ?>

    <?= $form->field($model, 'merchant_email_id')->textInput(['maxlength' => true,'readonly' => true]) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true,'readonly' => true]) ?>

    <?= $form->field($model, 'query')->textarea(['rows' => 6,'readonly' => true]) ?>

    <?= $form->field($model, 'solution')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'active' => 'Active', 'resolved' => 'Resolved', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'time')->textInput(['readonly' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
