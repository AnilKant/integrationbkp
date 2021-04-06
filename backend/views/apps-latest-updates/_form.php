<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\AppsFaq */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(
    '//cdn.ckeditor.com/4.6.2/standard/ckeditor.js',
    ['depends' => [\yii\web\JqueryAsset::className()],'position'=>View::POS_HEAD]
);

$list= ["All"=>"All","Bonanza"=>"Bonanza","Fruugo"=>"Fruugo","Tophatter"=>"Tophatter","Pricefalls"=>"Pricefalls","etsy"=>"Etsy","Wish"=>"wish","reverb"=>"Reverb","catch"=>"Catch","rakuten_fr"=>"Rakuten France","rakuten_us"=>"Rakuten US"];
$listdata= array("Enabled"=>"Enabled","Disabled"=>"Disabled");
$expire_days = [
    0=>'Today',
    1=>'Tomorrow',
    7=>'1 week',
    30=>'1 month',
];

$interval = date_diff(date_create($model->expired_in),date_create(date('Y-m-d')));
$model->expired_in = $interval->d;
?>

<div class="apps-faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'marketplace')->dropDownList($list,['prompt'=>'Choose Marketplace']);?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_enabled')->dropDownList($listdata,['prompt'=>'Select Status']);?>

    <?= $form->field($model, 'expired_in')->dropDownList($expire_days,[
        'prompt'=>'Select day to expire',
        'options' => [$model->expired_in => ['Selected'=>'selected']]
    ]);?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['onclick'=>'beforeSubmit()','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    //$(function() {
        var editor_short_description = CKEDITOR.replace('AppsLatestUpdates[description]');
   // });

    function beforeSubmit(){
        $('#appslatestupdates-description').val(editor_short_description.getData());
        return true;
    }

</script>