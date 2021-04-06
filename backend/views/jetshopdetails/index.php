<?php

use backend\models\JetShopDetailsSearch;
use frontend\modules\jet\components\Dashboard\Earninginfo;
use frontend\modules\jet\components\Dashboard\OrderInfo;
use frontend\modules\jet\components\Dashboard\Productinfo;
use frontend\modules\jet\components\Jetappdetails;
use frontend\modules\jet\components\Data;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\models\User;
use yii\bootstrap\Modal;
use common\components\IntegrationLoginHelper;

$this->title = 'Jet Shop Details';
$this->params['breadcrumbs'][] = $this->title;

ini_set('memory_limit', '-1');
$urlRegister = \yii\helpers\Url::toRoute(['jetshopdetails/view-merchant']);
$helper = new IntegrationLoginHelper();
$loginUrl = $helper->getLoginasMerchantUrl('jet');

?>
<head>
	<style type="text/css">
		.container{
			margin-left: 0;
		}
		.table > tbody > tr.red_alert > td, .table > tbody > tr.red_alert > th, .table > tbody > tr > td.red_alert, .table > tbody > tr > th.red_alert, .table > tfoot > tr.red_alert > td, .table > tfoot > tr.red_alert > th, .table > tfoot > tr > td.red_alert, .table > tfoot > tr > th.red_alert, .table > thead > tr.red_alert > td, .table > thead > tr.red_alert > th, .table > thead > tr > td.red_alert, .table > thead > tr > th.red_alert {
		  background-color: #c04f4f;
		}
		
	 	.table > tbody > tr.error > td, .table > tbody > tr.error > th, .table > tbody > tr > td.error, .table > tbody > tr > th.error, .table > tfoot > tr.error > td, .table > tfoot > tr.error > th, .table > tfoot > tr > td.error, .table > tfoot > tr > th.error, .table > thead > tr.error > td, .table > thead > tr.error > th, .table > thead > tr > td.error, .table > thead > tr > th.error {
		  background-color: #FFB9BB;
		} 
	</style>
