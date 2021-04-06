<?php 

namespace backend\components;
use Yii;
use common\models\JetOrderDetail;
use common\models\JetProduct;
use common\models\JetActiveMerchants;
use yii\base\Component;

class MyDashboard extends Component
{
	public function header()
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['login']);
		}
		$model="";
		$P_avail=0;
		$P_under=0;
		$P_complete=0;
		$connection=Yii::$app->getDb();
		
		$model = $connection->createCommand("SELECT DISTINCT `merchant_id` from `jet_product` ")->queryAll();
		
		foreach ($model as $key=>$val)
		{
			$model = $connection->createCommand("SELECT  `merchant_id` from `jet_product` WHERE `merchant_id`='".$val['merchant_id']."' AND `status`='Available for Purchase'")->queryAll();
			if ($model){
				$P_avail++;
			}
			$model = $connection->createCommand("SELECT  `merchant_id` from `jet_product` WHERE `merchant_id`='".$val['merchant_id']."' AND `status`='Under Jet Review'")->queryAll();
			if ($model){
				$P_under++;
			}
			$model = $connection->createCommand("SELECT  `merchant_id` from `jet_order_detail` WHERE `merchant_id`='".$val['merchant_id']."' AND `status`='complete'")->queryAll();
			if ($model){
				$P_complete++;
			}
		}
		$detailArray['avail']=$P_avail;
		$detailArray['under']=$P_under;
		$detailArray['complete']=$P_complete;
				
		
		$active_marchants_model="";
		$active_marchants=0;
		
		date_default_timezone_set('Asia/Kolkata');
		$active_marchants= count($connection->createCommand("SELECT  `merchant_id` from `jet_active_merchants` WHERE updated_at LIKE '%".date('Y-m-d')."%'")->queryAll());
		
		?>
		<head>
		
		
		<style type="text/css">
			h2{
			text-align: left;
			}
			.fa, .fa-5x {
			    margin-left: 5%;
			}
			.small-box-footer {
			    margin-left: -4%;
			}
			.inner > p {
			    margin: 5%;
			    
			}
			h1 {
			    float: right;
			    margin-right: 10%;
			    margin-top: 5%;
			}
			a:HOVER {
				text-decoration: none;
			}
			.fa ,h1,.small-box-footer,.inner > p{
			    color: white;
			}
			.outer_div{
				background-color: #f5f5f5; 
			    
			    border-top: 2px solid #DDD;
			    padding: 10px 15px;
			    margin: 1px;
			    margin-bottom: 1px;
			}
			.live-row,.outer_div_live a {
				font-weight:bold;
				font-size: 16px;
				color:#3207A3;
				
			}
			.outer_div_live{
				border-bottom: 1px solid #3207A3;
			}
			.outer_div_under{
				border-bottom: 1px solid #82124E;
			}
			.outer_div_active{
				border-bottom: 1px solid #10DC00;
			}
			.outer_div_complete{
				border-bottom: 1px solid #7D1B8E;
			}
			.under-row,.outer_div_under a{
				color:#82124E;
				font-weight:bold;
				font-size: 16px;
			}
			.active-row,.outer_div_active a{
				color:#0BAC31;
				font-weight:bold;
				font-size: 16px;
			}
			.complete-row , .outer_div_complete a{
				color:#7D1B8E;
				font-weight:bold;
				font-size: 16px;
			}
			.fa-arrow-circle-right{
				margin-left: 56%;
			}		
		</style>
		</head>
		<div class="row">
			<a href="<?=Yii::$app->request->baseUrl;?>/site/liveproducts">
	            <div class="col-lg-3 col-xs-6">
	              <!-- small box -->
	               
	              <div class="small-box bg-aqua" style="background-color: #3207A3" >
	              	
		                <div class="inner" >
		                	<span><i aria-hidden="true" class="fa fa-cloud fa-5x"></i></span>
		                    <span><h1><?php echo $detailArray['avail'];?></h1></span>
		                  <p>Merchants (Live Products)</p>
		                </div>
		                <div class="outer_div outer_div_live">
		                	<a href="<?=Yii::$app->request->baseUrl;?>/site/liveproducts" class="small-box-footer">More info ...<i class="fa live-row fa-arrow-circle-right"></i></a>
		                </div>
	              </div>
	            </div>
            </a>
            <!-- ./col -->
            <a href="<?=Yii::$app->request->baseUrl;?>/site/reviewproducts">
	            <div class="col-lg-3 col-xs-6">
	              <!-- small box -->
	              <div class="small-box bg-green" style="background-color: #82124E">
	                <div class="inner">
	                	<span><i aria-hidden="true" class="fa fa-clock-o fa-5x"></i></span>
		                 <span><h1><?php echo $detailArray['under'];?></h1></span>
		                  <p>Merchants (Under Review Products)</p>
	                </div>
	                <div class="outer_div outer_div_under" >
	                	<a href="<?=Yii::$app->request->baseUrl;?>/site/reviewproducts" class="small-box-footer">More info ... <i class="fa under-row fa-arrow-circle-right"></i></a>
	                </div>
	              </div>
	            </div><!-- ./col -->
	        </a>
	        <a href="<?=Yii::$app->request->baseUrl;?>/site/completeorders">
	            <div class="col-lg-3 col-xs-6">
	              <!-- small box -->
	              <div class="small-box bg-yellow" style="background-color: #7D1B8E">
	                <div class="inner">
	                	<span><i aria-hidden="true" class="fa fa-bar-chart fa-5x"></i></span>
		                 <span><h1><?php echo $detailArray['complete'];?></h1></span>
	                  <p>Merchants (Complete Order)</p>
	                </div>
	                <div class="outer_div outer_div_complete">
	                	<a href="<?=Yii::$app->request->baseUrl;?>/site/completeorders" class="small-box-footer">More info ... <i class="fa complete-row fa-arrow-circle-right"></i></a>
	                </div>
	              </div>
	            </div><!-- ./col -->
	        </a> 
	        <a href="<?=Yii::$app->request->baseUrl;?>/site/activemerchantstoday">
	            <div class="col-lg-3 col-xs-6">
	              <!-- small box -->
	              <div class="small-box bg-red" style="background-color: #0BAC31">
	                <div class="inner">
	                	<span><i aria-hidden="true" class="fa fa-eye fa-5x"></i></span>
	                	<span><h1><?php echo $active_marchants; ?></h1></span>
	                  
	                  <p>Active Merchants (Today) </p>
	                </div>
	                
	                <div class="outer_div outer_div_active">
	                	<a href="<?=Yii::$app->request->baseUrl;?>/site/activemerchantstoday" class="small-box-footer">More info ... <i class="fa active-row fa-arrow-circle-right"></i></a>
	                </div>	
	              </div>
	            </div><!-- ./col -->
            </a>
          </div><!-- /.row -->
		
		
		<?php 
		
	}
	
	public function activeMerchantsMenu(){
		?>
			<html>
				<head>
					<style>
						div.jet-extension-detail-index ul {
						    list-style-type: none;
						    margin: 0;
						    padding: 0;
						    overflow: hidden;
						    background-color: #8F0D85;
						}
						
						div.jet-extension-detail-index ul li {
						    float: left;
						    border-right:1px solid #bbb;
						    width: 20%;
						}
						
						div.jet-extension-detail-index ul li:last-child {
						    border-right: none;
						    float: right;
						}
						
						div.jet-extension-detail-index ul li a {
						    display: block;
						    color: white;
						    text-align: center;
						    padding: 14px 16px;
						    text-decoration: none;
						}
						div.jet-extension-detail-index ul li a:HOVER{
							background-color: #4CAF50;
						}
						
					</style>
				</head>
				<body>
				    <ul>
					  <li><a href="<?=Yii::$app->request->baseUrl;?>/site/activemerchantstoday">Active-Today </a></li>
					  <li><a href="<?=Yii::$app->request->baseUrl;?>/site/activemerchantsyesterday">Active-Yesterday </a></li>
					  <li><a href="<?=Yii::$app->request->baseUrl;?>/site/activemerchantssevendays">Active-Within Seven Days</a></li>
					  <li><a href="<?=Yii::$app->request->baseUrl;?>/site/activemerchantsfifteendays">Active-Within Fifteen Days</a></li>
					  <li><a href="<?=Yii::$app->request->baseUrl;?>/site/activemerchantsonemonth">Active-Current Month</a></li>
					</ul>
				</body>
			</html>
							
		<?php 
	}
}
?>