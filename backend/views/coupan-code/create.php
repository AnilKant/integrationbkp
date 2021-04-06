<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CoupanCode */

$this->title = 'Create Coupan Code';
$this->params['breadcrumbs'][] = ['label' => 'Coupan Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupan-code-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
