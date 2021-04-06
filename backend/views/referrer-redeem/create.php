<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ReferrerRedeemRequests */

$this->title = 'Create Referrer Redeem Requests';
$this->params['breadcrumbs'][] = ['label' => 'Referrer Redeem Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referrer-redeem-requests-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
