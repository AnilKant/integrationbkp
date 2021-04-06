<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\components\Data;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$calls = Data::sqlRecords('SELECT COUNT( `id` ) callnumber FROM `call_schedule` WHERE `status`="Pending"','one','select');
$pendingCalls = isset($calls['callnumber'])?$calls['callnumber']:0;
/*if($_SERVER['REMOTE_ADDR'] !== '103.97.184.162'){
    header('Location: https://apps.cedcommerce.com/');
}*/
?>
<?php $this->beginPage() ?>

    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Custom CSS -->
        <link href="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/css/sb-admin.css" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/css/morris.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    </head>

    <body>
    <?php $this->beginBody() ?>
    <div id="wrapper">
        <div id="LoadingMSG" style="display: none;" class="overlay">
            <!-- <img src="/images/loading-large.gif"> -->
            <div id="fountainG">
                <div class="fountainG" id="fountainG_1"></div>
                <div class="fountainG" id="fountainG_2"></div>
                <div class="fountainG" id="fountainG_3"></div>
                <div class="fountainG" id="fountainG_4"></div>
                <div class="fountainG" id="fountainG_5"></div>
                <div class="fountainG" id="fountainG_6"></div>
                <div class="fountainG" id="fountainG_7"></div>
                <div class="fountainG" id="fountainG_8"></div>
            </div>
        </div>
        <!-- Navigation -->
        <nav class="ced-navbar-top navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">

                <?php
                NavBar::begin([
                    'brandLabel' => '<img src='.Yii::$app->request->baseUrl.'/images/logo.png height="180%" >',
                    'brandUrl' => Yii::$app->request->baseUrl,
                    'options' => [
                        'class' => 'navbar-inverse navbar-fixed-top',
                    ],
                ]);
                // $menuItems = [
                //     ['label' => 'Home', 'url' => ['site/index']],
                // ];

                if (\Yii::$app->user->isGuest) {

                    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
                } else {
                    $menuItems[] = ['label' => 'Pricing', 
                        'items' => [
                            ['label'=>'Custom Plan','url'=>['/payment/app-custom-payment/index']],
                            ['label'=>'Pricing Plan','url'=>['/payment/app-pricing-plan/index']],
                            ['label'=>'Pricing Features','url'=>['/payment/pricing-features/index']],
                            ['label'=>'Combo Plan','url'=>['/payment/combo-pricing-plan/index']],
                            ['label'=>'Combo Request Clients','url'=>['/payment/combo-payment/index']],
                        ], 
                    ];
                    $menuItems[] = ['label' => 'Notifications', 'url' => ['/common-notification/index']];
                    $menuItems[] = ['label' => 'Calling', 'url' => ['callschedule/index']];
                    $menuItems[] = ['label' => 'Failed Orders', 'url' => ['/order-report/index']];
                    $menuItems[] = ['label' => 'Add FAQs', 
                                        'items' => [
                                            ['label'=>'jet','url'=>['jet-faq/index']],
                                            ['label'=>'walmart','url'=>['walmart-faq/index']],
                                            ['label'=>'newegg','url'=>['newegg-faq/index']],
                                            ['label'=>'sears','url'=>['sears-faq/index']],
                                            ['label'=>'new-apps','url'=>['apps-faq/index']],
                                           /* ['label'=>'etsy-apps','url'=>['etsy-faq/index']],*/
                                            ['label'=>'walmartca-app','url'=>['walmartca-faqs/index']]
                                        ],
                                            
                                    ];
                    $menuItems[] = ['label' => 'Latest Updates',
                        'items' => [
                            ['label'=>'New Apps','url'=>['apps-latest-updates/index']],
                            ['label'=>'Old Apps','url'=>['latest-updates/index']],
                        ],
                    ];
                    $menuItems[] = ['label' => 'Error Info NewApps',
                        'items' => [
                            ['label'=>'Notification','url'=>['newapps-error-notification/index']],
                            ['label'=>'Description','url'=>['newapps-error-description/index']],
                        ],
                    ];
                    $menuItems[] = ['label' => 'Export CSV', 
                                        'items' => [
                                            ['label'=>'Jet','url'=>['/jetshopdetails/export']],
                                            ['label' =>'Walmart', 'url' => ['/walmartshopdetails/export']],
                                            ['label'=>'Bonanza','url'=>['/site/exportbonuser']],
                                            ['label'=>'Newegg US','url'=>['/newegg-shop-detail/export']],
                                            ['label'=>'Newegg CAN','url'=>['/newegg-can-shop-detail/export']],
                                            ['label'=>'Etsy','url'=>['/etsy-shop-details/export']],
                                            ['label'=>'Fruugo','url'=>['/fruugo-shop-details/export']],
                                            ['label'=>'Wish','url'=>['/wishshopdetails/export']],
                                            ['label'=>'Export Merchant','url'=>['/export-merchant-details/index']]
                                        ],
                                   ];
                   $menuItems[] = ['label' => 'Emails',
                                         'items' => [
                                            ['label'=>'Templates','url'=>['/reports/jet-email-template/index']],
                                            ['label'=>'Reports','url'=>['reports/dashboard/index']]
                                        ],
                                   ];
                    $menuItems[] = [
                        'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ];
                }
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $menuItems,
                ]);
                NavBar::end();
                ?>
            </div>

            <?php if (!\Yii::$app->user->isGuest) {?>
                <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
                <div class="ced-navbar collapse navbar-collapse navbar-ex1-collapse">
                    <div class="search-wrapper">
                        <input id="searhApp" type="text" placeholder="Search App" class="form-control" onkeyup="searchApp()">
                    </div>
                    <ul id="appList" class="nav navbar-nav side-nav">

                       <!--  <li >
                            <a href="<?=Yii::$app->request->baseUrl;?>/site/index"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                        </li> -->
                       <!--  <li>
                            <a href="<?=Yii::$app->request->baseUrl;?>/order-report/index"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>Falied Orders</a>
                        </li> -->
                        <!-- <li>
                            <a href="<?=Yii::$app->request->baseUrl;?>/callschedule/index"><i class="fa fa-phone" aria-hidden="true"></i>Call Schedule
                            <span class="call_schedule_count"><?= $pendingCalls;?></span>
                            </a>
                        </li> -->

                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/jet.png' ?>">
                                <span class="app-name">Merchants</span>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/newappmerchantdetails/index">
                                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                                        <span>New Apps</span>
                                    </a>
                                </li>
                                    
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/oldappmerchantdetails/index"><i
                                                class="fa fa-money" aria-hidden="true"></i><span>Old Apps</span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/shop-erasure-data/index"><i
                                                class="fa fa-money" aria-hidden="true"></i><span>New Apps Deleted merchant</span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/old-shop-erasure-data/index"><i
                                                class="fa fa-money" aria-hidden="true"></i><span>Old Apps Deleted merchant</span></a>
                                </li>
                                                     

                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/jet.png' ?>">
                               <span class="app-name">Jet</span>
                               <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/jetshopdetails/index"><i class="fa fa-list-alt" aria-hidden="true"></i> <span>Jet Shop Details</span></a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/jet-recurring-payment/index"><i class="fa fa-money" aria-hidden="true"></i><span>Jet Payment Details</span></a>
                                </li>
                                 <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/jetclientdetails/index"><i class="fa fa-phone" aria-hidden="true"></i><span>Jet Client Details</span></a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/jetfileupload/index"><i class="fa fa-file" aria-hidden="true"></i><span> Jet File Uploads</span></a>
                               </li>
                            </ul>
                        </li>

                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/walmart.png' ?>">
                               <span class="app-name">Walmart</span>  <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                 <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/walmartshopdetails/index"><i class="fa fa-list-alt" aria-hidden="true"></i> Walmart Shop Details</a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/walmart-payment/index"><i class="fa fa-money" aria-hidden="true"></i> Walmart Payment Details</a>
                                </li>
                                 <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/walmart-client/index"><i class="fa fa-phone" aria-hidden="true"></i> Walmart Client Details</a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/walmartorderdetails/index"><i class="fa fa-bar-chart" aria-hidden="true"></i>Walmart Order Details</a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/walmart-error-description/index"><i class="fa fa-bar-chart" aria-hidden="true"></i>Walmart Error Description</a>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#"  class="test">Walmart Error Notification <span class="caret"></span></a>
                                   <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?=Yii::$app->request->baseUrl;?>/error-notification/index?inventory"><i class="fa fa-list-alt" aria-hidden="true"></i> Inventory Sync Error</a>
                                        </li>
                                        <li>
                                            <a href="<?=Yii::$app->request->baseUrl;?>/error-notification/index?product_delete"><i class="fa fa-list-alt" aria-hidden="true"></i> Product Delete Error</a>
                                        </li>
                                        <li>
                                            <a href="<?=Yii::$app->request->baseUrl;?>/error-notification/index?product_update"><i class="fa fa-list-alt" aria-hidden="true"></i> Product Update Error</a>
                                        </li>
                                        <li>
                                            <a href="<?=Yii::$app->request->baseUrl;?>/error-notification/index?product_create"><i class="fa fa-list-alt" aria-hidden="true"></i> Product Create Error</a>
                                        </li>
                                         <li>
                                            <a href="<?=Yii::$app->request->baseUrl;?>/error-notification/index?price_update"><i class="fa fa-list-alt" aria-hidden="true"></i> Price Update Error</a>
                                        </li>
                                        <li>
                                            <a href="<?=Yii::$app->request->baseUrl;?>/error-notification/index?sku_update"><i class="fa fa-list-alt" aria-hidden="true"></i> SKU Update Error</a>
                                        </li>
                                        <li>
                                            <a href="<?=Yii::$app->request->baseUrl;?>/error-notification/index?order_shipment"><i class="fa fa-list-alt" aria-hidden="true"></i> Order Shipment Error</a>
                                        </li>
                                        <li>
                                            <a href="<?=Yii::$app->request->baseUrl;?>/error-notification/index?marketplace_delete_error"><i class="fa fa-list-alt" aria-hidden="true"></i> Marketplace Product Delete</a>
                                        </li>
                                   </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/walmart.png' ?>">
                            <span class="app-name">Walmart Canada</span><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/walmartca-shop-details/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li> 
                            </ul>
                        </li>


                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/walmart.png' ?>">
                           <span class="app-name">Rakuten Marketplace</span><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/rakutenus-shop-details/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li> 
                            </ul>
                        </li>

                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/Rakuten-fr.png' ?>">
                           <span class="app-name">Rakuten France</span><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/rakutenfr-shop-details/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li> 
                            </ul>
                        </li>

                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/newegg.png' ?>">
                                <span class="app-name">Newegg</span><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/newegg-shop-detail/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/newegg-client-detail/index"><i class="fa fa-user" aria-hidden="true"></i>Client Details</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/newegg.png' ?>">
                                <span class="app-name"> Newegg Canada</span>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/newegg-can-shop-detail/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/newegg-can-client-detail/index"><i class="fa fa-user" aria-hidden="true"></i>Client Details</a>
                                </li>
                                
                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/sears.png' ?>">
                                <span class="app-name">Sears</span>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                 <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/searsextensiondetail/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/sears-client-detail/index"><i class="fa fa-user" aria-hidden="true"></i>Client Details</a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/sears-recurring-payment/index"><i class="fa fa-money" aria-hidden="true"></i>Payment Details</a>
                                </li>
                                
                            </ul>
                        </li>
                       
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/fruggo.png' ?>">
                                <span class="app-name">Fruugo</span>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                 <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/fruugo-shop-details/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li>
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/fruugo-client-detail/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Client Details</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                            <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/tophatter.png' ?>">
                            <span class="app-name">Tophatter</span>
                            <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/tophattershopdetails/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li>
                                
                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                            <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/bonanza.png' ?>">
                            <span class="app-name">Bonanza</span>
                            <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/bonanzashopdetails/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/bonanza-payment/index"><i class="fa fa-money" aria-hidden="true"></i>Payment Details</a>
                                </li>
                                    
                            </ul>
                        </li>

                        <!-- <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/pricefalls.png' ?>">
                                <span class="app-name">Pricefalls</span>
                               <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/pricefallsshopdetails/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li> 
                                
                            </ul>
                        </li> -->
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
                            <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/bestbuy.png' ?>">
                            <span class="app-name">Bestbuy Canada</span>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/bestbuyca-shop-details/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/catch.png' ?>">
                                 <span class="app-name">Catch</span><span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a  href="<?=Yii::$app->request->baseUrl;?>/catch-shop-details/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                    </li>
                                <!-- <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/etsy-payment/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Payment Details</a>
                                </li> -->
                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/reverb.png' ?>">
                                 <span class="app-name">Reverb</span><span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a  href="<?=Yii::$app->request->baseUrl;?>/reverb-shop-details/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                    </li>
                                <!-- <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/etsy-payment/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Payment Details</a>
                                </li> -->
                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/kit.png' ?>">
                               <span class="app-name">Kit</span>  <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-submenu">
                                    <a href="#"  class="test">Old Apps <span class="caret"></span></a>
                                   <ul class="dropdown-menu">
                                        <li>
                                           <a href="<?=Yii::$app->request->baseUrl;?>/kit-skill-conversation/index?app=old">Skill Conversation</a>
                                       </li>
                                       <li>
                                           <a href="<?=Yii::$app->request->baseUrl;?>/kit-trigger-failed-response/index?app=old">Failed Skills</a>
                                       </li>
                                   </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#"  class="test">New Apps<span class="caret"></span></a>
                                   <ul class="dropdown-menu">
                                        <li>
                                           <a href="<?=Yii::$app->request->baseUrl;?>/kit-skill-conversation/index?app=new">Skill Conversation</a>
                                       </li>
                                       <li>
                                           <a href="<?=Yii::$app->request->baseUrl;?>/kit-trigger-failed-response/index?app=new">Failed Skills</a>
                                       </li>
                                   </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                <img src="<?/*= Yii::$app->request->baseUrl . '/images/sidebar/kit.png' */?>">
                                Kit<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a  href="<?=Yii::$app->request->baseUrl;?>/kit-skill-conversation/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Kit Conversation</a>
                                    </li>
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/kit-trigger-failed-response/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Failed Skills</a>
                                </li>
                            </ul>
                        </li> -->
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/wish.png' ?>">
                                    <span class="app-name">Wish</span>
                                   <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/wishshopdetails/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="https://proxy.duckduckgo.com/iu/?u=https%3A%2F%2Ftse3.mm.bing.net%2Fth%3Fid%3DOIP.GXlQNhz7DYk9isKVRsRvLAHaHa%26pid%3D15.1&f=1">
                                 <span class="app-name">Etsy</span>
                               <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/etsy-shop-details/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li>
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/etsy-payment/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Payment Details</a>
                                </li>
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/newapps-error-notification/index?per-page=25&NewappsErrorNotificationSearch[marketplace]=etsy"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>Webhook Errors</a>
                                </li>
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/order-report/totalorder?param=date&marketplace=etsy&value=<?=date('Y-m-d')?>"><i class="fa fa-list-alt" aria-hidden="true"></i>Daily Orders</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/groupon.png' ?>" alt="Groupon Shop Details">
                                     <span class="app-name">Groupon</span>
                                   <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/grouponshopdetails/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Shop Details</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown filter-data">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <img src="<?= Yii::$app->request->baseUrl . '/images/sidebar/magento.png' ?>">
                            <span class="app-name">Magento</span>
                            <span class="caret"></span></a>
                             <ul class="dropdown-menu">
                                <li>
                                    <a  href="<?=Yii::$app->request->baseUrl;?>/magentoextensiondetail/index"><i class="fa fa-user-circle" aria-hidden="true"></i>Clients</a>
                                </li>
                            </ul>
                        </li>
                        <li class="filter-data">
                            <a  href="<?=Yii::$app->request->baseUrl;?>/jetshopdetails/productvalidation"><i class="fa fa-check-circle" aria-hidden="true"></i>
                             <span class="app-name">Validate Details</span>
                             </a>
                        </li>
                        <li class="filter-data">
                            <a  href="<?=Yii::$app->request->baseUrl;?>/combo-payment/index"><i class="fa fa-check-circle" aria-hidden="true"></i>
                            <span class="app-name">Combo Payment</span>
                            </a>
                        </li>
	                    <li class="filter-data">
	                        <a href="<?=Yii::$app->request->baseUrl;?>/upcomingclients/index"><i class="fa fa-tasks" aria-hidden="true"></i>
                             <span class="app-name"> Clients Requests</span>
                           </a>
	                   </li>
                       
                    
                        <!-- referral -->
                        <li class="dropdown filter-data">
                            <a class="dropdown-toggle" href="#" aria-expanded="false" data-toggle="dropdown">
                                <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                  <span class="app-name">Referral</span>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/referrer/index"> <i class="fa fa-sign-in"></i>Referrers</a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/referral/index"><i class="fa fa-download"></i>Referral Installations</a>
                                </li>
                                <li>
                                    <a href="<?=Yii::$app->request->baseUrl;?>/referrer-redeem/index"> <i class="fa fa-share-square"></i>Redeem Requests</a>
                                </li>
                            </ul>
                        </li>
                </div>
            <?php } ?>
            <!-- /.navbar-collapse -->
        </nav>

        <div class="page-main">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <?php if (!\Yii::$app->user->isGuest && Yii::$app->controller->id.'/'.Yii::$app->controller->action->id == 'site/index') {?>
        <script src="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/js/raphael.min.js"></script>

    <?php }?>

    <?php $this->endBody() ?>
    <script type="text/javascript">
            /*Search filter for app*/
            function searchApp() {
              // Declare variables
              var input, filter, ul, li, val, i, txtValue;
              input = document.getElementById('searhApp');
              filter = input.value.toUpperCase();
              ul = document.getElementById("appList");
              li = ul.getElementsByClassName('filter-data');

              // Loop through all list items, and hide those who don't match the search query
              for (i = 0; i < li.length; i++) {
                val = li[i].getElementsByClassName("app-name")[0];
                txtValue = val.textContent || val.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                  li[i].style.display = "";
                } else {
                  li[i].style.display = "none";
                }
              }
            }
            /*Search filter for app end*/

        $(document).ready(function(){
            $(document).on('pjax:send', function() {
                $('#LoadingMSG').show();
                console.log('pjax send');
            })
            $(document).on('pjax:complete', function() {
                $('#LoadingMSG').hide()
                console.log('pjax complete');
            })
            $('.validate').each(function(){
                var value=$(this).html();
                if($.trim(value)=="Not Purchased" || $.trim(value)=="Expired")
                {
                    $(this).addClass("rejected");
                }
            });
        });
    </script>
    <script>
        $(document).ready(function(){
        $("#searhApp").focus();
          $('.dropdown-submenu a.test').on("click", function(e){
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
          });
        });
    
    </script>
   

    </body>

    </html>
<?php $this->endPage() ?>

<!-- <script>
   $(document).ready(function($) {
        $('.ced-navbar .side-nav li.dropdown a').click(function(event){
        event.stopPropagation();
    })
   });
</script> -->