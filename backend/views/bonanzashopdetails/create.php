<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\fruugo\models\bonanzashopdetails */

$this->title = 'Create bonanzashopdetails';
$this->params['breadcrumbs'][] = ['label' => 'bonanzashopdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonanzashopdetails-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
