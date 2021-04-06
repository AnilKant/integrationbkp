<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ComboPayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="combo-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'selected_marketplace')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'shopify_payment_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
