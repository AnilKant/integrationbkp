<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartFaq */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(
    '@web/js/jquery.wysiwygEditor.js',
    ['depends' => [\yii\web\JqueryAsset::className()],'position'=>View::POS_HEAD]
);
$this->registerCssFile("@web/css/jquery.wysiwygEditor.css");
?>

<div class="walmart-faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'query')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6,'class' => 'wysiwyg-editor form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $('.wysiwyg-editor').wysiwygEditor();
</script>