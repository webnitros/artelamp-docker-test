$(document).on('pdopage_load', function (e, config, response) {
    if (screen.width < 768) {
        $('html, body').animate({
            scrollTop: positionMorePdoPage || 0
        }, 0)
    }
})
var positionMorePdoPage = null
$(document).on('click', '.search_products_more_btn', function (e) {
    e.preventDefault()
    if (screen.width < 768) {
        positionMorePdoPage = $(window).scrollTop()
        console.log(positionMorePdoPage)
    }
    return true
})