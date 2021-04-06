<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WishShopDetails */

$this->title = 'Create Wish Shop Details';
$this->params['breadcrumbs'][] = ['label' => 'Wish Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wish-shop-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
