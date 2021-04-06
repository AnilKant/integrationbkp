<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\KitSkillConversation */

$this->title = 'Create Kit Skill Conversation';
$this->params['breadcrumbs'][] = ['label' => 'Kit Skill Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-skill-conversation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
