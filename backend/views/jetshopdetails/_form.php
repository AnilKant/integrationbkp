<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\JetShopDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jet-shop-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'shop_url')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'shop_name')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'country_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seller_username')->textInput(['maxlength' => true]) ?>        
        
    <?= $form->field($model, 'seller_password')->textInput(['maxlength' => true]) ?> 
    <?= $form->field($model, 'prod_import_limit')->textInput(['maxlength' => true]) ?> 

    <?= $form->field($model, 'installed_on')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'purchased_on')->textInput() ?>

    <?= $form->field($model, 'expired_on')->textInput() ?>
    
    <?php $list= ["1"=>"Installed","0"=>"Uninstalled"]; ?>
    <?= $form->field($model, 'install_status')->dropDownList($list,['prompt'=>'Is Installed']);?>

    <?php $listdata= array("Purchased"=>"Purchased","Not Purchase"=>"Not Purchase",
        "License Expired"=>"License Expired","Trial Expired"=>"Trial Expired");    ?>
    <?= $form->field($model, 'purchase_status')->dropDownList($listdata,['prompt'=>'Select Status']);?>

    <?php $list= array("yes"=>"Yes","no"=>"No"); ?>
    <?= $form->field($model, 'sendmail')->dropDownList($list,['prompt'=>'Is Send']);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
