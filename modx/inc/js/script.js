function number_format(number, decimals, dec_point, thousands_sep) {  // Format a number with grouped thousands
	var i, j, kw, kd, km

	if(isNaN(decimals = Math.abs(decimals))) {
		decimals = 2
	}
	if(dec_point == undefined) {
		dec_point = ','
	}
	if(thousands_sep == undefined) {
		thousands_sep = '.'
	}

	i = parseInt(number = (+number || 0).toFixed(decimals)) + ''

	if((j = i.length) > 3) {
		j = j % 3
	} else {
		j = 0
	}

	km = (j ? i.substr(0, j) + thousands_sep : '')
	kw = i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousands_sep)
	kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : '')

	return km + kw + kd
}

function menuBars() {
	$('.c-hamburger').click(function(e) {
		e.preventDefault()
		$('.c-hamburger').toggleClass('is-active')
		$('.b2').toggleClass('active')
	})
}

function pointMainMenu() {

	var er2 = $('.main_menu>ul>li.open').length
	if(er2) {
		var c2 = $('.main_menu>ul>li.open').width()
		var d2 = $('.main_menu>ul>li.open').offset().left
		$('.header_menu_point .ic').css('left', d2 + (+c2 / 2))
	}
	$('.main_menu>ul>li').mouseenter(function() {
		$('.header_menu_point .ic').addClass('active')
		var a = $(this).offset().left
		var b = $(this).width()
		$('.header_menu_point .ic').css('left', a + (+b / 2))
	})

	$('.main_menu').mouseleave(function() {
		var er = $('.main_menu>ul>li.open').length
		if(er) {
			var c = $('.main_menu>ul>li.open').width()
			var d = $('.main_menu>ul>li.open').offset().left
			$('.header_menu_point .ic').css('left', d + (+c / 2))
		} else {
			$('.header_menu_point .ic').removeClass('active')
		}
	})
}

/**
 * Фиксация шапки при движении скрола вниз
 */
function fixHeaderCheck() {
	var a = $('.header').height()
	var scrollTop = $(this).scrollTop()
	if(scrollTop > a) {
		$('.header_menu_point .ic').removeAttr('style')
		$('body').addClass('fix')
		var w = $(window).width()
		$('.header').css('width', w)
	} else {
		$('body').removeClass('fix wow_db')
		$('.header').css('width', '100%')
	}
	if(scrollTop > a) {
		$('body').addClass('topright')
	} else {
		$('body').removeClass('topright')
	}

}

function fixHeader() {
	fixHeaderCheck()
	$(window).scroll(function() {
		pointMainMenu()
		fixHeaderCheck()
	})
}

function fixHeaderSearch() {
	$('#searchQuery').on('keyup', function(e) {
		if($(e.target).val()) {
			$('.header_search_list').addClass('active')
		} else {
			$('.header_search_list').removeClass('active')
		}
	})
	$(document).on('click', function(e) {
		const TARGET = $(e.target)
		if(TARGET.hasClass('btn_close_search') || TARGET.parents('.btn_close_search').length !== 0) {
			$('.header_search_controll').removeClass('active')
			$('.header_search_list').removeClass('active')
			return false
		}
		if(TARGET.hasClass('js_btn_open') || TARGET.parents('.js_btn_open').length !== 0) {
			$('.header_search_controll').addClass('active')
			// $('.header_search_controll').width($('.main_menu').innerWidth() + 60)
			return true
		}
		if(TARGET.parents('.header_search_list').length === 0 && !TARGET.hasClass('header_search_list') && TARGET.parents('.header_search').length === 0) {
			$('.header_search_controll').removeClass('active')
			$('.header_search_list').removeClass('active')
			return true
		}
		return true
	})

}

function listingSelects() {
	$('.listing_filters_units .the_select').niceSelect()
}

function listingFiltersListOpen() {
	$('.listing_filters_units .js_btn').click(function() {
		$(this).closest('.unit').find('.the_list').slideToggle(300)
		$(this).toggleClass('active')
	})
}

