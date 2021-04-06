<?php
use backend\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */
/*unset($this->assetBundles['yii\bootstrap\BootstrapAsset']);
unset($this->assetBundles['yii\web\JqueryAsset']);
Yii::$app->assetManager->bundles = [
//'yii\bootstrap\BootstrapPluginAsset' => false,
'yii\bootstrap\BootstrapAsset' => false,
'yii\web\JqueryAsset' => false,
];*/
AppAsset::register($this);
//unset(Yii::$app->assetManager->bundles['yii\web\JqueryAsset']); 
unset($this->assetBundles['yii\web\JqueryAsset']);
///var_dump($this->assetBundles);die('hh');
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<script src="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/js/bootstrap.min.js"></script>
   
    <script type="text/javascript" src="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/js/jquery.datetimepicker.full.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/css/jquery.datetimepicker.css" rel="stylesheet">
    
    <script type="text/javascript" src="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/js/loader.js"></script>
    
    <?php $this->head() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Bootstrap Core CSS -->
    <link href="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/css/sb-admin.css" rel="stylesheet">


    <!-- Custom Fonts -->
    <link href="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<style>
		.breadcrumb {
		    margin-top: 1%;
		}
		.site-index {
		  margin-top: 1%;
		}
		.navbar-nav.navbar-right.nav {
		  margin-right: -10%;
		}
		.nav.navbar-nav.side-nav {
		    width: 10%;
		}

		#LoadingMSG{
		display:none;
		}
		#LoadingMSG,#LoadingMSG img{
			margin:0 auto;
		}
		.overlay{
		    background-color: rgba(0, 0, 0, 0.20);
		    height: 100%;
		    left: 0;
		    position: fixed;
		    right: 0;
		    text-align: center;
		    top: 0;
		    width: 100%;
		    z-index: 999;
		}
		.rejected {
		    color: #bd1500;
		}
		.overlay img{
		    position: relative;
		    top: 40%;
		}
		
		/* .dropdown-menu > li > a {
		    padding: 3px 19px!important;
		} */
		</style>
</head>

<body>

	<?php $this->beginBody() ?>
    <div id="wrapper">
		
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
			            $menuItems = [
			                ['label' => 'Home', 'url' => ['site/index']],
			            ];
			            
			            if (\Yii::$app->user->isGuest) {
			            	
			                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
			            } else {
			            	$menuItems[] = ['label' => 'Create E-Mail CSV', 'url' => ['/site/createcsv']];
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
            
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li >
                        <a href="<?=Yii::$app->request->baseUrl;?>/site/index"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a style="float: left;" href="<?=Yii::$app->request->baseUrl;?>/upcomingclients/index"><i class="fa fa-list-alt" aria-hidden="true"></i>Upcoming Clients</a>
                    </li>
                    <li>
                        <a style="float: left;" href="<?=Yii::$app->request->baseUrl;?>/jetshopdetails/index"><i class="fa fa-list-alt" aria-hidden="true"></i> Jet Shop Details</a>
                    </li>
                   <li>
                        <a style="float: left;" href="<?=Yii::$app->request->baseUrl;?>/walmartshopdetails/index"><i class="fa fa-list-alt" aria-hidden="true"></i> Walmart Shop Details</a>
                   </li>
                   <li>
                        <a href="<?=Yii::$app->request->baseUrl;?>/jet-recurring-payment/index"><i class="fa fa-money" aria-hidden="true"></i> Jet Payment Details</a>
                   </li>
                   <li>
                        <a style="float: left;" href="<?=Yii::$app->request->baseUrl;?>/walmart-payment/index"><i class="fa fa-money" aria-hidden="true"></i> Walmart Payment Details</a>
                   </li>
                    <li >
                        <a href="<?=Yii::$app->request->baseUrl;?>/jetmerchantshelp/index"><i class="fa fa-envelope-o" aria-hidden="true"></i> Help Details</a>
                    </li> 
                    
                    <li>
                   		<a href="<?=Yii::$app->request->baseUrl;?>/jetclientdetails/index"><i class="fa fa-phone" aria-hidden="true"></i> Jet Client Details</a>
                    </li>
                    <li>
                   		<a href="<?=Yii::$app->request->baseUrl;?>/walmart-client/index"><i class="fa fa-phone" aria-hidden="true"></i> Walmart Client Details</a>
                    </li>
                    <li>
                        <a href="<?=Yii::$app->request->baseUrl;?>/site/sendmail"><i class="fa fa-envelope" aria-hidden="true"></i> Send Mail</a>
                    </li>  
                    <li>
                        <a style="float: left;" href="<?=Yii::$app->request->baseUrl;?>/jetshopdetails/productvalidation"><i class="fa fa-flag-checkered" aria-hidden="true"></i> Validate Details</a>
                    </li>
                    <li>
                        <a style="float: left;" href="<?=Yii::$app->request->baseUrl;?>/magentoextensiondetail/index"><i class="fa fa-flag-checkered" aria-hidden="true"></i> Magento Clients</a>
                    </li>
                    <li>
                        <a href="<?=Yii::$app->request->baseUrl;?>/reports/dashboard"><i class="fa fa-bar-chart" aria-hidden="true"></i> Reports</a>
                    </li>
                    <!-- MailChimp Campaign -->
                    <li>
                        <a href="<?=Yii::$app->request->baseUrl;?>/walmart/mailchimp/campaigns"><i class="fa fa-bar-chart" aria-hidden="true"></i>Campaign</a>
                   </li>
                   <!-- All template listed here -->
                    <li>
                        <a href="<?=Yii::$app->request->baseUrl;?>/reports/walmart-email-template/index"><i class="fa fa-bar-chart" aria-hidden="true"></i> Templates</a>
                   </li>
                   <!-- Email report status -->
                    <li>
                        <a href="<?=Yii::$app->request->baseUrl;?>/reports/email-report/index"><i class="fa fa-bar-chart" aria-hidden="true"></i>Email Report</a>
                   </li>
                    <li>
                        <a href="<?=Yii::$app->request->baseUrl;?>/callschedule/index"><i class="fa fa-bar-chart" aria-hidden="true"></i>Call Schedule</a>
                    </li>
                    <li>
                        <a href="<?=Yii::$app->request->baseUrl;?>/newegg-shop-detail/index"><i class="fa fa-bar-chart" aria-hidden="true"></i>Newegg Shop Details</a>
                    </li>
                    <li>
                        <a href="<?=Yii::$app->request->baseUrl;?>/newegg-client-detail/index"><i class="fa fa-bar-chart" aria-hidden="true"></i>Newegg Client Details</a>
                    </li>
                   
            </div>
            <!-- /.navbar-collapse -->
        </nav>

       <div class="container">
        	<?= Breadcrumbs::widget([
	            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
	        ]) ?>
	        <?= $content ?>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    
    <?php if(isset($this->assetBundles)):?>
    	<?php unset($this->assetBundles['yii\web\JqueryAsset'],$this->assetBundles['yii\bootstrap\BootstrapPluginAsset']);?>
    <?endif;?>
    <?php $this->endBody() ?>
	<script type="text/javascript">
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
		
</body>

</html>
<?php $this->endPage() ?>