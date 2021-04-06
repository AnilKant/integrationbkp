$(document).ready(function () {
    $('.dropdown').on('show.bs.dropdown', function (e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
    });

    // Add slideUp animation to dropdown
    $('.dropdown').on('hide.bs.dropdown', function (e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp(300);
    });

    //Show/Hide Pie Chart
    $(".tabs").on("click", function (e) {
        var id = $(this).attr('id');
        if (id == 'order') {
            $("#product_piechart").hide();
            $("#order_piechart").show();
        } else if (id == 'product') {
            $("#product_piechart").show();
            $("#order_piechart").hide();
        }
    });


    stickfooter();
    owlcl();
    manage_counter();
    $(window).resize(function () {
        manage_counter();
    });
});

function owlcl() {
    if ($(".customer-review-inner").length > 0) {

        $('.customer-review-inner').each(function () {
            $(this).owlCarousel({
                pagination: true,
                loop: true,
                nav: false,
                items: 1,
                itemsDesktop: [1199, 1],
                itemsDesktopSmall: [979, 1],
                itemsTablet: [768, 1],
                slideSpeed: 500,
                paginationSpeed: 800,
                rewindSpeed: 1000,
                autoHeight: true,
                addClassActive: true,
                autoplay: true,
                autoPlaySpeed: 5000,
                autoPlayTimeout: 5000
            });
        });
    }
}
function stickfooter() {
    var ht = $(window).height();
    var copyht = $('.copyright-section').height();
    var fh = ht - (copyht);
    var fs = $('.footer-section').height();
    if ($('body').has('.footer-section')) {
        var wfs = fh - fs;
        $('.ced-jet-navigation-mbl').css('min-height', wfs);
    }
    else {
        $('.ced-jet-navigation-mbl').css('min-height', fh);
    }
}

function manage_counter() {
    if ($(document).find('.trial-wrapper').length) {
        var th = $('.trial-wrapper').height();
        $('.navbar-default').css('margin-top', th + 10);
        if ($('div').has('.fixed-container-body-class')) {
            $('.fixed-container-body-class').css('padding-top', th + 67);
        }
    }
    else {
        $('.navbar-default').css('margin-top', '0');
    }
}
$("#closeImpNotice").click(function () {
    var th1 = $('.trial-wrapper').height();
    $("#counter-wrapper").slideUp("slow", function () {
    });
    // $(".fixed-container-body-class").slideUp("slow", function(){});
    $('.fixed-container-body-class').css('padding-top', th1 + 25);
    $('.navbar-default').css('margin-top', '0');
});

$("#show_apps_div").click(function () {
    var x = document.getElementById('display_apps');
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
});
$("#testing-trial").click(function () {
    var x = document.getElementById('show_plan');
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
});
