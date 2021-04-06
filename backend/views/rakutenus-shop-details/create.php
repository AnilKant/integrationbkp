<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\RakutenusShopDetails */

$this->title = 'Create Rakutenus Shop Details';
$this->params['breadcrumbs'][] = ['label' => 'Rakutenus Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rakutenus-shop-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
