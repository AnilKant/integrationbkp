<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AppsFaq */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(
    '//cdn.ckeditor.com/4.6.2/standard/ckeditor.js',
    ['depends' => [\yii\web\JqueryAsset::className()], 'position' => View::POS_HEAD]
);

$list= [
        "Bonanza"=>"Bonanza",
        "Sears"=>"Sears",
        "Fruugo"=>"Fruugo",
        "Tophatter"=>"Tophatter",
        "Pricefalls"=>"Pricefalls",
        "etsy"=>"etsy",
        "Wish"=>"Wish",
        "bestbuyca"=>"Bestbuyca",
        "catchmp"=>"Catch",
        "onbuy"=>"Onbuy",
        "Walmartca"=>"Walmart Ca",
        "rakutenus"=>"Rakuten Marketplace",
        "Pricing"=>"Plicing Plan",
    ];
$listdata = ["Enabled" => "Enabled", "Disabled" => "Disabled"];
$faqType = ["APPS FAQ" => "APPS FAQ", "MP FAQ" => "MP FAQ"];
$faqCategory = ["PRODUCT" => "PRODUCT", "ORDER" => "ORDER","LISTING"=>"LISTING"];
?>

<div class="apps-faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'marketplace')->dropDownList($list, ['prompt' => 'Choose Marketplace']); ?>

    <?= $form->field($model, 'faq_type')->dropDownList($faqType, ['prompt' => 'Choose FAQ Type']); ?>
    
    <?= $form->field($model, 'faq_category')->dropDownList($faqCategory, ['prompt' => 'Choose FAQ Category']); ?>

    <?= $form->field($model, 'query')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_enabled')->dropDownList($listdata, ['prompt' => 'Select Status']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['onclick' => 'beforeSubmit()', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    var editor_short_description = CKEDITOR.replace('AppsFaq[description]');

    function beforeSubmit() {
        $('#appsfaq-description').val(editor_short_description.getData());
        return true;
    }

</script>