function polzunFiltersCatalog() {
	var stepsSlider = document.getElementById('slider-value')
	var input0 = document.getElementById('input0')
	var input1 = document.getElementById('input1')
	var inputs = [input0, input1]
	var min = parseInt($('#slider-value').attr('data-min'))
	var max = parseInt($('#slider-value').attr('data-max'))
	var minval = parseInt($('#slider-value').attr('data-minval'))
	var maxval = parseInt($('#slider-value').attr('data-maxval'))

	noUiSlider.create(stepsSlider, {
		start  : [min, max],
		connect: true,
		range  : {
			'min': minval,
			'max': maxval
		}
	})

	stepsSlider.noUiSlider.on('update', function(values, handle) {
		inputs[handle].value = values[handle]
	})

	inputs.forEach(function(input, handle) {

		input.addEventListener('change', function() {
			stepsSlider.noUiSlider.setHandle(handle, this.value)
		})

		input.addEventListener('keydown', function(e) {

			var values = stepsSlider.noUiSlider.get()
			var value = Number(values[handle])

			// [[handle0_down, handle0_up], [handle1_down, handle1_up]]
			var steps = stepsSlider.noUiSlider.steps()

			// [down, up]
			var step = steps[handle]

			var position

			// 13 is enter,
			// 38 is key up,
			// 40 is key down.
			switch( e.which ) {

				case 13:
					stepsSlider.noUiSlider.setHandle(handle, this.value)
					break

				case 38:

					// Get step to go increase slider value (up)
					position = step[1]

					// false = no step is set
					if(position === false) {
						position = 1
					}

					// null = edge of slider
					if(position !== null) {
						stepsSlider.noUiSlider.setHandle(handle, value + position)
					}

					break

				case 40:

					position = step[0]

					if(position === false) {
						position = 1
					}

					if(position !== null) {
						stepsSlider.noUiSlider.setHandle(handle, value - position)
					}

					break
			}
		})
	})
}

function listFilterClean() {

	if($('.listing_filters_units').length) {
		var a = $('.listing_filters_units').offset().left
		var w = $('.listing_filters_units').width()
		var b = +a - 0
		var a2 = $('.listing_filters').offset().top
		var a3 = $('.listing_filters').height()
		$('.clean_filters').css({'left': b - 20, 'width': w + 39})
		$(window).scroll(function() {
			var g2 = $('.listing .dflex').height()
			var g1 = $('.listing .dflex').offset().top
			var g3 = +g2 - g1 - 130
			if($(this).scrollTop() > g3) {
				$('.clean_filters').addClass('active')
			} else {
				$('.clean_filters').removeClass('active')
			}

		})
	}
}

/**
 * Фильтры: сортировка на странице
 */
function filterCategoryLink() {
	var $filterCategory = $('#filter_category')
	if(!$filterCategory.length) {
		return false
	}

	// Переход по ссылке с добавленим параметров
	$(document).on('click', '.filter_category_link', function(e) {
		e.preventDefault()
		var $href = $(this).attr('href')
		var pos = window.location.href.indexOf('?')
		var hashes = ''
		if(pos > 0) {
			hashes = (pos != -1) ? decodeURIComponent(window.location.href.substr(pos + 1)) : ''
			hashes = '?' + hashes
		}
		window.location.href = $href + hashes
		return false
	})

}

/**
 * Фильтры: сортировка на странице
 */
