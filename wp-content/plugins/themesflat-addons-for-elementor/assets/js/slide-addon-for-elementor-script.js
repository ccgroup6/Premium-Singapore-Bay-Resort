;(function($) {

    "use strict";

    var vegasSlider = function($scope, $) {
        $(".hero-section").each(function() {
            var
            adminBarHeight = 0,
            topBarHeight = 0,
            contentTopMargin = 0,
            heroHeight = 0,
            customHeight = 0,
            adminBar = $('#wpadminbar'),
            topBar = $('#top-bar'),
            hero = $(this),
            windowHeight = $(window).height(),
            headerHeight = $('header').height(),
            heroContent = hero.find('.vegas-content'),
            contentHeight = heroContent.height(),
            delay = Number(hero.data('delay')),
            spacing = hero.data('content');
            customHeight = hero.data('height');

            if (topBar.length) topBarHeight = topBar.height();
            if (adminBar.length) adminBarHeight = adminBar.height();
            
            if ( customHeight == 'full-height' ) {                
                heroHeight = windowHeight;
            }else {
                heroHeight = customHeight;
            }

            if ( $('body').hasClass('header.header-absolute') ) {
                hero.css({ height: (heroHeight - adminBarHeight) + "px" });
                contentTopMargin = ((heroHeight - contentHeight) / 2) + topBarHeight + spacing;

                heroContent.css("padding-top", (contentTopMargin) + "px");
            } else {
                if ( customHeight == 'full-height' ) {
                    hero.css({ height: heroHeight + "px" });
                    contentTopMargin = ((heroHeight - contentHeight) / 2) + spacing;
                    heroContent.css("padding-top", (contentTopMargin) + "px");
                }else {
                    heroHeight = heroHeight - headerHeight - topBarHeight - adminBarHeight;
                    hero.css({ height: heroHeight + "px" });
                    contentTopMargin = ((heroHeight - contentHeight) / 2) + spacing;
                    heroContent.css("padding-top", (contentTopMargin) + "px");
                }
            }
            
            $(window).on('load resize', function(){
                var
                adminBarHeight = 0,
                topBarHeight = 0,
                contentTopMargin = 0,
                heroHeight = 0,
                customHeight = 0,
                adminBar = $('#wpadminbar'),
                topBar = $('#top-bar'),
                windowHeight = $(window).height(),
                headerHeight = $('#header').height(),
                heroContent = hero.find('.vegas-content'),
                contentHeight = heroContent.height(),
                spacing = hero.data('content');
                customHeight = hero.data('height');

                if (topBar.length) topBarHeight = topBar.height();
                if (adminBar.length) adminBarHeight = adminBar.height();

                if ( customHeight == 'full-height' ) {                
                    heroHeight = windowHeight;
                }else {
                    heroHeight = customHeight;
                }

                if ( $('body').hasClass('header.header-absolute') ) {
                    hero.css({ height: (heroHeight - adminBarHeight) + "px" });
                    contentTopMargin = ((heroHeight - contentHeight) / 2) + topBarHeight + spacing;

                    heroContent.css("padding-top", (contentTopMargin) + "px");

                } else {
                    if ( customHeight == 'full-height' ) {
                        hero.css({ height: heroHeight + "px" });
                        contentTopMargin = ((heroHeight - contentHeight) / 2) + spacing;
                        heroContent.css("padding-top", (contentTopMargin) + "px");
                    }else {
                        heroHeight = heroHeight - headerHeight - topBarHeight - adminBarHeight;
                        hero.css({ height: heroHeight + "px" });
                        contentTopMargin = ((heroHeight - contentHeight) / 2) + spacing;
                        heroContent.css("padding-top", (contentTopMargin) + "px");
                    }
                    
                }
                
            });

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
            flexSliderContent = $this.find('.flex_caption'),            
            contentHeight = flexSliderContent.height();
            $this.find('.item-slide').height(flexsliderHeight);
            if (topBar.length) topBarHeight = topBar.height();
            if (adminBar.length) adminBarHeight = adminBar.height();
            
            if (contentHeight == 0) {
                contentHeight = (flexsliderHeight * 0.5);
            }

            if ( $this.hasClass('header-absolute') ) {
                contentTopMargin = ((flexsliderHeight + topBarHeight + headerHeight - contentHeight ) / 2);
                flexSliderContent.css("padding-top", (contentTopMargin) + "px");
            } else {
                contentTopMargin = ((flexsliderHeight - contentHeight ) / 2);
                flexSliderContent.css("padding-top", (contentTopMargin) + "px");
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

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/vegas-slider.default', vegasSlider );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/flex-slider.default', flexSlider );
    });

    $(window).on('load resize', function() {
        flexSlider();
    });

})(jQuery);