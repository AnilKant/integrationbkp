<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\JetMerchantsHelp */

$this->title = 'Create Jet Merchants Help';
$this->params['breadcrumbs'][] = ['label' => 'Jet Merchants Helps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-merchants-help-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
