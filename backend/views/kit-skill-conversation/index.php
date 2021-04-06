<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\KitSkillConversationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kit Skill Conversations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-skill-conversation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'app_name',
            'skill_name',
            'shop_name',
            'conversation_uid',
            'type',
            'response:ntext',
            [
                'attribute'=>'status',
                'value' => function($data)
                {
                    if($data->status==1)
                        return "Completed";
                    return "Pending";
                },
                'filter'=>array("0"=>"Pending","1"=>"Completed"),
            ],
            'created_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}{view}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
