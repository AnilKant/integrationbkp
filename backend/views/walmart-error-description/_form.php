<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\modules\walmart\components\Product\ProductHelper;

$this->registerJsFile(
    Yii::$app->request->baseUrl . '/static/modules/walmart/assets/js/nicEdit.js',
    ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_HEAD]
);


/* @var $this yii\web\View */
/* @var $model backend\models\WalmartErrorDescription */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="error-description-form">
	<?php

		if($model->error_type == ProductHelper::PRODUCT_ERROR)
		{
            $model->error_type = "Product Error";
        }elseif($model->error_type == ProductHelper::PRICE_ERROR){
             $model->error_type = "Price Error";
        }elseif($model->error_type == ProductHelper::INVENTORY_ERROR){
             $model->error_type = "Inventory Error";
        }elseif($model->error_type == ProductHelper::RETIRE_ERROR){
             $model->error_type = "Product Retire Error";
        }elseif($model->error_type == ProductHelper::ORDER_ERROR){
            $model->error_type = "Order Error";
        }
        $error_type = [
        	'1' => "Product Error",
        	'2' => "Order Error",
        	'3' => "Inventory Error",
        	'4' => "Price Error",
        	'5' => "Product Retire Error"
        ];

	?>

    <?php $form = ActiveForm::begin(); ?>

    <?php
      	if(Yii::$app->controller->action->id == "update")
  		{ ?>

  		    <?= $form->field($model, 'error_type',['inputOptions' => ['class' => 'form-control', 'disabled' => 'disabled']])->textInput() ?>

  		    <?= $form->field($model, 'error_code',['inputOptions' => ['class' => 'form-control', 'disabled' => 'disabled']])->textInput() ?>

  		    <?= $form->field($model, 'error_description',['inputOptions' => ['class' => 'form-control','id' => 'error_description']])->textarea()->label("Error Description") ?>

          <?= $form->field($model, 'error_solution',['inputOptions' => ['class' => 'form-control','id' => 'error_solution']])->textarea()->label("Error Solution") ?>
  		<?php }else{?>
  		<?= $form->field($model, 'error_type')->dropDownList($error_type, ['prompt' => 'Choose...']) ?>

  		  <?= $form->field($model, 'error_code')->textInput() ?>

  		  <?= $form->field($model, 'error_description',['inputOptions' => ['class' => 'form-control','id' => 'error_description']])->textarea()->label("Error Description") ?>

        <?= $form->field($model, 'error_solution',['inputOptions' => ['class' => 'form-control','id' => 'error_solution']])->textarea()->label("Error Solution") ?>
  		<?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(document).ready(function () {
        var editor = new nicEditor({
            maxHeight: 100,
            buttonList: ['bold', 'italic', 'underline', 'left', 'center', 'right', 'justify', 'ol', 'ul', 'subscript', 'superscript', 'strikethrough', 'removeformat', 'indent', 'outdent', 'hr', 'image', 'forecolor', 'bgcolor', 'link', 'unlink', 'fontSize', 'fontFamily', 'fontFormat', 'xhtml']/*fullPanel : true*/
        }).panelInstance('error_description');

        var editor = new nicEditor({
            maxHeight: 100,
            buttonList: ['bold', 'italic', 'underline', 'left', 'center', 'right', 'justify', 'ol', 'ul', 'subscript', 'superscript', 'strikethrough', 'removeformat', 'indent', 'outdent', 'hr', 'image', 'forecolor', 'bgcolor', 'link', 'unlink', 'fontSize', 'fontFamily', 'fontFormat', 'xhtml']/*fullPanel : true*/
        }).panelInstance('error_solution');
    })
</script>
