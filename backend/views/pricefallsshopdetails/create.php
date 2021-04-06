<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\fruugo\models\Pricefallsshopdetails */

$this->title = 'Create Pricefallsshopdetails';
$this->params['breadcrumbs'][] = ['label' => 'Pricefallsshopdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pricefallsshopdetails-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
