<?php 

	$succes_img = 'http://apps.cedcommerce.com/static/modules/jet/assets/images/batchupload/fam_bullet_success.gif';
	$error_img = 'http://apps.cedcommerce.com/static/modules/jet/assets/images/batchupload/error_msg_icon.gif';
	$loader_img = 'http://apps.cedcommerce.com/static/modules/jet/assets/images/batchupload/rule-ajax-loader.gif';
	$warning_img ='http://apps.cedcommerce.com/static/modules/jet/assets/images/batchupload/fam_bullet_error.gif';
	$msg = "Syncing";
	if($action =="OrderApi"){
		$url = Yii::getAlias('/magento-fruugo-info/update-order-data?debug');
	}else{
		$url = Yii::getAlias('/magento-fruugo-info/update-order-info');
	}
	$backUrl = \yii\helpers\Url::to(Yii::$app->request->referrer);
?>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<style type="text/css" >
   .shopify-api ul { list-style-type:none; padding:0; margin:0; }
   .shopify-api ul li { margin-left:0; border:1px solid #ccc; margin:2px;. padding:2px 2px 2px 2px; font:normal 12px sans-serif; }
   .shopify-api img { margin-right:5px; }
   li span ul li{
   	border : 0px !important;
   	margin-left:18px !important;
   }
</style>

<div class="row">
    <div class="col-md-12" style="margin-top: 10px;">
        <div class="panel panel-default form new-section">
            <div class="jet-pages-heading">
                    <div class="title-need-help">
                        <h1 class="Jet_Products_style">order data update</h1>
                    </div>
                    <div class="product-upload-menu">
                        <a href="<?= $backUrl;?>">
                            <button class="btn btn-primary uptransform" type="button" title="Back"><span>Back</span></button>
                        </a>
                    </div>
                    <div class="clear"></div>
            </div>
        	<div class="block-content panel-body shopify-api ">         
                <ul class="warning-div" style="margin-top: 18px">
					<li style="background-color:#Fff;">
						<img src="http://apps.cedcommerce.com/static/modules/jet/assets/images/batchupload/note_msg_icon.gif" class="v-middle" style="margin-right:5px"/>
						Starting Product <?=$msg?>, please wait...
					</li>
					<li style="background-color:#FFD;">
						<img src="http://apps.cedcommerce.com/static/modules/jet/assets/images/batchupload/fam_bullet_error.gif" class="v-middle" style="margin-right:5px"/>
						Warning: Please do not close the window during product <?=$msg?> process
					</li>
				</ul>
				
				<ul id="profileRows">
					<li style="background-color:#DDF; ">
						<img class="v-middle" src="<?php echo $succes_img ?>">
						<?php echo 'Total '.$totalcount.' Product(s) Selected for '.$msg; ?>
					</li>
					<li style="background-color:#DDF;" id="update_row">
						<img class="v-middle" id="status_image" src="<?php echo $loader_img ?>">
						<span id="update_status" class="text"><?=$msg?>ing...</span>
					</li>
					<li id="liFinished" style="display:none;background-color:#Fff;">
						<img src="http://apps.cedcommerce.com/static/modules/jet/assets/images/batchupload/note_msg_icon.gif" class="v-middle" style="margin-right:5px"/>
						Finished order Data update process.
					</li>
				</ul>
            </div>
        </div>     
    </div>
</div>
<script type="text/javascript"> 
    //var $ = j$;
	var csrfToken = $('meta[name=csrf-token]').attr("content");
	var totalRecords = parseInt("<?php echo $totalcount; ?>");
	var pages = parseInt("<?php echo $pages; ?>");
	var countOfSuccess = 0;
	var id = 0;
	var my_id = document.getElementById('liFinished');	
	var update_status = document.getElementById('update_status');
	var update_row = document.getElementById('update_row');
	var status_image = document.getElementById('status_image');
	uploaddata();		

	function uploaddata(){
		percent = getPercent();
		update_status.innerHTML = percent+'% Page '+(id+1)+' Of total product pages '+pages+' Processing';

		$.ajax({
            url:'<?= $url?>',
            method: 'POST',
            contentType: "application/json; charset=utf-8", // this
            dataType: "json",
            data:{ index : id},
        	success: function(json) {
            	id++;
				if(json.success) {
					countOfSuccess += json.success_count;
					var span = document.createElement('li');
					span.innerHTML = '<img class="v-middle" src="<?= $succes_img ?>"><span class="text"><b>'+id+' '+json.success+'.</span>';
					span.id = 'id-'+id;
					span.style = 'background-color:#DDF';
					update_row.parentNode.insertBefore(span, update_row);
				}

				if(json.warning) {
					var span = document.createElement('li');
					span.innerHTML = span.innerHTML+'<img class="v-middle" src="<?= $error_img ?>"><span class="text"> Product(s) Having validation error '+json.warning_msg +' '+'</span>';
					span.style = 'background-color:#FDD';
					update_row.parentNode.insertBefore(span, update_row);
				}

				if(json.error){
					var span = document.createElement('li');
					countOfSuccess += json.success_count;
					span.innerHTML = span.innerHTML+'<img class="v-middle" src="<?= $error_img ?>"><span class="text">'+json.error +'</span>';
					span.style = 'background-color:#FDD';
					update_row.parentNode.insertBefore(span, update_row);					
				}
				
				if(id < pages) 
				{
					uploaddata();
				} 
				else 
				{
					status_image.src = '<?php echo $succes_img ?>';
					var span = document.createElement('li');
					span.innerHTML = '<img class="v-middle" src="<?php echo $succes_img ?>"><span id="update_status" class="text">'+countOfSuccess+' Order Data(s) Successfully Updated .'+'</span>';
					span.style = 'background-color:#DDF';
					my_id.parentNode.insertBefore(span, my_id);
					
					document.getElementById("liFinished").style.display="block";
					$(".warning-div").hide();
					$("#profileRows").css({'margin-top':'10px'});
					update_status.innerHTML = percent+'% '+(id)+' Of '+pages+' Processed.';
				}
            },            
			error: function(json) {
				id++;
				var span = document.createElement('li');
				span.innerHTML = '<img class="v-middle" src="<?php echo $error_img ?>"><span class="text">No data to update</span>';
				span.id = 'id-'+id;
				span.style = 'background-color:#FDD';
				update_row.parentNode.insertBefore(span, update_row);
				
				if(id < pages)
				{
					uploaddata();
				}
				else
				{
					status_image.src = '<?php echo $succes_img ?>';
					var span = document.createElement('li');
					span.innerHTML = '<img class="v-middle" src="<?php echo $succes_img ?>"><span id="update_status" class="text"> '+countOfSuccess+' order Data(s) Successfully Updated .'+'</span>';
					span.style = 'background-color:#DDF';
					my_id.parentNode.insertBefore(span, my_id);

					$(".warning-div").hide();
					$("#profileRows").css({'margin-top':'10px'});
					document.getElementById("liFinished").style.display="block";
				}
			}
		});		
	}

	function getPercent() 
	{	
       return Math.ceil(((id+1)/pages)*1000)/10;
    }
	
</script>
