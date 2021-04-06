<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AppsFaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apps Faqs';
$this->params['breadcrumbs'][] = $this->title;
$list= [
        "Bonanza"=>"Bonanza",
        "Sears"=>"Sears",
        "Fruugo"=>"Fruugo",
        "Tophatter"=>"Tophatter",
        "Pricefalls"=>"Pricefalls",
        "Etsy"=>"Etsy",
        "Wish"=>"Wish",
        "bestbuyca"=>"Bestbuyca",
        "catchmp"=>"Catch",
        "onbuy"=>"Onbuy",
        "Walmartca"=>"Walmart Ca",
        "rakutenus"=>"Rakuten Marketplace",
        "Pricing"=>"Plicing Plan",
    ];
?>
<div class="apps-faq-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('New Faq', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=> 'marketplace',
                'label'=>'Marketplace',
                'headerOptions' => ['width' => '80'],
                'format' => 'html',
                'filter' => $list,
                'value'=> function($data){
                    return $data['marketplace'];
                }
            ],
            [
                'attribute'=> 'is_enabled',
                'label'=>'IS Enabled',
                'headerOptions' => ['width' => '80'],
                'format' => 'html',
                'filter' => ["Enabled"=>"Enabled","Disabled"=>"Disabled"],
                'value'=> function($data){
                    return $data['is_enabled'];
                }
            ],
            [
                'attribute'=> 'faq_type',
                'label'=>'FAQ Type',
                'headerOptions' => ['width' => '80'],
                'format' => 'html',
                'filter' => ["APPS FAQ"=>"APPS FAQ","MP FAQ"=>"MP FAQ"],
                'value'=> function($data){
                    return $data['faq_type'];
                }
            ],
            [
                'attribute'=> 'faq_category',
                'label'=>'FAQ Category',
                'headerOptions' => ['width' => '80'],
                'format' => 'html',
                'filter' => ["PRODUCT"=>"PRODUCT","ORDER"=>"ORDER","LISTING"=>"LISTING"],
                'value'=> function($data){
                    return $data['faq_category'];
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
            [
                'attribute'=> 'query',
                'label'=>'Question',
                'headerOptions' => ['width' => '250'],
                'format' => 'html',
                'value'=> function($data){
                    return Html::tag('div',$data['query'],['class'=>'panel-body']);
                }
            ],
            [
                'attribute'=> 'description',
                'label'=>'Possible solution',
                //'headerOptions' => ['width' => '250'],
                'format' => 'html',
                'value'=> function($data){
                    return Html::tag('div',$data['description'],['class'=>'panel-body']);
                }
            ],
        ],
    ]); ?>
</div>
