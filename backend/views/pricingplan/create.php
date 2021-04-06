<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PricingPlan */

$this->title = Yii::t('app', 'Create Pricing Plan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pricing Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pricing-plan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
