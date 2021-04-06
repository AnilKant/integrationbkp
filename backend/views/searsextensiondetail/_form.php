<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SearsExtensionDetail */
/* @var $form yii\widgets\ActiveForm */
$sql = "SELECT `email`,`prod_import_limit` FROM `sears_shop_details` WHERE `merchant_id` = $model->merchant_id";
$query = \backend\components\Data::sqlRecords($sql,'one','select');
?>

<div class="sears-extension-detail-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'merchant_id')->textInput(['readonly'=> !$model->isNewRecord]) ?>

    <?= $form->field($model, 'install_date')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'expire_date')->textInput() ?>

    <label>Email </label>
    <input type="email" class="form-control" value="<?= $query['email'] ?>" name="merchant[email]">

    <label>Product Import Limit </label>
    <input type="text" class="form-control" value="<?= $query['prod_import_limit'] ?>" name="merchant[prod_import_limit]">	
    
    <?= $form->field($model, 'panel_username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'panel_password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uninstall_date')->textInput() ?>
    
    <?php $list= ["install"=>"Installed","uninstall"=>"Uninstalled"]; ?>
    <?= $form->field($model, 'app_status')->dropDownList($list,['prompt'=>'Is Installed']);?>

    <?php $listdata= array("Purchased"=>"Purchased","Not Purchase"=>"Not Purchase",
        "License Expired"=>"License Expired","Trial Expired"=>"Trial Expired");    ?>
    <?= $form->field($model, 'status')->dropDownList($listdata,['prompt'=>'Select Status']);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