</head>
<div class="jet-extension-detail-index">
	<h1><?= Html::encode($this->title) ?></h1>
	 <div class="list-page">
          Show per page 
          <select onchange="selectPage(this)" class="form-control" style="display: inline-block; width: auto; margin-top: 0px; margin-left: 5px; margin-right: 5px;" name="per-page">
            <option value="25" <?php if(isset($_GET['per-page']) && $_GET['per-page']==25){echo "selected=selected";}?>>25</option>
            <option <?php if(!isset($_GET['per-page'])){echo "selected=selected";}?> value="50">50</option>
            <option value="100" <?php if(isset($_GET['per-page']) && $_GET['per-page']==100){echo "selected=selected";}?> >100</option>
          </select>
          Items

        </div>
         <?php 
       Modal::begin([
        'header'=>'<h4>purchase_status</h4>',
        'id'=>'modal',
        'size'=>'modal-lg'
        ]);
       echo "<div id='modalContent'></div>";
       Modal::end();
       ?>
	   
    <?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
    <?= GridView::widget([
    	'id'=>"jet_extention_details",
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => "select[name='".$dataProvider->getPagination()->pageSizeParam."'],input[name='".$dataProvider->getPagination()->pageParam."']",
        'pager' => [
            'class' => \liyunfang\pager\LinkPager::className(),
            'pageSizeList' => [25,50,100],
            'pageSizeOptions' => ['class' => 'form-control','style' => 'display: none;width:auto;margin-top:0px;'],
            'maxButtonCount'=>5,
        ],
    	'rowOptions'=>function ($model){
            if ($model->sendmail=='no'){
                return ['class'=>'red_alert'];
            }elseif ($model->install_status=='0'){
	    		return ['class'=>'error'];
	    	}elseif (($model->purchase_status=='License Expired')||($model->purchase_status=='Trial Expired')){
    			return ['class'=>'danger'];
    		}elseif ($model->purchase_status=='Purchased'){
    			return ['class'=>'success'];
    		}
    		
    	},	
        'columns' => [
			['class' => 'yii\grid\CheckboxColumn',
        		'checkboxOptions' => function($data) 
        		{
        			return ['value' => $data['merchant_id'],'class'=>'bulk_checkbox','headerOptions'=>['id'=>'checkbox_header','data-step'=>'1','data-intro'=>"Select merchants to Export CSV",'data-position'=>'right']];
        		},               
    		],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {link}',
                'buttons' => [
                    'link' => function ($url,$model,$key) use ($loginUrl) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-log-in"> </span>',
                            $loginUrl.$model['merchant_id'],
                            ['title' => 'Login As','target'=>'_blank']
                        );
                        return '<a data-pjax="0" href="">Login as</a>';
                    },
                         
                ],
            ],
             [
        		'label'=>'Merchant ID',
        		'attribute'=>'merchant_id',
                'value' => function($data)
                {

                    return "<a href='javascript:void(0)' onclick=getMerchant(".$data->merchant_id.",'Registration')>".$data->merchant_id."</a>";
                },
                'format'=>'raw',
        	],
            'shop_url:url',
            'email:email',
            [
                'format'=>'raw',
                'attribute' => 'install_status',
                'value'=>function ($data)
                {                    
                    if ($data->install_status == "1") 
                        return "Installed";                    
                    else if ($data->install_status =="0") 
                        return "Uninstalled";                    
                },
                'filter'=>["1"=>"Installed","0"=>"Uninstalled"],
            ],
           	[
                'attribute' => 'purchase_status', 
               'format' => 'raw',
                'value' => function ($data) 
                { 
                    if($data->purchase_status=="Purchased" || $data->purchase_status=="License Expired")
                   {
                        return "<a href='javascript:void(0)' onclick=getMerchant(".$data->merchant_id.",'Payment')>".$data->purchase_status."</a>";
                   }
                   return $data->purchase_status;
               },                           
                'filter'=>array("Purchased"=>"Purchased","Not Purchase"=>"Not Purchase","License Expired"=>"License Expired","Trial Expired"=>"Trial Expired"),
            ],
            /*[
                'label'=>'Is Config',
                'attribute' => 'config',
                'value' => function($data)
                {
                    $isSet = Jetappdetails::checkConfiguration($data->merchant_id);
                    if ($isSet){
                        return "Yes";
                    }else{
                        return "No";
                    }                   
                },
            ],*/
            [
                'label' => 'Config Set',
                'attribute' => 'config',
                'value' => function($data){
                    if($data['config']){
                        return "Yes";
                    } else {
                        return "No";
                    }
                },
                'filter' => ['no'=>'No', 'yes'=>'Yes']
            ],
            [
                'label'=>'Valid Number',
                'attribute'=>'mobile',
                'value' => function($data){
                    $data['mobile'];
                     
                },
                'format'=>'raw',
            ],

            [
                'label'=>'Installed On',
                'attribute'=>'installed_on',                
            ], 
            
            [
                'label'=>'Expired On',
                'attribute'=>'expired_on',              
            ],
        	/*[
        		'label'=>'Live',
        		'value' => function($data){
                    $query="SELECT COUNT(*) as count FROM (SELECT `variant_id` as opt_id FROM `jet_product` WHERE merchant_id='".$data->merchant_id."' AND `status`='Available for Purchase' UNION SELECT `option_id` as opt_id FROM `jet_product_variants` WHERE merchant_id='".$data->merchant_id."' AND `status`='Available for Purchase') as `main` LIMIT 0,1";            
                    $result = Data::sqlRecords($query, 'one','select');
                    return $result['count'];
                },
               'headerOptions' => ['style'=>'min-width: 140px;'],
                'filter'=>'From : <input class="form-control" name="JetShopDetailsSearch[live_from]" type="text" value="'.$searchModel->live_from.'"/><br/>To: <input class="form-control" name="JetShopDetailsSearch[live_to]" type="text" value="'.$searchModel->live_to.'"/>',
                'format'=>'raw',
        	],
        	[
        		'label'=>'Review',
        		'value' => function($data){
                    $query="SELECT COUNT(*) as count FROM (SELECT `variant_id` as opt_id FROM `jet_product` WHERE merchant_id='".$data->merchant_id."' AND `status`='Under Jet Review' UNION SELECT `option_id` as opt_id FROM `jet_product_variants` WHERE merchant_id='".$data->merchant_id."' AND `status`='Under Jet Review') as `main` LIMIT 0,1";
                    $result = Data::sqlRecords($query, 'one');
                    return $result['count'];
        		},
                'headerOptions' => ['style'=>'min-width: 140px;'],
                'filter'=>'From : <input class="form-control" name="JetShopDetailsSearch[review_from]" type="text" value="'.$searchModel->review_from.'"/><br/>To: <input class="form-control" name="JetShopDetailsSearch[review_to]" type="text" value="'.$searchModel->review_to.'"/>',
        		'format'=>'raw',
        	],*/
        	/*[
        		'label'=>'Order',
        		'value' => function($data){
                    return OrderInfo::getCompletedOrdersCount($data->merchant_id);	        		
        		},
                'headerOptions' => ['style'=>'min-width: 140px;'],
        		'filter'=>'From : <input class="form-control" name="JetShopDetailsSearch[order_from]" type="text" value="'.$searchModel->order_from.'"/><br/>To: <input class="form-control" name="JetShopDetailsSearch[order_to]" type="text" value="'.$searchModel->order_to.'"/>',
                'format'=>'raw',
        	],
            [
                'label'=>'Revenue',
                'value' => function($data){
                    return Earninginfo::getTotalEarning($data->merchant_id);  
                },
                'format'=>'raw',
            ],*/
            [
                'label'=>'S Username',
                'attribute'=>'seller_username',                
            ],  
            [
                'label'=>'S Password',
                'attribute'=>'seller_password',                
            ],        		
        	
            [
                'label'=>'Uninstalled On',
                'attribute'=>'uninstall_date',              
            ],
            
            [
              'attribute'=>'last_login',
              //'format'=>'datetime',//date,datetime, time
            ],
            'ip_address',
            'prod_import_limit',
            [
                'attribute' => 'user_status',
            ],
        ],
    ]); ?>
<?php if(isset($this->assetBundles)):?>
    <?php unset($this->assetBundles['yii\web\JqueryAsset']);?>
<?endif;?>  
<?php Pjax::end(); ?>
<?=Html::endForm();?>
</div>
<div id="merchant_register" style="display:none"></div>
<script type="text/javascript">
var csrfToken = $('meta[name="csrf-token"]').attr("content");
    function getMerchant(id,param)
    {
        var url='<?= $urlRegister ?>';
        $('#LoadingMSG').show();
        $.ajax({
            method: "post",
            url: url,
            data: {id:id,param:param,_csrf : csrfToken }
        })
        .done(function(msg) {
            //console.log(msg);
            $('#LoadingMSG').hide();
            $('#merchant_register').html(msg);
            $('#merchant_register').css("display","block");
            $('#merchant_register #myModal').modal('show');
        });
    }
    function selectPage(node){
      var value=$(node).val();
      $('#jet_shop_details').children('select.form-control').val(value);
    }
$('.export_csv_submit').click(function(event){
  if($("input:checkbox:checked.bulk_checkbox").length == 0)
  {
    alert('please select merchant id to perform bulk action');
    event.preventDefault();
  }
});
</script>
