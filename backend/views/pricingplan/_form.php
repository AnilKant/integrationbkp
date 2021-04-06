<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ConditionalCharge;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\PricingPlan */
/* @var $form yii\widgets\ActiveForm */
$connection = Yii::$app->getDb();
$charge_id = $connection->createCommand('SELECT `id` FROM `conditional_charge`')->queryAll();


?>

<div class="pricing-plan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'plan_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plan_type')->dropDownList(array("Free" => "Free","Paid" => "Paid"),['prompt'=>'Select Plan Type']) ?>

    <?= $form->field($model, 'duration[0]')->dropDownList(array("Day" => "Day","Week" => "Week","Month" => "Month","Year" => "Year","Unlimited"=>"Unlimited"),['prompt'=>'Select Duration']) ?>
    <div class="duration">
        <?= $form->field($model, 'duration[1]')->textInput()->label("Duration count") ?>
    </div>

    <?= $form->field($model, 'plan_status')->dropDownList(array("Enable" => "Enable","Disable" => "Disable"),['prompt'=>'Select Status']) ?>

    <?= $form->field($model, 'base_price')->textInput() ?>

    <?= $form->field($model, 'special_price')->textInput() ?>
    <?= $form->field($model, 'capped_amount')->textInput()->hint('It is maximum amount that can be charged') ?>
    <?= $form->field($model, 'trial_period')->textInput()->hint('Please enter trail days') ?>

    <?= $form->field($model, 'apply_on')->dropDownList(array("All" => "All","Jet" => "Jet","Walmart" => "Walmart","Newegg" => "Newegg","Sears" => "Sears"),['multiple' => true]) ?>

           
           <?= $form->field($model, 'additional_condition')->dropDownList( ArrayHelper::map(ConditionalCharge::find()->all(),'id','charge_name'), ['multiple' => true]);?>
   
        <br><br>
        <?= $form->field($model, 'feature')->textarea()->label('Feature'); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".duration").hide();
            var editor = new nicEditor({buttonList: ['bold', 'italic', 'underline', 'left', 'center', 'right', 'justify', 'ol', 'ul', 'subscript', 'superscript', 'strikethrough', 'removeformat', 'indent', 'outdent', 'hr', 'image', 'forecolor', 'bgcolor', 'link', 'unlink', 'fontSize', 'fontFamily', 'fontFormat', 'xhtml']/*fullPanel : true*/}).panelInstance('pricingplan-feature');
        
    });
    $("button").click(function(){
        
        var nicInstance = nicEditors.findEditor('pricingplan-feature');
        var description = nicInstance.getContent();
        //alert(description);
        $("textarea").html(description);

    });
    $("#pricingplan-duration-0").change(function(){
        var val = $("#pricingplan-duration-0").val();
        if (val=="Unlimited") {
           $(".duration").hide(); 
       }else{
        $(".duration").show();
       }
        
    });
</script>