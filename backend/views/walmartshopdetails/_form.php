<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\WalmartExtensionDetail;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartShopDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="walmart-shop-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_url')->textInput(['readonly'=> !$model->isNewRecord])?>

    <?= $form->field($model, 'shop_name')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true])?>

    <?= $form->field($model, 'seller_username')->textInput(['maxlength' => true]) ?>        
        
    <?= $form->field($model, 'seller_password')->textInput(['maxlength' => true]) ?> 

    <?= $form->field($model->walmartExtensionDetail, 'install_date')->textInput() ?>

    <?= $form->field($model->walmartExtensionDetail, 'expire_date')->textInput() ?>

    <?php $listdata= array("Purchased"=>"Purchased","Not Purchase"=>"Not Purchase",
        "License Expired"=>"License Expired","Trial Expired"=>"Trial Expired");    ?>

    <?= $form->field($model->walmartExtensionDetail, 'status')->dropDownList($listdata,['prompt'=>'Select Status']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('<span> Update Order Start</span>',['/walmartshopdetails/update-order-start', 'id' => $model->merchant_id],['title' => 'Update Order Start Value']);?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
