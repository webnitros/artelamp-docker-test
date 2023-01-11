function mobileMenuLists () {
    $('.open > a').click(function (e) {
        var b = $(window).width()
        if (b < 1201) {
            e.preventDefault()
            $(this).closest('li').find('.plate').slideToggle()
            $(this).toggleClass('active')
        }
    })
    $('.open .plate_menu a.tit_name, .open .plate_menu a.tit_name').click(function (g) {
    //$('.open .plate_menu a.tit_name').click(function (g) {
        var c = $(window).width()
        if (c < 1201) {
            var $Submenu = $(this).closest('.flex-row-item').find('.submenu')
            if (!$Submenu.length) {
                window.location.href = $(this).attr('href')
            } else {

                g.preventDefault()
                $(this).closest('.unit').find('.submenu').slideToggle()
                $(this).closest('.tit').toggleClass('active')
            }
        }

    })
}


// Отключаем скролл у body и html
function ScrollModalEnable () {
    $('html').css('overflow','hidden')
    $('body').css('overflow','hidden')
}
// Включаем скролл у body и html
function ScrollModalDisabled () {
    $('html').css('overflow','')
    $('body').css('overflow','')
}

function mobileMenuBars () {
    $('.but_bars').click(function (e) {
        e.preventDefault()
        $(this).toggleClass('active')
        $('.header_menu').toggleClass('active')
        $('body').toggleClass('no_scrl')

        // Отключаем скролирование
        if ($('.header_menu').hasClass('active')) {
            ScrollModalEnable();
        } else {
            ScrollModalDisabled();
        }

    })
}

$(document).ready(function () {
    mobileMenuBars()
    //Открытие списков в мобильном меню
    mobileMenuLists()
})