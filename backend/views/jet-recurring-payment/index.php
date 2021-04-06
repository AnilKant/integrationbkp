<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\JetRecurringPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments Details';
$this->params['breadcrumbs'][] = $this->title;
$urlpayment= \yii\helpers\Url::toRoute(['jet-recurring-payment/viewpayment']);
$urlcancel= \yii\helpers\Url::toRoute(['jet-recurring-payment/cancelpayment']);

$url='';

?>
<script type="text/javascript" src="<?=Yii::$app->urlManagerFrontEnd->getBaseUrl();?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?=Yii::$app->urlManagerFrontEnd->getBaseUrl();?>/js/jquery-ui-timepicker-addon.js"></script>
<link href="<?=Yii::$app->urlManagerFrontEnd->getBaseUrl();?>/css/jquery-ui.css" rel="stylesheet"/>
<link href="<?=Yii::$app->urlManagerFrontEnd->getBaseUrl();?>/css/jquery-ui-timepicker-addon.css" rel="stylesheet"/>
<div class="jet-recurring-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>    
<?php Pjax::begin(['id'=>'pjax-new-gridview', 'timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
    <?= GridView::widget([
        'id'=>"product_grid",
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
    	'rowOptions'=>function ($model){
    		if ($model->status=='cancelled'){
    			return ['class'=>'danger'];
    		}elseif ($model->status=='active'){
    			return ['class'=>'success'];
    		}
    	},
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'merchant_id',
        	[
        		'attribute'=>'email',
        		'label'=>'Email',
        		'value'=>'jet_shop_details.email',
        	],
        	[
        		'attribute'=>'expired_on',
        		'label'=>'Expired Date',
        		'value'=>'jet_shop_details.expired_on',
        		'filterOptions'=>['id'=>'expired_on'],
        		'filter'=>'From : <input id="expired_on_from" size="30" class="form-control" type="text" name="JetRecurringPaymentSearch[expired_on_from]" value="'.urldecode($searchModel->expired_on_from).'" /><br/>'.'To : <input id="expired_on_to" size="30" class="form-control" type="text" name="JetRecurringPaymentSearch[expired_on_to]" value="'.urldecode($searchModel->expired_on_to).'"/>',
            ],
        	//'billing_on',
            //'activated_on',
            [
                'attribute'=>'activated_on',
                'value'=>'activated_on',
                //'filterOptions'=>['id'=>'expired_on'],
                //'filter'=>'From : <input id="expired_on_from" class="form-control" type="text" name="JetRecurringPaymentSearch[expired_on_from]" value="'.urldecode($searchModel->expired_on_from).'" /><br/>'.'To : <input id="expired_on_to" class="form-control" type="text" name="JetRecurringPaymentSearch[expired_on_to]" value="'.urldecode($searchModel->expired_on_to).'"/>',
                'filter'=>'From : <input id="activated_on_from" size="30" class="form-control" type="text" name="JetRecurringPaymentSearch[activated_on_from]" value="'.urldecode($searchModel->activated_on_from).'" /><br/>'.'To : <input id="activated_on_to" size="30" class="form-control" type="text" name="JetRecurringPaymentSearch[activated_on_to]" value="'.urldecode($searchModel->activated_on_to).'"/>',
            
            ],
        	//'plan_type',
        	[
        		'format'=>'raw',
        		'attribute' => 'plan_type',
        		'contentOptions' =>function ($model, $key, $index, $column){
        		return ['class' => 'validate'];
        		},
        		'value'=>'plan_type',
        		
        		'filter'=>array("Recurring Plan (Yearly)"=>"Recurring Plan (Yearly)","Recurring Plan (Monthly)"=>"Recurring Plan (Monthly)"),
        	],
            //'status',
        	[
        		'format'=>'raw',
        		'attribute' => 'status',
        		'contentOptions' =>function ($model, $key, $index, $column){
        		return ['class' => 'validate'];
        		},
        		'value'=>'status',
        		
        		'filter'=>array("active"=>"active","cancelled"=>"cancelled"),
        	],
        	
            // 'recurring_data:ntext',

            [
        		'class' => 'yii\grid\ActionColumn',
        		'template' => '{view}{link}{cancel}',
        		'buttons' => [
        		'view' => function ($url,$model)
    				{
    					return Html::a(
							'<span class="glyphicon glyphicon-eye-open "> </span>',
							'javascript:void(0)',['data-pjax'=>0,'onclick'=>'clickView(this.id,this.rev,this.type)','title'=>'Check Payment Detail','id'=>$model->id,'rev'=>$model->merchant_id,'type'=>$model->plan_type]
						);
    			    },
                'cancel' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-remove-circle"> </span>',
                            'javascript:void(0)',['data-pjax'=>0,'onclick'=>'cancelmonthly(this.id,this.rev,this.type)','title'=>'Check Payment Detail','id'=>$model->id,'rev'=>$model->merchant_id,'type'=>$model->plan_type]
                        );
                    },
        		],
    		], 
        ],
    ]); ?>
 <?php if(isset($this->assetBundles)):?>
    <?php unset($this->assetBundles['yii\web\JqueryAsset']);?>
