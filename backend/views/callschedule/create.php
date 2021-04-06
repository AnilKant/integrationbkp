<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Callschedule */

$this->title = 'Create Callschedule';
$this->params['breadcrumbs'][] = ['label' => 'Callschedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="callschedule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
