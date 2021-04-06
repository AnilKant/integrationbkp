<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\EtsyPayment */

$this->title = 'Create Etsy Payment';
$this->params['breadcrumbs'][] = ['label' => 'Etsy Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="etsy-payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
