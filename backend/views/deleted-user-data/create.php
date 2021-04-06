<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DeletedUserData */

$this->title = 'Create Deleted User Data';
$this->params['breadcrumbs'][] = ['label' => 'Deleted User Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deleted-user-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
