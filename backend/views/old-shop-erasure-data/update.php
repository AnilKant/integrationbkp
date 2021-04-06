<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\etsy\models\ShopErasureData */

$this->title = 'Update Shop Erasure Data: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shop Erasure Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shop-erasure-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
