<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OnbuyShopDetails */

$this->title = 'Create Onbuy Shop Details';
$this->params['breadcrumbs'][] = ['label' => 'Onbuy Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="onbuy-shop-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
