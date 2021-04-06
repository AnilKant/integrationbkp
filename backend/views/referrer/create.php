<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ReferrerUser */

$this->title = 'Create Referrer User';
$this->params['breadcrumbs'][] = ['label' => 'Referrer Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referrer-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
