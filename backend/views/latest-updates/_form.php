<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LatestUpdates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="latest-updates-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php //echo $form->field($model, 'marketplace')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'marketplace')->dropDownList(['all'=>'For all Marketplaces','jet' => 'Jet','walmart' => 'Walmart','sears' => 'Sears']) ?>

    <?php //echo $form->field($model, 'created_at')->textInput() ?>

    <?php //echo $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
