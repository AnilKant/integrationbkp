<div id="chat-box-actions" ><a id="load-previous">Load Previous Messages</a><a id="clear-all">Clear All</a></div>
<ul class="note-list">
	
</ul>
<input type="text" id="message">
<button id="send-btn">Send</button>
<script src="//code.jquery.com/jquery-3.2.1.js"></script>
<script type="text/javascript">

jQuery(document).ready(function(){
	//create a new WebSocket object.
	var wsUri = "ws://192.168.0.58:9002";
	websocket = new WebSocket(wsUri); 
	var vendorSid="vendorSid";
	var vendorRid="vendorRid";
	
	websocket.onopen = function(ev) { // connection is open 
		console.log("Connected!"); //notify user
		//prepare json data
		var msg = {
			hash: '<?= $hash ?>'
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));
	}
	jQuery('#load-previous').click(function(){
		jQuery.post('<?= \yii\helpers\Url::toRoute(['chat/load','id'=>10]) ?>',function(data){
			if(data==''){
				jQuery('.note-list').prepend('<li>No Previous Chat Available</li>');
			}
			else
			{
				jQuery('.note-list').html(data);
			}
			jQuery('#load-previous').remove();
			
		});
	});
	jQuery('#clear-all').click(function(){
		jQuery.post('<?= \yii\helpers\Url::toRoute(['chat/clear','id'=>10]) ?>',function(data){

			jQuery('#clear-all').remove();
			jQuery('.note-list').html('');
			jQuery('.note-list').prepend('<li>Chat Cleared Successfully..</li>');
		});
	});

	jQuery('#send-btn').click(function(){ //use clicks message send button	
		var mymessage = jQuery('#message').val(); //get message text
		var mapdata="teammember"+"-"+"vendor";
		if(mymessage == ""){ //emtpy message?
			alert("Enter Some message Please!");
			return;
		}
		
		//prepare json data
		var msg = {
		message: mymessage
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));
	});
	
	//#### Message received from server?
	websocket.onmessage = function(ev) {
		var msg = JSON.parse(ev.data); //PHP sends Json data
		var id=msg.user_id;//get vendor id from server
		var umsg = msg.message; //message text
		var user_name=msg.sender_name;
		console.log(msg);
		
		jQuery('.note-list').append('<li ><span class="user-name">'+user_name+'</span> : <span>'+umsg+"</span></li>");

		

		
		
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
