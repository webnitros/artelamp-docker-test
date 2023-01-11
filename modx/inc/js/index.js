function sliders(){
    var swiper2 = new Swiper('.main_slider_swiper', {
        pagination: {
            el: '.swiper-pagination',
        },
        loop: true,
        autoplay: {
            delay: 10*1000,
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
    var swiper3 = new Swiper('.new_slider_swiper', {
        slidesPerView: 'auto',
        spaceBetween: 0,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
}
$(document).ready(function ($) {
    sliders();
});