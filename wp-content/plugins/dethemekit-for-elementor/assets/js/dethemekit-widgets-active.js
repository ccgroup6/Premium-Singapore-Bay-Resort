;(function($){
"use strict";

   /* 
    * Product Slider 
    */
    var WidgetProductSliderHandler = function ($scope, $) {

        var slider_elem = $scope.find('.product-slider').eq(0);

        if (slider_elem.length > 0) {

            var settings = slider_elem.data('settings');
            var arrows = settings['arrows'];
            var dots = settings['dots'];
            var autoplay = settings['autoplay'];
            var rtl = settings['rtl'];
            var autoplay_speed = parseInt(settings['autoplay_speed']) || 3000;
            var animation_speed = parseInt(settings['animation_speed']) || 300;
            var fade = settings['fade'];
            var pause_on_hover = settings['pause_on_hover'];
            var display_columns = parseInt(settings['product_items']) || 4;
            var scroll_columns = parseInt(settings['scroll_columns']) || 4;
            var tablet_width = parseInt(settings['tablet_width']) || 800;
            var tablet_display_columns = parseInt(settings['tablet_display_columns']) || 2;
            var tablet_scroll_columns = parseInt(settings['tablet_scroll_columns']) || 2;
            var mobile_width = parseInt(settings['mobile_width']) || 480;
            var mobile_display_columns = parseInt(settings['mobile_display_columns']) || 1;
            var mobile_scroll_columns = parseInt(settings['mobile_scroll_columns']) || 1;

            slider_elem.slick({
                arrows: arrows,
                prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
                dots: dots,
                infinite: true,
                autoplay: autoplay,
                autoplaySpeed: autoplay_speed,
                speed: animation_speed,
                fade: false,
                pauseOnHover: pause_on_hover,
                slidesToShow: display_columns,
                slidesToScroll: scroll_columns,
                rtl: rtl,
                responsive: [
                    {
                        breakpoint: tablet_width,
                        settings: {
                            slidesToShow: tablet_display_columns,
                            slidesToScroll: tablet_scroll_columns
                        }
                    },
                    {
                        breakpoint: mobile_width,
                        settings: {
                            slidesToShow: mobile_display_columns,
                            slidesToScroll: mobile_scroll_columns
                        }
                    }
                ]
            });
        };
    };

    /*
    * Custom Tab
    */
    function dethemekit_tabs( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('htactive').parent().siblings().children('a').removeClass('htactive');
            $( $tabpane + $target ).addClass('htactive').siblings().removeClass('htactive');

            // slick refresh
            if( $('.slick-slider').length > 0 ){
                var $id = $this.attr('href');
                $( $id ).find('.slick-slider').slick('refresh');
            }

        });
    }

    /* 
    * Universal product 
    */
    function productImageThumbnailsSlider( $slider ){
        $slider.slick({
            dots: true,
            arrows: true,
            prevArrow: '<button class="slick-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="sli sli-arrow-right"></i></button>',
        });
    }
    if( $(".ht-product-image-slider").length > 0 ) {
        productImageThumbnailsSlider( $(".ht-product-image-slider") );
    }

    var WidgetThumbnaisImagesHandler = function thumbnailsimagescontroller(){
        dethemekit_tabs( $(".ht-product-cus-tab-links"), '.ht-product-cus-tab-pane' );
        dethemekit_tabs( $(".ht-tab-menus"), '.ht-tab-pane' );

        // Countdown
        var finalTime, daysTime, hours, minutes, second;
        $('.ht-product-countdown').each(function() {
            var $this = $(this), finalDate = $(this).data('countdown');
            var customlavel = $(this).data('customlavel');
            $this.countdown(finalDate, function(event) {
                $this.html(event.strftime('<div class="cd-single"><div class="cd-single-inner"><h3>%D</h3><p>'+customlavel.daytxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%H</h3><p>'+customlavel.hourtxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%M</h3><p>'+customlavel.minutestxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%S</h3><p>'+customlavel.secondstxt+'</p></div></div>'));
            });
        });

    }

    /*
    * dethemekitquickview slider
    */
    function dethemekitquickviewMainImageSlider(){
        $('.ht-quick-view-learg-img').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.ht-quick-view-thumbnails'
        });
    }
    function dethemekitquickviewThumb(){
        $('.ht-quick-view-thumbnails').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.ht-quick-view-learg-img',
            dots: false,
            arrows: true,
            focusOnSelect: true,
            prevArrow: '<button class="dethemekit-slick-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow: '<button class="dethemekit-slick-next"><i class="sli sli-arrow-right"></i></button>',
        });
    }

    /*
    * Tool Tip
    */
    function dethemekit_tool_tips(element, content) {
        if ( content == 'html' ) {
            var tipText = element.html();
        } else {
            var tipText = element.attr('title');
        }
        element.on('mouseover', function() {
            if ( $('.dethemekit-tip').length == 0 ) {
                element.before('<span class="dethemekit-tip">' + tipText + '</span>');
                $('.dethemekit-tip').css('transition', 'all 0.5s ease 0s');
                $('.dethemekit-tip').css('margin-left', 0);
            }
        });
        element.on('mouseleave', function() {
            $('.dethemekit-tip').remove();
        });
    }

    /*
    * Tooltip Render
    */
    var WidgetDethemeKitTooltipHandler = function dethemekit_tool_tip(){
        $('a.dethemekit-compare').each(function() {
            dethemekit_tool_tips( $(this), 'title' );
        });
        $('.dethemekit-cart a.add_to_cart_button,.dethemekit-cart a.added_to_cart,.dethemekit-cart a.button').each(function() {
            dethemekit_tool_tips( $(this), 'html');
        });
    }

    /*
    * Quick view
    */
    $(document).on('click', '.dethemekitquickview', function (event) {
        event.preventDefault();

        var $this = $(this);
        var productID = $this.data('quick-id');

        $('.htwl-modal-body').html(''); /*clear content*/
        $('#htwlquick-viewmodal').addClass('dethemekitquickview-open wlloading');
        $('#htwlquick-viewmodal .htcloseqv').hide();
        $('.htwl-modal-body').html('<div class="dethemekit-loading"><div class="wlds-css"><div style="width:100%;height:100%" class="wlds-ripple"><div></div><div></div></div>');

        var data = {
            id: productID,
            action: "dethemekit_quickview",
        };
        $.ajax({
            url: dethemekit_addons.dethemekitajaxurl,
            data: data,
            method: 'POST',
            success: function (response) {
                setTimeout(function () {
                    $('.htwl-modal-body').html(response);
                    $('#htwlquick-viewmodal .htcloseqv').show();
                    dethemekitquickviewMainImageSlider();
                    dethemekitquickviewThumb();
                }, 300 );
            },
            complete: function () {
                $('#htwlquick-viewmodal').removeClass('wlloading');
                $('.htwl-modal-dialog').css("background-color","#ffffff");
            },
            error: function () {
                console.log("Quick View Not Loaded");
            },
        });

    });
    $('.htcloseqv').on('click', function(event){
        $('#htwlquick-viewmodal').removeClass('dethemekitquickview-open');
        $('body').removeClass('dethemekitquickview');
        $('.htwl-modal-dialog').css("background-color","transparent");
    });

    /*
    * Product Tab
    */
    var  WidgetProducttabsHandler = dethemekit_tabs( $(".ht-tab-menus"),'.ht-tab-pane' );

    /*
    * Single Product Video Gallery tab
    */
    var WidgetProductVideoGallery = function thumbnailsvideogallery(){
        dethemekit_tabs( $(".dethemekit-product-video-tabs"), '.video-cus-tab-pane' );
    }

    /*
    * Run this code under Elementor.
    */
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-product-tab.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-product-tab.default', WidgetProducttabsHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-product-display.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-product-display.default', WidgetDethemeKitTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-product-display.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-cross-sell-product-custom.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-cross-sell-product-custom.default', WidgetDethemeKitTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-cross-sell-product-custom.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-upsell-product-custom.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-upsell-product-custom.default', WidgetDethemeKitTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-upsell-product-custom.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-related-product-custom.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-related-product-custom.default', WidgetDethemeKitTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dethemekit-related-product-custom.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-product-video-gallery.default', WidgetProductVideoGallery );
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-brand-logo.default', WidgetProductSliderHandler );
    });


})(jQuery);