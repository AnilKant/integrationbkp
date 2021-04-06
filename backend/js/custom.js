/************************Custom scroll js to slide tables************************/
    /*var heightTbody = 0;
    var containerWidth = 0;
    var contentWidth = 0;
    var scrollRight = 0;
    var scrollbarWidth = 0;//SCROLLBAR width actual
    var scrollbarHeight =0;
    var swipeHtml = '<div class="follow"><div class="swipe-left" id="swipeLeft" style="display:none;" ><span><i class="fa fa-chevron-left" aria-hidden="true"></i>'+
        '</span></div><div class="swipe-right" id="swipeRight" style="display:none;" ><span><i class="fa fa-chevron-right" aria-hidden="true"></i>'+
        '</span></div></div>';   
    $(document).on('pjax:success', function() {
        gridContentChanged();
    });
    $(function(){
        $('body').prepend(swipeHtml);
        heightTbody = $('table.table tbody').outerHeight();
        appendScrollTabFunction();
    });

    $(window).on('resize',function() {
        $('#swipeLeft').css('display', 'none');
        $('#swipeRight').css('display', 'none');
        containerWidth = 0;
        contentWidth = 0;
        scrollbarWidth = 0;
        scrollbarHeight = 0;
        heightTbody = $('table.table tbody').outerHeight();
        appendScrollTabFunction();
    });

    function gridContentChanged(){
        $('#swipeLeft').css('display', 'none');
        $('#swipeRight').css('display', 'none');
        containerWidth = 0;
        contentWidth = 0;
        scrollbarWidth = 0;
        scrollbarHeight = 0;
        heightTbody = $('table.table tbody').outerHeight();
        appendScrollTabFunction();
    }
    function appendScrollTabFunction(){
        containerWidth = $('div.grid-view').width();
        containerWidth = parseFloat(containerWidth);
        contentWidth = $('table.table').width();
        contentWidth = parseFloat(contentWidth);
        scrollbarHeight = getScrollbarHeight();
        //maxScroll = parseFloat(((containerWidth/contentWidth)*100).toFixed(2));//SCROLLBAR width
        if(contentWidth > containerWidth){
            scrollbarWidth = getScrollbarWidth();
            mainContentScroll();
        }
    }

    $(document).on('click', '#swipeRight', function(){
        $('div.grid-view').animate( { scrollLeft: contentWidth }, 800);
    });

    $(document).on('click', '#swipeLeft', function(){
        $('div.grid-view').animate( { scrollLeft: 0 }, 800);
    });
    $('div.grid-view').scroll(function(){
        if(contentWidth > containerWidth){
            mainContentScroll();
        }
    });
    $(document).scroll(function(){
        if(contentWidth > containerWidth){
            mainContentScroll();
        }
    });
    function mainContentScroll()
    {
        var currentScrollValue = $('div.grid-view').scrollLeft();
        currentScrollValue = parseFloat(currentScrollValue);
        var top = $('table.table tbody').offset().top;
        
        $('.follow').css('display', 'none');
        if((parseFloat($(window).scrollTop())+scrollbarHeight)>=top+100 && (parseFloat($(window).scrollTop())+scrollbarHeight)<=top + heightTbody+100){// - followHeight
            //scr += parseFloat($(window).scrollTop());//(parseFloat($(window).scrollTop())+scrollbarHeight)-top;
            $('.follow').css('display', 'block');
        }
        

        //if(elementInViewportNew(document.querySelectorAll('table.table tbody tr'))){//if(elementStart < scroll && elementEnd > scroll){
        if(currentScrollValue == 0){// && currentScrollValue < maxScroll
            //scrollbar at left part
            $('#swipeLeft').css('display', 'none');
            $('#swipeRight').css('display', 'block');
        }else if(currentScrollValue > 0  && currentScrollValue < contentWidth-scrollbarWidth){
            //scrollbar at middle part
            $('#swipeLeft').css('display', 'block');
            $('#swipeRight').css('display', 'block');
        }else{
            //scrollbar at right part
            $('#swipeLeft').css('display', 'block');
            $('#swipeRight').css('display', 'none');
        }
    }

    function getScrollbarWidth() {
        var original = $('div.grid-view').scrollLeft();
        $('div.grid-view').scrollLeft(contentWidth);
        scrollRight = $('div.grid-view').scrollLeft();
        scrollRight = parseFloat(scrollRight);
        $('div.grid-view').scrollLeft(original);
        return contentWidth - scrollRight
    }
    function getScrollbarHeight() {
        var original = $(document).scrollTop();
        $(window).scrollTop($(document).height());
        var scrollBottom = $(window).scrollTop();
        scrollBottom = parseFloat(scrollBottom);
        $(document).scrollTop(original);
        return parseFloat($(document).height()) - scrollBottom;
    }*/
/******************Custom scroll js to slide tables end******************/

/***********Script for custom scroll secondary*****************/

 var scrollHtml = '<div class="ced-horizontal-scroll"><div class="ced-horizontal-wrap"><div class="ced-horizontal-inner"></div></div></div>';  
$(document).ready(function(){
$('.ced-navbar-top').prepend(scrollHtml);
 setTimeout(function(){
  var tablewth = $('.grid-view table.table-bordered').width();
  
  $(".ced-horizontal-inner").css('width',tablewth);
  leftscroll();
  $('.ced-horizontal-wrap').on('scroll', function(){
   leftscroll();
  });
 }, 2000);
});

function leftscroll(){
 var scrlft = $('.ced-horizontal-wrap').scrollLeft();
 $('.grid-view table.table-bordered').css('left',-scrlft);
}

/*********************Script for custom scroll secondary end*********************/

/**************Script to stop closing sidebar menu on page click**************/

$('.page-main').click(function(event){
    event.stopPropagation();
});

/**************Script to stop closing sidebar menu on page click end**************/
