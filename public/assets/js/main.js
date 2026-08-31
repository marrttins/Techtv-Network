(function($){

    'use strict';


    function isScrolledIntoViewport(elem) {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();

        var elemTop = $(elem).offset().top;
        var elemBottom = elemTop + $(elem).height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }

    // Site Preload & Fix transitions on load
    $(window).on('load', function() {
        $("body").addClass("site-loaded");
    });


    // Smooth Scroll
    if( typeof SmoothScroll == 'function' && $('body').hasClass('rivax-smooth-scroll') ) {
        SmoothScroll({ keyboardSupport: false });
    }


    // Post Reading Progress Indicator
    if($('.post-reading-progress-indicator').length) {
        $(window).on('scroll', function () {
            let docHeight = $("body").height();
            let winHeight = $(window).height();
            let viewport = docHeight - winHeight;
            let scrollPos = $(window).scrollTop();
            let scrollPercent = parseInt((scrollPos / viewport) * 100);
            $(".post-reading-progress-indicator span").css("width", scrollPercent + "%");

        });
    }


    // Offcanvas
    $('.offcanvas-container').on('click', function (e) {
        e.stopPropagation();
    });
    $('body').on('click', '.offcanvas-opener', function (e) {
        e.stopPropagation();
        $(this).closest('.rivax-offcanvas').find('.offcanvas-wrapper').addClass('open');
    });

    $('body, .offcanvas-close').on('click', function (e) {
        $('.offcanvas-wrapper').removeClass('open');
    });

    // Popup Search
    $('body').on('click', '.popup-search-opener', function (e) {
        e.stopPropagation();
        $(this).closest('.popup-search-wrapper').find('.popup-search').addClass('open');
    });

    $('.popup-search-container').on('click', function (e) {
        e.stopPropagation();
    });

    $('body, .popup-search-close').on('click', function (e) {
        $('.popup-search').removeClass('open');
    });



    // Sticky Header
    $(window).on('scroll', function () {

        if ( $('#site-sticky-header').length == 0 ) { // Check sticky header is enabled
            return;
        }

        var stickyPos = $('#site-header').outerHeight() + 500;
        var scroll = $(window).scrollTop();

        if( scroll > stickyPos ) {
            $('#site-sticky-header').addClass('fixed');

            // Smart Sticky Sidebar & Elementor Column
            var stickyHeight = $('#site-sticky-header').outerHeight();
            $('.sidebar-container.sticky .sidebar-container-inner, .e-con.rivax-sticky-column').css('top', stickyHeight + 10);
        }
        else {
            $('#site-sticky-header').removeClass('fixed');
            $('#site-sticky-header .popup-search').removeClass('open');
            $('#site-sticky-header .offcanvas-wrapper').removeClass('open');

            // Smart Sticky Sidebar & Elementor Column
            $('.sidebar-container.sticky .sidebar-container-inner, .e-con.rivax-sticky-column').css('top', 10);
        }
    });


	// Header Nav prevent # jump
	$('.rivax-header-nav li > a[href="#"]').on('click', function (e) {
        e.preventDefault();
    });


    // Header Vertical Nav
    $('.rivax-header-v-nav li.menu-item-has-children > a').on('click', function (e) {
        e.preventDefault();
        $(this).siblings('.sub-menu').slideToggle();
    });



    // Header Mega Menu
	$('.rivax-header-nav > li[class*="rivax-mega-menu"] > .sub-menu').css('display', 'block');
    $('.rivax-header-nav li.rivax-mega-menu-3-col > .sub-menu, .rivax-header-nav li.rivax-mega-menu-4-col > .sub-menu').each(function() {
        $(this).children('li').wrapAll('<ul class="sub-menu-cols"></ul>');
    });

    $('.rivax-header-nav li[class*="rivax-mega-menu"]').each(function() {
        positionMegaMenu($(this));
    });
    $('body').on('mouseenter', '.rivax-header-nav li[class*="rivax-mega-menu"]', function() {
        positionMegaMenu($(this));
    });

    function positionMegaMenu($item) {
        var con = $item.closest('div[data-elementor-type] > .e-con');

        // Elementor Editor
        if(con.length == 0) {
            con = $item.closest('div[data-elementor-type] .elementor-section-wrap > .e-con');
        }
        if(con.length == 0) {
            return;
        }
        if( con.hasClass('e-con-boxed') ) {
            con = con.children('.e-con-inner');
        }

        const subMenu = $item.find('.sub-menu');
        const windowWidth = $(window).width();
        const leftMenuPos = $item.offset().left;
        const rightMenuPos = leftMenuPos + $item.innerWidth();

        if($item.closest('.rivax-header-nav-wrapper').hasClass('mega-menu-wide')) {

            subMenu.css('width', windowWidth + 'px');
            if( $('body').hasClass('rtl') ) {
                subMenu.css('right', (rightMenuPos - windowWidth) + 'px');
            }
            else {
                subMenu.css('left', (0 - leftMenuPos) + 'px');
            }
        }
        else {

            const conWidth = con.width();
            const conPadding = ( con.outerWidth() - con.width() ) / 2;
            const leftConPos = con.offset().left + conPadding;
            const rightConPos = con.offset().left + con.outerWidth() - conPadding;

            subMenu.css('width', conWidth + 'px');

            if( $('body').hasClass('rtl') ) {
                subMenu.css('right', (rightMenuPos - rightConPos) + 'px');
            }
            else {
                subMenu.css('left', (leftConPos - leftMenuPos) + 'px');
            }
        }
    }



    // Back to Top Button
    $(window).on('scroll', function (e) {

        var showPos = $('#site-header').outerHeight() + 100;
        var scroll = $(window).scrollTop();

        if( scroll > showPos ) {
            $('#back-to-top').addClass('show');
        }
        else {
            $('#back-to-top').removeClass('show');
        }

        // Reading progress
        let docHeight = $("body").height();
        let winHeight = $(window).height();
        let viewport = docHeight - winHeight;
        let scrollPos = $(window).scrollTop();
        if (viewport <= 0) {
            return;
        }
        let scrollPercent = (scrollPos / viewport) * 100;
        scrollPercent = Math.min(Math.max(scrollPercent, 0), 100);

        $(".post-reading-progress-indicator span").css("width", scrollPercent + "%");
        $("#back-to-top .progress-path").css("stroke-dashoffset", 100 - scrollPercent);
    });

    $('#back-to-top').on('click', function(e) {
        $('html,body').animate({scrollTop:0},500);
    });


    // Dark Mode Switcher
    $('body').on('click', '.dark-mode-switcher', function(e) {

        if( $('html').attr('scheme') === 'dark' ) {

            $('body').addClass("noTransition");
            $('html').attr('scheme', 'light');
            setTimeout(function() { $('body').removeClass("noTransition"); }, 200);

            if (navigator.cookieEnabled) {
                let date = new Date()
                date.setTime(date.getTime() + (30*24*60*60*1000));
                document.cookie = 'newsfyDarkMode=disabled; path=/; expires=' + date.toUTCString() + ';';
            }
        }
        else {
            $('body').addClass("noTransition");
            $('html').attr('scheme', 'dark');
            setTimeout(function() { $('body').removeClass("noTransition"); }, 200);

            if (navigator.cookieEnabled) {
                let date = new Date()
                date.setTime(date.getTime() + (30*24*60*60*1000));
                document.cookie = 'newsfyDarkMode=enabled; path=/; expires=' + date.toUTCString() + ';';
            }
        }
    });



    // Privacy Notice
    if( localStorage.getItem("newsfyPrivacy") != "1" ) {
        $('.privacy-notice-wrap').addClass('show');
    }
    else {
        $('.privacy-notice-wrap').addClass('d-none');
    }

    $('#privacy-btn').on('click', function(e) {
        e.preventDefault();
        localStorage.setItem("newsfyPrivacy", "1");
        $('.privacy-notice-wrap').removeClass('show');
    });



	// Mouse Cursor
    if($(".rivax-mouse-cursor").length) {

        $(document).on('mousemove', function(e) {
            var mouseX = e.clientX;
            var mouseY = e.clientY;

            $(".rivax-mouse-cursor").addClass('visible' );
            $(".rivax-mouse-cursor").css('transform', "translate(" + mouseX + "px, " + mouseY + "px)" );
        });

        $(document).on('mouseleave', function() {
            $(".rivax-mouse-cursor").removeClass('visible' );
        });

        var hoverSelectors = 'a, .popup-search-opener, .offcanvas-opener, .offcanvas-close, .popup-search-close, .toc-header-collapse, .dark-mode-switcher,' +
            ' .comments-compact-btn, .footer-canvas-menu-btn, button, input[type="button"], input[type="reset"], input[type="submit"]';

        $('body').on('mouseenter', hoverSelectors, function() {
            $(".rivax-mouse-cursor").addClass('hovered' );
        }).on('mouseleave', hoverSelectors, function() {
            $(".rivax-mouse-cursor").removeClass('hovered' );
        });
    }



    // Comments compact
    $('.comments-compact-btn').on('click', function(){
        $('.comments-compact-btn').remove();
        $('.comment-list-wrapper').removeClass('compact');
    });


    // Single Post Share Link Box
    $(".single-share-box-link .share-link-btn").on('click', function(){
        $(".single-share-box-link .share-link-text").select();
        document.execCommand('copy');
        $('.single-share-box-link .copied-popup-text').addClass('show');
        setTimeout(function () {
            $('.single-share-box-link .copied-popup-text').removeClass('show');
        }, 2000);
    });


    // Table Of Content
    $('body').on('click', '.toc-header-collapse', function(){
        $(this).closest('.rivax-toc-wrap').find('.rivax-toc-items').slideToggle();
    });

    $('body').on('click', '.rivax-toc-anchor', function(e){

        var href = $(this).attr('href');
        if( href.substr(0,1) === '#' ) {
            e.preventDefault();
            var tocId = $(this).data('num');
            var section = $(this).closest('.single-post-wrapper').find('.rivax-toc-section[data-num=' + tocId + ']');
            var scrollPos = section.offset().top - 30;
            if($('#site-sticky-header').length) {
                scrollPos -= $('#site-sticky-header').outerHeight();
            }

            $('html,body').animate({scrollTop:scrollPos },500);
        }
    });


    // Autoload next post in single post
    $(window).on('scroll', function () {

        if($( "#autoload-next-post-loader:not(.loading)" ).length) {

            if( isScrolledIntoViewport("#autoload-next-post-loader") ) {

                var data = {
                    action: 'rivax_autoload_next_post',
                    postsLoaded: $( "#autoload-next-post-loader" ).data('loaded'),
                    nextID: $( "#autoload-next-post-loader" ).data('next'),
                };
                $.post({
                    url: rivax_ajax_object.AjaxUrl,
                    data: data,
                    dataType: 'json',
                    beforeSend: function() {
                        $("#autoload-next-post-loader").addClass('loading');
                        $("#autoload-next-post-loader .rivax-post-load-more-loader").addClass('show');
                    },
                    success: function(response) {
                        if( response.success === false ) {
                            $("#autoload-next-post-loader").remove();
                        }
                        else {

                            if( response.data.content ) {
                                $(".main-wrapper").append(response.data.content);
                            }

                            if( response.data.nextID == 0 ) {
                                $("#autoload-next-post-loader").remove();
                            }
                            else {
                                $( "#autoload-next-post-loader" ).data('loaded', response.data.loaded).data('next', response.data.nextID);
                            }
                        }

                    },
                    error: function() {
                        $("#autoload-next-post-loader").remove();
                    },
                    complete: function() {
                        $("#autoload-next-post-loader").removeClass('loading');
                        $("#autoload-next-post-loader .rivax-post-load-more-loader").removeClass('show');
                    }
                });

            }
        }


        // Update some details
        if($( ".single-post-wrapper" ).length >= 2) {

            $( ".single-post-wrapper" ).each(function( index ) {
                var scroll = $(window).scrollTop();
                var article = $(this);
                var articleTopPos = article.offset().top;
                var articleBottomPos = articleTopPos + article.outerHeight(true);
                var articleInfo = article.find('.autoload-next-post-info');

                if ( scroll > (articleTopPos - 120) && scroll < (articleBottomPos + 20) && (location.href !== articleInfo.data('url')) ) {
                    document.title = articleInfo.data('title');
                    history.replaceState(null, null, articleInfo.data('url'));
                }

            });
        }

    });



    // Infinite Scroll Load More
    $(window).on('scroll', function () {

        $( ".rivax-post-load-more.infinite-scroll" ).each(function( index ) {

            if( isScrolledIntoViewport($(this)) ) {
                $(this).trigger("click");
            }
        });
    });


    // Load more posts ajax
    $('.rivax-post-load-more').on('click', function (e) {
        e.preventDefault();
        if($(this).hasClass('hide')) return;

        var loadMoreBtn = $(this);
        var loadMoreLoader = loadMoreBtn.next('.rivax-post-load-more-loader');
        var scope = loadMoreBtn.closest('.rivax-posts-container').find('.rivax-posts-wrapper');

        var widgetId = $(this).data('widget-id');
        var postId = $(this).data('post-id');
        var pageNumber = $(this).data('current-page') + 1;


        var data = {
            action: 'rivax_get_load_more_posts',
            widgetId: widgetId,
            postId: postId,
            pageNumber: pageNumber,
            qVars: (typeof rivaxLoadMoreQVars !== 'undefined')? rivaxLoadMoreQVars : '',
        };
        $.post({
            url: rivax_ajax_object.AjaxUrl,
            data: data,
            dataType: 'json',
            beforeSend: function() {
                loadMoreBtn.addClass('hide');
                loadMoreLoader.addClass('show');
            },
            success: function(response) {
                if(response.data) {
                    var items = $(response.data);

                    scope.append(items);
                    if( scope.hasClass('layout-masonry') ) {
                        scope.masonry( 'appended', items );
                    }
                }


                if(response.no_more) {
                    loadMoreBtn.closest('.rivax-posts-pagination-wrap').remove();
                }
                else {
                    loadMoreBtn.data('current-page', pageNumber);
                }
            },
            error: function() {

            },
            complete: function() {
                loadMoreBtn.removeClass('hide');
                loadMoreLoader.removeClass('show');
            }
        });

    });



    // Contact Form
    $('.rivax-contact-form').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('.submit-btn');
        var msgWrapper = form.parent('.rivax-contact-form-wrapper').find('.msg-wrapper');

        if(submitBtn.hasClass('loading')) {
            return;
        }

        var data = {
            action: 'rivax_submit_contact_form',
            firstName: form.find('#first-name').val(),
            lastName: form.find('#last-name').val(),
            email: form.find('#email').val(),
            subject: form.find('#subject').val(),
            message: form.find('#message').val(),
        };

        $.post({
            url: rivax_ajax_object.AjaxUrl,
            data: data,
            dataType: 'json',
            beforeSend: function() {
                submitBtn.addClass('loading');
            },
            success: function(response) {
                if(response.msg == 'fill') {
                    msgWrapper.removeClass('success').addClass('error');
                    msgWrapper.children('.msg').text( msgWrapper.children('.msg').data('fill') );
                }
                else if(response.msg == 'error') {
                    msgWrapper.removeClass('success').addClass('error');
                    msgWrapper.children('.msg').text( msgWrapper.children('.msg').data('error') );
                }
                else if(response.msg == 'success') {
                    msgWrapper.removeClass('error').addClass('success');
                    msgWrapper.children('.msg').text( msgWrapper.children('.msg').data('success') );
                    form.trigger("reset");
                }

            },
            error: function() {
                msgWrapper.removeClass('success').addClass('error');
                msgWrapper.children('.msg').text( msgWrapper.children('.msg').data('error') );
            },
            complete: function() {
                submitBtn.removeClass('loading');
            }
        });

    });


	// Social Icon Widget
	$('.rivax-social-icons a[href=""], .rivax-social-icons a[href="#"]').on('click', function(e) {
		e.preventDefault();
	});



    // Elementor Widgets
    var rivaxWidgetsHandler = function( $scope, $ ) {

        // Post Masonry Layout
        var masonryPosts = $scope.find('.rivax-posts-wrapper.layout-masonry');
        if ( masonryPosts.length ){
            masonryPosts.masonry({
                itemSelector: '.post-item',
                percentPosition: true,
            });
        }


        // Post Carousel Layout
        var carouselPosts = $scope.find('.rivax-posts-wrapper.layout-carousel .rivax-posts-carousel-wrapper');
        if ( carouselPosts.length ){
            var carouselSettings = carouselPosts.data('settings');
            var carouselContainer = carouselPosts.find('.swiper');
            var carouselSelector = '#' + carouselPosts.attr('id') + ' .swiper';


            if ( 'undefined' === typeof Swiper ) {
                const asyncSwiper = elementorFrontend.utils.swiper;

                new asyncSwiper( carouselContainer, carouselSettings ).then( ( newSwiperInstance ) => {
                    var swiper = newSwiperInstance;
                } );
            } else {
                var swiper = new Swiper(carouselSelector, carouselSettings);
            }

        }



        // Single Post Gallery Hero
        if ( $('.single-hero-gallery-container').length ){

            if( $('.single-hero-inside .single-hero-gallery-container').length ) {
                var gallerySettings = {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
					parallax: true,
					effect: 'fade',
					fadeEffect: {
						crossFade: true
					},
					speed: 1000,
                    loop: true,
                    autoplay: true,
                    spaceBetween: 0,
                    a11y: {
                        enabled: false,
                    },
                    navigation: {
                        nextEl: ".single-hero-gallery-container .swiper-button-next",
                        prevEl: ".single-hero-gallery-container .swiper-button-prev",
                    }
                };
            }
            else {
                var gallerySettings = {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                    //loop: true,
                    autoplay: true,
                    spaceBetween: 20,
                    a11y: {
                        enabled: false,
                    },
                    navigation: {
                        nextEl: ".single-hero-gallery-container .swiper-button-next",
                        prevEl: ".single-hero-gallery-container .swiper-button-prev",
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        },
                        1025: {
                            slidesPerView: 3,
                        }
                    }
                };
            }

            var carouselContainer = $('.single-hero-gallery-container .swiper');
            var carouselSelector = '.single-hero-gallery-container .swiper';

            if ( 'undefined' === typeof Swiper ) {
                const asyncSwiper = elementorFrontend.utils.swiper;

                new asyncSwiper( carouselContainer, gallerySettings ).then( ( newSwiperInstance ) => {
                    var swiper = newSwiperInstance;
                } );
            } else {
                var swiper = new Swiper(carouselSelector, gallerySettings);
            }

        }


        // News Ticker
        if( $scope.find('.rivax-news-ticker-content-wrapper:not(.animation-marquee)').length ) {

            var newsTicker = $scope.find('.rivax-news-ticker-content-wrapper');
            var tickerSettings = newsTicker.data('settings');

            var carouselContainer = newsTicker.find('.swiper');
            var carouselSelector = '#' + newsTicker.attr('id') + ' .swiper';

            if ( 'undefined' === typeof Swiper ) {
                const asyncSwiper = elementorFrontend.utils.swiper;

                new asyncSwiper( carouselContainer, tickerSettings ).then( ( newSwiperInstance ) => {
                    var swiper = newSwiperInstance;
                } );
            } else {
                var swiper = new Swiper(carouselSelector, tickerSettings);
            }
        }


        // Horizontal Tags
        if ( $scope.find('.rivax-horizontal-tags').length){

            var elmID = $scope.data('id');
            var tagsSettings = {
                freeMode: {
                    enabled: true,
                    sticky: true,
                },
                a11y: {
                    enabled: false,
                },
                slidesPerView: 'auto',
                spaceBetween: 10,
                navigation: {
                    nextEl: '.elementor-element-' + elmID + ' .carousel-nav-next',
                    prevEl: '.elementor-element-' + elmID + ' .carousel-nav-prev',
                },
            };

            var carouselContainer = $scope.find('.swiper');
            var carouselSelector = '.elementor-element-' + elmID + ' .swiper';

            if ( 'undefined' === typeof Swiper ) {
                const asyncSwiper = elementorFrontend.utils.swiper;

                new asyncSwiper( carouselContainer, tagsSettings ).then( ( newSwiperInstance ) => {
                    var swiper = newSwiperInstance;
                } );
            } else {
                var swiper = new Swiper(carouselSelector, tagsSettings);
            }
        }



        // Mailchimp
        if( $scope.find('.rivax-mailchimp-wrapper').length ) {

            var elForm = $scope.find('.rivax-mailchimp-form'),
                elMessage = $scope.find('.rivax-mailchimp-response-message');

            elForm.on('submit', function (e) {
                e.preventDefault();
                var data = {
                    action: 'rivax_mailchimp_subscribe',
                    subscriber_info: elForm.serialize(),
                    list_id: elForm.data('list-id'),
                };
                $.ajax({
                    type: 'post',
                    url: rivax_ajax_object.AjaxUrl,
                    data: data,
                    success: function success(response) {
                        elForm.trigger('reset');

                        if (response.status) {
                            elMessage.removeClass('error');
                            elMessage.addClass('success');
                            elMessage.text(response.msg);
                        } else {
                            elMessage.addClass('error');
                            elMessage.removeClass('success');
                            elMessage.text(response.msg);
                        }

                        var hideMsg = setTimeout(function () {
                            elMessage.removeClass('error');
                            elMessage.removeClass('success');
                            clearTimeout(hideMsg);
                        }, 5000);
                    },
                    error: function error() {

                    }
                });
            });

        }




        // Post Stellar
        if( $scope.find('.rivax-stellar-wrapper').length ) {

            var wrapper = $scope.find('.rivax-stellar-wrapper');

            wrapper.find('.post-item').on('mouseenter click', function() {
                if( $(this).hasClass('active') )
                    return;

                var id = $(this).data('id');
                wrapper.find('.post-item').removeClass('active');
                $(this).addClass('active');

                wrapper.find('.image-item').removeClass('active');
                wrapper.find('.image-item[data-id="' + id + '"]').addClass('active');

            });
        }



        // Cross Slider
        if ( $scope.find('.cross-slider').length ) {
            var slider = $scope.find('.rivax-posts-carousel-wrapper');
            var sliderSettings = slider.data('settings');
            var sliderContainer = slider.find('.swiper');
            var sliderSelector = '#' + slider.attr('id') + ' .swiper';


            if ( 'undefined' === typeof Swiper ) {
                const asyncSwiper = elementorFrontend.utils.swiper;

                new asyncSwiper( sliderContainer, sliderSettings ).then( ( newSwiperInstance ) => {
                    var swiper = newSwiperInstance;
                } );
            } else {
                var swiper = new Swiper(sliderSelector, sliderSettings);
            }

        }


        // Flank Slider
        if ( $scope.find('.flank-slider').length ) {
            var slider = $scope.find('.rivax-posts-carousel-wrapper');
            var sliderSettings = slider.data('settings');
            var sliderContainer = slider.find('.swiper');
            var sliderSelector = '#' + slider.attr('id') + ' .swiper';


            if ( 'undefined' === typeof Swiper ) {
                const asyncSwiper = elementorFrontend.utils.swiper;

                new asyncSwiper( sliderContainer, sliderSettings ).then( ( newSwiperInstance ) => {
                    var swiper = newSwiperInstance;
                } );
            } else {
                var swiper = new Swiper(sliderSelector, sliderSettings);
            }

        }



        // Notch Slider
        if ( $scope.find('.notch-slider').length ) {
            var slider = $scope.find('.rivax-posts-carousel-wrapper');
            var sliderSettings = slider.data('settings');
            var sliderContainer = slider.find('.main-slider');
            var sliderSelector = '#' + slider.attr('id') + ' .main-slider';

            var thumbs = $scope.find('.thumbs-slider-wrapper');
            var thumbsSettings = thumbs.data('settings');
            var thumbsContainer = thumbs.find('.thumbs-slider');
            var thumbsSelector = '#' + thumbs.attr('id') + ' .thumbs-slider';


            if ( 'undefined' === typeof Swiper ) {

                const asyncSwiper = elementorFrontend.utils.swiper;

                new asyncSwiper( thumbsContainer, thumbsSettings ).then( ( newSwiperInstance ) => {
                    var thumbsSwiper = newSwiperInstance;
                    new asyncSwiper( sliderContainer, sliderSettings ).then( ( newSwiperInstance ) => {
                        var mainSwiper = newSwiperInstance;

                        mainSwiper.controller.control = thumbsSwiper;
                        thumbsSwiper.controller.control = mainSwiper;
                    } );
                } );
            }
            else {

                var thumbsSwiper = new Swiper(thumbsSelector, thumbsSettings);
                var mainSwiper = new Swiper(sliderSelector, sliderSettings);

                mainSwiper.controller.control = thumbsSwiper;
                thumbsSwiper.controller.control = mainSwiper;
            }
        }



        // Spot Slider
        if ( $scope.find('.spot-slider').length ) {
            var slider = $scope.find('.rivax-posts-carousel-wrapper');
            var sliderSettings = slider.data('settings');
            var sliderContainer = slider.find('.main-slider');
            var sliderSelector = '#' + slider.attr('id') + ' .main-slider';

            var thumbs = $scope.find('.thumbs-slider-wrapper');
            var thumbsSettings = thumbs.data('settings');
            var thumbsContainer = thumbs.find('.thumbs-slider');
            var thumbsSelector = '#' + thumbs.attr('id') + ' .thumbs-slider';


            if ( 'undefined' === typeof Swiper ) {

                const asyncSwiper = elementorFrontend.utils.swiper;

                new asyncSwiper( thumbsContainer, thumbsSettings ).then( ( newSwiperInstance ) => {
                    var thumbsSwiper = newSwiperInstance;
                    new asyncSwiper( sliderContainer, sliderSettings ).then( ( newSwiperInstance ) => {
                        var mainSwiper = newSwiperInstance;

                        mainSwiper.controller.control = thumbsSwiper;
                        thumbsSwiper.controller.control = mainSwiper;
                    } );
                } );
            }
            else {

                var thumbsSwiper = new Swiper(thumbsSelector, thumbsSettings);
                var mainSwiper = new Swiper(sliderSelector, sliderSettings);

                mainSwiper.controller.control = thumbsSwiper;
                thumbsSwiper.controller.control = mainSwiper;
            }
        }



        // Pansy Slider
        if ( $scope.find('.pansy-slider').length ) {
            var slider = $scope.find('.rivax-posts-carousel-wrapper');
            var sliderSettings = slider.data('settings');
            var sliderContainer = slider.find('.main-slider');
            var sliderSelector = '#' + slider.attr('id') + ' .main-slider';

            var thumbs = $scope.find('.thumbs-slider-wrapper');
            var thumbsSettings = thumbs.data('settings');
            var thumbsContainer = thumbs.find('.thumbs-slider');
            var thumbsSelector = '#' + thumbs.attr('id') + ' .thumbs-slider';


            if ( 'undefined' === typeof Swiper ) {

                const asyncSwiper = elementorFrontend.utils.swiper;

                new asyncSwiper( thumbsContainer, thumbsSettings ).then( ( newSwiperInstance ) => {
                    var thumbsSwiper = newSwiperInstance;
                    new asyncSwiper( sliderContainer, sliderSettings ).then( ( newSwiperInstance ) => {
                        var mainSwiper = newSwiperInstance;

                        mainSwiper.controller.control = thumbsSwiper;
                        thumbsSwiper.controller.control = mainSwiper;
                    } );
                } );
            }
            else {

                var thumbsSwiper = new Swiper(thumbsSelector, thumbsSettings);
                var mainSwiper = new Swiper(sliderSelector, sliderSettings);

                mainSwiper.controller.control = thumbsSwiper;
                thumbsSwiper.controller.control = mainSwiper;
            }
        }

    }



    var rivaxContainerHandler = function( $scope, $ ) {

        if ($scope.hasClass('rivax-particles')) {
            let pId = 'rivax-particles-' + $scope.data('id');
            let pData = $scope.data('rivax-particles');
            if (typeof pData === 'string' || pData instanceof String) {
                pData = JSON.parse(pData);
            }

            $scope.prepend(`<div class="rivax-particles-wrapper" id="${pId}"></div>`);
            if(typeof particlesJS == 'function') {
				particlesJS(pId, pData);
			}
        }
        else if($scope.children('.rivax-particles-wrapper').length != 0) { // Editor
            let particleWrapper = $scope.children('.rivax-particles-wrapper');
            let pId = particleWrapper.attr('id');
            let pData = particleWrapper.data('rivax-particles');
            if (typeof pData === 'string' || pData instanceof String) {
                pData = JSON.parse(pData);
            }

            if(typeof particlesJS == 'function') {
				particlesJS(pId, pData);
			}
        }

    }



    // Elementor Widgets
    $( window ).on( 'elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', rivaxWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/container', rivaxContainerHandler );
    });


})( jQuery );
