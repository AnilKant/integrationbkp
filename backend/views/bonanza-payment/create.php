<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\BonanzaPayment */

$this->title = 'Create Bonanza Payment';
$this->params['breadcrumbs'][] = ['label' => 'Bonanza Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonanza-payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
