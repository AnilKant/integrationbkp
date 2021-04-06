<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ConditionalCharge */

$this->title = Yii::t('app', 'Create Conditional Charge');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Conditional Charges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conditional-charge-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
