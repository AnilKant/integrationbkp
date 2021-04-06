<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartShopDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="walmart-shop-details-form">

    <?php $form = ActiveForm::begin(); ?>
     <?= $form->field($model, 'merchant_id')->textInput(['readonly'=> !$model->isNewRecord])?>

    <?= $form->field($model, 'shop_url')->textInput(['readonly'=> !$model->isNewRecord])?>

    <?= $form->field($model, 'shop_name')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'email')->textInput(['readonly'=> !$model->isNewRecord])?> 

    <?= $form->field($model, 'install_date')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'expire_date')->textInput() ?>

    <?= $form->field($model, 'allowed_sku')->textInput() ?>

    

    <?php $listdata= array("Purchased"=>"Purchased","Not Purchase"=>"Not Purchase",
        "License Expired"=>"License Expired","Trial Expired"=>"Trial Expired");    ?>

    <?= $form->field($model, 'purchase_status')->dropDownList($listdata,['prompt'=>'Select Status']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
