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
$(document).ready(function ($) {
    sliders();
});