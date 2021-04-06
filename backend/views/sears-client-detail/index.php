<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\sears\models\SearsRegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sears Client Detail';
$this->params['breadcrumbs'][] = $this->title;

$timeZoneOptions = [
    'Pacific Time' => 'Pacific Time',
    'Mountain Time' => 'Mountain Time',
    'Central Time' => 'Central Time',
    'Eastern Time' => 'Eastern Time',
    'Hawaii-Aleutian Time' => 'Hawaii-Aleutian Time',
    'Alaska Time' => 'Alaska Time',
    'Other' => 'Other'
];

$timeslot = [
    '0-2' => '0-2',
    '2-4' => '2-4',
    '4-6' => '4-6',
    '6-8' => '6-8',
    '8-10' => '8-10',
    '10-12' => '10-12',
];
$time_format = [
    'AM' => 'AM',
    'PM' => 'PM'
];

$mps = [
    'Amazon' => 'Amazon',
    'Jet' => 'Jet',
    'Walmart' => 'Walmart',
    'Sears' => 'Sears',
    'Tophatter' => 'Tophatter',
    'Bestbuy' => 'Bestbuy',
    'Wish' => 'Wish',
    'Pricefalls' => 'Pricefalls',
    'Newegg' => 'Newegg',
    'Groupon' => 'Groupon',
    'Overstock' => 'Overstock',
    'Etsy' => 'Etsy',
    'EBay' => 'EBay',
    'Rakuten' => 'Rakuten',
    'None' => 'None'
];
$referenceOpt = ['Shopify App Store' => 'Shopify App Store', 'Facebook' => 'Facebook', 'Google' => 'Google', 'Yahoo' => 'Yahoo', 'LinkedIn' => 'LinkedIn', 'YouTube' => 'YouTube', 'quora' => 'quora', 'Shopify Forum' => 'Shopify Forum', 'Cedcommerce Blog' => 'Cedcommerce Blog', 'whatech.com' => 'whatech.com', 'Other' => 'Other'];

?>
<div class="sears-registration-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'merchant_id',
            'name',
            'mobile',
            [
                'label' => 'Time Zone',
                'attribute' => 'time_zone',
                'filter' => $timeZoneOptions,
            ],
            [
                'label' => 'Time Slot',
                'attribute' => 'time_slot',
                'filter' => $timeslot,
            ],

            [
                'label' => 'Already selling',
                'attribute' => 'selling_on_sears',
                'filter' => ['yes'=>'yes','no'=>'no'],
            ],

            'selling_on_sears_source',

            [
                'label' => 'Other MPS',
                'attribute' => 'other_mps',
                'filter' => $mps,
            ],
            [
                'label' => 'Reference',
                'attribute' => 'reference',
                'filter' => $referenceOpt,
            ],
            'other_reference',
            ['class' => 'yii\grid\ActionColumn'],


        ],
    ]); ?>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".glyphicon-pencil").hide();
        $(".glyphicon-trash").hide();
    });
</script>
