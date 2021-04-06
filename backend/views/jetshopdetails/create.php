<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\JetShopDetails */

$this->title = 'Create Jet Shop Details';
$this->params['breadcrumbs'][] = ['label' => 'Jet Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-shop-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
