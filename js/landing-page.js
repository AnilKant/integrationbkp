$(document).ready(function(){
	var ht=$(window).height();
	var fh = $('.footer').height();
	ht1 = ht-(fh+130);	
	$(".gradient-bg").css("min-height", ht1);
});


$(document).ready(function(){
	 minheight();
	$( window ).resize(function() {
		minheight();
	});
});
function minheight(){
	var height = $(window).height();
	var width = $(window).width();
	if(width >= 768) {
		$(".wrapper").css("height", height);
	}
	else{
		$(".wrapper").css("height", "auto");
	}
	
}