<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\bonanza\models\Bonanzashopdetails */
/* @var $form yii\widgets\ActiveForm */

$list = ["1" => "Installed", "0" => "Uninstalled"];
$cronStatus = ["0" => "No","1" => "Yes"];
$listdata = array("Purchased" => "Purchased", "Not Purchase" => "Not Purchase",
    "License Expired" => "License Expired", "Trial Expired" => "Trial Expired");

$sql = "SELECT `email` FROM merchant WHERE `merchant_id` = $model->merchant_id";
$query = \backend\components\Data::IntegrationsqlRecords($sql,'one','select');
?>

<div class="tophattershopdetails-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'merchant_id')->textInput(['readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'seller_username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'seller_password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'installed_on')->textInput(['readonly' => !$model->isNewRecord]) ?>
    <?= $form->field($model, 'expired_on')->textInput() ?>
    <?= $form->field($model, 'uninstall_date')->textInput() ?>

    <?= $form->field($model, 'prod_import_limit')->textInput() ?>
	<?= $form->field($model, 'limit_renew_till')->widget(
		\dosamigos\datepicker\DatePicker::className(), [
		
		'clientOptions' => [
			'autoclose' => true,
			'format' => 'yyyy-mm-dd',
			'todayBtn'=>true,
		]
	]);?>

    <label>Email </label>
    <input type="email" class="form-control" value="<?= $query['email'] ?>" name="merchant[email]">
    <?= $form->field($model, 'install_status')->dropDownList($list, ['prompt' => 'Is Installed']); ?>

    <?= $form->field($model, 'purchase_status')->dropDownList($listdata, ['prompt' => 'Select Purchase Status']); ?>
    <?= $form->field($model, 'disable_cron')->dropDownList($cronStatus, ['prompt' => 'Stop cron work']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>