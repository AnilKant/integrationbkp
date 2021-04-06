<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BestbuycaShopDetails */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="etsy-shop-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'install_date')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'uninstall_date')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'do_not_contact')->dropDownList(["0"=>"no","1"=>"Yes"],['prompt'=>'Choose Option']) ?>
    
    <?= $form->field($model, 'is_digital_allowed')->dropDownList([0=>"No",1=>"Yes"],['prompt'=>'Supports digital items']) ?>

    <?= $form->field($model, 'purchase_status')->dropDownList(["1"=>"Trial","2"=>"Purchased","3"=>"Trial Expired","4"=>"License Expired"],['prompt'=>'Choose status']) ?>

    <?= $form->field($model, 'expire_date')->widget(
        \dosamigos\datepicker\DatePicker::className(), [

            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn'=>true,
            ]
    ]);?>

    <?= $form->field($model, 'product_count')->textInput() ?>

    <?= $form->field($model, 'order_count')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
