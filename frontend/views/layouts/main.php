<?php
use \yii\helpers\Url;
use frontend\modules\jet\assets\AppAsset;
use frontend\modules\jet\components\Data;
use frontend\modules\jet\components\Upgradeplan;
use frontend\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;
use frontend\modules\jet\controllers\GetnotificationController;

$valuecheck="";
$valuecheck=Yii::$app->request->get('shop');
AppAsset::register($this);
$urlCall = \yii\helpers\Url::toRoute(['site/schedulecall']);
$notificationurl = \yii\helpers\Url::toRoute(['getnotification/setread']);
$scheduleMessage=Data::getJetScheduleMessage();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<link rel="icon" href="<?= Yii::$app->request->baseUrl?>/images/favicon.ico">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta content="INDEX,FOLLOW" name="robots">
	<meta name="google-site-verification" content="vJ8BS5PRRVFvpk5dCrUMgTZdDVIuWKMbvmCo2QTIEag" />
	<script type="text/javascript" src="<?= Yii::$app->getUrlManager()->getBaseUrl();?>/js/jquery.js"></script>
	    <?= Html::csrfMetaTags() ?>
	     <title><?= Html::encode("Shopify Jet Integration | CedCommerce");?></title>
	<title><?= Html::encode($this->title) ?></title>
	    <?php $this->head() ?>
