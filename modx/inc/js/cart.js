$(document).ready(function() {

	// Клик из мобильной версии по кнопке добавить в корзину
	$(document).on('click', '.btn_link_add_to_cart', function(e) {
		if($(this).closest('.unit').length) {
			if($(this).closest('.unit').find('.ms2_form').length) {
				if($(this).closest('.unit').find('.ms2_form').find('.btn_buy').length) {
					var $buttomCartID = $(this).closest('.unit').find('.ms2_form').find('.btn_buy')
					$buttomCartID.click()
				}
			}
		}
		return true
	})
})

$(document).on('msoneclick_load', function(e, response) {

	var $oneConfirmation = $('.one_click_confirmation')

	if($oneConfirmation.length > 0) {
		$oneConfirmation.removeClass('error')
		if(!response.success) {
			var field
			for(var i = 0; i < response.data.length; i++) {
				if(!response.data.hasOwnProperty(i)) {
					continue
				}
				var field = response.data[i]
				if(field === 'confirmation') {
					$oneConfirmation.addClass('error')
				}
			}
		}
	}
})

function cartOpenForm() {
	$('.cart_line_links .js_btn_open_form').click(function(e) {
		if($(this).hasClass('disabled')) {
			e.preventDefault()
			return false
		}
		$('.cart_order').toggleClass('active')
		if($('.cart_order').hasClass('active')) {
			$('html').animate({
					scrollTop: ($('.cart_order').offset().top - $('section.header').height()) // прокручиваем страницу к требуемому элементу
				}, 1500 // скорость прокрутки
			)
		}
	})
}

/**
 * Калькулятор подсчета
 */
function cartCalc() {

	// Минусуем
	$(document).on('click', '.cartcalc .ccalc-minus', function(e) {
		e.preventDefault()
		var $outerForm = $(this).closest('.ms2_form')
		if($outerForm.length) {
			var $inputCount = $outerForm.find('input[name="count"]')
			if($inputCount.length) {
				var $old_count = parseInt($inputCount.val())
				if($old_count > 1) {
					var b = +$old_count - 1
					$inputCount.val(b)
				} else {
					$inputCount.val($old_count)
				}
				var $cartcalc_error = $outerForm.find('.cartcalc_error')
				if($cartcalc_error.length) {
					$cartcalc_error.remove()
				}

				if($(this).parents('#msCart').length) {
					// Отправляем в корзину только в на странице корзины
					$outerForm.submit()
				}
			}
		}
		return false
	})

	// Плюсуем
	$(document).on('click', '.cartcalc .ccalc-plus', function(e) {
		e.preventDefault()
		var $outerForm = $(this).closest('.ms2_form')
		if($outerForm.length) {
			var $inputCount = $outerForm.find('input[name="count"]')
			var $inputCountMax = $outerForm.find('input[name="max_count"]')
			if($inputCount.length) {
				var maxCount
				if($inputCountMax.length) {
					maxCount = parseInt($inputCountMax.val())
				}
				var $old_count = parseInt($inputCount.val())
				var b = +$old_count + 1
				if(maxCount >= b) {
					$inputCount.val(b)

					if($(this).parents('#msCart').length) {
						// Отправляем в корзину только в на странице корзины
						$outerForm.submit()
					}
				} else {
					maxCountPopover($outerForm)
				}
			}
		}
		return false
	})

	// Удаление модельного окна
	$(document).on('click', '.btn_close', function(e) {
		e.preventDefault()
		var $cartcalc_error = $(this).closest('.cartcalc_error')
		if($cartcalc_error.length) {
			$cartcalc_error.remove()
		}
		return false
	})
	$(document).on('keydown', '.cartcalc input[name=count]', function(e) {
		if(/[0-9]+/.test(e.key) != true && e.key != 'Backspace' && e.key != 'Delete') {
			e.preventDefault()
			return false
		}
	})
	$(document).on('input', '.cartcalc input[name=count]', function(e) {
		var $outerForm = $(this).parent('.ms2_form')
		if($outerForm.length) {
			var value = parseInt($outerForm.find('input[name="count"]').val())
			var maxValue = parseInt($outerForm.find('input[name="max_count"]').val())
			if(value > maxValue) {
				e.preventDefault()
				$(this).val(maxValue)
				return false
			}
		}
		return true
	})
}

function maxCountPopover($outer) {
	if(!$outer.find('.cartcalc_error').length) {

		var $popover = '<div class="cartcalc_error"><span>Вы уже указали максимальное количество по наличию на складе</span><button class="btn_close"></button></div>'

		if($outer.hasClass('cartcalc')) {
			$outer.append($popover)
		} else {
			$outer.find('.cartcalc').append($popover)
		}

	}

}
var stop = false

window.cardCharactersOpenList = function() {
	if(!stop) {
		stop = true
		$('.card_characters_list_title .btn').click(function(event) {
			$(this).closest('.card_characters_list').toggleClass('active')
		})
	}
}
$(document).ready(function() {
	cartOpenForm()
	cardCharactersOpenList()
	cartCalc()
	$('#msOrder #phone').inputmask('9 (999) 999-99-99')
	$('[data-toggle="tooltip"]').tooltip()
	miniShop2.Callbacks.add('Cart.add.response.success', 'total_count_change', function(res) {
		console.log("Добавлен товар",res)
		window.ym(49284802,'reachGoal','add_basket')
		return true
	})
	// установка текста с общей суммой по каждому товару
	miniShop2.Callbacks.add('Cart.change.response.success', 'total_count_change', function(res) {
		$.each(res.data.cart, function(key, value) {
			var $prPrice = value.price * parseInt(value.count)
			$('#' + key).find('.total_product_one').text(miniShop2.Utils.formatPrice($prPrice))
		})
		// Текст товаров товар
		var $total = declension(res.data.total_count, ['товар', 'товара', 'товаров'])
		$('.ms2_total_count_text').text($total)
		console.log($total)
		return true
	})

	// Установка текста с количеством товаров
	miniShop2.Callbacks.add('Cart.remove.response.success', 'total_count_change', function(res) {
		// Текст товаров товар
		var $total = declension(res.data.total_count, ['товар', 'товара', 'товаров'])
		$('.ms2_total_count_text').text($total)
		return true
	})

	// Установка текста с ошибками в форме
	miniShop2.Callbacks.add('Order.submit.response.error', 'order_submit_response_error', function(res) {
		$('.error_controll').text('')

		$.each(res.data, function(key, field) {
			$('.error_field_' + field).text(res.message)
		})
	})

	// Установка текста с ошибками в форме
	miniShop2.Callbacks.add('Order.submit.before', 'order_submit_before', function(res) {
		var confirmation = $('#msoc_confirmation').prop('checked')
		$('#msoc_confirmation').closest('.cart_order_form_controll').removeClass('error_confirmation')
		if(!confirmation) {
			miniShop2.Message.error('Подтверждаю согласие на обработку своих персональных данных')
			$(':button, a', miniShop2.Order.order).attr('disabled', false).prop('disabled', false)
			$('#msoc_confirmation').closest('.cart_order_form_controll').addClass('error_confirmation')
			return false
		}
		return true
	})

	$("#msMiniCart").on('click',()=>{
		document.location.href = "/basket/"
	})
})