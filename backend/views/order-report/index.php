    <?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    use function GuzzleHttp\json_decode;
    /* @var $this yii\web\View */
    /* @var $searchModel app\models\WalmartRecurringPaymentSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    $url = \yii\helpers\Url::toRoute(['order-report/index']);
    $this->title = 'Failed Order Report';
    $this->params['breadcrumbs'][] = $this->title;
    $isToday=false;
    if(date('Y-m-d')==$value){
        $isToday=true;
        $value=date('d-m-Y',strtotime($value));
    }   
    ?>
    <div class="order-report-index">
      <h1>Order Report</h1>
    <div class="failed-order-header"">
        <div class="clearfix">
            <div class="col-lg-12">
                <div class="on-date">
                    <label>Failed Orders On</label>
                    <select class="form-control" onchange="changeDuration(this)">
                         <option value=""></option>
                        <option value="1 DAY">1 Day</option>
                        <option value="2 DAY">2 Days</option>
                        <option value="1 WEEK">1 Week</option>
                    </select>
                </div>
        <div class="to-date">
        <label>Failed Orders Date</label>
        <?php if($isToday)
        {
            echo DatePicker::widget([
            'name' => 'date',
            'value' => $value,
            'id'=>'date-filter',
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy',
                    
                ],
            ]);
        }
        else
        {
            echo DatePicker::widget([
            'name' => 'date',
            'value' => '',
            'id'=>'date-filter',
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy',
                    
                ],
            ]);
        }?>
                </div>
             </div>
        </div>
    </div>
    <div class="failed-order-report">
        <div id="order_total_count" class="clearfix order_total_count">
        <?php 
            foreach ($order_total as $key => $val2) {
            ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 count-failed-<?=$key?>">
            <a href="<?=Yii::$app->request->baseUrl;?>/order-report/totalorder?param=<?=$param?>&marketplace=<?=$key?>&value=<?=$value?>">
               <div class="inner" style="background-color: #0BAC31; margin-bottom: 5px;">
                    <span class="dash-icon"><i aria-hidden="true" class="fa fa-eye fa-5x"></i></span>
                    <span class="count-val"><?= $val2['count']; ?></span>
                    <p class="order-head"><?= $key; ?> Total Orders</p>
                      <div class="outer_div outer_div_live">
                        More info ...<i class="fa live-row fa-arrow-circle-right"></i>
                      </div>
                    </div>
                     </a>
                </div>
             
             <?php }?>
        </div>
        <div style="clear:both;"></div>
        <div style="clear:both;"></div>

        <div id="failed-count-data" class="clearfix failed-count-data">
            <?php
            foreach ($data as $key=>$val) {
                if ($key!="jet_ready_order"){
            ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 count-failed-<?=$key?>">
             <a href="<?=Yii::$app->request->baseUrl;?>/order-report/view?param=<?=$param?>&marketplace=<?=$key?>&value=<?=$value?>">
            
                  <!-- small box -->
                  <div class="small-box bg-aqua" style="background-color: #bb321f" >
                        <div class="inner" >
                              <span class="dash-icon"><i aria-hidden="true" class="fa fa-exclamation-triangle fa-3x"></i></span>
                              <span class="count-val"><?php echo $val['count'];?></span>
                              <p class="order-head"><?=$key?> Failed Orders</p>
                        </div>
                        <div class="outer_div outer_div_live">
                            More info ...<i class="fa live-row fa-arrow-circle-right"></i>
                        </div>
                  </div>
                  </a>
                </div>
            
            <?php
                }
            }?>
        </div> 
        <div id="ready-count-data" class="clearfix ready-count-data">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3>Jet Ready State Order details</h3>
        <?php 
            if (!empty($data) && isset($data['jet_ready_order']) && trim($data['jet_ready_order'])!="" )
            {
                $readyData = [];
                $readyData = json_decode($data['jet_ready_order'],true);
                ?>
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    Merchant_id
                                </th>
                                <th>
                                    Ready Order
                                </th>
                                <th>
                                    Status Code
                                </th>
                                <th>
                                    Login
                                </th>
                            </tr>
                        </thead>
                        <tbody>                     
                            <?php
                                foreach ($readyData as $key=>$orderData){
                                    ?>
                                        <tr>
                                            <td><?= $orderData['merchant_id'] ?></td>
                                            <td><?= $orderData['ready_order_count'] ?></td>
                                            <td><?= $orderData['status_code'] ?></td>
                                            <td><a data-pjax="0" href="<?=Yii::getAlias('@webjeturl')?>/site/managerlogin?ext=<?=$orderData['merchant_id']?>&&enter=true">Login as</a></td>
                                        </tr>
                                        
                                    <?php 
                                }
                            ?>
                        </tbody>                    
                    </table>
                    </div>
                <?php 
            }    
        ?>
        </div>
        </div>           
    </div>
    </div>
    <script type="text/javascript">
        function changeDuration(node){
            var duration = $(node).val();
            if(duration)
            callAjaxRequest("duration",duration);
        }
        $(document).on('click','.day',function(){
            var date=$('#date-filter').val();
            callAjaxRequest("date",date);
        });
        function callAjaxRequest(param,param_value)
        {
            $.ajax({
               type: "POST",
               data: {param:param,value:param_value,isAjax:true,marketplace:'<?=Yii::$app->request->get('marketplace');?>'},
               url: "<?php echo $url;?>",
               success: function(countData)
               {
                    var html='';
                    var thtml = '';
                    var faliedCountData=[];
                    faliedCountData=JSON.parse(countData);
                    $.each(faliedCountData['data'], function( index, value ) 
                    {
                        if(value != null){
                            html+='<a href="<?php echo Yii::$app->request->baseUrl;?>/order-report/view?param='+faliedCountData['param']+'&marketplace='+index+'&value='+faliedCountData['value']+'"><div class="col-lg-3 col-xs-6 count-failed-'+index+'"><div class="small-box bg-aqua" style="background-color: #bb321f" ><div class="inner" ><span><i aria-hidden="true" class="fa fa-exclamation-triangle fa-3x"></i></span><span><h1>'+value['count']+'</h1></span><p>'+index+' Failed Orders</p></div><div class="outer_div outer_div_live">More info ...<i class="fa live-row fa-arrow-circle-right"></i></div></div></div></a>';
                        }
                    });
                    $('#failed-count-data').html('');
                    $('#failed-count-data').html(html);
                     $.each(faliedCountData['order_total'], function( index, value )
                    {
                       thtml+='<a href="<?php echo Yii::$app->request->baseUrl;?>/order-report/totalorder?param='+faliedCountData['param']+'&marketplace='+index+'&value='+faliedCountData['value']+'"><div class="col-lg-3 col-xs-6 count-failed-'+index+'"><div class="inner" style="background-color: #0BAC31;margin-bottom: 5px;"><span><i aria-hidden="true" class="fa fa-eye fa-5x"></i></span><span><h1>'+value['count']+'</h1></span><p>'+index+' Total Orders</p><div class="outer_div outer_div_live">More info ...<i class="fa live-row fa-arrow-circle-right"></i></div></div></div></a>';
                    });
                    $('#order_total_count').html('');
                    $('#order_total_count').html(thtml);
               }
            });
        }
    </script>