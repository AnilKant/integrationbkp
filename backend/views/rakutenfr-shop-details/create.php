<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RakutenfrShopDetails */

$this->title = 'Create Rakutenfr Shop Details';
$this->params['breadcrumbs'][] = ['label' => 'Rakutenfr Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rakutenfr-shop-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
