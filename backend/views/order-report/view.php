<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->title = 'Total Orders';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="jet-order-import-error-index content-section">
<div class="form new-section">
    <div class="jet-pages-heading">
        <div class="title-need-help">
            <h1 class="Jet_Products_style"><?= Html::encode($this->title) ?></h1>
        </div>
        <?= Html::a('Order Reporting', yii\helpers\Url::toRoute('order-report/index'), ['data-step'=>'8', 'data-position'=>'left', 'data-intro'=>'Back order report page' ,'class' => 'btn btn-primary']) ?>
        <div class="clear"></div>
    </div>

    <?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
    <div class="responsive-table-wrap">
        <?= GridView::widget([
            'id' => "ordererror_grid",
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                //['class' => 'yii\grid\CheckboxColumn'],
                [
                    'attribute' => 'order_id',
                    'headerOptions' => ['data-toggle' => 'tooltip1', 'title' => 'Order Id'],
                ],
                [
                    'attribute'=> 'merchant_id',
                    'label'=>'Merchant Id',
                    'format' => 'raw',
                    'value'=> function($data){  
                        if($data['marketplace']=='jet')
                            $marketplaceUrl= Yii::getAlias('@webjeturl');      
                        elseif($data['marketplace']=='walmart')
                            $marketplaceUrl= Yii::getAlias('@webwalmarturl');
                        elseif($data['marketplace']=='wish')
                            $marketplaceUrl= Yii::getAlias('@webwishurl');
                        elseif($data['marketplace']=='bonanza')
                            $marketplaceUrl= Yii::getAlias('@webbonanzaurl'); 
                        elseif($data['marketplace']=='sears')
                            $marketplaceUrl= Yii::getAlias('@websearsurl');   
                        elseif($data['marketplace']=='newegg')
                            $marketplaceUrl= Yii::getAlias('@webneweggurl');
                        elseif($data['marketplace']=='newegg_ca')
                            $marketplaceUrl= Yii::getAlias('@webneweggcanadaurl');
                        elseif($data['marketplace']=='etsy')
                            $marketplaceUrl= Yii::getAlias('@webetsyurl');
                        elseif($data['marketplace']=='bestbuyca')
                            $marketplaceUrl= Yii::getAlias('@webbestbuycaurl'); 
                        elseif($data['marketplace']=='walmart-canada') 
                            $marketplaceUrl= Yii::getAlias('@webwalmartcaurl');
                        elseif($data['marketplace']=='pricefalls') 
                            $marketplaceUrl= Yii::getAlias('@webpricefallsurl');
                        elseif($data['marketplace']=='tophatter') 
                            $marketplaceUrl= Yii::getAlias('@webtophatterurl');  
                        elseif($data['marketplace']=='fruugo') 
                            $marketplaceUrl= Yii::getAlias('@webfruugourl');
                        return Html::a($data['merchant_id'], $marketplaceUrl.'/site/managerlogin?ext='.$data['merchant_id'].'&&enter=true', ['target' => '_blank','title' => 'click to login as a merchant']);
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'headerOptions' => ['data-toggle' => 'tooltip1', 'title' => 'Reason'],
                    'value' => function($data){
                        if($data['marketplace']=='etsy'){
                            return date('Y-m-d H:i:s',$data['created_at']);
                        }
                        return $data['created_at'];
                    }
                ],                
                [
                    'attribute' => 'reason',
                    'headerOptions' => ['data-toggle' => 'tooltip1', 'title' => 'Created At'],
                ],
                [
                    'attribute' => 'time_diff',
                    'label' => 'Fetch difference time',
                    'format' => ['html'],
                    'value' => function($data){
                        if($data['marketplace']=='etsy')
                        {
                            if(!is_null($data['created_at_app']) && !is_null($data['created_at']))
                            {
                                $data['created_at'] = date('Y-m-d H:i:s',$data['created_at']);
                                $time = [];
                                $remain_days_obj = date_diff(date_create($data['created_at_app']),date_create($data['created_at']));
                                if($remain_days_obj->d)
                                    $time[]=$remain_days_obj->d.' day';
                                if($remain_days_obj->h)
                                    $time[]=$remain_days_obj->h.'hr';
                                if($remain_days_obj->i)
                                    $time[]=$remain_days_obj->i.'min';
                                if($remain_days_obj->s)
                                    $time[]=$remain_days_obj->s.'sec';
                                return $data['created_at_app'].'<hr>'.implode(' : ',$time);
                            }

                        }
                        return 'not-set';
                    }

                ]
                /*[
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{vieworder}{cancel}{link}',
                    'buttons' => [
                    	'vieworder' => function ($url,$model) 
                		{
                        	return Html::a(
                                '<span class="glyphicon glyphicon-eye-open"> </span>',
                                'javascript:void(0)',['title'=>'Order detail on Jet','data-pjax'=>0,'onclick'=>'checkorderstatus(this.id)','id'=>$model->merchant_order_id]);                        	
                		},                   	
                		'cancel' => function ($url,$model) 
                		{
                			if ($model->status =="ready") 
                			{
                            	return Html::a(
                                    '<span>Cancel</span>',
                                    'javascript:void(0)',['title'=>'Cancel Order on Jet','data-pjax'=>0,'onclick'=>'cancel(this.id)','id'=>$model->merchant_order_id]);
                        	}
                		},                    	                        
                    ],
                ],*/
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
    <?= Html::endForm(); ?>
    </div>
</div>