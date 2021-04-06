<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\jet\models\JetRegistration */

$this->title = 'Update Jet Registration: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Jet Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jet-registration-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
