<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\LatestUpdates */

$this->title = 'Create Latest Updates';
$this->params['breadcrumbs'][] = ['label' => 'Latest Updates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="latest-updates-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