function filterSortSelect() {

	var $filterSortSelect = $('.option_select_sort')
	if($filterSortSelect.length) {
		$filterSortSelect.niceSelect()

		// Добавление иконок
		/* var c = $filterSortSelect.find('option').length
		 for (var i = 1; i <= c; i++) {
		 var b = $('.option_select select option:nth-child(' + i + ')').attr('data-ic')
		 $('.option_select .nice-select .list .option:nth-child(' + i + ')').attr('data-ic', b)
		 }*/

		var c = $filterSortSelect.find('option').length
		var $filterSortSelectNice = $('.nice-select.option_select_sort')
		for(var i = 1; i <= c; i++) {
			var b = $filterSortSelect.find('option:nth-child(' + i + ')').attr('data-ic')
			$filterSortSelectNice.find('.option:nth-child(' + i + ')').attr('data-ic', b)
		}

		var $filterSortValue = ''

		// Установка по умлчанию значения
		var $filterSortDefault = $filterSortSelect.find('option:selected')
		if($filterSortDefault.length) {
			$filterSortValue = $filterSortDefault.val()
		}

		// При выборе из выпадающего списка автоматически нажимаем на ссылку
		$filterSortSelect.change(function() {
			var $filterSortOption = $(this).find('option:selected')
			var $filterSort = $filterSortOption.val()
			var $filterSortLink = $('#mse2_sort').find('a[data-id="' + $filterSort + '"]')

			if($filterSortValue !== $filterSort) {
				$filterSortValue = $filterSort

				if($filterSortLink.length) {
					$filterSortLink.click()
				} else {
					console.error('sort not found: ' + $filterSort)
				}
			}
		})
	}
}

/**
 * Фильтры: лимит товаро на странице
 */
function filterLimitSelect() {
	var $filterLimitSelect = $('.option_select_limit')
	if($filterLimitSelect.length) {
		$filterLimitSelect.niceSelect()
	}
}

function selectCustom2() {

	$('.listing_filters_units_headline select').niceSelect()
	var c = $('.option_select2 select option').length

	for(var i = 1; i <= c; i++) {
		var b = $('.option_select2 select option:nth-child(' + i + ')').attr('data-ic')
		$('.option_select2 .nice-select .list .option:nth-child(' + i + ')').attr('data-ic', b)
	}
	var cur = $('.option_select2 select option:nth-child(' + 1 + ')').attr('data-ic')
	$('.top_filter_menu_select .nice-select .current').attr('data-ic', cur)
	$('.option_select2 .nice-select .list .option').mouseup(function() {
		var ic = $(this).attr('data-ic')
		$(this).closest('.nice-select').find('.current').attr('data-ic', ic)
	})
}

function readMore() {
	$('.listing_content_catalog_units .unit').slice(0, 6).show()
	$('.listing_content_catalog_units .more_units .js_btn').click(function() {
		$('.listing_content_catalog_units .unit:hidden').slice(0, 6).show()
	})
}

