;(function($) {

    "use strict";

    var responsive_menu = function() {
        
        $('.tf-nav-menu').each(function(){
            var $this = $(this).data('id_random'),
            $tf_nav_menu = $('.'+$this),
            $btn_menu_mobile = $('.'+$this).find('.btn-menu-mobile'),
            $close_menu_panel_style_default = $('.'+$this).find('.close-menu-panel-style-default'),
            $btn_menu_only = $('.'+$this).find('.btn-menu-only'),
            $mobile_menu_overlay = $('.'+$this).find('.mobile-menu-overlay'),
            $mobile_menu_close = $('.'+$this).find('.tf-close');

            $('.'+$this).find('.btn-submenu').remove();
            var hasChildMenu = $tf_nav_menu.find('.mainnav-mobi').find('li:has(ul)');
            hasChildMenu.children('ul').hide();                                    
            hasChildMenu.children('a').after('<span class="btn-submenu"><i class="fa fa-angle-down" aria-hidden="true"></i></span>');

            var menuType = 'desktop';
            $(window).on('load resize', function() {
                var currMenuType = 'desktop';

                if ( matchMedia( 'only screen and (max-width: 991px)' ).matches ) {                
                    currMenuType = 'mobile';
                }

                if ( currMenuType !== menuType ) {
                    menuType = currMenuType;
                } else {                             
                    $('.'+$this).find('.mobile-menu-overlay').removeClass('active');
                    $('.'+$this).find('.nav-panel').removeClass('active');      
                }

            });

            $(document).on('click', '.mainnav-mobi li .btn-submenu', function(e) {
                $(this).toggleClass('active').next('ul').slideToggle(300);
                e.stopImmediatePropagation();
                e.preventDefault();
            }); 

            //Open Nav
            $($btn_menu_mobile).on('click', function() {                
                $(this).addClass('active');
                $(this).siblings().addClass('active');
            });             

            //Close Nav
            $($close_menu_panel_style_default).on('click', function() {             
                $(this).closest('.nav-panel').removeClass('active');             
                $(this).closest('.nav-panel').siblings().removeClass('active');           
            });

            $($mobile_menu_overlay).on('click', function() {             
                $(this).siblings().removeClass('active');            
                $(this).removeClass('active');            
            });

            $($mobile_menu_close).on('click', function() {             
                $(this).siblings().removeClass('active');            
                $(this).removeClass('active');            
            }); 

            $($btn_menu_only).on('click', function() { 
                $(this).siblings().addClass('active');
            });


            
        });        
                         
    }
    
    var carousel_Box = function() {
        if ( $().owlCarousel ) {
            $('.tf-carousel-box').each(function(){
                var
                $this = $(this),
                item = $this.data("column"),
                item2 = $this.data("column2"),
                item3 = $this.data("column3"),
                spacer = Number($this.data("spacer")),
                prev_icon = $this.data("prev_icon"),
                next_icon = $this.data("next_icon");

                var loop = false;
                if ($this.data("loop") == 'yes') {
                    loop = true;
                }

                var arrow = false;
                if ($this.data("arrow") == 'yes') {
                    arrow = true;
                } 

                var auto = false;
                if ($this.data("auto") == 'yes') {
                    auto = true;
                }                

                $this.find('.owl-carousel').owlCarousel({
                    loop: loop,
                    margin: spacer,
                    nav: true,
                    pagination: true,
                    autoplay: auto,
                    autoplayTimeout: 5000,
                    smartSpeed: 850,
                    autoplayHoverPause: true,
                    navText : ["<i class=\""+prev_icon+"\"></i>","<i class=\""+next_icon+"\"></i>"],
                    responsive: {
                        0:{
                            items:item3
                        },
                        768:{
                            items:item2
                        },
                        1000:{
                            items:item
                        }
                    }
                });
            });
        }
    }

    var onepage_nav = function () {
        $('.tf-nav-menu.has-one-page .mainnav > ul > li > a').on('click',function(e) {

            var anchor = $(this).attr('href').split('#')[1];            
            var largeScreen = matchMedia('only screen and (min-width: 992px)').matches;
            var headerHeight = 0;
            headerHeight = $('.header').height();        
            if ( anchor ) {
                if ( $('#'+anchor).length > 0 ) {
                   if ( $('.header-shadow').length > 0 ) {
                        headerHeight = headerHeight;
                   } else {
                        headerHeight = 0;
                   }                   
                   var target = $('#'+anchor).offset().top - headerHeight;
                   $('html,body').animate({scrollTop: target}, 1000, 'easeInOutExpo');
                }
            }

            e.preventDefault();

        });
    } 

    var search_form = function(){
        $('.tf-widget-search').each(function(){
            $(this).find('.tf-icon-search').on('click' , function(){
                $(this).siblings('.tf-modal-search-panel').addClass('show');
            });
        });
        $(document).on('click', '.tf-widget-search .tf-modal-search-panel', function() {
            $(this).removeClass('show');
        });
        $(document).on('click', '.tf-widget-search .tf-search-form', function(e) {
            e.stopImmediatePropagation();
        });
    };

    var blogPostsOwl = function() {
        if ( $().owlCarousel ) {
            $('.tf-posts-wrap.has-carousel').each(function(){
                var
                $this = $(this),
                item = $this.data("column"),
                item2 = $this.data("column2"),
                item3 = $this.data("column3"),
                spacer = Number($this.data("spacer")),
                prev_icon = $this.data("prev_icon"),
                next_icon = $this.data("next_icon");

                var loop = false;
                if ($this.data("loop") == 'yes') {
                    loop = true;
                }

                var arrow = false;
                if ($this.data("arrow") == 'yes') {
                    arrow = true;
                } 

                var auto = false;
                if ($this.data("auto") == 'yes') {
                    auto = true;
                }                

                $this.find('.owl-carousel').owlCarousel({
                    loop: loop,
                    margin: spacer,
                    nav: true,
                    pagination: false,
                    autoplay: auto,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    animateIn: 'fadeIn',
                    animateOut: 'fadeOut',
                    navText : ["<i class=\""+prev_icon+"\"></i>","<i class=\""+next_icon+"\"></i>"],
                    responsive: {
                        0:{
                            items:item3
                        },
                        768:{
                            items:item2
                        },
                        1000:{
                            items:item
                        }
                    }
                });

            });
        }
    } 

    var blogPostsGallery = function() {
        $(".featured-image-gallery").each(function() {
            var $this = $(this);
            var animation = $this.data('animation_images'),
                autoplay = $this.data('autoplay'),
                slideshowSpeed = $this.data('slideshowSpeed'),
                controlNav = $this.data('controlnav'),
                directionNav = $this.data('directionnav'),
                prevText = $this.data('prevtext'),
                nextText = $this.data('nexttext');
            $this.flexslider({
                animation: animation,
                slideshow: autoplay,
                slideshowSpeed: slideshowSpeed,
                animationSpeed: 1000,
                animationLoop: true,
                controlNav: controlNav,
                directionNav: directionNav,
                prevText: '<i class="' + prevText + '" aria-hidden="true"></i>',
                nextText: '<i class="' + nextText + '" aria-hidden="true"></i>',
                useCCS: false
            });
        });
    }    

    var blogLoadMore = function() {

        /*var $container_wrap = $('.tf-posts-wrap'); 
        var $container = $('.tf-posts-wrap').find('.tf-posts');  

        $('.navigation.loadmore a').on('click', function(e) {
            e.preventDefault(); 

            $container.after('<div class="tfpost-loading"><span></span></div>');

            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                dataType: "html",
                success: function( out ) {
                    var result = $(out).find('.column');  
                    var nextlink = $(out).find('.navigation.loadmore a').attr('href');

                    result.css({ opacity: 0 , visibility: 'hidden' });
                    if ($container.hasClass('masonry')) {
                        $container.append(result).imagesLoaded(function () {
                            result.css({ opacity: 1 , visibility: 'visible' });
                            $container.isotope('appended', result);
                        });
                    }
                    else {
                        $container.append(result).imagesLoaded(function () {
                            result.css({ opacity: 1 , visibility: 'visible' });
                            $container.isotope('appended', result);
                        });                         
                    }

                    if ( nextlink != undefined ) {
                        $('.navigation.loadmore a').attr('href', nextlink);
                        $container_wrap.find('.tfpost-loading').remove();
                    } else {
                        $container_wrap.find('.tfpost-loading').addClass('no-ajax').text('All posts loaded').delay(2000).queue(function() {$(this).remove();});
                        $('.navigation.loadmore a').remove();
                    }
                }
            });
        });*/

        $('.tf-posts-wrap .navigation.loadmore a').on('click', function(e) {
            e.preventDefault();
            var class_id =  $(this).closest('.tf-posts-wrap').data('class_id');
            var class_id_string = '.'+class_id;
            var $container_wrap = $(class_id_string);
            var $container = $container_wrap.find('.tf-posts');
            var scroll_loadmore = $container_wrap.find('.scroll-loadmore');
            
            $(this).closest('.navigation.loadmore').addClass('loader');

            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                dataType: "html",
                success: function( out ) {
                    var result = $(out).find('.column');  
                    var nextlink = $(out).find('.navigation.loadmore a').attr('href');
                    
                    $container.each(function() {
                        $(this).append(result).imagesLoaded(function () {
                            setTimeout(function() {                            
                                $container.isotope('appended', result);
                            }, 1000);                                                
                        });   
                    });                                       

                    $('.navigation.loadmore').removeClass('loader');

                    if ( nextlink != undefined ) {
                        $('.navigation.loadmore a').attr('href', nextlink);
                    } else {                        
                        $('.navigation.loadmore').remove();
                    }

                    $('html, body').animate({
                        scrollTop: scroll_loadmore.offset().top - 300
                    }, 700);
                }
            });
        });           
    }

    

    var blogMasonry = function() {
        $('.tf-posts-wrap .tf-posts').each(function(){
            var $this = $(this);
            if ($this.hasClass('masonry')) {
                var $grid = $this.isotope({
                    itemSelector: '.column',
                    percentPosition: true,
                    masonry: {
                    columnWidth: '.grid-sizer'
                    }
                });
                
                $grid.imagesLoaded().progress( function() {
                    $grid.isotope('layout');
                });
            } 
        });            
    } 

    var tftabs = function() {   
     
        $('.tf-tabs').each( function() {
            
            $(this).find('.tf-tabnav ul > li').filter(':first').addClass('active').removeClass('inactive');
            $(this).find('.tf-tabcontent').children().filter(':first').addClass('active');

            
            if ( $(this).find('.tf-tabnav ul > li').hasClass('set-active-tab') ) {
                $(this).find('.tf-tabnav ul > li').siblings().removeClass('active');                
            }
            if ( $(this).find('.tf-tabcontent').children().hasClass('set-active-tab') ) {
                $(this).find('.tf-tabcontent').children().siblings().removeClass('active');
            }

            $(this).find('.tf-tabnav ul > li').on('click', function(){
                var tab_id = $(this).attr('data-tab');

                $(this).siblings().removeClass('active').removeClass('set-active-tab').addClass('inactive');
                $(this).closest('.tf-tabs').find('.tf-tabcontent').children().removeClass('active').removeClass('set-active-tab').addClass('inactive');

                $(this).addClass('active').removeClass('inactive');
                $(this).closest('.tf-tabs').find('.tf-tabcontent').children('#'+tab_id).addClass('active').removeClass('inactive');
            });
        });
    }

    var vegasSlider = function() {
        $(".hero-section").each(function() {            
            var            
            contentTopMargin = 0,
            heroHeight = 0,
            customHeight = 0,
            hero = $(this),
            windowHeight = $(window).height(),
            heroContent = hero.find('.vegas-content'),
            contentHeight = heroContent.height(),
            delay = Number(hero.data('delay')),
            spacing = hero.data('content'),
            slide_type = hero.data('slide_type'),
            customHeight = hero.data('height');
            var customHeightTablet = hero.data('height_tablet');
            var customHeightMobile = hero.data('height_mobile');

            if ( slide_type == 'full-height' ) {                
                heroHeight = windowHeight;
            }else {                
                heroHeight = customHeight;
                if ( matchMedia( 'only screen and (max-width: 991px)' ).matches ) {
                    heroHeight = customHeightTablet;
                }
                if ( matchMedia( 'only screen and (max-width: 767px)' ).matches ) {
                    heroHeight = customHeightMobile;
                }
            }
            
            if ( slide_type == 'full-height' ) {
                hero.css({ height: heroHeight + "px" });
                contentTopMargin = ((heroHeight - contentHeight) / 2) + spacing;
                heroContent.css("padding-top", (contentTopMargin) + "px");
            }else {                    
                hero.css({ height: heroHeight + "px" });
                contentTopMargin = ((heroHeight - contentHeight) / 2) + spacing;
                heroContent.css("padding-top", (contentTopMargin) + "px");
            }
            
            if ( $().vegas ) {
                $(".hero-section.slidehero").each(function() {
                    var
                    $this = $(this),
                    count = $this.data('count'),
                    count = parseInt(count,10),
                    effect = $this.data('effect'),
                    images = $this.data('image'),
                    cOverlay = $this.data('overlay'),
                    pOverlay = $this.data('poverlay'),
                    i = 0,
                    slides = [],
                    imgs = images.split('|');
                    while ( i < count ) {
                        slides.push( {src:imgs[i]} );
                        i++;
                    }
                    $this.vegas({
                        slides: slides,
                        overlay: true,
                        transition: effect,
                        delay: delay,
                    });
                    var overlay = $('<div />', {
                        class: 'overlay',
                        style: 'background:' + cOverlay
                    });                   
                    $(this).append(overlay).find('.vegas-overlay').addClass(pOverlay);
                });
            }        
            if ( $().YTPlayer ) {
                $(".hero-section.slidevideo").each(function() {
                    var
                    $this = $(this),
                    cOverlay = $this.data('overlay'),
                    overlay = $('<div />', {
                        class: 'overlay',
                        style: 'position: absolute; width: 100%; height: 100%; background:' + cOverlay
                    });
                    $this.YTPlayer().append(overlay);
                });
            }
            if ( $('.slide-fancy-text').is('.scroll') ) {
                $('.slide-fancy-text.scroll').each(function() {
                    var
                    $this = $(this),
                    current = 1,
                    height = $this.children('.heading').height(),
                    numberDivs = $this.children().length,
                    first = $this.children('.heading:nth-child(1)');
                    $this.height(height);
                    $this.siblings('.prefix-text, .suffix-text').height(height);
                    setInterval(function() {                                    
                        var number = current * -height;                   
                        first.css('margin-top', number + 'px');
                        if ( current === numberDivs ) {
                            first.css('margin-top', '0px');
                            current = 1;
                        } else current++;
                    }, delay);
                });
            }       
            if ( $('.slide-fancy-text').is('.typed') ) {
                if ( $().typed ) {
                    $('.slide-fancy-text.typed').each(function() {
                        var
                        $this = $(this),
                        texts = $this.data('fancy').split(',');
                        $this.find('.text').typed({
                            strings: texts,
                            typeSpeed: 40,
                            loop:true,
                            backDelay: delay
                        });
                    });
                }
            }
        });
        $(".hero-section").each(function() {
            var $this = $(this);
            $this.find('.scroll-target').on('click',function() {
                var anchor = $(this).attr('href').split('#')[1];
                if ( anchor ) {
                    if ( $('#'+anchor).length > 0 ) {
                        var headerHeight = 0;
                        if ( $('body').hasClass('header-sticky') ) {
                            headerHeight = $('#site-header').height();
                        }
                        var target = $('#' + anchor).offset().top - headerHeight;
                        if ( $('body').hasClass('admin-bar') ) {
                            var wpadminbar = $('#wpadminbar').height();
                            target = $('#' + anchor).offset().top - headerHeight - wpadminbar;
                        }
                        $('html,body').animate({scrollTop: target}, 1000, 'easeInOutExpo');
                   }
                }
                return false;
            });
        });
    };

    var flexSlider = function() {
        $(".flexslider").each(function() {
            var $this = $(this),
            adminBarHeight = 0,
            topBarHeight = 0,
            contentTopMargin = 0,
            adminBar = $('#wpadminbar'),
            topBar = $('#top-bar'),
            headerHeight = $('header').height(),            
            flexsliderHeight = $this.data('height'),
            flexsliderHeightTablet = $this.data('height_tablet'),
            flexsliderHeightMobile = $this.data('height_mobile'),
            flexSliderContent = $this.find('.flex_caption'),            
            contentHeight = flexSliderContent.outerHeight();            
            if ( matchMedia( 'only screen and (max-width: 991px)' ).matches ) {
                flexsliderHeight = flexsliderHeightTablet;
            }
            if ( matchMedia( 'only screen and (max-width: 767px)' ).matches ) {
                flexsliderHeight = flexsliderHeightMobile;
            }

            $this.find('.item-slide').height(flexsliderHeight);
            if (topBar.length) topBarHeight = topBar.height();
            if (adminBar.length) adminBarHeight = adminBar.height();          
            
            if (contentHeight == 0) {
                contentHeight = (flexsliderHeight * 0.5);                
            }

            if ( $this.hasClass('header-absolute') ) {
                contentTopMargin = ((flexsliderHeight + topBarHeight + headerHeight - contentHeight ) / 2);
                flexSliderContent.css("margin-top", (contentTopMargin) + "px");
            } else {
                contentTopMargin = ((flexsliderHeight - contentHeight ) / 2);
                flexSliderContent.css("margin-top", (contentTopMargin) + "px");
            }

            var animation = $this.data('animation_images'),
                autoplay = $this.data('autoplay'),
                slideshowSpeed = $this.data('slideshowSpeed'),
                controlNav = $this.data('controlnav'),
                directionNav = $this.data('directionnav'),
                prevText = $this.data('prevtext'),
                nextText = $this.data('nexttext');
            $this.flexslider({
                animation: 'fade',
                slideshow: autoplay,
                slideshowSpeed: slideshowSpeed,
                animationSpeed: 1000,
                animationLoop: true,
                controlNav: controlNav,
                directionNav: directionNav,
                prevText: '<i class="' + prevText + '" aria-hidden="true"></i>',
                nextText: '<i class="' + nextText + '" aria-hidden="true"></i>',
                useCCS: false
            });
        });
    }  

    var postFormatIziModal = function(){
        if ($('body').find('div').hasClass('izimodal')) {
            $(".izimodal").iziModal({
                width: 850,
                top: null,
                bottom: null,
                borderBottom: false,
                padding: 0,
                radius: 3,
                zindex: 999999,
                iframe: false,
                iframeHeight: 400,
                iframeURL: null,
                focusInput: false,
                group: '',
                loop: false,
                arrowKeys: true,
                navigateCaption: true,
                navigateArrows: true, // Boolean, 'closeToModal', 'closeScreenEdge'
                history: false,
                restoreDefaultContent: true,
                autoOpen: 0, // Boolean, Number
                bodyOverflow: false,
                fullscreen: false,
                openFullscreen: false,
                closeOnEscape: true,
                closeButton: true,
                appendTo: 'body', // or false
                appendToOverlay: 'body', // or false
                overlay: true,
                overlayClose: true,
                overlayColor: 'rgba(0, 0, 0, .7)',
                timeout: false,
                timeoutProgressbar: false,
                pauseOnHover: false,
                timeoutProgressbarColor: 'rgba(255,255,255,0)',
                transitionIn: 'comingIn',
                transitionOut: 'comingOut',
                transitionInOverlay: 'fadeIn',
                transitionOutOverlay: 'fadeOut',
                onFullscreen: function(){},
                onResize: function(){},
                onOpening: function(){},
                onOpened: function(){},
                onClosing: function(){},
                onClosed: function(){},
                afterRender: function(){}
            });

            $(document).on('click', '.trigger', function (event) {
                event.preventDefault();
                $('.izimodal').iziModal('setZindex', 99999999);
                $('.izimodal').iziModal('open', { zindex: 99999999 });
                $('.izimodal').iziModal('open');
            });
        }
    }   

    /* Woo
    ------------------------------------*/  
    var productCarousel = function() {       
        if ( $().owlCarousel ) {
            $('.tf-woo-product.carousel-yes').each(function(){
                var 
                $this = $(this),
                item = $this.data("column"),
                item2 = $this.data("column2"),
                item3 = $this.data("column3"),
                spacer = Number($this.data("spacer")),
                prev_icon = $this.data("prev_icon"),
                next_icon = $this.data("next_icon");

                var loop = false;
                if ($this.data("loop") == 'yes') {
                    loop = true;
                }

                var arrow = false;
                if ($this.data("arrow") == 'yes') {
                    arrow = true;
                } 

                var auto = false;
                if ($this.data("auto") == 'yes') {
                    auto = true;
                }  
                            

                $this.find('.owl-carousel.owl-theme').owlCarousel({
                    loop: loop,
                    margin: spacer,
                    nav: true,
                    pagination: true,
                    autoplay: auto,
                    autoplayTimeout: 5000,
                    smartSpeed: 850,
                    autoplayHoverPause: true,
                    navText : ["<i class=\""+prev_icon+"\"></i>","<i class=\""+next_icon+"\"></i>"],
                    responsive: {
                        0:{
                            items:item3
                        },
                        768:{
                            items:item2
                        },
                        1000:{
                            items:item
                        }
                    }
                });
            });
        }
    }

    var productLoadMore = function() {
        var $container_wrap = $('.tf-woo-product'); 
        var $container = $('.tf-woo-product').find('.products'); 
        var $products = $container.find('.products > .product-item');

        $('.navigation.loadmore a').on('click', function(e) {
            e.preventDefault(); 
            
            $(this).closest('.navigation.loadmore').addClass('loading');

            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                dataType: "html",
                success: function( out ) {
                    var result = $(out).find('.product-item');  
                    var nextlink = $(out).find('.navigation.loadmore a').attr('href');
                    
                    if (result.length) {
                        for (var index = 0; index < result.length; index++) {
                            $(result[index]).css('animation-delay', index * 100 + 'ms');
                        }
                        result.addClass('soberFadeInUp soberAnimation');
                    }
                    
                    $container.each(function() {
                        $(this).append(result).imagesLoaded(function () {
                            setTimeout(function() {                            
                                $container.isotope('appended', result);
                            }, index * 100);                                                
                        });   
                    });                                       

                    $('.navigation.loadmore').removeClass('loading');

                    if ( nextlink != undefined ) {
                        $('.navigation.loadmore a').attr('href', nextlink);
                    } else {                        
                        $('.navigation.loadmore').remove();
                    }
                }
            });
        });             
    }

    var productFilterForm = function() { 
        $(window).on('load resize', function() {
            $('.tf-woo-product').each(function(){
                if ( $(this).hasClass('show_filter_product') ) {
                    var $wrap_container = $(this);
                    var $container = $(this).find('.products');
                    var loading = '<span class="loading-icon"><span class="bubble"><span class="dot"></span></span><span class="bubble"><span class="dot"></span></span><span class="bubble"><span class="dot"></span></span></span>';
                    
                    $wrap_container.find('.shop-columns').on('click', function() {
                        $(this).siblings('.shop-columns').removeClass('active');
                        $(this).addClass('active');
                        var $data_columns = $(this).attr('data-columns');                        
                        $wrap_container.find('.products').append(loading);
                        $wrap_container.find(".products").attr('class', function(i, c){                            
                            setTimeout(function() {
                                $wrap_container.find('.products .loading-icon').fadeOut('slow', function(){
                                    setTimeout(function() {
                                        $wrap_container.find('.products .loading-icon').remove(); 
                                    }, 500);
                                });                              
                            }, 700); 
                            return c.replace(/(^|\s)columns-\S+/g, ' columns-'+$data_columns);
                        });
                    });
                    
                    $('.toggle-filter-form').on('click', function() {
                        $(this).siblings('.wrap-form-filter').fadeIn();
                    });
                    $('.wrap-form-filter .widget-title .close').on('click', function() {
                        $(this).closest('.wrap-form-filter').fadeOut();
                    });

                    $('#form_filter .filter-button').on('click', function() {
                        $wrap_container.find('.products').append(loading);  
                    });

                    $('#form_filter').submit(function(){
                        var form_filter = $(this);   
                                           
                        $.ajax({
                            url:form_filter.attr('action'),
                            data:form_filter.serialize(), // form data
                            type:form_filter.attr('method'), // POST
                            beforeSend:function(xhr){                                
                                form_filter.find('button').text('Processing...'); // changing the button label
                            },
                            success:function(data){
                                var numItems =  $(data).filter('.product-item').length;
                                $wrap_container.find('.toolbar-control .woocommerce-result-count').text(numItems + ' products');  
                                form_filter.find('button').text('Filter'); // changing the button label back
                                $wrap_container.find('.content-tab .content-tab-inner.active .products').html(data).hide().fadeIn('slow'); // insert data 

                                $wrap_container.find('.products .loading-icon').fadeOut('slow', function(){
                                    setTimeout(function() {
                                        $wrap_container.find('.products .loading-icon').remove(); 
                                    },1000);
                                });                  
                            }
                        });
                        return false;
                    });

                };  
            });
        });         
    };  

    var productFilterTabs = function() {        
        $('.show_filter_product').each(function() {
            var $wrap_container = $(this).closest('.tf-woo-product');
            var loading = '<span class="loading-icon"><span class="bubble"><span class="dot"></span></span><span class="bubble"><span class="dot"></span></span><span class="bubble"><span class="dot"></span></span></span>';
            $(this).children('.content-tab').children().hide();
            $(this).children('.content-tab').children().first().show().addClass('active');            
            $(this).find('.products-filter').children('li').on('click', function(e) {
                $wrap_container.find('.products').append(loading);
                var datafilter = $(this).attr('data-filter');
                $(this).closest('.tf-woo-product').find('#form_filter #product_tab_badge').val(datafilter);

                var liActive = $(this).index(),
                    contentActive = $(this).siblings().removeClass('active').parents('.show_filter_product').children('.content-tab').children().eq(liActive);
                
                contentActive.addClass('active').fadeIn('slow'); 
                var numItems = contentActive.find('.products').children('.product-item').length;                   
                    $(this).closest('.filter-bar').find('.toolbar-control .woocommerce-result-count').text(numItems + ' products');

                contentActive.siblings().removeClass('active');
                $(this).addClass('active').parents('.show_filter_product').children('.content-tab').children().eq(liActive).siblings().hide();
                e.preventDefault();                    

                setTimeout(function() {
                    $wrap_container.find('.products .loading-icon').fadeOut('', function(){
                        setTimeout(function() {
                            $wrap_container.find('.products .loading-icon').remove(); 
                        }, 500);
                    });                              
                }, 700);

            });
        });
    };

    var productSingleImage = function() {
        $('.tf-woo-product-single-image').each(function(){
            $(this).find('#image-carousel').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: true,
                slideshow: true,
                itemWidth: 277,                
                itemMargin: 20, 
                asNavFor: $(this).find('#image-flexslider'),
                prevText: '<i class="fa fa-angle-left"></i>',
                nextText: '<i class="fa fa-angle-right"></i>'
            });
            $(this).find('#image-flexslider').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: true,
                slideshow: true,                
                sync: $(this).find('#image-carousel'),
                directionNav: false,
                prevText: '<i class="fa fa-angle-left"></i>',
                nextText: '<i class="fa fa-angle-right"></i>'
            });
        });
    };  

    var cavas_product_mini_cart = function() { 
        $('#mini-cart-click, #mini-cart-click a, .products .ajax_add_to_cart').on('click', function(e){            
            $('#canvas-mini-cart').addClass('canvas-cart-open');
            $('.mini-cart .overlay-mini-cart').addClass('canvas-overlay-open');
            e.preventDefault();       
        });

        $('.mini-cart .overlay-mini-cart, #canvas-mini-cart .cart-close').on('click', function(e){            
            $('#canvas-mini-cart').removeClass('canvas-cart-open');
            $('.mini-cart .overlay-mini-cart').removeClass('canvas-overlay-open');
            e.preventDefault();
        });
    } 

    /* Sticky
    ------------------------------------*/
    var TF_Sticky = function() {
        var wpadminbar = $('#wpadminbar').outerHeight(); 
        $('.tf-sticky-section').each(function() { 
            var section =  $(this),
                section_id =  section.data('id');                                         
                
            if (section.hasClass('tf-sticky-yes')) {
                var class_section = $('.elementor-element-'+section_id+'.tf-sticky-yes'),
                    section_height = $(class_section).outerHeight(),
                    section_width = $(class_section).outerWidth(),         
                    injectSpace = $('<div />', { height: section_height }).insertAfter($(class_section));   
                    injectSpace.hide();

                var element_class_sticky = '.elementor-element-'+section_id,
                    tfsticky = $(element_class_sticky+'.tf-sticky-yes'),
                    offset = tfsticky.offset(),
                    tfsticky_offset_top = offset.top;   
                    $(element_class_sticky+'.elementor-inner-section.tf-sticky-yes').css({'width':section_width});                 
                    if ($('body').hasClass('admin-bar')) {    
                        var tfsticky_offset_top = tfsticky_offset_top - wpadminbar;
                    }
                $(window).on('scroll', function() { 
                    if ( $(window).scrollTop() >=  tfsticky_offset_top  ) {                             
                        tfsticky.addClass('tf-element-sticky');
                        injectSpace.show();
                    } else {  
                        tfsticky.removeClass('tf-element-sticky');
                        injectSpace.hide();
                    }
                }); 
            }
        })              
    } 

    /* Go top
    ------------------------------------*/
    var tfScrollTop = function() { 
        $(window).scroll(function() {
            if ( $(this).scrollTop() > 200 ) {
                $('#tf-scroll-top').addClass('show');
            } else {
                $('#tf-scroll-top').removeClass('show');
            }
        });

        $('#tf-scroll-top .inner-scroll-top').on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 1000 , 'easeInOutExpo');
            return false;
        });
    } 

    var tf_swiper_slider = function() {
        $('.tf-slide-swiper').each(function(){
            var container = $(this), 
                swiper_container = container.find('.swiper-container-primary'),
                autoplay_speed = container.data('autoplay_speed'),
                autoplay = (container.data('autoplay') == 'yes') ? { delay: autoplay_speed } : false ,
                pause_on_interaction = container.data('pause_on_interaction'),
                infinite_loop = (container.data('infinite_loop') == 'yes')? true : false ,
                transition_speed = container.data('transition_speed'),
                bullets_type = container.data('bullets_type'),
                direction = container.data('direction'),
                reverse_direction = (container.data('reverse_direction') == 'yes')? true : false ,
                space_between = container.data('space_between'),
                slides_show = container.data('slides_show'),
                slides_show_tablet = container.data('slides_show_tablet'),
                slides_show_mobile = container.data('slides_show_mobile');

                if (reverse_direction) {
                    autoplay = (container.data('autoplay') == 'yes') ? { delay: autoplay_speed, reverseDirection: true } : false ;
                }

            var swiper = new Swiper(swiper_container, {
                // Optional parameters,
                slidesPerView: slides_show,
                direction: direction, //vertica or horizontal                
                spaceBetween: space_between,
                effect: 'slide',
                speed: transition_speed,
                loop: infinite_loop,
                autoplay: autoplay,
                grabCursor: true,  
                reverseDirection: true,       

                // If we need pagination
                pagination: {
                    el: '.swiper-pagination',
                    type: bullets_type /*progressbar, bullets, fraction */,
                    clickable: true,
                },

                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                }, 

                breakpoints: {  
                    '320': {
                      slidesPerView: slides_show_mobile,
                    },
                    '768': {
                      slidesPerView: slides_show_tablet,
                    },
                    '1025': {
                      slidesPerView: slides_show,
                    },
                },               
            });

            if (pause_on_interaction == 'yes') {
                swiper_container.on('mouseenter', function(e){
                    swiper.autoplay.stop();
                });
                swiper_container.on('mouseleave', function(e){
                    swiper.autoplay.start();
                });
            }
        });
    };

    var tf_animated_headline = function() {
        var highlightedWave = $('.tf-highlighted-wave'),
            highlightedDrop = $('.tf-highlighted-drop-in'),
            highlightedSlide = $('.tf-highlighted-slide');

        if ( highlightedWave.length ) {
            highlightedWave.each(function (index ,item) {
                item.innerHTML = $(this).text().replace(/\S/g, "<span class='letter'>$&</span>");
            });

            anime.timeline({loop: true})
                .add({
                    targets: '.tf-highlighted-wave .letter',
                    scale: [4,1],
                    opacity: [0,1],
                    translateZ: 0,
                    easing: "easeOutExpo",
                    duration: 950,
                    delay: (el, i) => 70*i
                }).add({
                targets: '.tf-highlighted-wave',
                opacity: 0,
                duration: 1000,
                easing: "easeOutExpo",
                delay: 1000
            });
        }

        if(highlightedDrop.length){
            highlightedDrop.each(function (index ,item) {
                item.innerHTML = $(this).text().replace(/\S/g, "<span class='letter'>$&</span>");
            });

            anime.timeline({loop: true})
                .add({
                    targets: '.tf-highlighted-drop-in .letter',
                    scale: [0, 1],
                    duration: 1500,
                    elasticity: 600,
                    delay: (el, i) => 45 * (i+1)
                }).add({
                targets: '.tf-highlighted-drop-in',
                opacity: 0,
                duration: 1000,
                easing: "easeOutExpo",
                delay: 1000
            });
        }

        if(highlightedDrop.length){
            highlightedDrop.each(function (index ,item) {
                item.innerHTML = $(this).text().replace(/\S/g, "<span class='letter'>$&</span>");
            });

            anime.timeline({loop: true})
                .add({
                    targets: '.tf-highlighted-drop-in .letter',
                    scale: [0, 1],
                    duration: 1500,
                    elasticity: 600,
                    delay: (el, i) => 45 * (i+1)
                }).add({
                targets: '.tf-highlighted-drop-in',
                opacity: 0,
                duration: 1000,
                easing: "easeOutExpo",
                delay: 1000
            });
        }

        if(highlightedSlide.length){
            highlightedSlide.each(function (index ,item) {
                item.innerHTML = $(this).text().replace(/\S/g, "<span class='letter'>$&</span>");
            });

            anime.timeline({loop: true})
                .add({
                    targets: '.tf-highlighted-slide .letter',
                    translateX: [40,0],
                    translateZ: 0,
                    opacity: [0,1],
                    easing: "easeOutExpo",
                    duration: 1200,
                    delay: (el, i) => 500 + 30 * i
                }).add({
                targets: '.tf-highlighted-slide .letter',
                translateX: [0,-30],
                opacity: [1,0],
                easing: "easeInExpo",
                duration: 1100,
                delay: (el, i) => 100 + 30 * i
            });
        }

    }

    /* Preloader
    ------------------------------------*/
    var tfRemovePreloader = function() {  
        setTimeout(function() {  
            $(".tf-preloader").fadeOut('slow',function(){
                $(this).remove(); 
            });
        }, 1000); 
    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tf-nav-menu.default', responsive_menu );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfcarousel.default', carousel_Box );        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tf-nav-menu.default', onepage_nav );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tf-search.default', search_form );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfposts.default', blogPostsOwl );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfposts.default', blogPostsGallery );        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfposts.default', blogLoadMore );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfposts.default', blogMasonry );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfposts.default', postFormatIziModal );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tftabs.default', tftabs );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/vegas-slider.default', vegasSlider );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/flex-slider.default', flexSlider ); 
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tf-scroll-top.default', tfScrollTop );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tf-preload.default', tfRemovePreloader );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tf-slide-swiper.default', tf_swiper_slider );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfanimated_headline.default', tf_animated_headline );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfwooproductgrid.default', productCarousel );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfwooproductgrid.default', productLoadMore );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfwooproductgrid.default', productFilterForm );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfwooproductgrid.default', productFilterTabs );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tfwooproductsingleimage.default', productSingleImage );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tf-mini-cart.default', cavas_product_mini_cart );    
        
    });

    $(window).on('load resize', function() {
        responsive_menu();
        onepage_nav();
        search_form();              
    });

    // Dom Ready
    $(function() {
        flexSlider();
        vegasSlider();
        postFormatIziModal();
        TF_Sticky();               
    });

    $(window).on('resize', function() {
        flexSlider();
        vegasSlider();       
    });

    $(window).on('load', function() {
        cavas_product_mini_cart();
    });

})(jQuery);
