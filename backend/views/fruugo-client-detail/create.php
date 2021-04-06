<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\fruugo\models\fruugoRegistration */

$this->title = 'Create fruugo Registration';
$this->params['breadcrumbs'][] = ['label' => 'fruugo Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fruugo-registration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