</head>
<body class="iframe-body">
	<div class="overlay" style="display: none;" id="LoadingMSG">
		<div id="fountainG">
			<div id="fountainG_1" class="fountainG"></div>
			<div id="fountainG_2" class="fountainG"></div>
			<div id="fountainG_3" class="fountainG"></div>
			<div id="fountainG_4" class="fountainG"></div>
			<div id="fountainG_5" class="fountainG"></div>
			<div id="fountainG_6" class="fountainG"></div>
			<div id="fountainG_7" class="fountainG"></div>
			<div id="fountainG_8" class="fountainG"></div>
		</div>
	</div>
	<?php
	$flag=false;
	$value="";
	?>
	<?php $this->beginBody() ?>
 	<div class="wrap ced-jet-navigation-mbl">
			<nav class="navbar navbar-default">
				<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#"></a>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

						<ul class="nav navbar-nav navbar-right">

							<li><a data-toggle='tooltip' href="<?= Yii::getAlias('@webjeturl') ?>/site/index">Home</a></li>
							<li><a  href="<?= Yii::getAlias('@webjeturl') ?>/faq/index">Help & FAQs</a></li>
						<?php if (Yii::$app->user->isGuest) {
								?>
							<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/pricing">Pricing</a></li>
							<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/guide">Documentation</a></li>
							<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/login">Login</a></li>
		        	 	<?php }
		        	 	else{
		        	 	?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Products<span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a  href="<?= Yii::getAlias('@webjeturl') ?>/categorymap/index">Map Category</a></li>
									<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jet-attributemap/index">Map Jet Attributes</a></li>
									<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetproduct/index">Manage Products</a></li>
									<li role="separator"></li>
									<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetproduct-fileupload/index">Bulk Upload/Update</a></li>
									<li><a  href="<?=Yii::getAlias('@webjeturl') ?>/jetproductslist/index">Listing on Jet</a></li>
								</ul>
							</li>
                            <li class="dropdown">
                                <a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Export/Import<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li role="separator"></li>
                                    <li><a href="<?= Yii::getAlias('@webjeturl') ?>/productcsv/index">Product Update </a></li>
                                    <li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetcsvupdate/index">Product Archive/Unarchive</a></li>
                                    <li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetproduct-fileupload/csvupload">CSV Product Upload</a></li>
                                </ul>
                            </li>

							<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Orders<span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a  href="<?= Yii::getAlias('@webjeturl') ?>/jetorderdetail/index">Sales Orders</a></li>
									<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetorderimporterror/index">Failed Orders</a></li>
									<li><a  href="<?= Yii::getAlias('@webjeturl') ?>/jetreturn/index">Return Orders</a></li>
									<li role="separator"></li>
									<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetrefund/index">Refund Orders</a></li>
								</ul>
							</li>
							<?php if (!Yii::$app->user->isGuest)
							{
								?>
							        
					            <li>
					                <a class="icon-items " href="javascript:void(0)" onclick="callView()">
					                    <img class="call-icon-img" src="<?= Yii::getAlias('@jetbasepath'); ?>/assets/images/icons/call.png">
					                </a>

					            </li>
							<?php 
							}?>

							<!-- Code For Referral Start -->
                            <li>
                                <a href="<?= Yii::$app->request->baseUrl ?>/referral/account/dashboard" target="_blank">Referrals</a>
                                <span class="ref">new</span>
                            </li>
                            <!-- Code For Referral End -->
                            <li>
                                    <a id="anchor-notification" class="notification-anchor">
                                        <img src="<?= Yii::getAlias('@webbaseurl')?>/images/notification.png">
                                        <span class="notification-bell"></span>
                                    </a>
                                   
                                
                            </li>
							<li ><a class="icon-items" href="<?= Yii::getAlias('@webjeturl') ?>/jetconfiguration/index"><img src="<?= Yii::getAlias('@jetbasepath'); ?>/assets/images/icons/setting.png"></a></li>

							<li class="dropdown ">
								<a href="#" class="dropdown-toggle icon-items" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="<?= Yii::getAlias('@jetbasepath'); ?>/assets/images/icons/dropdown.png"></a>
								<ul class="dropdown-menu">
									<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/paymentplan">Pricing</a></li>
									<li><a href="http://support.cedcommerce.com/" target="_blank">Support</a></li>
									<li><a href="<?= Yii::getAlias('@webjeturl') ?>/sell-on-jet">Documentation</a></li>
									<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/index?tour">Quick Tour</a></li>
									<?/*<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetreport/index">Reports</a></li>*/?>
									<li class="logout_merchant"><a href="<?= Yii::getAlias('@webjeturl') ?>/site/logout">Logout</a></li>
								</ul>
							</li>
							<?php } ?>
						</ul>


						<ul class="nav navbar-nav navbar-right navbar-2">
							<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/index">Home</a></li>
							<?php
								if (Yii::$app->user->isGuest)
								{
									?>
										<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/pricing">Pricing</a></li>
										<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/guide">Documentation</a></li>
										<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/login">Login</a></li>
					        	 	<?php
			        	 		}
			        	 		else
			        	 		{
			        	 			?>
										<li class="dropdowns">
											<a href="#">Products</a>
											<ul class="dropdown-menus">
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/categorymap/index">Map Category</a></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jet-attributemap/index">Map Jet Attributes</a></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetproduct/index">Manage Products</a></li>
												<li role="separator"></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetdynamicprice/index">Jet Repricing</a></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetproductslist/index">Listing on Jet</a></li>
											</ul>
										</li>
										<li class="dropdowns">
											<a href="#">Export/Import</a>
											<ul class="dropdown-menus">
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/productcsv/index">Product update</a></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetcsvupdate/index">Product Archieve/Unarchieve</a></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetproduct-fileupload/csvupload">CSV Product Upload</a></li>
											</ul>
										</li>

										<li class="dropdowns">
											<a href="#">Order</a>
											<ul class="dropdown-menus">
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetorderdetail/index">Sales Order</a></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetorderimporterror/index">Failed Order</a></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetreturn/index">Return Order</a></li>
												<li role="separator"></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetrefund/index">Refund Order</a></li>
											</ul>
										</li>

										<!-- Code For Referral Start -->
				                        <li>
				                            <a href="<?= Yii::$app->request->baseUrl ?>/referral/account/dashboard" target="_blank">Referrals</a>
				                            <span class="ref-mobile">new</span>
				                        </li>
				                        <li>
			                                    <a id="anchor-notification" class="notification-anchor">
			                                        <img src="<?= Yii::getAlias('@webbaseurl')?>/images/notification.png">
			                                        <span class="notification-bell"></span>
			                                    </a>
			                                   
			                                
			                            </li>
				                        <!-- Code For Referral End -->
                            
										<li >
											<a class="icon-items" href="<?= Yii::getAlias('@webjeturl') ?>/jetconfiguration/index">setting</a>
										</li>
										<li class="dropdowns ">
										<a href="#">Account</a>
											<ul class="dropdown-menus">
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/pricing">Pricing</a></li>
												<li><a href="http://support.cedcommerce.com/">Support</a></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/sell-on-jet">Documentation</a></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/site/index?tour">Quick Tour</a></li>
												<li><a href="<?= Yii::getAlias('@webjeturl') ?>/jetreport/index">Reports</a></li>
												<li class="logout_merchant"><a href="<?= Yii::getAlias('@webjeturl') ?>/site/logout">Logout</a></li>
											</ul>
										</li>
									<?php
								}
							?>
						</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>
	 	<!-- </div>  --> <!--  trial counter end div  -->
	 	<div class="fixed-container-body-class">
	 		<?php
 				if (!Yii::$app->user->isGuest)
				{
					?>
					 	<div class="trial-wrapper">
					 		<div class="col-sm-9 plateform-switch-body no-padding">
					 			<?
					 				$merchant_id = Yii::$app->user->identity->id;
						 			
						 				$newpath = "site";
						 			
						 			$appurls=Data::checkInstalledApp($merchant_id,true);
						 		?>
			 					<div class="install-walmart">
		 							<div class="install-button">
		 								<div id="show_apps_div">
		 									<h2 class="rw-sentence">
							                    <span>Switch to other integrations app</span>
							                    <div class="rw-words rw-words-1">
							                        <span>Walmart</span>
							                        
							                        <span>Sears</span>

							                        <span>Newegg</span>
							                        
							                    </div>
		 									    <i class="fa fa-chevron-down" aria-hidden="true"></i>
		 								  <!-- Code For Referral Start -->
			                               <!--  <p class="referal-notice"> Become a Referrer & Earn Money or 1 Month Free Subscription
			                                    by <a href="<?= Yii::$app->request->baseUrl ?>/referral/account/dashboard"
			                                          target="_blank" class="referal-link">Clicking Here</a></p> -->
			                                <!-- Code For Referral End -->
			                                 <!-- Code For Survey for -->
			                                <!-- end survey for  -->
							                </h2>
		 								</div>
		 								<div id="display_apps" style="display: none;">
		 									<div class="walmart">
		 										<span class="walmart-app">Walmart app</span>
		 										<a <?php if($appurls['walmart']['type']=="Install"){ echo 
				 											"target='_blank' href=".$appurls['walmart']['url'];}
				 											else{echo "href=".$appurls['walmart']['url']."/".$newpath;}?>>
		 											<button class="btn-path"><?= $appurls['walmart']['type'];?></button>
		 										</a>
		 									</div>
		 									<div class="sears">
		 										<span class="sears-app">Sears app</span>
		 										<a <?php if($appurls['sears']['type']=="Install"){ echo 
				 											"target='_blank' href=".$appurls['sears']['url'];}
				 											else{echo "href=".$appurls['sears']['url']."/".$newpath;}?>>
		 											<button class="btn-path"><?= $appurls['sears']['type'];?></button>
		 										</a>
		 									</div>
		 									<div class="newegg">
		 										<span class="newegg-app">Newegg app</span>
		 										<a <?php if($appurls['newegg']['type']=="Install"){ echo "target='_blank' href=".$appurls['newegg']['url'];}else{echo "href=".$appurls['newegg']['url']."/".$newpath;}?>>
		 											<button class="btn-path"><?= $appurls['newegg']['type'];?></button>
		 										</a>
		 									</div>
		 									
		 								</div>
		 							</div>
		 						</div>
					 		</div>
					 		<!-- <div class="schedule_maintenance"><?php /*if($scheduleMessage){ echo $scheduleMessage;}*/?></div> -->
					 		<div class="col-sm-3 upgradeplean-body no-padding">
					 			<?php
				         		if(( (Yii::$app->controller->id.'/'.Yii::$app->controller->action->id!='site/paymentplan') ))
				         		{
				         			Upgradeplan::remainingDays(Yii::$app->user->identity->id);
				         		}
						        ?>
					 		</div>
				 		</div>
	 		<?php
			}
	 		?>

	        <?= Breadcrumbs::widget([
	        	'homeLink' => ['label' => 'Home','url' =>  Yii::getAlias('@webjeturl').'/site'],
	            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
	        ]) ?>
	    	<?= Alert::widget() ?>
	        <?= $content ?>
		    <div id="helpSection" style="display:none"></div>
		</div>
	<?php
	if(Yii::$app->controller->id.'/'.Yii::$app->controller->action->id != 'site/guide')
	{
	?>
		<footer class="container-fluid footer-section">
			<div class="contact-section">
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<a title="Click Here to Submit a Support Ticket" href="http://support.cedcommerce.com/" target="_blank">
						<div class="ticket">
							<div class="icon-box">
								<div class="image">
									<img src="<?= Yii::$app->request->baseUrl ?>/images/ticket.png">
								</div>
							</div>
							<div class="text-box">
								<span>Submit issue via ticket</span>
							</div>
							<div class="clear"></div>
						</div>
						</a>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<a title="Click Here to Contact us through Mail" href="mailto:apps@cedcommerce.com" target="_blank">
						<div class="mail">
							<div class="icon-box">
								<div class="image">
									<img src="<?= Yii::$app->request->baseUrl ?>/images/mail.png">
								</div>
							</div>
							<div class="text-box">
								<span>Send us an E-mail</span>
							</div>
							<div class="clear"></div>
						</div>
						</a>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
	                    <?php
                                        $skypeIds = ['live:cedcommerce', 'live:support_35785', 'live:danbakker_2', 'tonystark.cedcommerce', 'live:nehasingh_20', 'live:nidhirajput_2', 'barryallen.cedcommerce', 'live:swatishukla_5', 'jimmoore.cedcommerce', 'live:steveroger_7', 'stephenjones.cedcommerce', 'james.cedcommerce', 'live:f4acf2f09cc4c216'];
                                        $topic = 'CEDCOMMERCE SUPPORT';
                                        if (!Yii::$app->user->isGuest) {
                                            $topic = Yii::$app->user->identity->username;
                                        }
                                        ?>
                       <a title="Click Here to Contact us through Mail" href="skype:<?= implode(';', $skypeIds) ?>?chat&topic=<?= $topic ?>" target="_blank">                              
                        <div class="skype">
                            <div class="icon-box">
                                <div class="image">
                                    
                                        <img src="<?= Yii::$app->request->baseUrl ?>/images/skype.png">
                                </div>
                            </div>
                            <div class="text-box">
                                <span>Connect via skype</span>
                            </div>
                            <div class="clear"></div>
                        </div>
                        </a>
	                </div>
				</div>
			</div>
		</footer>
		<div class="copyright-section">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<a title="Click Here to install shopify - jet/walmart integration app (Android)" href="https://play.google.com/store/apps/details?id=com.cedcommerce.shopifyintegration&hl=en" target="_blank"><img src="<?= Yii::$app->request->baseUrl ?>/images/GooglePlay.png"></a>
					<a title="Click Here to install shopify - jet/walmart integration app (IOS)" href="https://itunes.apple.com/us/app/cedbridge-for-shopify/id1186746708?ls=1&mt=8" target="_blank"><img src="<?= Yii::$app->request->baseUrl ?>/images/App-Store.png"></a>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="copyright">
						<span>Copyright &copy; <?= date('Y');?> CEDCOMMERCE | All Rights Reserved.</span>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
	?>
	<div class="call-schedule">
	 	<div id="view_call" style="display: none;"></div>
	</div>
    <?php $this->endBody() ?>
   <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
   <script type="text/javascript">
        function callView()
        {
           var url = '<?= $urlCall ?>';
           $('#LoadingMSG').show();
           $.ajax({
               method: "post",
               url: url,

           })
               .done(function (msg) {
                   //console.log(msg);
                   $('#LoadingMSG').hide();
                   $('#view_call').html(msg);
                   $('#view_call').css("display", "block");
                   $('#view_call #myModal').modal('show');
               });
        }
        $(document).ready(function()
        {
            $('.dropdown').addClass('dropdown1').removeClass('dropdown');
            $(document).on('pjax:send', function() {
                $('#LoadingMSG').show();
            })
            $(document).on('pjax:complete', function() {
                $('#LoadingMSG').hide()
            })
        });
        var submit_form = false;
        function selectFilter()
        {
          if(submit_form === false) {
            submit_form = true;
            $("#product_grid").yiiGridView("applyFilter");
          }
        }
   </script>
  <?php
  	if(!Yii::$app->user->isGuest)
  	{
	?>
		<script type="text/javascript">
		if ( self !== top )
		{
			var head1=$(self.document.head);
			var url = '<?= Yii::$app->getUrlManager()->getBaseUrl();?>/css/embapp.css';
			head1.append($("<link/>", { rel: "stylesheet", href: url, type: "text/css" } ));
			$('.logout_merchant').css('display','none');
			//$('nav.navbar-fixed-top').css('display','none');
			//$('.wrap > .container').css('padding',0)
			ShopifyApp.init({
				apiKey: "<?= PUBLIC_KEY;?>",
				shopOrigin: "<?= "https://".Yii::$app->user->identity->username;?>"
			});
			ShopifyApp.ready(function()
			{
				ShopifyApp.Bar.loadingOff();

			});
		}
		</script>
	<?php
	}
	?>
	<!-- Hotjar Tracking Code for https://apps.cedcommerce.com/integration/jet --> 
	<script> 
	(function(h,o,t,j,a,r){ h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)}; h._hjSettings={hjid:684305,hjsv:6}; a=o.getElementsByTagName('head')[0]; r=o.createElement('script');r.async=1; r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv; a.appendChild(r); })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv='); 
	</script>

    
	<!--Start of Zopim Live Chat Script-->

	<script type="text/javascript">
		window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
		d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
		_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
		$.src="//v2.zopim.com/?322cfxiaxE0fIlpUlCwrBT7hUvfrtmuw";z.t=+new Date;$.
		type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");

		$zopim(function(){
		window.setTimeout(function() {
		}, 2000); //time in milliseconds
		});
	</script>
