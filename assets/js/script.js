jQuery(function ($) {
    // Products list/grid view
    $('.dokani-products-view.buttons > button').on('click', function (e) {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');

        if ($(this).hasClass('grid')) {
            $('.site-main ul.products').removeClass('list').addClass('grid');
        } else if ($(this).hasClass('list')) {
            $('.site-main ul.products').removeClass('grid').addClass('list');
        }
    });

    if (window.innerWidth > 767) {
        $('ul.products .add_to_cart_button').each(function () {
            $(this).append('<span>' + $(this).attr('title') + '</span>');
        });
    }


    $('ul.dropdown-menu li.dropdown').hover(function () {
        $(this).addClass('open');
    }, function () {
        $(this).removeClass('open');
    });

    $('[data-toggle="tooltip"]').tooltip();

    // set dashboard menu height
    var dashboardMenu = $('ul.dokan-dashboard-menu'),
        contentArea = $('#content article');

    if (contentArea.height() > dashboardMenu.height()) {
        if ($(window).width() > 767) {
            dashboardMenu.css({height: contentArea.height()});
        }
    }

    // cat drop stack, disable parent anchors if has children
    if ($(window).width() < 767) {
        $('#cat-drop-stack li.has-children').on('click', '> a', function (e) {
            e.preventDefault();

            $(this).siblings('.sub-category').slideToggle('fast');
        });
    } else {
        $('#cat-drop-stack li.has-children > .sub-category').each(function (index, el) {
            var sub_cat = $(el);
            var length = sub_cat.find('.sub-block').length;

            if (length == 3) {
                sub_cat.css('width', '260%');
            } else if (length > 3) {
                sub_cat.css('width', '340%');
            }
        });
    }

    // tiny helper function to add breakpoints
    function getGridSize() {
        return (window.innerWidth < 600) ? 2 : (window.innerWidth < 900) ? 2 : 3;
    }

    $('.product-sliders').flexslider({
        animation: "slide",
        animationLoop: false,
        itemWidth: 190,
        itemMargin: 30,
        controlNav: false,
        prevText: "",
        nextText: "",
        minItems: getGridSize(),
        maxItems: getGridSize()
    });

    $('body').on('added_to_cart wc_cart_button_updated', function (fragment, data) {
        var viewCartText = $('a.added_to_cart.wc-forward').attr('title');

        $('i.fa-shopping-cart').removeClass('fa-spin');
        $('a.added_to_cart.wc-forward').html('<i class="fas fa-eye" data-toggle="tooltip" data-placement="top" title="' + viewCartText + '" aria-hidden="true"></i><span>View Cart</span>');
        $('[data-toggle="tooltip"]').tooltip();

        $('.dokan-cart-amount-top > .amount').fadeOut('fast', function () {
            $('.dokan-cart-amount-top > .amount').html(data.dokan_cart_amount).fadeIn('fast');
        });
    });

    $('body').on('adding_to_cart', function (e, button) {
        $(button).children('i').addClass('fa-spin');
    });

    $( document ).on( 'ready', function(){
        if( ! $('.profile-frame').hasClass('layout1') && window.innerWidth < 768 ) {
            $('.profile-frame').removeClass('layout3');
            $('.profile-frame').addClass('layout1');
        }
    } )

    // quantity product single page
    $(document).on('click', '.quantity-btn .plus', function(e) {
        let $input = $(this).parents('.quantity_wrap').children('input.qty');
        let val = parseInt($input.val()) || 0;
        let step = $input.attr('step');
        step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
        $input.val( val + step ).change();
    });
    $(document).on('click', '.quantity-btn .minus', function(e) {
        $input = $(this).parents('.quantity_wrap').children('input.qty');
        let val = parseInt($input.val()) || 0;
        let step = $input.attr('step');
        step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
        if (val > 0) {
            $input.val( val - step ).change();
        } 
    });

    /* Toggle on product category */
    var caretIcon = $('.wc-block-product-categories ul li ul');
    caretIcon.before('<span class="toggleIcon"><i class="flaticon-arrow-down-sign-to-navigate"></i></span>');
    $('.wc-block-product-categories ul li ul').hide();
    var toggleIcon = $('.wc-block-product-categories ul li .toggleIcon');
    toggleIcon.on('click', function () {
        $(this).toggleClass('active');
        $(this).next().slideToggle();
    })
    

});



