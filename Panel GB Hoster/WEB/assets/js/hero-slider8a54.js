(function($) {
    "use strict";

    var time = 10;

    var $progressBar = $('.slider-progress-bar').find('.progress-line'),
        isPause,
        heroThumbSlider = $('.slider-thumbnails .thumbnails'),
        tick,
        currentItem = 0,
        percentTime;

    function startProgressBar(cItem = '') {

        setTimeout(function () {
            if (heroThumbSlider.find('.owl-item').length < 1) {
                $('.slider-progress-bar').remove();
                return;
            }
        }, 300);
        $progressBar.css({
            width: '0%'
        });

        if (cItem !== '') {
            currentItem = cItem;
        }
        percentTime = 0;
        isPause = false;
        if (tick !== undefined) {
            clearInterval(tick);
        }
        tick = setInterval(interval, 10);
    };

    function interval() {
        if (isPause === false){
            percentTime += 1 / time;
            $progressBar.css({
                width: percentTime + '%'
            });

            if (percentTime >= 100){
                currentItem ++;
                if (currentItem > heroThumbSlider.find('.owl-item').length - 1) {
                    currentItem = 0;
                }
                heroThumbSlider.find('.owl-item').eq(currentItem).find('a.slide').click();
                $('.hero-slider').trigger("to.owl.carousel", currentItem)
                startProgressBar();
            }
        }
    }

    heroThumbSlider.on('initialize.owl.carousel', function (e) {
        startProgressBar();

        setTimeout(function () {
            heroThumbSlider.find('.owl-item').find('a.slide-item').on('click', function() {
                currentItem = $(this).index('.slide-item');
                startProgressBar(currentItem);
            });
        }, 100);
    })

    heroThumbSlider.owlCarousel({
        items:4,
        loop: false,
        margin: 14,
        mouseDrag: false,
        responsive: {
            0: {
                items: 1,
                center: false,
                mouseDrag: true,
            },
            991: {
                items: 2,

            },

            1200: {
                items: 4,
                center: false
            }
        }
    });

})(jQuery);
