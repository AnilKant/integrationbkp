<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ReferralUser */

$this->title = 'Create Referral User';
$this->params['breadcrumbs'][] = ['label' => 'Referral Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referral-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
