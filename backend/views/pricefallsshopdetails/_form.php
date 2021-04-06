<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\fruugo\models\Pricefallsshopdetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pricefallsshopdetails-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput(['readonly'=> !$model->isNewRecord]) ?>


    <?= $form->field($model, 'install_date')->textInput(['readonly'=> !$model->isNewRecord]) ?>

   

    <?= $form->field($model, 'install_status')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'uninstall_date')->widget(
        \dosamigos\datepicker\DatePicker::className(), [

            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn'=>true,
            ]
    ]);?>

    <?= $form->field($model, 'purchase_status')->dropDownList(["0"=>"Not Purchased","1"=>"Purchased","2"=>"Trial Expired","3"=>"License Expired"],['prompt'=>'Select option']) ?>

    <?= $form->field($model, 'expire_date')->widget(
        \dosamigos\datepicker\DatePicker::className(), [

            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn'=>true,
            ]
    ]);?>
 

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