function sliders() {
	var swiper = new Swiper('.lastlook_slider1 .swiper-container', {
		slidesPerView: 1,
		spaceBetween : 10,
		speed        : 1000,
		navigation   : {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		breakpoints  : {
			320 : {
				slidesPerView: 1,
				spaceBetween : 10
			},
			450 : {
				slidesPerView: 1.5,
				spaceBetween : 10
			},
			600 : {
				slidesPerView: 2,
				spaceBetween : 20
			},
			1280: {
				slidesPerView: 3,
				spaceBetween : 20
			}
		}
	})
	var swiperll = new Swiper('.lastlook_slider2 .swiper-container', {
		slidesPerView: 1,
		spaceBetween : 10,
		speed        : 1000,
		navigation   : {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		breakpoints  : {
			320 : {
				scrollbar    : {
					el  : '.swiper-scrollbar',
					hide: true,
				},
				slidesPerView: 1.4,
				spaceBetween : 20
			},
			450 : {
				slidesPerView: 2,
				spaceBetween : 20
			},
			600 : {
				slidesPerView: 2,
				spaceBetween : 20
			},
			992 : {
				slidesPerView: 3,
				spaceBetween : 20
			},
			1280: {
				slidesPerView: 4,
				spaceBetween : 20
			},
			1440: {
				slidesPerView: 5,
				spaceBetween : 20
			}
		}
	})

	var swiper2 = new Swiper('.main_slider_swiper', {
		pagination   : {
			el: '.swiper-pagination',
		},
		autoplay     : {
			delay               : 209500,
			disableOnInteraction: false,
		},
		navigation   : {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		slidesPerView: 1,
	})
	var a = $(window).scrollTop()
	if(a) {
		$('body').addClass('wow_db')
	}

	var swiper3 = new Swiper('.new_slider_swiper', {
		slidesPerView: 'auto',
		spaceBetween : 0,
		pagination   : {
			el       : '.swiper-pagination',
			clickable: true,
		},
		navigation   : {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
	})

}

function mainFiltersMobileOpener() {
	$('.listing_content_filter .js_open_filter_mobile').click(function() {
		$('.listing_filters').show()
		ScrollModalEnable()
	})
	$('.listing_filters_units_headline .the_close').click(function() {
		$('.listing_filters').hide()
		ScrollModalDisabled()
	})
	/*
	 if ($('.header_menu').hasClass('active')) {
	 ScrollModalEnable();
	 } else {
	 ScrollModalDisabled();
	 }
	 */
}

/**
 * Показ информации в списки товаров
 */
function mainMegaMenu() {
	$('.main_menu>ul>li').hoverIntent({
										  over    : makeHoverInfoTall,
										  out     : makeHoverInfoShort,
										  timeout : 100,
										  interval: 50
									  })

	function makeHoverInfoTall() {
		var $plate = $(this).closest('li').find('.plate')
		if(!$plate.hasClass('active')) {
			$plate.addClass('active')
		}
	}

	function makeHoverInfoShort() {
		var $plate = $(this).closest('li').find('.plate')
		$plate.removeClass('active')
	}
}

/**
 *
 */
function showModalProduct(id, mode) {

	var $titile
	switch( mode ) {
		case 'favorites':
			$titile = 'ИЗБРАННЫЕ ТОВАРЫ'
			break
		case 'comparison':
			$titile = 'ТОВАР ДОБАВЛЕН В СРАВНЕНИЕ'
			break
		case 'basket':
			$titile = 'ТОВАР ДОБАВЛЕН В КОРЗИНУ'
			break
		default:
			break
	}

	//modal_block_comparison

	var $productForm = $('.product_modal_' + id)
	if($productForm.length) {
		var $productFormV = $productForm.find('.ms2_form')

		var $modalProductShow = $('#liked')

		var $productArticle = $productFormV.find('input[name="article"]').val()
		var $productName = $productFormV.find('input[name="name"]').val()
		var $productPrice = $productFormV.find('input[name="price"]').val()
		var $productOldPrice = $productFormV.find('input[name="old_price"]').val()
		var $productSale = $productFormV.find('input[name="sale"]').val()
		var $productNew = $productFormV.find('input[name="new"]').val()
		var $productThumb = $productFormV.find('input[name="thumb"]').val()
		var $productUrl = $productFormV.find('input[name="url"]').val()

		$('.modal_product_thumb').attr('src', $productThumb)
		$('.modal_product_name').text($productName)
		$('.modal_product_article').text($productArticle)
		$('.modal_product_article').attr('href', $productUrl)
		$('.js_modal_price p').text($productPrice + ' р.')
		$('.js_modal_price del span').text($productOldPrice + ' р.')

		$('.modal_product_marker').hide()
		if($productSale === '1' || $productNew === '1') {
			$('.modal_product_marker').show()
		}

		if($productSale === '1') {
			$('.modal_product_marker_sale').show()
		} else {
			$('.modal_product_marker_sale').hide()
		}
		if($productNew === '1') {
			$('.modal_product_marker_new').show()
		} else {
			$('.modal_product_marker_new').hide()
		}

		if($productSale === '1') {
			$('.modal_product_sale').show()
		} else {
			$('.modal_product_sale').hide()
		}

		$('.modal_block').hide()

		// Показываем область
		$('.modal_block_' + mode).show()

		$modalProductShow.removeClass('modal_is_comparison').removeClass('modal_is_favorites').removeClass('modal_is_basket')

		$modalProductShow.find('.the_text').text($titile)
		//$titile
		$modalProductShow.addClass('modal_is_' + mode)

		$modalProductShow.modal('show')

		//data-target="#liked"
		//data-toggle="modal"
	}

}

function openHeaderCart() {
	$('.el_mob .but4').mouseenter(function() {
		$('.jheader_popup_cart').addClass('open')
		$(this).closest('.el_mob').addClass('wider')
	})

	$('.header_icons').mouseleave(function() {
		$('.jheader_popup_cart').removeClass('open')
		$('.el_mob').removeClass('wider')
	})

	$('.header_icons .but3').mouseenter(function() {
		$('.jheader_popup_cart').removeClass('open')
		$('.el_mob').removeClass('wider')
	})
	$('.el_mob .but4').click(function(e) {
		e.preventDefault()
		$('.jheader_popup_cart').addClass('open')
		$(this).closest('.el_mob').addClass('wider')
	})
	$(document).mouseup(function(ev) {
		var div = $('.jheader_popup_cart')
		if(!div.is(ev.target)
		   && div.has(ev.target).length === 0) {
			$('.jheader_popup_cart').removeClass('open')
			$('.el_mob').removeClass('wider')
		}
	})
}

function maxLength() {
	$(`*[data-maxlength]`).on('input', function(event) {
		var max = parseInt($(this).data('maxlength'))
		$(this).val($(this).val().slice(0, max))
	})
}

$(document).ready(function() {
	$(document).on('keydown', '#mse2_filters input', function(event) {
		if(event.key === 'Enter') {
			event.stopPropagation()
			event.preventDefault()
			$(this).trigger('change')
		}
	})
	maxLength()
	openHeaderCart()
	mainMegaMenu()
	selectCustom2()
	listFilterClean()
	menuBars()
	pointMainMenu()
	fixHeader()
	fixHeaderSearch()
	listingSelects()
	listingFiltersListOpen()
	////////polzunFiltersCatalog();

	//readMore();
	sliders()
	mainFiltersMobileOpener()

	if($('#mse2_mfilter').length) {
		filterSortSelect()
		filterLimitSelect()
		filterCategoryLink()
	}

	$('.the_list_scroll').mCustomScrollbar({
											   axis: 'y'
										   })

	miniShop2.Callbacks.add('Cart.add.response.success', 'show_modal_product', function(res) {
		showModalProduct($formaProductID, 'basket')
		return true
	})

	var $formaProductID
	$(document).on('click', '.btn_buy', function(e) {
		$formaProductID = $(this).closest('.ms2_form').find('input[name="id"]').val()
		return true
	})

	$(document).on('click', '.js_close_popblock', function(e) {
		$('.userlocation-location-confirm').removeClass('unconfirmed')
		$('.userlocation-location-confirm').addClass('confirmed')
		return true
	})

	$(`.the_cost p`).each(function() {
		let price = parseInt($(this).text().replace(/\D/, ''))
		if(price > 0) {
			$(this).text(number_format(price, 0, ',', ' ') + ' р.')
		}
	})
	$('.jheader_popup_scroll').mCustomScrollbar({
													axis: 'y'
												})

	$(document).on('msoneclick_after_sendorder', (response) => {
		ym(49284802, 'reachGoal', 'buy_one_clikuis')
	})

})

// Склонение существительных. Использование: declension(2, ['товар', 'товара', 'товаров'])
function declension(n, titles) {
	return titles[(n % 10 === 1 && n % 100 !== 11) ? 0 : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2]
}

$(window).resize(function() {
	var w = $(window).width()
	$('.header').css('width', w)
})

/*
 if (screen.width>768) {
 mse2Config['mode'] = 'default'; // Для обычной версии
 } else {
 mse2Config['mode'] = 'button';  // Для мобильной версии scroll button
 }*/