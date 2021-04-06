<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\NewappsErrorNotification;

/* @var $this yii\web\View */
/* @var $model backend\models\NewappsErrorDescription */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="error-description-form">

    <?php $form = ActiveForm::begin(); ?>

    <?
    $error_type_array = [
        NewappsErrorNotification::PRODUCT_ERROR => 'Product Error',
        NewappsErrorNotification::PRICE_ERROR => 'Price Error',
        NewappsErrorNotification::INVENTORY_ERROR => 'Inventory Error',
        NewappsErrorNotification::ORDER_ERROR => 'Order Error'
    ];
    $helper = new \common\components\IntegrationLoginHelper();
    $modules = $helper->getMarketplaceIntegrationModules();
    $mpFilterArray = [];
    foreach ($modules as $module=>$classInfo):
        $mpFilterArray[$module] = $module;
    endforeach;
    ?>
    <?= $form->field($model, 'marketplace')->dropDownList($mpFilterArray,['prompt'=>'Choose Marketplace']);?>
    <?= $form->field($model, 'error_type')->dropDownList($error_type_array,['prompt'=>'Choose Error Type']);?>

    <?= $form->field($model, 'error_code')->textInput(['placeholder' => "Enter exact error data coming from respective marketplace"]) ?>

    <?= $form->field($model, 'error_description')->textInput(['placeholder' => "Explaing meaning of above error code in few words"]) ?>

    <?= $form->field($model, 'error_solution')->textInput(['placeholder' => "Enter error solution in details"]) ?>

    <?= $form->field($model,'is_enable')->dropDownList([1=>'Enabled',0=>'Disabled'],['prompt'=>'Is solution active'])?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
