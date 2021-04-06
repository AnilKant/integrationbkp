<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\sears\models\SearsRegistration */

$this->title = 'Create Sears Registration';
$this->params['breadcrumbs'][] = ['label' => 'Sears Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sears-registration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
