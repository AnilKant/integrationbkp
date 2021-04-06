<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ComboPayment */

$this->title = 'Create Combo Payment';
$this->params['breadcrumbs'][] = ['label' => 'Combo Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="combo-payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
