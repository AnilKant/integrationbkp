<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\JetProductFileUploadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jet-product-file-upload-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'merchant_id') ?>

    <?= $form->field($model, 'local_file_path') ?>

    <?= $form->field($model, 'file_name') ?>

    <?= $form->field($model, 'file_type') ?>

    <?php // echo $form->field($model, 'file_url') ?>

    <?php // echo $form->field($model, 'jet_file_id') ?>

    <?php // echo $form->field($model, 'received') ?>

    <?php // echo $form->field($model, 'processing_start') ?>

    <?php // echo $form->field($model, 'processing_end') ?>

    <?php // echo $form->field($model, 'total_processed') ?>

    <?php // echo $form->field($model, 'error_count') ?>

    <?php // echo $form->field($model, 'error_url') ?>

    <?php // echo $form->field($model, 'error_excerpt') ?>

    <?php // echo $form->field($model, 'expires_in_seconds') ?>

    <?php // echo $form->field($model, 'file_upload_time') ?>

    <?php // echo $form->field($model, 'error') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
