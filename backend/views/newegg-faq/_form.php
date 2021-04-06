<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/nicEdit.js'
);
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

<div class="newegg-faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'query')->textarea(['rows' => 6, 'id'=>'querytextarea']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6,'id'=> 'desctextarea']) ?>

    <?=$form->field($model, 'marketplace')->dropDownList(["newegg_ca"=>"newegg_ca","newegg_us"=>"newegg_us"],[
        'multiple'=>'multiple',
        'id' => 'marketplace_select',
        'options' => [
             'selected' => true
        ]
    ])->label("Add Marketplace");?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    $(document).ready(function (){

      var editor = new nicEditor({buttonList: ['bold', 'italic', 'underline', 'left', 'center', 'right', 'justify', 'ol', 'ul', 'subscript', 'superscript', 'strikethrough', 'removeformat', 'indent', 'outdent', 'hr', 'image', 'forecolor', 'bgcolor', 'link', 'unlink', 'fontSize', 'fontFamily', 'fontFormat', 'xhtml']/*fullPanel : true*/}).panelInstance('querytextarea');

      var descriptionEditor = new nicEditor({buttonList: ['bold', 'italic', 'underline', 'left', 'center', 'right', 'justify', 'ol', 'ul', 'subscript', 'superscript', 'strikethrough', 'removeformat', 'indent', 'outdent', 'hr', 'image', 'forecolor', 'bgcolor', 'link', 'unlink', 'fontSize', 'fontFamily', 'fontFormat', 'xhtml']/*fullPanel : true*/}).panelInstance('desctextarea');
    });
    $("#marketplace_select").select2({
            closeOnSelect: false,
            placeholder: "Please select marketplace",
    });
  </script>