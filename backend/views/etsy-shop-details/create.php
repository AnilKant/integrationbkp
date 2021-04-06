<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\BestbuycaShopDetails */

$this->title = 'Create Etsy Shop Details';
$this->params['breadcrumbs'][] = ['label' => 'Etsy Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bestbuyca-shop-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
