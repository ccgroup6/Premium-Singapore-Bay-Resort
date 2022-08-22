(function($) {
  "use strict";

  var Fleurdesel = {
    initPreloader: function () {
      if (!$('#preloader').hasClass('preloader--off')) {
        $('#preloader').fadeOut(800, function () {
          $(this).addClass('preloader--off');
        });
      };
    },

    initMenu: function() {
      $('#menu-open-btn').on('click', function(e) {
        e.preventDefault();

        var target = $(this).attr('href');

        $(target).addClass('open');
        $(this).closest('.menu-hide').addClass('close');
        $('body').addClass('side-panel-open');
      });

      $('#menu-close-btn').on('click', function(e) {
        e.preventDefault();

        var target = $(this).attr('href');

        $(target).removeClass('open');
        $('#menu-open-btn').closest('.menu-hide').removeClass('close');
        $('body').removeClass('side-panel-open');
      });

      $('#fs-panel-open-btn').on('click', function(e) {
        e.preventDefault();

        var target = $(this).attr('href');

        $(target).addClass('open');

        $('body').addClass('fs-panel-open');
      });

      $('#fs-panel-close-btn').on('click', function(e) {
        e.preventDefault();

        var target = $(this).attr('href');

        $(target).removeClass('open');

        $('body').removeClass('fs-panel-open');
      });
    },

    initSlick: function() {
      $('[data-init="slick"]').each(function () {
        var el = $(this);

        var breakpointsWidth = {xs: 300, sm: 768, md: 992, lg: 1200};

        var slickDefault = {
          // fade: true,
          infinite: true,
          autoplay: true,
          pauseOnHover: true,
          speed: 1000,
          adaptiveHeight: true,

          slidesToShow: 1,
          slidesToScroll: 1,
          mobileFirst: true
        };

        // Merge settings.
        var settings = $.extend(slickDefault, el.data());
        delete settings.init;

        // Build breakpoints.
        if (settings.breakpoints) {
          var _responsive = [];
          var _breakpoints = settings.breakpoints;

          var buildBreakpoints = function (key, show, scroll) {
            if (breakpointsWidth[key] < 992) {
              _responsive.push({
                breakpoint: breakpointsWidth[key],
                settings: {
                  slidesToShow: parseInt(show),
                  slidesToScroll: 1,
                  arrows: false,
                  dots: true
                }
              });
            } else {
              _responsive.push({
                breakpoint: breakpointsWidth[key],
                settings: {
                  slidesToShow: parseInt(show),
                  slidesToScroll: 1
                }
              });
            }
          };

          if (typeof _breakpoints === "object") {
            $.each(_breakpoints, buildBreakpoints);
          }

          delete settings.breakpoints;
          settings.responsive = _responsive;
        };

        el.slick(settings);
      });
    },

    initSlickModern: function() {
      $('[data-init="slick-modern"]').each(function () {
        var $el = $(this);

        $el.slick({
          centerMode: false,
          centerPadding: 0,
          dots: true,
          arrows: false,
          slidesToShow: 1,
          mobileFirst: true,
          responsive: [
            {
              breakpoint: 960,
              settings: {
                arrows: true,
                dots: false,
                centerMode: true,
                slidesToShow: 3
              }
            },
            {
              breakpoint: 720,
              settings: {
                arrows: true,
                dots: false,
              }
            }
          ]
        });
      });
    },

    videoBox: function() {
      $('[data-init="video-box"]').each(function() {
        var $vbox = $(this),
          effect = $(this).attr('data-effect') || 'scrollup';

        $vbox.find('.video-box__button').on('click', function(e) {
          e.preventDefault();

          if ( effect == 'popup' ) {
            $.magnificPopup.open({
              items: {
                type: 'inline',
                src: $vbox.find('.video-box__video')
              },

              callbacks: {
                open: function() {
                  this.content.find('iframe')[0].src += '&autoplay=1';
                },

                close: function() {
                  var src = this.content.find('iframe')[0].src;

                  console.log(src);

                  src = src.replace( '&autoplay=1', '' );

                  this.content.find('iframe')[0].src = src;
                }
              }
            });
          } else {
            $vbox.addClass('show');

            $vbox.find('iframe')[0].src += '&autoplay=1';
          }
        });
      });
    },

    initCountUp: function() {
      var number = $('.fleurdesel-animate .fleurdesel-animate__number');

      number.each(function() {
        var el = $(this);

        el.counterUp();
      });
    },

    initGallery: function() {
      $('[data-init="gallery"]').each(function() {
        var $popup = $(this).find('[data-gallery="popup"]');

        $popup.each(function() {
          $(this).magnificPopup({
            delegate: 'a',
            type: 'image',
            gallery: {
              enabled:true,
              arrowMarkup: '<button title="%title%" type="button" class="fleurdesel-mfp-arrow fleurdesel-mfp-arrow-%dir%"></button>',
              tCounter: '<span class="mfp-counter">%curr%/%total%</span>'
            }
          });
        });
      });
    },

    initIsotope: function($method) {
      $('[data-init="isotope"]').each(function () {
        var el = $(this);

        var container = el.isotope({
          layoutMode: 'packery',
          itemSelector: '[data-grid="grid-item"]',
          percentPosition: true,
          // getSortData: {
          //   name: '.name',
          //   price: '.price'
          // },
          masonry: {
            columnWidth: '.grid-sizer'
          }
        });
      });

      // Click filter item
      $('[data-init="filter"]').each(function() {
        var el = $(this),
            btn = el.find('button');

        el.find('a').on('click', function (e) {
          e.preventDefault();
          var filterValue = $(this).attr('data-filter');

          el.find('.current').removeClass('current');
          $(this).addClass('current');
          btn.text($(this).text());

          $('[data-init="isotope"]').isotope({
            filter: filterValue
          });
        });
      });
    },

    initMasonry: function($method) {
      var $grid = $('[data-init="masonry"]').masonry({
        itemSelector: '[data-grid="grid-item"]',
        columnWidth: '.grid-sizer',
        percentPosition: true
      });
    },

    initPopup: function() {
      $('.open-popup-link').magnificPopup({
        type:'inline',
        midClick: true,
        removalDelay: 300,
        mainClass: 'mfp-fade'
      });
    },

    initMapjs: function() {
      $('[data-init="mapjs"]').each(function() {
        var el = $(this),
            icon = el.find('[data-mapjs="icon"]');

        icon.on('click', function() {
          $(this).parent().toggleClass('off');
        });
      });
    },

    initLauchIntoFullscreen: function() {
      var self = this;

      $('.fleurdesel-slider').each(function() {
        var el = $(this);
        el.removeAttr('style');

        var btnOpen = $('.fleurdesel-slider__icon--zoom-in', el),
            btnClose = $('.fleurdesel-slider__icon--close', el),
            slider = $('.fleurdesel-slider__wrap', el),
            windowHeight = $(window).height(),
            sliderWidth = slider.outerWidth(),
            sliderHeight = slider.outerHeight(),
            sliderOffsetTop = slider.offset().top,
            sliderOffsetLeft = slider.offset().left;

        el.css({
          'width': sliderWidth,
          'height': sliderHeight
        });

        el.parents('.vc_row').css('z-index','99');

        btnOpen.on('click', function(event) {
          event.preventDefault();

          var scrollTop = $(window).scrollTop();

          el.addClass('fullscreen');
          $('html, body').addClass('overflow-hidden');

          btnClose.show();
          $(this).hide();

          slider.css({
            'position': 'fixed',
            'top': sliderOffsetTop - scrollTop,
            'left': sliderOffsetLeft,
            'width': sliderWidth,
            'height': sliderHeight
          }).animate({
            'top': 0,
            'left': 0,
            'width': '100%',
            'height': '100%'
          }, 400, function() {
            $(document).trigger('resize');
          });
        });

        btnClose.on('click', function(event) {
          event.preventDefault();

          var scrollTop = $(window).scrollTop();

          $('html, body').removeClass('overflow-hidden');

          btnOpen.show();
          $(this).hide();

          slider.addClass('slider-fix').animate({
            'top': sliderOffsetTop - scrollTop,
            'left': sliderOffsetLeft,
            'width': sliderWidth,
            'height': sliderHeight
          }, 400, function() {
            el.removeClass('fullscreen');
            slider.removeClass('slider-fix').removeAttr('style');

            $(document).trigger('resize');
          });
        });
      });
    },

    initLauchAPBFullscreen: function() {
      var self = this;

      $('.fleurdesel-apb-carousel').each(function() {
        var el = $(this);
        el.removeAttr('style');

        var btnOpen = $('.fleurdesel-apb-carousel__icon--zoom-in', el);
        var btnClose = $('.fleurdesel-apb-carousel__icon--zoom-out', el);

        var slider = $('.fleurdesel-apb-carousel__product-image', el);
        var windowHeight = $(window).height();
        var sliderWidth = slider.outerWidth();
        var sliderHeight = slider.outerHeight();
        var sliderOffsetTop = slider.offset().top;
        var sliderOffsetLeft = slider.offset().left;

        el.css({
          'width': sliderWidth,
          'height': sliderHeight
        });

        btnOpen.on('click', function(event) {
          event.preventDefault();

          var scrollTop = $(window).scrollTop();

          el.addClass('fullscreen');
          $('html, body').addClass('overflow-hidden');

          btnClose.show();
          $(this).hide();

          slider.css({
            'position': 'fixed',
            'top': sliderOffsetTop - scrollTop,
            'left': sliderOffsetLeft,
            'width': sliderWidth,
            'height': sliderHeight
          }).animate({
            'top': 0,
            'left': 0,
            'width': '100%',
            'height': '100%'
          }, 400, function() {
            $(document).trigger('resize');
          });
        });

        btnClose.on('click', function(event) {
          event.preventDefault();

          var scrollTop = $(window).scrollTop();

          $('html, body').removeClass('overflow-hidden');

          btnOpen.show();
          $(this).hide();

          slider.addClass('slider-fix').animate({
            'top': sliderOffsetTop - scrollTop,
            'left': sliderOffsetLeft,
            'width': sliderWidth,
            'height': sliderHeight
          }, 400, function() {
            el.removeClass('fullscreen');
            slider.removeClass('slider-fix').removeAttr('style');

            $(document).trigger('resize');
          });
        });
      });
    },

    getDebounce: function(func, wait, immediate) {
      var timeout;
      return function() {
        var context = this, args = arguments;
        var later = function() {
          timeout = null;
          if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
      };
    },
    tabsAweBooking: function() {
      $('.awebooking-tab').each(function() {
        var $el = $(this);
        var $control = $el.find('.awebooking-tab__controls');
        var $content = $el.find('.awebooking-tab__content');

        $control.find('li').eq(0).addClass('active');
        $content.eq(0).show();

        $control.find('li').each(function() {
          var $item = $(this);

          $item.find('a').on('click', function(e) {
            e.preventDefault();
            var id = $(this).attr('href');

            $control.find('li').removeClass('active');
            $(this).parent().addClass('active');

            $content.hide();
            $(id).show();
          });
        });
      });
    },

    load: function() {
      var self = this;
      // Call functions use debounce resize function
      var resizeDebounce = self.getDebounce(function() {
        self.initLauchIntoFullscreen();
        self.initLauchAPBFullscreen();
        self.initSlick();
      }, 250);

      this.initMenu();

      this.initSlick();

      this.initSlickModern();

      this.videoBox();

      this.initCountUp();

      this.initGallery();

      this.initPopup();

      this.initMapjs();

      this.tabsAweBooking();

      window.addEventListener('resize', resizeDebounce);
    }
  };

  $(function() {
    Fleurdesel.load();
  });

  //Google map
  $(function() {
    JFFUtils.gMapInit('#map_standard');
    JFFUtils.gMapInit('#map_popup');
  });

  $(window).on('load', function() {
    Fleurdesel.initPreloader();
    Fleurdesel.initIsotope();
    Fleurdesel.initMasonry();
    Fleurdesel.initLauchIntoFullscreen();
    Fleurdesel.initLauchAPBFullscreen();
  });
})(jQuery);
