<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WalmartcaShopDetails */

$this->title = 'Create Walmartca Shop Details';
$this->params['breadcrumbs'][] = ['label' => 'Walmartca Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="walmartca-shop-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
