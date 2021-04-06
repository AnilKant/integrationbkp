<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ConditionalRange;

/* @var $this yii\web\View */
/* @var $model common\models\ConditionalCharge */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="conditional-charge-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'charge_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'charge_description')->textarea()->label('Charge Description'); ?>

    <?= $form->field($model, 'charge_condition')->dropDownList(array("Product" => "Product","Order" => "Order","Revenue" => "Revenue"),['prompt'=>'Select.....']) ?>

    <?= $form->field($model, 'charge_range')->dropDownList(array("Fixed" => "Fixed","Range" => "Range"),['prompt'=>'Select.....']) ?>
    
    <div class="dynamic-form">
   	
   </div>
    <button class="btn btn-success" id="add_range">Add range</button>
    <div class="fixed_range_div">

    </div>
   
    <?= $form->field($model, 'merchant_base',['options' => ['class' => 'merchant-base-select']])->dropDownList(array("All" => "All","Merchant" => "Merchant"),['prompt'=>'Select.....']) ?>
    <div id="merchants">
    <?= $form->field($model, 'merchants',['options' => ['class' => 'merchant-base-id']])->textInput(['maxlength' => true])->hint("Enter Merchant Ids like.. 259,359,459") ?>
    </div>
    <?= $form->field($model, 'charge_type')->dropDownList(array("Monthly" => "Monthly","Instant" => "Instant"),['prompt'=>'Select.....']) ?>
    <?= $form->field($model, 'apply')->dropDownList(array("Yes" => "Yes","No" => "No"),['prompt'=>'Select.....']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
	 <div class="fixed_range_val">
    	<?= $form->field($model->conditional_range, 'fixed_range[]')->textInput(['maxlength' => true]) ?>
    	 <?= $form->field($model->conditional_range, 'amount_type[]', ['options' => ['class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-6']])->DropDownList(array("Fixed" => "Fixed","Percentage" => "Percentage"),['prompt'=>'Select.....'])  ?>
    	<?= $form->field($model->conditional_range, 'amount[]',['options' => ['class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-6']])->textInput(['maxlength' => true]) ?>

    </div>

	<div class="conditional_range_to row">
    	<?= $form->field($model->conditional_range, 'from_range[]', ['options' => ['class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-6']])->textInput(['maxlength' => true]) ?>
    	<?= $form->field($model->conditional_range, 'to_range[]',['options' => ['class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-6']])->textInput(['maxlength' => true]) ?>
    	 <?= $form->field($model->conditional_range, 'amount_type[]', ['options' => ['class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-6']])->DropDownList(array("Fixed" => "Fixed","Percentage" => "Percentage"),['prompt'=>'Select.....'])  ?>
    	<?= $form->field($model->conditional_range, 'amount[]',['options' => ['class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-6']])->textInput(['maxlength' => true]) ?> 	
    </div>

<script type="text/javascript">
    $(document).ready(function () {
	    $(".fixed_range_val").hide();
	    $(".conditional_range_to").hide();
	    $("#add_range").hide();
	    $("#merchants").hide();
		
	});
    $("#conditionalcharge-merchant_base").change(function(){
    	var mid = $("#conditionalcharge-merchant_base").val();
    	if (mid=='Merchant') {
    		$("#merchants").show();
    	}
    	else{
    		$("#merchants").hide();
    	}
    });
    $("#add_range").click(function(){
    	$(".dynamic-form").append("<section></section>");
    	 var range = $(".conditional_range_to").html();

         $("section").each(function(key,data){
            if ($(data).html()== "") {

				$(data).html(range+"<button class='btn btn-danger' onclick='removerange(this)'>X</button>");
			}
			
         });
	});
   
    $("#conditionalcharge-charge_range").change(function(){
        var val = $("#conditionalcharge-charge_range").val();
        var ht = "<div></div>";
        if (val=="Range") {
        	$("#add_range").show();
            $(".fixed_range_div").html(ht);
        	
        }
        else if(val=="Fixed"){
        	var fxht = $(".fixed_range_val").html();
        	 $(".fixed_range_div").html(fxht);
        	$("section").remove();
        	$("#add_range").hide();
        }
   });
    $( "#target" ).submit(function( event ) {
  alert( "Handler for .submit() called." );
});
    $(".btn-success").change(function(){
    	 $(this).find("input[type='hidden']").each(function(){
     	alert($(this).val());
 	 });
    });
   function removerange(e){
   		$(e).parent("section").remove();
   		alert("removed");
   }

</script>