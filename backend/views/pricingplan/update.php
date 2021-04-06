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

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pricing Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!--  <div class="form-group field-pricingplan-duration-0 has-success">
<label class="control-label" for="pricingplan-duration-0">Duration</label>
<select id="pricingplan-duration-0" class="form-control" name="PricingPlan[duration][0]">
<option value="">Select Duration</option>
<option value="Day">Day</option>
<option value="Week">Week</option>
<option value="Month">Month</option>
<option value="Year">Year</option>
<option value="Unlimited">Unlimited</option>
</select>

<div class="help-block"></div>
</div> -->

<div class="pricing-plan-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?= $form->field($model, 'plan_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plan_type')->dropDownList(array("Free" => "Free","Paid" => "Paid"),['prompt'=>'Select Plan Type']) ?>
    <?php $duration = explode(" ",$model->duration); 
    	if ($duration[0]=="Unlimited") {
    		$duration[1] = $duration[0];
    		$duration[0] = "";
    	}
    ?> 

    <div class="form-group field-pricingplan-duration-0 has-success">
		<label class="control-label" for="pricingplan-duration-0">Duration</label>
		<select id="pricingplan-duration-0" class="form-control" name="PricingPlan[duration][0]">
		<option value="">Select Duration</option>
		<?php 
		if($duration[1]=="Days"){     ?>
		<option value="Days" selected="">Days</option>
		<?php }else{
		?>
		<option value="Days" selected="">Days</option>
		<?php }
		if($duration[1]=="Weeks"){     ?>
		<option value="Weeks" selected="">Weeks</option>
		<?php }else{
		?>
		<option value="Weeks">Weeks</option>
		<?php }
		if($duration[1]=="Months"){     ?>
		<option value="Months" selected="">Months</option>
		<?php }else{
		?>
		<option value="Months">Months</option>
		<?php }
		if($duration[1]=="Years"){     ?>
		<option value="Years" selected="">Years</option>
		<?php }else{
		?>
		<option value="Years">Years</option>
		<?php }
		if($duration[1]=="Unlimited"){     ?>
		<option value="Unlimited" selected="">Unlimited</option>
		<?php }else{
		?>
		<option value="Unlimited" >Unlimited</option>

		<?php } ?>

		</select>

	<div class="help-block"></div>
	</div>

	<div class="duration">
	        <div class="form-group field-pricingplan-duration-1">
		<label class="control-label" for="pricingplan-duration-1">Duration count</label>
		<input id="pricingplan-duration-1" class="form-control" name="PricingPlan[duration][1]" type="text" value="<?= $duration[0]; ?>">

		<div class="help-block"></div>
		</div>    
	</div>

    <?= $form->field($model, 'plan_status')->dropDownList(array("Enable" => "Enable","Disable" => "Disable"),['prompt'=>'Select Status']) ?>

    <?= $form->field($model, 'base_price')->textInput() ?>

    <?= $form->field($model, 'special_price')->textInput() ?>
    <?= $form->field($model, 'trial_period')->textInput()->hint("Enter number of days...") ?>
    <?= $form->field($model, 'capped_amount')->textInput()->hint('It is maximum amount that can be charged...') ?>
    <?= $form->field($model, 'apply_on')->dropDownList(array("All" => "All","Jet" => "Jet","Walmart" => "Walmart","Newegg" => "Newegg","Sears" => "Sears"),['prompt' => 'Select apply on', 'multiple' => true, 'selected' => 'selected']) ?>
           
    <?= $form->field($model, 'additional_condition')->dropDownList( ArrayHelper::map(ConditionalCharge::find()->all(),'id','charge_name'), ['prompt' => 'Select conditions', 'multiple' => true, 'selected' => 'selected']);?>
   
        <br><br>
        <?= $form->field($model, 'feature')->textarea()->label('Feature'); ?>
    

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    $(document).ready(function () {
    	var duration = '<?= $duration[1]; ?>'; 
    	if (duration=="Unlimited") {
    		$(".duration").hide();
    	}
        
            var editor = new nicEditor({buttonList: ['bold', 'italic', 'underline', 'left', 'center', 'right', 'justify', 'ol', 'ul', 'subscript', 'superscript', 'strikethrough', 'removeformat', 'indent', 'outdent', 'hr', 'image', 'forecolor', 'bgcolor', 'link', 'unlink', 'fontSize', 'fontFamily', 'fontFormat', 'xhtml']/*fullPanel : true*/}).panelInstance('pricingplan-feature');
        
    });
    $("button").click(function(){
        
        var nicInstance = nicEditors.findEditor('pricingplan-feature');
        var description = nicInstance.getContent();
        //alert(description);
        $("textarea").html(description);

    });
    $("#pricingplan-duration-0").change(function(){
        $(".duration").show();
    });
</script>