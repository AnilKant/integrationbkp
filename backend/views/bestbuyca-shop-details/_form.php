<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BestbuycaShopDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bestbuyca-shop-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'install_date')->textInput(['readonly'=> !$model->isNewRecord]) ?>

     <?= $form->field($model, 'uninstall_date')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'maximum_prod_limit')->textInput() ?>

    <?= $form->field($model, 'seller_username')->textInput() ?>

    <?= $form->field($model, 'seller_password')->textInput() ?>


       <?= $form->field($model, 'purchase_status')->dropDownList(["1"=>"Not Purchased","2"=>"Purchased","3"=>"Trial Expired","4"=>"License Expired"],['prompt'=>'Select option']) ?>

    <?= $form->field($model, 'expire_date')->widget(
        \dosamigos\datepicker\DatePicker::className(), [

            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn'=>true,
            ]
    ]);?>

    <?= $form->field($model, 'import_renew_till')->widget(
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
