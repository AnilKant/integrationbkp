<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CommonNotification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="common-notification-form">

    <?php $form = ActiveForm::begin(); ?>
     <?= $form->field($model, 'heading')->textInput() ?>

    <?= $form->field($model, 'html_content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'from_date')->widget(\dosamigos\datepicker\DatePicker::classname(), [
    // inline too, not bad
         'inline' => true, 
         // modify template for custom rendering
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
]) ?>

    <?= $form->field($model, 'to_date')->widget(\dosamigos\datepicker\DatePicker::classname(), [
   // inline too, not bad
         'inline' => true, 
         // modify template for custom rendering
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
]) ?>

    <?= $form->field($model, 'enable')->dropDownList(['no'=>'No','yes'=>'Yes'])?>

     <?= $form->field($model, 'marketplace')            
         ->dropDownList(['all'=>'ALL','jet'=>'JET','walmart'=>'WALMART','newegg'=>'NEWGG'],
         [
          'multiple'=>'multiple'              
         ]             
        )->label("marketplace");
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
