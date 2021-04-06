<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\GrouponShopDetails */

$this->title = 'Create Groupon Shop Details';
$this->params['breadcrumbs'][] = ['label' => 'Groupon Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="groupon-shop-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
