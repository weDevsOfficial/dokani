jQuery(function($) {
    // $('button.single_add_to_cart_button').removeClass('button').addClass('btn btn-danger');
    // $('a.button').removeClass('button').addClass('btn btn-danger');

    // store list/grid view
    $('.dokan-seller-view.buttons > button').on('click',function(e) {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');

        if ($(this).hasClass('grid')) {
            $('.seller-listing-content ul.dokan-seller-list').removeClass('list').addClass('grid');
        }
        else if($(this).hasClass('list')) {
            $('.seller-listing-content ul.dokan-seller-list').removeClass('grid active').addClass('list active');
        }
    });

    // Products list/grid view
    $('.dokanee-products-view.buttons > button').on('click',function(e) {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');

        if ($(this).hasClass('grid')) {
            $('.site-main ul.products').removeClass('list').addClass('grid');
        }
        else if($(this).hasClass('list')) {
            $('.site-main ul.products').removeClass('grid').addClass('list');
        }
    });

    $('ul.products li.product .button.product_type_simple').append( "<span>Add to Cart</span>" );
    $('ul.products li.product .button.product_type_variable').append( "<span>Select Options</span>" );


    $('ul.dropdown-menu li.dropdown').hover(function() {
        $(this).addClass('open');
    }, function() {
        $(this).removeClass('open');
    });

    $('[data-toggle="tooltip"]').tooltip();

    // set dashboard menu height
    var dashboardMenu = $('ul.dokan-dashboard-menu'),
        contentArea = $('#content article');

    if ( contentArea.height() > dashboardMenu.height() ) {
        if ( $(window).width() > 767) {
            dashboardMenu.css({ height: contentArea.height() });
        }
    }

    // cat drop stack, disable parent anchors if has children
    if ( $(window).width() < 767) {
        $('#cat-drop-stack li.has-children').on('click', '> a', function(e) {
            e.preventDefault();

            $(this).siblings('.sub-category').slideToggle('fast');
        });
    } else {
        $('#cat-drop-stack li.has-children > .sub-category').each(function(index, el) {
            var sub_cat = $(el);
            var length = sub_cat.find('.sub-block').length;

            if ( length == 3 ) {
                sub_cat.css('width', '260%');
            } else if ( length > 3) {
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

    $('body').on('added_to_cart wc_cart_button_updated', function( fragment, data ) {
        var viewCartText = $('a.added_to_cart.wc-forward').text();

        $('i.fa-shopping-cart').removeClass('fa-spin');
        $('a.added_to_cart.wc-forward').html('<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="' + viewCartText + '" aria-hidden="true"></i><span>View Cart</span>');
        $('[data-toggle="tooltip"]').tooltip();

        $('.dokan-cart-amount-top > .amount').fadeOut( 'fast', function(){
            $('.dokan-cart-amount-top > .amount').html( data.dokan_cart_amount ).fadeIn('fast');
        });
    });

    $('body').on('adding_to_cart', function(e, button) {
        $(button).children('i').addClass('fa-spin');
    });

});



