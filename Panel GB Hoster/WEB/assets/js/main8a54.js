(function($){
    "use strict";

    var	heroSlider = $('.hero-slider'),
        heroThumbnail = $('.slider-thumbnails a'),
        sliderSponsors = $('.sponsors .slider'),
        shopSlider = $('.shop .slider'),
        backToTop = $('.back-to-top'),
        tournamentTeams = $('.tournament.inner-page').find('.tab-content').find('.owl-carousel'),
        achivementSlider = $('.achievements');

    $('.scrollbar-outer').scrollbar();

    // Back to Top
    backToTop.on('click', function(){
        $('html, body').animate({
            scrollTop:$($('header')).offset().top
        },600);
    });

    // Add Active Class on Hero thumbnail click
    heroThumbnail.on('click', function() {
        heroThumbnail.removeClass('active');
        $(this).addClass('active');
    });

    // Tournament Teams Slider
    tournamentTeams.owlCarousel({
        items:3,
        loop: false,
        margin: 15,
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 2,
            },
            992: {
                items: 3,
            }
        }
    });

    // Hero Slider
    heroSlider.owlCarousel({
        items:1,
        loop: false,
        margin: 0,
        mouseDrag: false,
        URLhashListener: true,
    });

    // Slider for Sponsors
    sliderSponsors.owlCarousel({
        items: 1,
        loop: true,
        margin: 60,
        autoplay: true,
        responsive: {
            480: {
                items: 3,
            },
            768: {
                items: 4,
            },
            992: {
                items: 5,
            },
        }
    });

    // Shop Slider
    shopSlider.owlCarousel({
        items:1,
        loop: false,
        margin: 0,
        mouseDrag: false,
        nav: false,
        navText: ['<i class="fas fa-angle-left"></i>','<i class="fas fa-angle-right"></i>'],
        responsive: {
            760: {
                nav: true
            }
        }
    });

    // Achievements Slider
    achivementSlider.owlCarousel({
        loop: false,
        nav: true,
        navText: ['<i class="fas fa-angle-left"></i>','<i class="fas fa-angle-right"></i>'],
        responsive: {
            0 : {
                items: 1,
                margin: 0,
                autoWidth: false
            },

            460: {
                items: 3,
                autoWidth: false,
                margin: 40
            },

            992 : {
                items: 3,
                margin: 40,
                autoWidth: true,
            }
        }

    });

    // LightBox Gallery
    $('.lightboxgallery-gallery-item').on('click', function(event) {
        event.preventDefault();
        $(this).lightboxgallery({
            showCounter: true,
            showTitle: false,
            showDescription: false
        });
    });


    $('.copy-btn').on('click', function(e){
        e.stopImmediatePropagation();
        e.stopPropagation();
        e.preventDefault();

        var value = $(this).data('url'), $temp = $("<input>");
        $("body").append($temp);
        $temp.val(value).select();
        document.execCommand('copy');
        $temp.remove();
    });

    var $first = jQuery('.days li:first'), $last = jQuery('.days li:last');
    jQuery('.next-day').on('click', function () {
        var $next, $selected = jQuery(".days li.active");
        $next = $selected.next('li').length ? $selected.next('li') : $first;
        $selected.removeClass("active");
        jQuery('#' + $selected.attr('data-selector')).removeClass('active');
        $next.addClass('active');
        jQuery('#' + $next.attr('data-selector')).addClass('active');
    });

    jQuery('.previous-day').on('click', function () {
        var $prev, $selected = jQuery(".days li.active");
        $prev = $selected.prev('li').length ? $selected.prev('li') : $last;
        $selected.removeClass("active");
        jQuery('#' + $selected.attr('data-selector')).removeClass('active');
        $prev.addClass('active');
        jQuery('#' + $prev.attr('data-selector')).addClass('active');
    });

    $('.filterize').on('click', function(e) {
        e.stopImmediatePropagation();
        e.preventDefault();

        var $this = $(this);
        window.location = $this.data('filter-url');
    });

    let dataImageElement = $('[data-image]')
    if(dataImageElement !== undefined && dataImageElement.length > 0) {
      $('[data-image]').each(function (index) {
        $('head').find('style').append("." + $(this).attr('class') + ":after { background-image: url('" + $(this).attr('data-image') + "') }");
      });
    }
})(jQuery);