<!-- notfication html  -->
<div id="inner-notification" class="inner-notification">
    <div class="inner-notification-pos">
        <a href="#" class="notification-close"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
        <div class="inner-notification-pos-scroll">
            <div class="notification-pos-scroll">
            <?php if (!Yii::$app->user->isGuest) 
                  echo GetnotificationController::getNotification(Yii::$app->user->identity->id);?>
            </div>
        </div>
    </div>
</div>
<!-- notfication html  end -->
<script type="text/javascript">
    $('.notification-anchor').on('click',function(){
        $('.inner-notification').toggleClass('active');
        if($('.notification-bell').length>0){
            var url = '<?= $notificationurl ?>';
            var merchant_id = '<?php if (!Yii::$app->user->isGuest)
            							echo Yii::$app->user->identity->id;
            							else
            								echo ""; ?>';
            $.ajax({
              method: "post",
              url: url,
              data: {id: merchant_id}
            })
            .done(function (response) {
                $(".notification-bell").remove();
            });
        }        
    });
    $('.notification-close').on('click',function(e){
        //alert('dsfdsfdsfdas');
        $('.inner-notification').removeClass('active');  
        e.preventDefault();
    });

</script>
<script type="text/javascript">
    $(document).ready(function() {
        notification();
        $(window).resize(function(){
            notification();
        });
        function notification(){
            var ht = $('.notification-pos-scroll').height();
            var wh = $(window).height();
            if(ht > wh){
                $('.inner-notification').addClass('overflow');
            }
            else{
                $('.inner-notification').removeClass('overflow');
            }
        }
        // $( ".inner-notification-pos .noti-ced" ).each(function() {
        //   var ht = $(this).height();
        //   alert(ht);
        // });


        if($('.show-notification').length>0){
            
        }
        else{
            $(".notification-bell").remove();
        }
   });
</script>
	<!-- end menu -->
	</body>
</html>
<?php $this->endPage() ?>
