<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KitSkillConversation */

$this->title = 'Update Kit Skill Conversation: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kit Skill Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kit-skill-conversation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
