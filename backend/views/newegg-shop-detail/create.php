<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\NeweggShopDetail */

$this->title = 'Create Newegg Shop Detail';
$this->params['breadcrumbs'][] = ['label' => 'Newegg Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newegg-shop-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
