<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\JetProductFileUpload */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jet-product-file-upload-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput() ?>

    <?= $form->field($model, 'local_file_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file_url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'jet_file_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'received')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'processing_start')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'processing_end')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_processed')->textInput() ?>

    <?= $form->field($model, 'error_count')->textInput() ?>

    <?= $form->field($model, 'error_url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'error_excerpt')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'expires_in_seconds')->textInput() ?>

    <?= $form->field($model, 'file_upload_time')->textInput() ?>

    <?= $form->field($model, 'error')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
