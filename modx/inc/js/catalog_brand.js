function sliders(){
    var swiper2 = new Swiper('.main_slider_swiper', {
        pagination: {
            el: '.swiper-pagination',
        },
        loop: true,
        autoplay: {
            delay: 209500,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        slidesPerView: 1,
    })
    var a = $(window).scrollTop();
    if(a){
        $('body').addClass('wow_db');
    }
}

function catalogSlider() {
    var galleryThumbs2 = new Swiper('.gallery-thumbs2', {
        direction: 'vertical',
        spaceBetween: 10,
        slidesPerView: 7,
        freeMode: true,
        loopedSlides: 1, //looped slides should be the same
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
    });
    var galleryTop2 = new Swiper('.gallery-top2', {
        loopedSlides: 1, //looped slides should be the same
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        thumbs: {
            swiper: galleryThumbs2,
        },
    });
}
// Хак для отключения нажатия на баннер
$(document).on('click', '.cat_sl_img', function (e) {
    e.preventDefault();

    return false;
})
$(document).ready(function ($) {
    sliders();
    catalogSlider();
    var t = new WOW().init()
});