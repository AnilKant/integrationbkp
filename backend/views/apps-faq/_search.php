<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AppsFaqSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="apps-faq-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'marketplace') ?>

    <?= $form->field($model, 'faq_type') ?>
    
    <?= $form->field($model, 'faq_category') ?>

    <?= $form->field($model, 'query') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'is_enabled') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>