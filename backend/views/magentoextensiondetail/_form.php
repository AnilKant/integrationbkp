<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MagentoExtensionDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magento-extension-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'store_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_product')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'published')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unpublished')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_order')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'complete_orders')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'totalRevenue')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'config_set')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'pilot_seller')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'plateform')->dropDownList([ 'm1' => 'M1', 'm2' => 'M2', 'woocommerce' => 'Woocommerce' ], ['prompt' => '']) ?>
    
    <?= $form->field($model, 'ac_details')->textarea(['rows' => '6']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
