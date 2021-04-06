<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CoupanCode */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="coupan-code-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'promo_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(array("Enable" => "Enable","Disable" => "Disable"),['prompt'=>'Select...']) ?>

    <?= $form->field($model, 'amount_type')->dropDownList(array("Fixed" => "Fixed","Percentage" => "Percentage"),['prompt'=>'Select...']) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'applied_on')->dropDownList(array("All" => "All","Jet" => "Jet","Walmart" => "Walmart","Newegg" => "Newegg","Sears" => "Sears"),['prompt'=>'Select...']) ?>

    <?= $form->field($model, 'expire_date')->textInput() ?>

    <?= $form->field($model, 'applied_merchant')->dropDownList(array("All" => "All","Merchant" => "Merchant"),['prompt'=>'Select...']) ?>
    
    <?= $form->field($model, 'merchant_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.field-coupancode-merchant_id').hide();
    });
    $('#coupancode-applied_merchant').change(function(){
        var applied = $('#coupancode-applied_merchant').val();
        if (applied == "Merchant") {
        $('.field-coupancode-merchant_id').show();
        }
        else{
           $('.field-coupancode-merchant_id').hide(); 
        }
    });
</script>