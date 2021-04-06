<div>
	Active Merchants:
	<ul id="merchant-chat-list">
		<?php foreach($activeMerchants as $merchant){ ?>
		<li class="chat-merchant" merchant-id="<?= $merchant['merchant_id'] ?>" id="li<?= $merchant['merchant_id'] ?>">
			<span id="status<?= $merchant['merchant_id'] ?>" class="connected">connected</span>
			<span id="title<?= $merchant['merchant_id'] ?>" ><?= $merchant['merchant_id'] ?></span>
			<div style="display:none;" id="conversation<?= $merchant['merchant_id'] ?>">
				<ul id="conversation-list<?= $merchant['merchant_id'] ?>">

				</ul>
			</div>
		</li>
		<?php } ?>
	</ul>
</div>
<div id="chat-box" style="display:none;">
	<div id="chat-box-actions" ><a id="load-previous">Load Previous Messages</a><a id="clear-all">Clear All</a></div>
	<ul id="merchant-message">
		
	</ul>
	<input current-merchant="" type="text" id="message">
	<button current-merchant="" id="send-btn">Send</button>
</div>
<script src="//code.jquery.com/jquery-3.2.1.js"></script>
<script type="text/javascript">

jQuery(document).ready(function(){
	//create a new WebSocket object.
	var wsUri = "ws://192.168.0.58:9002";
	websocket = new WebSocket(wsUri); 
	var vendorSid="vendorSid";
	var vendorRid="vendorRid";

	jQuery('#load-previous').click(function(){
		var id = jQuery('#send-btn').attr('current-merchant');
		jQuery.post('<?= \yii\helpers\Url::toRoute(['chat/load']) ?>?id='+id,function(data){
			if(data==''){
				jQuery('#merchant-message').prepend('<li>No Previous Chat Available</li>');
			}
			else
			{
				jQuery('#merchant-message').prepend(data);
			}
			jQuery('#load-previous').hide();
			
		});
	});
	jQuery('#clear-all').click(function(){
		var id = jQuery('#send-btn').attr('current-merchant');
		jQuery.post('<?= \yii\helpers\Url::toRoute(['chat/clear']) ?>?id='+id,function(data){

			jQuery('#clear-all').hide();
			jQuery('#merchant-message').html('');
			jQuery('#merchant-message').prepend('<li>Chat Cleared Successfully..</li>');
		});
	});
	jQuery('#merchant-chat-list').on('click','.chat-merchant',function(){
		var id = jQuery(this).attr('merchant-id');
		$chatHtml = jQuery('#conversation-list'+id).html();
		jQuery('#merchant-message').html($chatHtml);
		jQuery('#chat-box').show();
		jQuery('#send-btn').attr('current-merchant',id);

	});
	websocket.onopen = function(ev) { // connection is open 
		console.log("Connected!"); //notify user
		//prepare json data
		var msg = {
			hash: '<?= $hash ?>'
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));
	}

	jQuery('#send-btn').click(function(){ //use clicks message send button	
		var mymessage = jQuery('#message').val(); //get message text
		var mapdata="teammember"+"-"+"vendor";
		if(mymessage == ""){ //emtpy message?
			alert("Enter Some message Please!");
			return;
		}
		var id = jQuery(this).attr('current-merchant');
		//prepare json data
		if(id==''){
			alert('Kindly Select Merchant');
			return;
		}
		var msg = {
			message: mymessage,
			id: id
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));
	});
	
	//#### Message received from server?
	websocket.onmessage = function(ev) {
		var msg = JSON.parse(ev.data); //PHP sends Json data
		var id=msg.user_id;//get vendor id from server
		var umsg = msg.message; //message text
		var user_name = msg.sender_name;
		//list all client

		console.log(msg);

		if(msg.message_type=='status'){
			if(jQuery('#status'+id).length>0){
				jQuery('#status'+id).attr('class',msg.message);
				jQuery('#status'+id).html(msg.message);		
			}
			else
			{
				$html = '<li class="chat-merchant" merchant-id="'+id+'" id="li'+id+'">\
					<span id="status'+id+'" class="connected">connected</span>\
					<span id="title'+id+'" >'+id+'</span>\
					<div style="display:none;" id="conversation'+id+'">\
					<ul id="conversation-list'+id+'"></ul>\
					</div>\
				</li>';
				jQuery('#merchant-chat-list').append($html);
			}
			
		}else{
			$formattedMsg = '<li ><span class="user-name">'+user_name+'</span> : <span>'+umsg+"</span></li>";
			if(id=='shopify_walmart_team')
				id = jQuery('#send-btn').attr('current-merchant');
			console.log(jQuery('#conversation-list'+id).length);
			if(jQuery('#conversation-list'+id).length>0){
				jQuery('#conversation-list'+id).append($formattedMsg);
				if(parseInt(jQuery('#send-btn').attr('current-merchant'))==parseInt(id)){
					jQuery('#merchant-message').append($formattedMsg);
				}
			}
			else
			{

				$html = '<li class="chat-merchant" merchant-id="'+id+'" id="li'+id+'">\
					<span id="status'+id+'" class="connected">connected</span>\
					<span id="title'+id+'" >'+id+'</span>\
					<div id="conversation'+id+'">\
					<ul id="conversation-list'+id+'">\
					'+$formattedMsg+'\
					</ul>\
					</div>\
				</li>';
				jQuery('#merchant-chat-list').append($html);
			}
		}
		
		

		

		
		
		jQuery('#message').val(''); //reset text
	};
	
	websocket.onerror	= function(ev){
	var notified="Error Occurred-"+ev.data;
	alert(notified);
	}; 
	websocket.onclose 	= function(ev){
		alert("Connection Closed")
	}; 
});

</script>
