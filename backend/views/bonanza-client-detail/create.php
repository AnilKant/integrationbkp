<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\bonanza\models\bonanzaRegistration */

$this->title = 'Create Bonanza Registration';
$this->params['breadcrumbs'][] = ['label' => 'bonanza Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonanza-registration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
