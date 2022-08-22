(function ($) {

    /****** DethemeKit Progress Bar Handler ******/
    var DethemeKitProgressBarWidgetHandler = function ($scope, trigger) {

        var $progressbarElem = $scope.find(".dethemekit-progressbar-container"),
            settings = $progressbarElem.data("settings"),
            length = settings.progress_length,
            speed = settings.speed,
            type = settings.type;


        if ("line" === type) {

            var $progressbar = $progressbarElem.find(".dethemekit-progressbar-bar");

            if (settings.gradient)
                $progressbar.css("background", "linear-gradient(-45deg, " + settings.gradient + ")");

            $progressbar.animate({
                width: length + "%"
            }, speed);

        } else if ("circle" === type) {
            if (length > 100)
                length = 100;

            $progressbarElem.prop({
                'counter': 0
            }).animate({
                counter: length
            }, {
                duration: speed,
                easing: 'linear',
                step: function (counter) {
                    var rotate = (counter * 3.6);

                    $progressbarElem.find(".dethemekit-progressbar-right-label span").text(Math.ceil(counter) + "%");

                    $progressbarElem.find(".dethemekit-progressbar-circle-left").css('transform', "rotate(" + rotate + "deg)");
                    if (rotate > 180) {

                        $progressbarElem.find(".dethemekit-progressbar-circle").css({
                            '-webkit-clip-path': 'inset(0)',
                            'clip-path': 'inset(0)',
                        });

                        $progressbarElem.find(".dethemekit-progressbar-circle-right").css('visibility', 'visible');
                    }
                }
            });

        } else {

            var $progressbar = $progressbarElem.find(".dethemekit-progressbar-bar-wrap"),
                width = $progressbarElem.outerWidth(),
                dotSize = settings.dot || 25,
                dotSpacing = settings.spacing || 10,
                numberOfCircles = Math.ceil(width / (dotSize + dotSpacing)),
                circlesToFill = numberOfCircles * (length / 100),
                numberOfTotalFill = Math.floor(circlesToFill),
                fillPercent = 100 * (circlesToFill - numberOfTotalFill);

            $progressbar.attr('data-circles', numberOfCircles);
            $progressbar.attr('data-total-fill', numberOfTotalFill);
            $progressbar.attr('data-partial-fill', fillPercent);

            var className = "progress-segment";
            for (var i = 0; i < numberOfCircles; i++) {
                className = "progress-segment";
                var innerHTML = '';

                if (i < numberOfTotalFill) {
                    innerHTML = "<div class='segment-inner'></div>";
                } else if (i === numberOfTotalFill) {

                    innerHTML = "<div class='segment-inner'></div>";
                }

                $progressbar.append("<div class='" + className + "'>" + innerHTML + "</div>");

            }

            if ("frontend" !== trigger) {
                DethemeKitProgressDotsHandler($scope);
            }

        }

    };

    var DethemeKitProgressDotsHandler = function ($scope) {

        var $progressbarElem = $scope.find(".dethemekit-progressbar-container"),
            settings = $progressbarElem.data("settings"),
            $progressbar = $scope.find(".dethemekit-progressbar-bar-wrap"),
            data = $progressbar.data(),
            speed = settings.speed,
            increment = 0;

        var numberOfTotalFill = data.totalFill,
            numberOfCircles = data.circles,
            fillPercent = data.partialFill;

        dotIncrement(increment);

        function dotIncrement(inc) {

            var $dot = $progressbar.find(".progress-segment").eq(inc),
                dotWidth = 100;

            if (inc === numberOfTotalFill)
                dotWidth = fillPercent

            $dot.find(".segment-inner").animate({
                width: dotWidth + '%'
            }, speed / numberOfCircles, function () {
                increment++;
                if (increment <= numberOfTotalFill) {
                    dotIncrement(increment);
                }

            });
        }
    };

    /****** DethemeKit Progress Bar Scroll Handler *****/
    var DethemeKitProgressBarScrollWidgetHandler = function ($scope, $) {

        var $progressbarElem = $scope.find(".dethemekit-progressbar-container"),
            settings = $progressbarElem.data("settings"),
            type = settings.type;

        if ("dots" === type) {
            DethemeKitProgressBarWidgetHandler($scope, "frontend");
        }

        elementorFrontend.waypoint($scope, function () {
            if ("dots" !== type) {
                DethemeKitProgressBarWidgetHandler($(this));
            } else {
                DethemeKitProgressDotsHandler($(this));
            }

        }, {
            offset: Waypoint.viewportHeight() - 150,
            triggerOnce: true
        });
    };

    /****** DethemeKit Video Box Handler ******/
    var DethemeKitVideoBoxWidgetHandler = function ($scope, $) {

        var $videoBoxElement = $scope.find(".dethemekit-video-box-container"),
            $videoListElement = $scope.find(".dethemekit-video-box-playlist-container"),
            $videoContainer = $videoBoxElement.find(".dethemekit-video-box-video-container"),
            $videoInnerContainer = $videoBoxElement.find('.dethemekit-video-box-inner-wrap'),
            $videoImageContainer = $videoInnerContainer.find('.dethemekit-video-box-image-container'),
            type = $videoBoxElement.data("type"),
            thumbnail = $videoBoxElement.data("thumbnail"),
            sticky = $videoBoxElement.data('sticky'),
            stickyOnPlay = $videoBoxElement.data('sticky-play'),
            hoverEffect = $videoBoxElement.data("hover"),
            video, vidSrc;

        // Youtube playlist option
        if ($videoListElement.length) {

            //Make sure that video were pulled from the API.
            if (!$videoContainer.length)
                return;

            $videoContainer.each(function (index, item) {

                var vidSrc,
                    $videoContainer = $(item),
                    $videoBoxElement = $videoContainer.closest(".dethemekit-video-box-container");

                vidSrc = $videoContainer.data("src");
                vidSrc = vidSrc + "&autoplay=1";

                $videoBoxElement.on("click", function () {

                    var $iframe = $("<iframe/>");

                    $iframe.attr({
                        "src": vidSrc,
                        "frameborder": "0",
                        "allowfullscreen": "1",
                        "allow": "autoplay;encrypted-media;"
                    });
                    $videoContainer.css("background", "#000");
                    $videoContainer.html($iframe);

                    $videoBoxElement.find(
                        ".dethemekit-video-box-image-container, .dethemekit-video-box-play-icon-container"
                    ).remove();

                });

            });

            return;
        }

        if ("self" === type) {

            video = $videoContainer.find("video");
            vidSrc = video.attr("src");

        } else {

            vidSrc = $videoContainer.data("src");

            if (!thumbnail || -1 !== vidSrc.indexOf("autoplay=1")) {

                //Check if Autoplay on viewport option is enabled
                if ($videoBoxElement.data("play-viewport")) {
                    elementorFrontend.waypoint($videoBoxElement, function () {
                        playVideo();
                    });
                } else {
                    playVideo();
                }

            } else {
                vidSrc = vidSrc + "&autoplay=1";
            }

        }

        function playVideo() {

            if ($videoBoxElement.hasClass("playing")) return;

            $videoBoxElement.addClass("playing");

            if (stickyOnPlay === 'yes')
                stickyOption();

            if ("self" === type) {

                $(video).get(0).play();

                $videoContainer.css({
                    opacity: "1",
                    visibility: "visible"
                });

            } else {

                var $iframe = $("<iframe/>");

                $iframe.attr({
                    "src": vidSrc,
                    "frameborder": "0",
                    "allowfullscreen": "1",
                    "allow": "autoplay;encrypted-media;"
                });
                $videoContainer.css("background", "#000");
                $videoContainer.html($iframe);
            }

            $videoBoxElement.find(
                ".dethemekit-video-box-image-container, .dethemekit-video-box-play-icon-container, .dethemekit-video-box-description-container"
            ).remove();

            if ("vimeo" === type)
                $videoBoxElement.find(".dethemekit-video-box-vimeo-wrap").remove();
        }

        $videoBoxElement.on("click", function () {
            playVideo();
        });


        if ("yes" !== sticky || "yes" === stickyOnPlay)
            return;

        stickyOption();

        function stickyOption() {

            var stickyDesktop = $videoBoxElement.data('hide-desktop'),
                stickyTablet = $videoBoxElement.data('hide-tablet'),
                stickyMobile = $videoBoxElement.data('hide-mobile'),
                stickyMargin = $videoBoxElement.data('sticky-margin');

            $videoBoxElement.off('click').on('click', function (e) {
                // if ('yes' === sticky) {
                var stickyTarget = e.target.className;
                if ((stickyTarget.toString().indexOf('dethemekit-video-box-sticky-close') >= 0) || (stickyTarget.toString().indexOf('dethemekit-video-box-sticky-close') >= 0)) {
                    return false;
                }
                // }
                playVideo();

            });

            //Make sure Elementor Waypoint is defined
            if (typeof elementorFrontend.waypoint !== 'undefined') {

                var stickyWaypoint = elementorFrontend.waypoint(
                    $videoBoxElement,
                    function (direction) {
                        if ('down' === direction) {

                            $videoBoxElement.removeClass('dethemekit-video-box-sticky-hide').addClass('dethemekit-video-box-sticky-apply dethemekit-video-box-filter-sticky');

                            //Fix conflict with Elementor motion effects
                            if ($scope.hasClass("elementor-motion-effects-parent")) {
                                $scope.removeClass("elementor-motion-effects-perspective").find(".elementor-widget-container").addClass("dethemekit-video-box-transform");
                            }

                            if ($videoBoxElement.data("mask")) {
                                //Fix Sticky position issue when drop-shadow is applied
                                $scope.find(".dethemekit-video-box-mask-filter").removeClass("dethemekit-video-box-mask-filter");

                                $videoBoxElement.find(':first-child').removeClass('dethemekit-video-box-mask-media');

                                $videoImageContainer.removeClass(hoverEffect).removeClass('dethemekit-video-box-mask-media').css({
                                    'transition': 'width 0.2s, height 0.2s',
                                    '-webkit-transition': 'width 0.2s, height 0.2s'
                                });
                            }

                            $(document).trigger('dethemekit_after_sticky_applied', [$scope]);

                            // Entrance Animation Option
                            if ($videoInnerContainer.data("video-animation") && " " != $videoInnerContainer.data("video-animation")) {
                                $videoInnerContainer.css("opacity", "0");
                                var animationDelay = $videoInnerContainer.data('delay-animation');
                                setTimeout(function () {

                                    $videoInnerContainer.css("opacity", "1").addClass("animated " + $videoInnerContainer.data("video-animation"));

                                }, animationDelay * 1000);
                            }

                        } else {

                            $videoBoxElement.removeClass('dethemekit-video-box-sticky-apply  dethemekit-video-box-filter-sticky').addClass('dethemekit-video-box-sticky-hide');

                            //Fix conflict with Elementor motion effects
                            if ($scope.hasClass("elementor-motion-effects-parent")) {
                                $scope.addClass("elementor-motion-effects-perspective").find(".elementor-widget-container").removeClass("dethemekit-video-box-transform");
                            }

                            if ($videoBoxElement.data("mask")) {
                                //Fix Sticky position issue when drop-shadow is applied
                                $videoBoxElement.parent().addClass("dethemekit-video-box-mask-filter");

                                $videoBoxElement.find(':first-child').eq(0).addClass('dethemekit-video-box-mask-media');
                                $videoImageContainer.addClass('dethemekit-video-box-mask-media');
                            }

                            $videoImageContainer.addClass(hoverEffect).css({
                                'transition': 'all 0.2s',
                                '-webkit-transition': 'all 0.2s'
                            });

                            $videoInnerContainer.removeClass("animated " + $videoInnerContainer.data("video-animation"));
                        }
                    }, {
                    offset: 0 + '%',
                    triggerOnce: false
                }
                );
            }

            var closeBtn = $scope.find('.dethemekit-video-box-sticky-close');

            closeBtn.off('click.closetrigger').on('click.closetrigger', function (e) {
                stickyWaypoint[0].disable();

                $videoBoxElement.removeClass('dethemekit-video-box-sticky-apply dethemekit-video-box-sticky-hide');

                //Fix conflict with Elementor motion effects
                if ($scope.hasClass("elementor-motion-effects-parent")) {
                    $scope.addClass("elementor-motion-effects-perspective").find(".elementor-widget-container").removeClass("dethemekit-video-box-transform");
                }

                if ($videoBoxElement.data("mask")) {
                    //Fix Sticky position issue when drop-shadow is applied
                    $videoBoxElement.parent().addClass("dethemekit-video-box-mask-filter");

                    //Necessary classes for mask shape option
                    $videoBoxElement.find(':first-child').eq(0).addClass('dethemekit-video-box-mask-media');
                    $videoImageContainer.addClass('dethemekit-video-box-mask-media');
                }


            });

            checkResize(stickyWaypoint);

            checkScroll();

            window.addEventListener("scroll", checkScroll);

            $(window).resize(function (e) {
                checkResize(stickyWaypoint);
            });

            function checkResize(stickyWaypoint) {
                var currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

                if ('' !== stickyDesktop && currentDeviceMode == stickyDesktop) {
                    disableSticky(stickyWaypoint);
                } else if ('' !== stickyTablet && currentDeviceMode == stickyTablet) {
                    disableSticky(stickyWaypoint);
                } else if ('' !== stickyMobile && currentDeviceMode == stickyMobile) {
                    disableSticky(stickyWaypoint);
                } else {
                    stickyWaypoint[0].enable();
                }
            }

            function disableSticky(stickyWaypoint) {
                stickyWaypoint[0].disable();
                $videoBoxElement.removeClass('dethemekit-video-box-sticky-apply dethemekit-video-box-sticky-hide');
            }

            function checkScroll() {
                if ($videoBoxElement.hasClass('dethemekit-video-box-sticky-apply')) {
                    $videoInnerContainer.draggable({
                        start: function () {
                            $(this).css({
                                transform: "none",
                                top: $(this).offset().top + "px",
                                left: $(this).offset().left + "px"
                            });
                        },
                        containment: 'window'
                    });
                }
            }

            $(document).on('dethemekit_after_sticky_applied', function (e, $scope) {
                var infobar = $scope.find('.dethemekit-video-box-sticky-infobar');

                if (0 !== infobar.length) {
                    var infobarHeight = infobar.outerHeight();

                    if ($scope.hasClass('dethemekit-video-sticky-center-left') || $scope.hasClass('dethemekit-video-sticky-center-right')) {
                        infobarHeight = Math.ceil(infobarHeight / 2);
                        $videoInnerContainer.css('top', 'calc( 50% - ' + infobarHeight + 'px )');
                    }

                    if ($scope.hasClass('dethemekit-video-sticky-bottom-left') || $scope.hasClass('dethemekit-video-sticky-bottom-right')) {
                        if ('' !== stickyMargin) {
                            infobarHeight = Math.ceil(infobarHeight);
                            var stickBottom = infobarHeight + stickyMargin;
                            $videoInnerContainer.css('bottom', stickBottom);
                        }
                    }
                }
            });

        }

    };

    /****** DethemeKit Media Grid Handler ******/
    var DethemeKitGridWidgetHandler = function ($scope, $) {

        var $galleryElement = $scope.find(".dethemekit-gallery-container"),
            settings = $galleryElement.data("settings"),
            layout = settings.img_size,
            deviceType = elementorFrontend.getCurrentDeviceMode(),
            loadMore = settings.load_more,
            columnWidth = null,
            filter = null,
            isFilterClicked = false,
            minimum = settings.minimum,
            imageToShow = settings.click_images,
            counter = minimum,
            ltrMode = settings.ltr_mode,
            shuffle = settings.shuffle;

        var $filters = $scope.find(".dethemekit-gallery-cats-container li");

        if (layout === "metro") {

            var gridWidth = $galleryElement.width(),
                cellSize = Math.floor(gridWidth / 12),
                suffix = null;

            setMetroLayout();

            function setMetroLayout() {

                deviceType = elementorFrontend.getCurrentDeviceMode();
                gridWidth = $galleryElement.width();
                cellSize = Math.floor(gridWidth / 12);
                suffix = "";

                if ("tablet" === deviceType) {
                    suffix = "_tablet";
                } else if ("mobile" === deviceType) {
                    suffix = "_mobile";
                }

                $galleryElement.find(".dethemekit-gallery-item").each(function (index, item) {

                    var cells = $(item).data("metro")["cells" + suffix],
                        vCells = $(item).data("metro")["vcells" + suffix];

                    if ("" == cells || undefined == cells) {
                        cells = $(item).data("metro")["cells"];
                    }
                    if ("" == vCells || undefined == vCells) {
                        vCells = $(item).data("metro")["vcells"];
                    }

                    $(item).css({
                        width: Math.ceil(cells * cellSize),
                        height: Math.ceil(vCells * cellSize)
                    });
                });

                columnWidth = cellSize;
            }

            layout = "masonry";

            $(window).resize(function () {

                setMetroLayout();

                $isotopeGallery.isotope({
                    itemSelector: ".dethemekit-gallery-item",
                    masonry: {
                        columnWidth: columnWidth
                    },
                });

            });
        }


        var $isotopeGallery = $galleryElement.isotope({
            itemSelector: ".dethemekit-gallery-item",
            percentPosition: true,
            animationOptions: {
                duration: 750,
                easing: "linear"
            },
            filter: settings.active_cat,
            layoutMode: layout,
            originLeft: ltrMode,
            masonry: {
                columnWidth: columnWidth
            },
            sortBy: settings.sort_by
        });

        $isotopeGallery.imagesLoaded().progress(function () {
            $isotopeGallery.isotope("layout");
        });

        $(document).ready(function () {

            $isotopeGallery.isotope("layout");
            //Make sure to filter after all images are loaded
            $isotopeGallery.isotope({
                filter: settings.active_cat
            });

            //Trigger filter tabs from differet pages
            var url = new URL(window.location.href);

            if (url) {
                var filterIndex = url.searchParams.get(settings.flag);

                if (filterIndex) {

                    var $targetFilter = $filters.eq(filterIndex).find("a");

                    $targetFilter.trigger('click');

                }

            }

        });

        if (loadMore) {

            $galleryElement.parent().find(".dethemekit-gallery-load-more div").addClass(
                "dethemekit-gallery-item-hidden");
            if ($galleryElement.find(".dethemekit-gallery-item").length > minimum) {
                $galleryElement.parent().find(".dethemekit-gallery-load-more").removeClass(
                    "dethemekit-gallery-item-hidden");
                $galleryElement.find(".dethemekit-gallery-item:gt(" + (minimum - 1) + ")").addClass(
                    "dethemekit-gallery-item-hidden");

                function appendItems(imagesToShow) {

                    var instance = $galleryElement.data("isotope");
                    $galleryElement.find(".dethemekit-gallery-item-hidden").removeClass(
                        "dethemekit-gallery-item-hidden");
                    $galleryElement.parent().find(".dethemekit-gallery-load-more").removeClass(
                        "dethemekit-gallery-item-hidden");
                    var itemsToHide = instance.filteredItems.slice(imagesToShow, instance
                        .filteredItems.length).map(function (item) {
                            return item.element;
                        });
                    $(itemsToHide).addClass("dethemekit-gallery-item-hidden");
                    $isotopeGallery.isotope("layout");
                    if (0 == itemsToHide) {
                        $galleryElement.parent().find(".dethemekit-gallery-load-more").addClass(
                            "dethemekit-gallery-item-hidden");
                    }
                }

                $galleryElement.parent().on("click", ".dethemekit-gallery-load-more-btn", function () {
                    if (isFilterClicked) {
                        counter = minimum;
                        isFilterClicked = false;
                    } else {
                        counter = counter;
                    }
                    counter = counter + imageToShow;
                    $.ajax({
                        url: appendItems(counter),
                        beforeSend: function () {
                            $galleryElement.parent().find(
                                ".dethemekit-gallery-load-more div").removeClass(
                                    "dethemekit-gallery-item-hidden");
                        },
                        success: function () {
                            $galleryElement.parent().find(
                                ".dethemekit-gallery-load-more div").addClass(
                                    "dethemekit-gallery-item-hidden");
                        }
                    });
                });
            }
        }

        if ("yes" !== settings.light_box) {

            $galleryElement.find(".dethemekit-gallery-video-wrap").each(function (index, item) {
                var type = $(item).data("type");
                $(item).closest(".dethemekit-gallery-item").on("click", function () {
                    var $this = $(this);
                    $this.find(".pa-gallery-img-container").css("background", "#000");

                    $this.find("img, .pa-gallery-icons-caption-container, .pa-gallery-icons-wrapper").css("visibility", "hidden");

                    if ("style3" !== settings.skin)
                        $this.find(".dethemekit-gallery-caption").css("visibility", "hidden");

                    if ("hosted" !== type) {
                        var $iframeWrap = $this.find(".dethemekit-gallery-iframe-wrap"),
                            src = $iframeWrap.data("src");

                        src = src.replace("&mute", "&autoplay=1&mute");

                        var $iframe = $("<iframe/>");

                        $iframe.attr({
                            "src": src,
                            "frameborder": "0",
                            "allowfullscreen": "1",
                            "allow": "autoplay;encrypted-media;"
                        });

                        $iframeWrap.html($iframe);

                        $iframe.css("visibility", "visible");
                    } else {
                        var $video = $(item).find("video");
                        $video.get(0).play();
                        $video.css("visibility", "visible");
                    }
                });
            });

        }

        $filters.find("a").click(function (e) {
            e.preventDefault();

            isFilterClicked = true;

            //Show category images
            $filters.find(".active").removeClass("active");
            $(this).addClass("active");

            filter = $(this).attr("data-filter");
            $isotopeGallery.isotope({
                filter: filter
            });

            if (shuffle) $isotopeGallery.isotope("shuffle");

            if (loadMore) appendItems(minimum);

            return false;
        });

        if ("default" === settings.lightbox_type) {

            $scope.find(".dethemekit-img-gallery a[data-rel^='prettyPhoto']").prettyPhoto({
                theme: settings.theme,
                hook: "data-rel",
                opacity: 0.7,
                show_title: false,
                deeplinking: false,
                overlay_gallery: settings.overlay,
                custom_markup: "",
                default_width: 900,
                default_height: 506,
                social_tools: ""
            });

        }
    };

    /****** DethemeKit Counter Handler ******/
    var DethemeKitCounterHandler = function ($scope, $) {

        var $counterElement = $scope.find(".dethemekit-counter");

        elementorFrontend.waypoint($counterElement, function () {

            var counterSettings = $counterElement.data(),
                incrementElement = $counterElement.find(".dethemekit-counter-init"),
                iconElement = $counterElement.find(".icon");

            $(incrementElement).numerator(counterSettings);

            $(iconElement).addClass("animated " + iconElement.data("animation"));

        });

    };

    /****** DethemeKit Fancy Text Handler ******/
    var DethemeKitFancyTextHandler = function ($scope, $) {

        var $elem = $scope.find(".dethemekit-fancy-text-wrapper");
        var settings = $elem.data("settings");

        function escapeHtml(unsafe) {
            return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(
                /"/g, "&quot;").replace(/'/g, "&#039;");
        }

        if (settings.effect === "typing") {

            var fancyStrings = [];
            settings.strings.forEach(function (item) {
                fancyStrings.push(escapeHtml(item));
            });

            $elem.find(".dethemekit-fancy-text").typed({
                strings: fancyStrings,
                typeSpeed: settings.typeSpeed,
                backSpeed: settings.backSpeed,
                startDelay: settings.startDelay,
                backDelay: settings.backDelay,
                showCursor: settings.showCursor,
                cursorChar: settings.cursorChar,
                loop: settings.loop
            });

        } else if (settings.effect === "slide") {

            $elem.find(".dethemekit-fancy-text").vTicker({
                speed: settings.speed,
                showItems: settings.showItems,
                pause: settings.pause,
                mousePause: settings.mousePause,
                direction: "up"
            });

        } else {

            setFancyAnimation();

            function setFancyAnimation() {

                var $item = $elem.find(".dethemekit-fancy-list-items"),
                    current = 1;

                //Get effect settings
                var delay = settings.delay || 2500,
                    loopCount = settings.count;

                //If Loop Count option is set
                if (loopCount) {
                    var currentLoop = 1,
                        fancyStringsCount = $elem.find(".dethemekit-fancy-list-items").length;
                }

                var loopInterval = setInterval(function () {

                    var animationClass = "";

                    //Add animation class
                    if (settings.effect === "custom")
                        animationClass = "animated " + settings.animation;

                    //Show current active item
                    $item.eq(current).addClass("dethemekit-fancy-item-visible " + animationClass).removeClass("dethemekit-fancy-item-hidden");

                    var $inactiveItems = $item.filter(function (index) {
                        return index !== current;
                    });

                    //Hide inactive items
                    $inactiveItems.addClass("dethemekit-fancy-item-hidden").removeClass("dethemekit-fancy-item-visible " + animationClass);

                    current++;

                    //Restart loop
                    if ($item.length === current)
                        current = 0;

                    //Increment interval and check if loop count is reached
                    if (loopCount) {
                        currentLoop++;

                        if ((fancyStringsCount * loopCount) === currentLoop)
                            clearInterval(loopInterval);
                    }


                }, delay);
            }
        }
    };

    /****** DethemeKit Countdown Handler ******/
    var DethemeKitCountDownHandler = function ($scope, $) {

        var $countDownElement = $scope.find(".dethemekit-countdown"),
            settings = $countDownElement.data("settings"),
            label1 = settings.label1,
            label2 = settings.label2,
            newLabe1 = label1.split(","),
            newLabel2 = label2.split(",");

        if (settings.event === "onExpiry") {

            $countDownElement.find(".dethemekit-countdown-init").pre_countdown({
                labels: newLabel2,
                labels1: newLabe1,
                until: new Date(settings.until),
                format: settings.format,
                padZeroes: true,
                timeSeparator: settings.separator,
                onExpiry: function () {
                    $countDownElement.find('.dethemekit-countdown-init').html(settings.text);
                },
                serverSync: function () {
                    return new Date(settings.serverSync);
                }
            });

        } else if (settings.event === "expiryUrl") {

            $countDownElement.find(".dethemekit-countdown-init").pre_countdown({
                labels: newLabel2,
                labels1: newLabe1,
                until: new Date(settings.until),
                format: settings.format,
                padZeroes: true,
                timeSeparator: settings.separator,
                expiryUrl: settings.text,
                serverSync: function () {
                    return new Date(settings.serverSync);
                }
            });

        } else if (settings.event === "digit") {

            $countDownElement.find(".dethemekit-countdown-init").pre_countdown({
                labels: newLabel2,
                labels1: newLabe1,
                until: new Date(settings.until),
                format: settings.format,
                padZeroes: true,
                timeSeparator: settings.separator,
                serverSync: function () {
                    return new Date(settings.serverSync);
                }
            });
        }

        times = $countDownElement.find(".dethemekit-countdown-init").pre_countdown("getTimes");

        function runTimer(el) {
            return el == 0;
        }

        if (times.every(runTimer)) {

            if (settings.event === "onExpiry") {
                $countDownElement.find(".dethemekit-countdown-init").html(settings.text);
            } else if (settings.event === "expiryUrl") {
                var editMode = $("body").find("#elementor").length;
                if (editMode > 0) {
                    $countDownElement.find(".dethemekit-countdown-init").html(
                        "<h1>You can not redirect url from elementor Editor!!</h1>");
                } else {
                    window.location.href = settings.text;
                }
            }
        }

    };

    /****** DethemeKit Carousel Handler ******/
    var DethemeKitCarouselHandler = function ($scope, $) {

        var $carouselElem = $scope.find(".dethemekit-carousel-wrapper"),
            settings = $($carouselElem).data("settings"),
            isEdit = elementorFrontend.isEditMode();

        function slideToShow(slick) {

            var slidesToShow = slick.options.slidesToShow,
                windowWidth = $(window).width();
            if (windowWidth > settings.tabletBreak) {
                slidesToShow = settings.slidesDesk;
            }
            if (windowWidth <= settings.tabletBreak) {
                slidesToShow = settings.slidesTab;
            }
            if (windowWidth <= settings.mobileBreak) {
                slidesToShow = settings.slidesMob;
            }
            return slidesToShow;

        }

        //Get templates content on the editor page
        if (isEdit) {

            $carouselElem.find(".item-wrapper").each(function (index, slide) {

                var templateID = $(slide).data("template");

                if (undefined !== templateID) {
                    $.ajax({
                        type: "GET",
                        url: DethemeKitSettings.ajaxurl,
                        dataType: "html",
                        data: {
                            action: "get_elementor_template_content",
                            templateID: templateID
                        }
                    }).success(function (response) {

                        var data = JSON.parse(response).data;

                        if (undefined !== data.template_content) {

                            $(slide).html(data.template_content);
                            $carouselElem.find(".dethemekit-carousel-inner").slick("refresh");

                        }
                    });
                }
            });

        }

        $carouselElem.on("init", function (event) {

            event.preventDefault();

            setTimeout(function () {
                resetAnimations("init");
            }, 500);

            $(this).find("item-wrapper.slick-active").each(function () {
                var $this = $(this);
                $this.addClass($this.data("animation"));
            });

            $(".slick-track").addClass("translate");

        });

        $carouselElem.find(".dethemekit-carousel-inner").slick({
            vertical: settings.vertical,
            slidesToScroll: settings.slidesToScroll,
            slidesToShow: settings.slidesToShow,
            responsive: [{
                breakpoint: settings.tabletBreak,
                settings: {
                    slidesToShow: settings.slidesTab,
                    slidesToScroll: settings.slidesTab
                }
            },
            {
                breakpoint: settings.mobileBreak,
                settings: {
                    slidesToShow: settings.slidesMob,
                    slidesToScroll: settings.slidesMob
                }
            }
            ],
            useTransform: true,
            fade: settings.fade,
            infinite: settings.infinite,
            speed: settings.speed,
            autoplay: settings.autoplay,
            autoplaySpeed: settings.autoplaySpeed,
            draggable: settings.draggable,
            touchMove: settings.touchMove,
            rtl: settings.rtl,
            adaptiveHeight: settings.adaptiveHeight,
            pauseOnHover: settings.pauseOnHover,
            centerMode: settings.centerMode,
            centerPadding: settings.centerPadding,
            arrows: settings.arrows,
            prevArrow: $carouselElem.find(".dethemekit-carousel-nav-arrow-prev").html(),
            nextArrow: $carouselElem.find(".dethemekit-carousel-nav-arrow-next").html(),
            dots: settings.dots,
            customPaging: function () {
                var customDot = $carouselElem.find(".dethemekit-carousel-nav-dot").html();
                return customDot;
            }
        });

        function resetAnimations(event) {

            var $slides = $carouselElem.find(".dethemekit-carousel-template");

            if ("init" === event)
                $slides = $slides.not(".slick-current");

            $slides.find(".animated").each(function (index, elem) {

                var settings = $(elem).data("settings");

                if (!settings)
                    return;

                if (!settings._animation && !settings.animation)
                    return;

                var animation = settings._animation || settings.animation;

                $(elem).removeClass("animated " + animation).addClass("elementor-invisible");
            });
        };

        function triggerAnimation() {

            $carouselElem.find(".slick-active .elementor-invisible").each(function (index, elem) {

                var settings = $(elem).data("settings");

                if (!settings)
                    return;

                if (!settings._animation && !settings.animation)
                    return;

                var delay = settings._animation_delay ? settings._animation_delay : 0,
                    animation = settings._animation || settings.animation;

                setTimeout(function () {
                    $(elem).removeClass("elementor-invisible").addClass(animation +
                        ' animated');
                }, delay);
            });
        }

        $carouselElem.on("afterChange", function (event, slick, currentSlide) {

            var slidesScrolled = slick.options.slidesToScroll,
                slidesToShow = slideToShow(slick),
                centerMode = slick.options.centerMode,
                slideToAnimate = currentSlide + slidesToShow - 1;

            //Trigger Aniamtions for the current slide
            triggerAnimation();

            if (slidesScrolled === 1) {
                if (!centerMode === true) {
                    var $inViewPort = $(this).find("[data-slick-index='" + slideToAnimate +
                        "']");
                    if ("null" != settings.animation) {
                        $inViewPort.find("p, h1, h2, h3, h4, h5, h6, span, a, img, i, button")
                            .addClass(settings.animation).removeClass(
                                "dethemekit-carousel-content-hidden");
                    }
                }
            } else {
                for (var i = slidesScrolled + currentSlide; i >= 0; i--) {
                    $inViewPort = $(this).find("[data-slick-index='" + i + "']");
                    if ("null" != settings.animation) {
                        $inViewPort.find("p, h1, h2, h3, h4, h5, h6, span, a, img, i, button")
                            .addClass(settings.animation).removeClass(
                                "dethemekit-carousel-content-hidden");
                    }
                }
            }
        });

        $carouselElem.on("beforeChange", function (event, slick, currentSlide) {

            //Reset Aniamtions for the other slides
            resetAnimations();

            var $inViewPort = $(this).find("[data-slick-index='" + currentSlide + "']");

            if ("null" != settings.animation) {
                $inViewPort.siblings().find(
                    "p, h1, h2, h3, h4, h5, h6, span, a, img, i, button").removeClass(
                        settings.animation).addClass(
                            "dethemekit-carousel-content-hidden");
            }
        });

        if (settings.vertical) {

            var maxHeight = -1;

            elementorFrontend.elements.$window.on('load', function () {
                $carouselElem.find(".slick-slide").each(function () {
                    if ($(this).height() > maxHeight) {
                        maxHeight = $(this).height();
                    }
                });
                $carouselElem.find(".slick-slide").each(function () {
                    if ($(this).height() < maxHeight) {
                        $(this).css("margin", Math.ceil(
                            (maxHeight - $(this).height()) / 2) + "px 0");
                    }
                });
            });
        }
        var marginFix = {
            element: $("a.ver-carousel-arrow"),
            getWidth: function () {
                var width = this.element.outerWidth();
                return width / 2;
            },
            setWidth: function (type) {
                type = type || "vertical";
                if (type == "vertical") {
                    this.element.css("margin-left", "-" + this.getWidth() + "px");
                } else {
                    this.element.css("margin-top", "-" + this.getWidth() + "px");
                }
            }
        };
        marginFix.setWidth();
        marginFix.element = $("a.carousel-arrow");
        marginFix.setWidth("horizontal");

        $(document).ready(function () {

            settings.navigation.map(function (item, index) {

                if (item) {

                    $(item).on("click", function () {

                        var currentActive = $carouselElem.find(".dethemekit-carousel-inner").slick("slickCurrentSlide");

                        if (index !== currentActive) {
                            $carouselElem.find(".dethemekit-carousel-inner").slick("slickGoTo", index)
                        }

                    })
                }

            })
        })

    };

    /****** DethemeKit Banner Handler ******/
    var DethemeKitBannerHandler = function ($scope, $) {
        var $bannerElement = $scope.find(".dethemekit-banner"),
            $bannerImg = $bannerElement.find("img");


        if ($bannerElement.data("box-tilt")) {
            var reverse = $bannerElement.data("box-tilt-reverse");
            UniversalTilt.init({
                elements: $bannerElement,
                settings: {
                    reverse: reverse
                },
                callbacks: {
                    onMouseLeave: function (el) {
                        el.style.boxShadow = "0 45px 100px rgba(255, 255, 255, 0)";
                    },
                    onDeviceMove: function (el) {
                        el.style.boxShadow = "0 45px 100px rgba(255, 255, 255, 0.3)";
                    }
                }
            });
        }


        $bannerElement.find(".dethemekit-banner-ib").hover(function () {
            $bannerImg.addClass("active");
        }, function () {
            $bannerImg.removeClass("active");
        });
    };

    /****** DethemeKit Modal Box Handler ******/
    var DethemeKitModalBoxHandler = function ($scope, $) {

        var $modalElem = $scope.find(".dethemekit-modal-box-container"),
            settings = $modalElem.data("settings"),
            $modal = $modalElem.find(".dethemekit-modal-box-modal-dialog"),
            instance = null;

        if (settings.trigger === "pageload") {
            $(document).ready(function ($) {
                setTimeout(function () {
                    $modalElem.find(".dethemekit-modal-box-modal").modal();
                }, settings.delay * 1000);
            });
        }

        if ($modal.data("modal-animation") && " " != $modal.data("modal-animation")) {
            var animationDelay = $modal.data('delay-animation');
            var waypoint = new Waypoint({
                element: $modal,
                handler: function () {
                    setTimeout(function () {
                        $modal.css("opacity", "1"),
                            $modal.addClass("animated " + $modal.data("modal-animation"));
                    }, animationDelay * 1000);
                    this.destroy();
                },
                offset: Waypoint.viewportHeight() - 150,
            });
        }
    };

    /****** DethemeKit Blog Handler ******/
    var DethemeKitBlogHandler = function ($scope, $) {

        var $blogElement = $scope.find(".dethemekit-blog-wrap"),
            $blogPost = $blogElement.find(".dethemekit-blog-post-outer-container"),
            scrollAfter = $blogElement.data("scroll"),
            carousel = $blogElement.data("carousel"),
            grid = $blogElement.data("grid"),
            layout = $blogElement.data("layout"),
            pagination = $blogElement.data("pagination"),
            activeCategory = $scope.find(".category.active").data("filter"),
            filterTabs = $scope.find(".dethemekit-blog-filter").length,
            pageNumber = 1;

        var $metaSeparators = $blogPost.first().find(".dethemekit-blog-meta-separator");

        if (1 === $metaSeparators.length) {
            //If two meta only are enabled. One of them is author meta.
            if (!$blogPost.find(".fa-user").length) {
                $blogPost.find(".dethemekit-blog-meta-separator").remove();
            }
        } else {
            if (!$blogPost.find(".fa-user").length) {
                $blogPost.each(function (index, post) {
                    $(post).find(".dethemekit-blog-meta-separator").first().remove();
                });
            }
        }

        if (filterTabs) {
            $scope.find(".dethemekit-blog-filters-container li a").click(function (e) {

                e.preventDefault();

                $scope.find(".dethemekit-blog-filters-container li .active").removeClass("active");

                $(this).addClass("active");

                //Get clicked tab slug
                activeCategory = $(this).attr("data-filter");

                //Make sure to reset pagination before sending our AJAX request
                pageNumber = 1;

                getPostsByAjax(scrollAfter);

            });
        }

        if (!filterTabs || "*" === activeCategory) {
            if ("masonry" === layout && !carousel) {
                $blogElement.imagesLoaded(function () {
                    $blogElement.isotope({
                        itemSelector: ".dethemekit-blog-post-outer-container",
                        percentPosition: true,
                        filter: activeCategory,
                        animationOptions: {
                            duration: 750,
                            easing: "linear",
                            queue: false
                        }
                    });
                });
            }
        } else {
            //If `All` categories not exist, then we need to get posts through AJAX.
            getPostsByAjax(false);
        }


        if (carousel) {

            var autoPlay = $blogElement.data("play"),
                speed = $blogElement.data("speed"),
                fade = $blogElement.data("fade"),
                center = $blogElement.data("center"),
                spacing = $blogElement.data("slides-spacing"),
                arrows = $blogElement.data("arrows"),
                dots = $blogElement.data("dots"),
                cols = $blogElement.data("col"),
                colsTablet = $blogElement.data("col-tablet"),
                colsMobile = $blogElement.data("col-mobile"),
                slidesToScroll = $blogElement.data("scroll-slides"),
                prevArrow = null,
                nextArrow = null;

            if (!grid)
                cols = colsTablet = colsMobile = 1;

            if (arrows) {
                (prevArrow =
                    '<a type="button" data-role="none" class="carousel-arrow carousel-prev" aria-label="Next" role="button" style=""><i class="fas fa-angle-left" aria-hidden="true"></i></a>'
                ), (nextArrow =
                    '<a type="button" data-role="none" class="carousel-arrow carousel-next" aria-label="Next" role="button" style=""><i class="fas fa-angle-right" aria-hidden="true"></i></a>'
                    );
            } else {
                prevArrow = prevArrow = "";
            }

            $blogElement.slick({
                infinite: true,
                slidesToShow: cols,
                slidesToScroll: slidesToScroll || cols,
                responsive: [{
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: colsTablet,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: colsMobile,
                        slidesToScroll: 1
                    }
                }
                ],
                autoplay: autoPlay,
                rows:0,
                autoplaySpeed: speed,
                nextArrow: nextArrow,
                prevArrow: prevArrow,
                fade: fade,
                centerMode: center,
                centerPadding: spacing + "px",
                draggable: true,
                dots: dots,
                customPaging: function () {
                    return '<i class="fas fa-circle"></i>';
                }
            });
        }

        //Force posts inner boxes to take the same height
        if ("even" === layout) {

            var equalHeight = $blogElement.data("equal");

            if (equalHeight) {

                forceEqualHeight();

            }

        }

        function forceEqualHeight() {

            var heights = new Array();

            $blogElement.find(".dethemekit-blog-content-wrapper").each(function (index, post) {

                var height = $(post).outerHeight();

                heights.push(height);

            });

            var maxHeight = Math.max.apply(null, heights);

            $blogElement.find(".dethemekit-blog-content-wrapper").css("height", maxHeight + "px");

        }

        //Handle Pagination
        if (pagination) {

            $scope.on('click', '.dethemekit-blog-pagination-container .page-numbers', function (e) {

                e.preventDefault();

                //If pagination item is clicked twice
                if ($(this).hasClass("current"))
                    return;

                var currentPage = parseInt($scope.find('.dethemekit-blog-pagination-container .page-numbers.current').html());

                if ($(this).hasClass('next')) {
                    pageNumber = currentPage + 1;
                } else if ($(this).hasClass('prev')) {
                    pageNumber = currentPage - 1;
                } else {
                    pageNumber = $(this).html();
                }

                getPostsByAjax(scrollAfter);

            })
        }

        function getPostsByAjax(shouldScroll) {

            //If filter tabs is not enabled, then always set category to all.
            if ('undefined' === typeof activeCategory) {
                activeCategory = '*';
            }

            $.ajax({
                url: DethemeKitSettings.ajaxurl,
                dataType: 'json',
                type: 'POST',
                data: {
                    action: 'pa_get_posts',
                    page_id: $blogElement.data('page'),
                    widget_id: $scope.data('id'),
                    page_number: pageNumber,
                    category: activeCategory,
                    nonce: DethemeKitSettings.nonce,
                },
                beforeSend: function () {

                    $blogElement.append('<div class="dethemekit-loading-feed"><div class="dethemekit-loader"></div></div>');

                    if (shouldScroll) {
                        $('html, body').animate({
                            scrollTop: (($blogElement.offset().top) - 50)
                        }, 'slow');
                    }


                },
                success: function (res) {

                    if (!res.data)
                        return;

                    $blogElement.find(".dethemekit-loading-feed").remove();

                    var posts = res.data.posts,
                        paging = res.data.paging;

                    //Render the new markup into the widget
                    $blogElement.html(posts);

                    $scope.find(".dethemekit-blog-footer").html(paging);

                    if ("even" === layout) {
                        var equalHeight = $blogElement.data("equal");
                        if (equalHeight) {
                            forceEqualHeight();
                        }
                    } else {

                        $blogElement.imagesLoaded(function () {
                            $blogElement.isotope('reloadItems');
                            $blogElement.isotope({
                                itemSelector: ".dethemekit-blog-post-outer-container",
                                animate: false
                            });
                        });
                    }

                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    };

    /****** DethemeKit Image Scroll Handler ******/
    var DethemeKitImageScrollHandler = function ($scope, $) {
        var scrollElement = $scope.find(".dethemekit-image-scroll-container"),
            scrollOverlay = scrollElement.find(".dethemekit-image-scroll-overlay"),
            scrollVertical = scrollElement.find(".dethemekit-image-scroll-vertical"),
            dataElement = scrollElement.data("settings"),
            imageScroll = scrollElement.find("img"),
            direction = dataElement["direction"],
            reverse = dataElement["reverse"],
            transformOffset = null;

        function startTransform() {
            imageScroll.css("transform", (direction === "vertical" ? "translateY" : "translateX") + "( -" +
                transformOffset + "px)");
        }

        function endTransform() {
            imageScroll.css("transform", (direction === "vertical" ? "translateY" : "translateX") + "(0px)");
        }

        function setTransform() {
            if (direction === "vertical") {
                transformOffset = imageScroll.height() - scrollElement.height();
            } else {
                transformOffset = imageScroll.width() - scrollElement.width();
            }
        }
        if (dataElement["trigger"] === "scroll") {
            scrollElement.addClass("dethemekit-container-scroll");
            if (direction === "vertical") {
                scrollVertical.addClass("dethemekit-image-scroll-ver");
            } else {
                scrollElement.imagesLoaded(function () {
                    scrollOverlay.css({
                        width: imageScroll.width(),
                        height: imageScroll.height()
                    });
                });
            }
        } else {
            if (reverse === "yes") {
                scrollElement.imagesLoaded(function () {
                    scrollElement.addClass("dethemekit-container-scroll-instant");
                    setTransform();
                    startTransform();
                });
            }
            if (direction === "vertical") {
                scrollVertical.removeClass("dethemekit-image-scroll-ver");
            }
            scrollElement.mouseenter(function () {
                scrollElement.removeClass("dethemekit-container-scroll-instant");
                setTransform();
                reverse === "yes" ? endTransform() : startTransform();
            });
            scrollElement.mouseleave(function () {
                reverse === "yes" ? startTransform() : endTransform();
            });
        }
    };


    /****** DethemeKit Contact Form 7 Handler ******/
    var DethemeKitContactFormHandler = function ($scope, $) {

        var $contactForm = $scope.find(".dethemekit-cf7-container");
        var $input = $contactForm.find(
            'input[type="text"], input[type="email"], textarea, input[type="password"], input[type="date"], input[type="number"], input[type="tel"], input[type="file"], input[type="url"]'
        );

        $input.wrap("<span class='wpcf7-span'>");

        $input.on("focus blur", function () {
            $(this).closest(".wpcf7-span").toggleClass("is-focused");
        });
    };

    /****** DethemeKit Team Members Handler ******/
    var DethemeKitTeamMembersHandler = function ($scope, $) {

        var $persons = $scope.find(".multiple-persons");

        if (!$persons.length) return;

        var carousel = $persons.data("carousel");

        if (carousel) {

            var autoPlay = $persons.data("play"),
                speed = $persons.data("speed"),
                rtl = $persons.data("rtl"),
                colsNumber = $persons.data("col"),
                colsTablet = $persons.data("col-tablet"),
                colsMobile = $persons.data("col-mobile"),
                prevArrow =
                    '<a type="button" data-role="none" class="carousel-arrow carousel-prev" aria-label="Next" role="button" style=""><i class="fas fa-angle-left" aria-hidden="true"></i></a>',
                nextArrow =
                    '<a type="button" data-role="none" class="carousel-arrow carousel-next" aria-label="Next" role="button" style=""><i class="fas fa-angle-right" aria-hidden="true"></i></a>';

            $persons.slick({
                infinite: true,
                slidesToShow: colsNumber,
                slidesToScroll: colsNumber,
                responsive: [{
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: colsTablet,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: colsMobile,
                        slidesToScroll: 1
                    }
                }
                ],
                autoplay: autoPlay,
                rows: 0,
                autoplaySpeed: speed,
                rtl: rtl ? true : false,
                nextArrow: nextArrow,
                prevArrow: prevArrow,
                draggable: true,
                pauseOnHover: true
            });
        }

        if ($persons.hasClass("dethemekit-person-style1")) return;

        if ("yes" !== $persons.data("persons-equal")) return;

        var heights = new Array();

        $persons.find(".dethemekit-person-container").each(function (index, person) {
            $(person).imagesLoaded(function () { }).done(function () {
                var imageHeight = $(person).find(".dethemekit-person-image-container")
                    .outerHeight();
                heights.push(imageHeight);
            });
        });

        $persons.imagesLoaded(function () { }).done(function () {
            var maxHeight = Math.max.apply(null, heights);
            $persons.find(".dethemekit-person-image-wrap").css("height", maxHeight + "px");
        });
    };

    /****** DethemeKit Title Handler ******/
    var DethemeKitTitleHandler = function ($scope, $) {

        var $titleContainer = $scope.find(".dethemekit-title-container"),
            $titleElement = $titleContainer.find('.dethemekit-title-text');

        if ($titleContainer.hasClass('style9')) {
            var $style9 = $scope.find(".dethemekit-title-style9");

            $style9.each(function () {
                var elm = $(this);
                var holdTime = elm.attr('data-blur-delay') * 1000;
                elm.attr('data-animation-blur', 'process')
                elm.find('.dethemekit-title-style9-letter').each(function (index, letter) {
                    index += 1;
                    var animateDelay;
                    if ($('body').hasClass('rtl')) {
                        animateDelay = 0.2 / index + 's';
                    } else {
                        animateDelay = index / 20 + 's';
                    }
                    $(letter).css({
                        '-webkit-animation-delay': animateDelay,
                        'animation-delay': animateDelay
                    });
                })
                setInterval(function () {
                    elm.attr('data-animation-blur', 'done')
                    setTimeout(function () {
                        elm.attr('data-animation-blur', 'process')
                    }, 150);
                }, holdTime);
            });
        }


        if ($titleContainer.hasClass('style8')) {

            var holdTime = $titleElement.attr('data-shiny-delay') * 1000,
                duration = $titleElement.attr('data-shiny-dur') * 1000;

            function shinyEffect() {
                $titleElement.get(0).setAttribute('data-animation', 'shiny');
                setTimeout(function () {
                    $titleElement.removeAttr('data-animation')
                }, duration);
            }

            (function repeat() {
                shinyEffect();
                setTimeout(repeat, holdTime);
            })();
        }

    };

    /****** DethemeKit Bullet List Handler ******/
    var DethemeKitIconListHandler = function ($scope, $) {

        var $listItems = $scope.find(".dethemekit-icon-list-box"),
            items = $listItems.find(".dethemekit-icon-list-content");

        items
            .each(function (index, item) {
                if ($listItems.data("list-animation") && " " != $listItems.data("list-animation")) {
                    var waypoint = new Waypoint({
                        element: item,
                        handler: function () {
                            var element = $(this.element),
                                delay = element.data('delay');
                            setTimeout(function () {
                                element.next('.dethemekit-icon-list-divider , .dethemekit-icon-list-divider-inline').css("opacity", "1");
                                element.next('.dethemekit-icon-list-divider-inline , .dethemekit-icon-list-divider').addClass("animated " + $listItems.data("list-animation"));
                                element.css("opacity", "1"),
                                    element.addClass("animated " + $listItems.data("list-animation"));
                            }, delay);
                            this.destroy();
                        },
                        offset: Waypoint.viewportHeight() - 150,
                    });
                }
            });
    };

    /****** DethemeKit Grow Effect Handler ******/
    var DethemeKitButtonHandler = function ($scope, $) {

        var $btnGrow = $scope.find('.dethemekit-button-style6-bg');

        if ($btnGrow.length !== 0 && $scope.hasClass('dethemekit-mouse-detect-yes')) {
            $scope.on('mouseenter mouseleave', '.dethemekit-button-style6', function (e) {

                var parentOffset = $(this).offset(),
                    left = e.pageX - parentOffset.left,
                    top = e.pageY - parentOffset.top;

                $btnGrow.css({
                    top: top,
                    left: left,
                });

            });
        }

    };

    //Elementor JS Hooks
    $(window).on("elementor/frontend/init", function () {

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-video-box.default", DethemeKitVideoBoxWidgetHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-img-gallery.default", DethemeKitGridWidgetHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-fancy-text.default", DethemeKitFancyTextHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-counter.default", DethemeKitCounterHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-title.default", DethemeKitTitleHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-countdown-timer.default", DethemeKitCountDownHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-carousel-widget.default", DethemeKitCarouselHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-banner.default", DethemeKitBannerHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-modal-box.default", DethemeKitModalBoxHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-blog.default", DethemeKitBlogHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-image-scroll.default", DethemeKitImageScrollHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-contact-form.default", DethemeKitContactFormHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-person.default", DethemeKitTeamMembersHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-icon-list.default", DethemeKitIconListHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-button.default", DethemeKitButtonHandler);

        elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-image-button.default", DethemeKitButtonHandler);

        if (elementorFrontend.isEditMode()) {
            elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-progressbar.default", DethemeKitProgressBarWidgetHandler);
        } else {
            elementorFrontend.hooks.addAction("frontend/element_ready/dethemekit-addon-progressbar.default", DethemeKitProgressBarScrollWidgetHandler);
        }
    });
})(jQuery);