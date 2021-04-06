<?php 
	$this->title = 'Send Mail to Clients';

?>              
<script type="text/javascript" src="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/js/jquery.wysiwygEditor.js"></script>
<link rel="stylesheet" href="<?= str_replace('/admin','',Yii::$app->request->baseUrl)?>/css/jquery.wysiwygEditor.css"/>

<!-- quick email widget -->
 <div class="box box-info">
    <div class="box-header">
        <h3 class="box-title"> <i class="fa fa-envelope"></i> Quick Email for merchant</h3>
    </div>
    <div class="box-body" id="form-body-data">
       <form action="#" method="post">
           <div class="form-group">
           	   <span><b>Recipient Eamil Id</b></span>
               <input type="email" class="form-control" id="clientMailId" name="emailto" placeholder="Email to:">
           </div>
           <div class="form-group">
           		<span><b>Subject</b></span>
               <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
           </div>
            <div> 
            	<span><b>Enter your Message</b></span>
               <textarea  class="wysiwyg-editor" id="msg" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
           </div> 
         <script type="text/javascript">
		    $('.wysiwyg-editor').wysiwygEditor();
		  </script>
       </form>
    </div>
    <div class="box-footer clearfix" style="margin-top: 2%;">
         <button class="pull-right btn btn-default" id="sendEmail" >Send <i class="fa fa-arrow-circle-right"></i></button>
    </div>
</div>
<div id="wait_load" style="display: none;" align="center">
	<img alt="wait" src="<?= str_replace('/admin','',Yii::$app->request->baseUrl).'/images/wait.gif'?>">
</div>
<div class="jet_config_popup_overlay" style="display: none;"></div>
<script type="text/javascript">
	$("#sendEmail").click(function(){
		var clientMailId = j$('#clientMailId').val();
		var subject		 = j$('#subject').val();
		var msg			 = j$('#msg').val();

		JSON.stringify(msg);

		// All fields must be filled
		if(clientMailId.trim()=="" || subject.trim()=="" || msg.trim()==""){
			alert("Please fill all the fields!");
			return false;
		}
		// Email Validation 
		var atpos = clientMailId.indexOf("@");
	    var dotpos = clientMailId.lastIndexOf(".");
	    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=clientMailId.length) {
	        alert("Please Enter a valid e-mail address");
	        return false;
	    }	    
	    // Post ajax request 
	    var sendmailquery="<?= Yii::$app->request->baseUrl?>/site/mailto";
	    //j$('#form-body-data').css('display','none');
	    j$('.jet_config_popup_overlay').css('display','block');
	    j$('#wait_load').css('display','block');
	    j$.ajax({
	        method: "GET",
	        url: sendmailquery,
	        data: { clientMailId : clientMailId ,subject: subject, message: msg }
	   }).done(function() {
		    j$('#clientMailId').val("");
		    j$('#subject').val("");
		    j$('#msg').val("");
		    j$('.jet_config_popup_overlay').css('display','none');	  
		    j$('#wait_load').css('display','none');
		    //j$('#form-body-data').css('display','block');
		    alert("Mail Sent to : "+clientMailId);
		    
	   });
	   
	   /*  j$('#clientMailId').val("");
	    j$('#subject').val("");
	    j$('#msg').val("");	  
	    alert("Mail Sent to : "+clientMailId);	      */
	});
</script>