<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UpcomingClients */

$this->title = 'Create Upcoming Clients';
$this->params['breadcrumbs'][] = ['label' => 'Upcoming Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upcoming-clients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
