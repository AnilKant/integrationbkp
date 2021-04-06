<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ConditionalRange;

/* @var $this yii\web\View */
/* @var $model common\models\ConditionalCharge */
/* @var $form yii\widgets\ActiveForm */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Conditional Charges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="conditional-charge-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'charge_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'charge_description')->textarea()->label('Charge Description'); ?>

    <?= $form->field($model, 'charge_condition')->dropDownList(array("Product" => "Product","Order" => "Order","Revenue" => "Revenue"),['prompt'=>'Select.....']) ?>

    <?= $form->field($model, 'charge_range')->dropDownList(array("Fixed" => "Fixed","Range" => "Range"),['prompt'=>'Select.....']) ?>
    
   
   <div class="dynamic-form">
   <?php foreach ($model->conditional_range as $key => $value) {
      ?>
   		<section>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-from_range">
			<label class="control-label" for="conditionalrange-from_range">From Range</label>
			<input id="conditionalrange-from_range" class="form-control" name="ConditionalRange[from_range][]" type="text" value="<?= $value['from_range']; ?>">

			</div>    	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-to_range">
			<label class="control-label" for="conditionalrange-to_range">To Range</label>
			<input id="conditionalrange-to_range" class="form-control" name="ConditionalRange[to_range][]" type="text" value="<?= $value['to_range']; ?>">

			</div>    	 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-amount_type">
			<label class="control-label" for="conditionalrange-amount_type">Amount Type</label>
			<select id="conditionalrange-amount_type" class="form-control" name="ConditionalRange[amount_type][]" >
			<?php  
				if ($value['amount_type']=="Fixed") {
					?>
					<option value="Fixed" selected="">Fixed</option>
					<?php
				}
				else if ($value['amount_type']=="Percentage"){
					?>
					<option value="Percentage" selected="">Percentage</option>
				<?php
				}
				else{
					?>
					<option value="">Select.....</option>
					<option value="Fixed">Fixed</option>
					<option value="Percentage">Percentage</option>
				<?php 
				}
					
					?>
			
			</select>

			</div>    	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-amount">
			<label class="control-label" for="conditionalrange-amount">Amount</label>
			<input id="conditionalrange-amount" class="form-control" name="ConditionalRange[amount][]" type="text" value="<?= $value['amount']; ?>">

			</div> 	
    <button class="btn btn-danger" onclick="removerange(this)">X</button></section>
    	<?php }?>
    </div>
    <button type="button" id="add_range" class="btn btn-success">Add range</button>
    <div class="fixed_range_div">
        <div class="fixed_range_val" style="display: none;">
        <div class="form-group field-conditionalrange-fixed_range">
        <label class="control-label" for="conditionalrange-fixed_range">Fixed Range</label>
        <input id="conditionalrange-fixed_range" class="form-control" name="ConditionalRange[fixed_range][]" type="text" value="<?= $value['fixed_range']; ?>">

        </div>       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-amount_type">
        <label class="control-label" for="conditionalrange-amount_type">Amount Type</label>
        <select id="conditionalrange-amount_type" class="form-control" name="ConditionalRange[amount_type][]">
        <?php  
                if ($value['amount_type']=="Fixed") {
                    ?>
                    <option value="Fixed" selected="">Fixed</option>
                    <?php
                }
                else if ($value['amount_type']=="Percentage"){
                    ?>
                    <option value="Percentage" selected="">Percentage</option>
                <?php
                }
                else{
                    ?>
                    <option value="">Select.....</option>
                    <option value="Fixed">Fixed</option>
                    <option value="Percentage">Percentage</option>
                <?php 
                }
                    
                    ?>
            
        </select>

        </div>      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-amount">
        <label class="control-label" for="conditionalrange-amount">Amount</label>
        <input id="conditionalrange-amount" class="form-control" name="ConditionalRange[amount][]" type="text" value="<?= $value['amount']; ?>">

        </div>
    </div>
    </div>
 
    <?= $form->field($model, 'merchant_base',['options' => ['class' => 'merchant-base-select']])->dropDownList(array("All" => "All","Merchant" => "Merchant"),['prompt'=>'Select.....']) ?>
    <div id="merchants">
    <?= $form->field($model, 'merchants',['options' => ['class' => 'merchant-base-id']])->textInput(['maxlength' => true]) ?>
    </div>
    <?= $form->field($model, 'charge_type')->dropDownList(array("Monthly" => "Monthly","Instant" => "Instant"),['prompt'=>'Select.....']) ?>
    <?= $form->field($model, 'apply')->dropDownList(array("Yes" => "Yes","No" => "No"),['prompt'=>'Select.....']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
	 <div class="fixed_range_val" style="display: none;">
    	<div class="form-group field-conditionalrange-fixed_range">
		<label class="control-label" for="conditionalrange-fixed_range">Fixed Range</label>
		<input id="conditionalrange-fixed_range" class="form-control" name="ConditionalRange[fixed_range][]" type="text">

		</div>    	 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-amount_type">
		<label class="control-label" for="conditionalrange-amount_type">Amount Type</label>
		<select id="conditionalrange-amount_type" class="form-control" name="ConditionalRange[amount_type][]">
		<option value="">Select.....</option>
		<option value="Fixed">Fixed</option>
		<option value="Percentage">Percentage</option>
		</select>

		</div>    	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-amount">
		<label class="control-label" for="conditionalrange-amount">Amount</label>
		<input id="conditionalrange-amount" class="form-control" name="ConditionalRange[amount][]" type="text">

		</div>
    </div>

	<div class="conditional_range_to row" style="display: none;">
    	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-from_range">
		<label class="control-label" for="conditionalrange-from_range">From Range</label>
		<input id="conditionalrange-from_range" class="form-control" name="ConditionalRange[from_range][]" type="text">

		</div>    	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-to_range">
		<label class="control-label" for="conditionalrange-to_range">To Range</label>
		<input id="conditionalrange-to_range" class="form-control" name="ConditionalRange[to_range][]" type="text">

		</div>    	 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-amount_type">
		<label class="control-label" for="conditionalrange-amount_type">Amount Type</label>
		<select id="conditionalrange-amount_type" class="form-control" name="ConditionalRange[amount_type][]">
		<option value="">Select.....</option>
		<option value="Fixed">Fixed</option>
		<option value="Percentage">Percentage</option>
		</select>

		</div>    	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 field-conditionalrange-amount">
		<label class="control-label" for="conditionalrange-amount">Amount</label>
		<input id="conditionalrange-amount" class="form-control" name="ConditionalRange[amount][]" type="text">

		</div> 	
    </div>
<script type="text/javascript">
    $(document).ready(function () {
	    $(".fixed_range_val").hide();
	    $(".conditional_range_to").hide();
	    $("#merchants").hide();
	    $(".dynamic-form").hide();
	    $("#add_range").hide();
	     var val = $("#conditionalcharge-charge_range").val();
          var ht = "<div></div>";
        if (val=="Range") {
        	$("#add_range").show();
            $(".fixed_range_div").html(ht);
        	$(".dynamic-form").show();
        }
        else if(val=="Fixed"){
        	var fxht = $(".fixed_range_val").html();
        	 $(".fixed_range_div").html(fxht);
        	$("section").remove();
        	$("#add_range").hide();
        }

		
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
                $(".dynamic-form").show();
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
        	$("section").html(ht);
        	$("#add_range").hide();
        }
   });
   /* $( "#target" ).submit(function( event ) {
  alert( "Handler for .submit() called." );
});*/
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