<?endif;?>   
<?php Pjax::end(); ?>
</div>
<div id="view_payment" style="display:none">
</div>
<script type="text/javascript">
    function cancelmonthly(id,mid,type){
        if (!confirm("Are you sure ?"))
        {
            return false;
        }
       $('#LoadingMSG').show();
        $.post("<?= $urlcancel ?>",
            {
                id:id, 
                merchant_id : mid,
                type : type,
            },
            function(data,status){
                
                $('#LoadingMSG').hide();
                alert(data);
                
            });
    }
	function clickView(id,mid,type){
		$('#LoadingMSG').show();
		$.post("<?= $urlpayment ?>",
	        {
				id:id, 
				merchant_id : mid,
				type : type,
	        },
	        function(data,status){
	        	$('#LoadingMSG').hide();
	            $('#view_payment').html(data);
	            $('#view_payment').css("display","block");	  
	            $('#view_payment #myModal').modal('show');
	        });
	}

 
$(document).ready(function(){
    // $(document).pjax("#expired_on_from", '.grid-view', { push: false, replaceRedirect: false });
    $(document).on('focus', "#expired_on_from, #activated_on_from", function(event){
        $(this).datetimepicker({
            showSecond: false,
            timeFormat: '00:00:00',//hh:ii:ss
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,

            beforeShow: function( input ) {

                setTimeout(function() {
                    var buttonPane = $( input )
                        .datepicker( "widget" )
                        .find( ".ui-datepicker-buttonpane" );
                    $( "<button>", {
                        text: "Reset",
                        /*click: function() {
                            console.log(this);
                           $.datepicker._clearDate( this );
                           $(instance).datetimepicker('setDate', null);
                            //$("#expired_on_from").datetimepicker('setDate', null);
                       }*/
                    }).on('click',{inp:input}, function(event){$(event.data.inp).datetimepicker('setDate', null);}).appendTo( buttonPane ).addClass("ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all");
                    $('.ui-datepicker').css('z-index', 99999999999999);
                    //$('.ui-datepicker').css('left', 900);
                }, 2 );
                
            },
            onChangeMonthYear: function( year, month, instance ) {
               
                //$(document.querySelector("#expired_on_from")).datetimepicker('setDate', null);
                setTimeout(function() {
                        var buttonPane = $( instance )
                            .datepicker( "widget" )
                            .find( ".ui-datepicker-buttonpane" );
                           
                        $( "<button>", {
                            text: "Reset",
                            click: function() {
                                // $.datepicker._clearDate( instance.input );
                                $("#"+instance.id).datetimepicker('setDate', null);
                               //$(document.querySelector("#expired_on_from")).datetimepicker('setDate', null);
                            }
                        })./*on('click',{inp:instance}, function(event){$('#'+event.data.inp.id).datetimepicker('setDate', null);}).*/appendTo( buttonPane ).addClass("ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all");
                        $('.ui-datepicker').css('z-index', 99999999999999);
                        //$('.ui-datepicker').css('left', 900);
                    }, 1 );
                
               
            },
            onClose: function(dateText, inst){
              $('#product_grid-filters input').trigger('keyup', [{somedata:true}]);
            },
            /*beforeShow: function(input, inst){
                $('a.ui-datepicker-prev').attr('data-pjax', false);
                $('a.ui-datepicker-next').attr('data-pjax', false);
            },*/
            //pickTime: false,
            /*onSelect: function(dateText, inst){
                alert(dateText);
            },*/
            //changeMonth: true,
            //changeYear: true,
            //autoSize: true,
            /*{format: 'yyyy-mm-dd hh:ii:ss'}*/
        });

    });
    
	$(document).on('focus', "#expired_on_to, #activated_on_to", function(){
        $(this).datetimepicker({
            showSecond: false,
            timeFormat: '23:59:59',//hh:ii:ss
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            onClose: function(dateText, inst){
               //$("#product_grid").yiiGridView("applyFilter");

               $('#product_grid-filters input').trigger('keyup', [{somedata:true}]);
            },
             beforeShow: function( input ) {

                setTimeout(function() {
                    var buttonPane = $( input )
                        .datepicker( "widget" )
                        .find( ".ui-datepicker-buttonpane" );
                    $( "<button>", {
                        text: "Reset",
                        click: function() {
                            //$.datepicker._clearDate( this );
                            $(input).datetimepicker('setDate', null);
                        }
                    }).appendTo( buttonPane ).addClass("ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all");
                    $('.ui-datepicker').css('z-index', 99999999999999);
                    //$('.ui-datepicker').css('left', 900);
                }, 2 );
                
            },
            onChangeMonthYear: function( year, month, instance ) {
               setTimeout(function() {
                        var buttonPane = $( instance )
                            .datepicker( "widget" )
                            .find( ".ui-datepicker-buttonpane" );
                           
                        $( "<button>", {
                            text: "Reset",
                            click: function() {
                                //$.datepicker._clearDate( instance.input );
                                $("#"+instance.id).datetimepicker('setDate', null);
                               //$(document.querySelector("#expired_on_from")).datetimepicker('setDate', null);
                            }
                        }).appendTo( buttonPane ).addClass("ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all");
                        $('.ui-datepicker').css('z-index', 99999999999999);
                        //$('.ui-datepicker').css('left', 900);
                    }, 1 );
                
               
            },
           
        });
    });
   /* $(document).on('pjax:beforeSend', function(xhr, options){
        var str = "";
        str = xhr.currentTarget.activeElement.className;
        console.log(xhr);
        console.log($(xhr.currentTarget.activeElement).closest('div#ui-datepicker-div'));
        console.log(str.includes('ui-datepicker-close'));
        if(!(str.includes('ui-datepicker-close')) && $(xhr.currentTarget.activeElement).closest('div#ui-datepicker-div').length>0){
            return false;
        }
    });*/
	
});
/*$(document).on('pjax:success', function(){
    if($(".ui-datepicker").length>0){
        $(".ui-datepicker").remove();
    }
});*/

var input;
var submit_form = false;
var filter_selector = '#product_grid-filters input, #product_grid-filters select';

$("body").on('beforeFilter', "#product_grid" , function(event) {
    console.log("aya");
    return submit_form;
});

$("body").on('afterFilter', "#product_grid" , function(event) {
    submit_form = false;
});

$(document)
.off('keydown.yiiGridView change.yiiGridView', filter_selector)
.on('keyup', filter_selector, function(event, data) {
    console.log('ppp');
    input = $(this).attr('name');
    if (event.keyCode == 13 || data.somedata) {
        if(submit_form === false) {
            submit_form = true;
            console.log('jhh');
            $("#product_grid").yiiGridView("applyFilter");
        }
    }
});
/*.on('pjax:success', function() {
    var i = $("[name='"+input+"']");
    var val = i.val();
    i.focus().val(val);
});*/
</script>

<style>
.ui-datepicker-next, .ui-datepicker-prev, .ui-datepicker-current{display:none;}
.ui-datepicker{
    z-index: 99999;
}
</style>
