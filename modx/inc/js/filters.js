

$(document).ready(function() {



    // Закрывае окно при нажатии на показать
    $(document).on('click', '.clean_filters .btn_mob_val', function (e) {
        $('.listing_filters_units_headline .the_close').click()
        return true
    })


});


// После загрузки результатов показываем количество и скрываем кнопку по таймауту
$(document).on('mse2_load', function (e, response) {

    if (response.success) {
        var total = response.data.total;
        $('#mse2_total_mobile').text(total)
        var $totalText = declension(total, ['товар', 'товара', 'товаров'])
        $('#mse2_total_text_mobile').text($totalText)
    }
})