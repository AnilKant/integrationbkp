<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MagentoFruugoInfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magento-fruugo-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'domain') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'live_sku') ?>

    <?= $form->field($model, 'uploaded_sku') ?>

    <?php // echo $form->field($model, 'total_revenue') ?>

    <?php // echo $form->field($model, 'config_set') ?>

    <?php // echo $form->field($model, 'install_on') ?>

    <?php // echo $form->field($model, 'framework') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
