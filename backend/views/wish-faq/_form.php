<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/*$this->registerJsFile(
    '@web/js/nicEdit-latest.js',
    ['depends' => [\yii\web\JqueryAsset::className()],'position'=>View::POS_HEAD]
);*/
$this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/nicEdit.js'
);
?>
<div class="wish-faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'query')->textarea(['rows' => 6,'id'=>'querytextarea']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6,'id'=>'desctextarea']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

   <!-- <script type="text/javascript" src="https://js.nicedit.com/nicEdit-latest.js"></script>  -->
   <script type="text/javascript">
    $(document).ready(function (){

      var editor = new nicEditor({buttonList: ['bold', 'italic', 'underline', 'left', 'center', 'right', 'justify', 'ol', 'ul', 'subscript', 'superscript', 'strikethrough', 'removeformat', 'indent', 'outdent', 'hr', 'image', 'forecolor', 'bgcolor', 'link', 'unlink', 'fontSize', 'fontFamily', 'fontFormat', 'xhtml']/*fullPanel : true*/}).panelInstance('querytextarea');

      var descriptionEditor = new nicEditor({buttonList: ['bold', 'italic', 'underline', 'left', 'center', 'right', 'justify', 'ol', 'ul', 'subscript', 'superscript', 'strikethrough', 'removeformat', 'indent', 'outdent', 'hr', 'image', 'forecolor', 'bgcolor', 'link', 'unlink', 'fontSize', 'fontFamily', 'fontFormat', 'xhtml']/*fullPanel : true*/}).panelInstance('desctextarea');

      // new nicEditor().panelInstance('querytextarea');  
      // new nicEditor({fullPanel : true}).panelInstance('desctextarea');  
    });
       // bkLib.onDomLoaded(function() {
       
         /* var myNicEditor = new nicEditor();
          myNicEditor.setPanel('querytextarea');*/
          // nicEditors.allTextAreas() 
    // });
  </script>
