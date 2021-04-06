<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LatestUpdates */
/* @var $form yii\widgets\ActiveForm */

$annualRevenueOptions = [
                         '$10,000-$50,000'=>'$10,000-$50,000',
                         '$50,001-$100,000'=>'$50,001-$100,000',
                         '$100,001-$250,000'=>'$100,001-$250,000',
                         '$250,001-$500,000'=>'$250,001-$500,000',
                         '$500,001-$1 million'=>'$500,001-$1 million',
                         '$1 million-$2 million'=>'$1 million-$2 million',
                         'Over $2 million'=>'Over $2 million',
                         'Over $5 million'=>'Over $5 million',
                         'Over $10 million'=>'Over $10 million'
                         ];
?>

<div class="latest-updates-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fname', ['options'=>[],'inputOptions'=>['placeholder'=>'Enter Your First Name', 'class'=>'form-control']]) ?>
   <?= $form->field($model, 'lname', ['options'=>[],'inputOptions'=>['placeholder'=>'Enter Your Last Name', 'class'=>'form-control']]) ?>
   <?= $form->field($model, 'legal_company_name', ['options'=>[],'inputOptions'=>['placeholder'=>'Enter Your Legal Company Name', 'class'=>'form-control']]) ?>
   <?= $form->field($model, 'store_name', ['options'=>[],'inputOptions'=>['placeholder'=>'Enter Your DBA SELLER STORE NAME', 'class'=>'form-control']]) ?>
   <?= $form->field($model, 'mobile', ['options'=>['data-tooltipClass'=>'intro-jet-mobile'],'inputOptions'=>['placeholder'=>'Enter Your Mobile No.', 'class'=>'form-control']]) ?>
   <?= $form->field($model, 'email', ['options'=>[],'inputOptions'=>['placeholder'=>'xyz@example.com', 'class'=>'form-control']]) ?>
   <?= $form->field($model, 'annual_revenue', ['options'=>[]])->dropDownList($annualRevenueOptions,['prompt'=>'Choose...']); ?>
   <?= $form->field($model, 'website', ['options'=>[],'inputOptions'=>['placeholder'=>'http://example.com OR https://example.com', 'class'=>'form-control']]) ?>
   <?= $form->field($model, 'amazon_seller_url', ['options'=>[],'inputOptions'=>['placeholder'=>'Enter Your Amazon Seller Url', 'class'=>'form-control']]) ?>
   <span class="text-validator control-label">Please Enter 'N/A' OR 'Not Applicable' if you don't have Amazon Seller Url</span>
   <?= $form->field($model, 'position_in_company', ['options'=>[],'inputOptions'=>['placeholder'=>'Enter Job Title/Position in Company', 'class'=>'form-control']]) ?>
   <?= $form->field($model, 'product_count', ['options'=>[],'inputOptions'=>['placeholder'=>'Enter Estimated No of Sku\'s', 'class'=>'form-control']]) ?>
   <?= $form->field($model, 'company_address', ['options'=>[],'inputOptions'=>['placeholder'=>'Enter Your Company Address', 'class'=>'form-control']]) ?>

   <?= $form->field($model, 'country', ['options'=>[]])->dropDownList(['US'=>'US','Other'=>'Other than US'],['prompt'=>'Choose...']); ?>
   <?= $form->field($model, 'have_valid_tax', ['options'=>[]])->dropDownList(['yes'=>'Yes','no'=>'No'],['prompt'=>'Choose...'])->label('Do you have a Valid Tax Id and W9 Form?'); ?>
   <?= $form->field($model, 'usa_warehouse', ['options'=>[]])->dropDownList(['yes'=>'Yes','no'=>'No'],['prompt'=>'Choose...'])->label('Do you have a warehouse in USA?'); ?> 
   <?= $form->field($model, 'products_type_or_category', ['options'=>[],'inputOptions'=>['placeholder'=>'Enter Your Business Category/Type of Products', 'class'=>'form-control']]) ?>


   <?= $form->field($model, 'selling_on_walmart', ['options'=>[]])->dropDownList(['yes'=>'Yes','no'=>'No'],['prompt'=>'Choose...'])->label('Are you already selling on WalMart Marketplace?'); ?> 
   <?= $form->field($model, 'selling_on_walmart_source', ['options'=>[]])->dropDownList(['channel_partner'=>'Different Channel Partner','other'=>'Others'],['prompt'=>'Choose...'])->label('How do you integrate with WalMart Marketplace?'); ?>
   <?= $form->field($model, 'other_selling_source', ['options'=>[],'inputOptions'=>['class'=>'form-control']]); ?>

   <?= $form->field($model, 'contact_to_walmart', ['options'=>[]])->dropDownList(['yes'=>'Yes','no'=>'No'],['prompt'=>'Choose...'])->label('Have you contacted WalMart Marketplace before?'); ?>

   <?= $form->field($model, 'approved_by_walmart', ['options'=>[]])->dropDownList(['yes'=>'Yes','no'=>'No'],['prompt'=>'Choose...'])->label('Have you been approved by WalMart to sell on the Marketplace?'); ?>


   <?= $form->field($model, 'reference', ['options'=>[]])->dropDownList(['App Store'=>'App Store','Facebook'=>'Facebook','Google'=>'Google','Yahoo'=>'Yahoo','LinkedIn'=>'LinkedIn', 'YouTube'=>'YouTube', 'Other'=>'Other'],['prompt'=>'Choose...'])->label('How did you hear about us?'); ?>
   <?= $form->field($model, 'other_reference', ['options'=>[]])->textarea()->label("Description"); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
