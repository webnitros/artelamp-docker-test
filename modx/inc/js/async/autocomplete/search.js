var fdkQuerySearch = {
	initializeAutocomplete: false,
	response              : null,
	clean                 : false,
	$area                 : null,
	$inputQuery           : null,
	changeAlias           : 'catalog',

	// Запуск функции
	initialize: function() {
		try {

			if($('#searchQuery').length === 0) {
				return false
			}

			var $isMobile = $(window).width() < 768

			// Для открытия мобильного поиска
			$('.header_search_mobile_opener .btn').click(function() {
				$('.header_search').addClass('js_open')
				ScrollModalEnable()
				if($isMobile) {
					var $heightWindow = $(window).height() - 222
					$('.header_search_list_scroll').css('minHeight', $heightWindow + 'px')
				}
			})
			// Для закрытия мобильного поиска
			$('.header_search_mob_title_close .btn').click(function() {
				$('.header_search').removeClass('js_open')
				ScrollModalDisabled()
			})

			var configAutocomplete = {
				serviceUrl            : '/assets/components/msearch2/action.php',
				onSelect              : function(suggestion) {
					window.location.href = suggestion.data.url
				},
				noCache               : true,
				containerClass        : 'header_search_autocomplete autocomplete-suggestions',
				formatResult          : function(suggestion, currentValue) {
					var pr = suggestion.data
					var sale = pr.sale ? '' : 'style="display: none"'
					var price = pr.price
					var old_price = pr.old_price
					return '<div class="header_search_list_line"><span  class="header_search_list_unit"><span class="header_search_list_imgwr"><img src="' +
						   pr.thumb +
						   '" alt="" class="header_search_list_img"></span><span class="header_search_list_content"><span class="header_search_list_title">' +
						   pr.label +
						   '</span><span class="header_search_list_cost"><span class="cost_now">' +
						   price +
						   'р.</span><del ' +
						   sale +
						   ' class="cost_old">' +
						   old_price +
						   ' р.</del></span></span></a></div>'
				},
				beforeRender          : function(container, suggestions) {

					if(fdkQuerySearch.response.total_all > fdkQuerySearch.response.total) {
						container.append('<a href="/search/?query=' + fdkQuerySearch.response.query + '" class="btn btn_black">ПОКАЗАТЬ ВСЕ РЕЗУЛЬТАТЫ (' + fdkQuerySearch.response.total_all + ')</a>')
					}

				},
				onSearchStart         : function(params) {
					$('.header_search_list_scroll').addClass('load')
					return params
				},
				showContainer         : function() {

					if(!$isMobile) {
						$('.header_search_controll').addClass('active')
					}
					$('.header_search_list').addClass('active')
				},
				minChars              : 3,
				maxHeight             : 560,
				dataType              : 'json',
				params                : {
					service: 'userlocation',
					action : 'search',
				},
				showNoSuggestionNotice: true,
				noSuggestionNotice    : 'К сожалению, по вашему запросу ничего не найдено',
				paramName             : 'query',
				appendTo              : $('.header_search_list_scroll'),
				transformResult       : function(response) {
					$('.header_search_list_scroll').removeClass('load')

					fdkQuerySearch.response = response.data

					return {
						suggestions: $.map(response.data.results, function(dataItem) {
							return {
								value: dataItem.id,
								data : dataItem
							}
						})
					}
				}
			}

			$('#searchQuery').autocomplete(configAutocomplete).data('autocomplete').hide = function() {

				//$('.header_search_controll').removeClass('active')
				//$('.header_search_list').removeClass('active')

				var that      = this,
					container = $(that.suggestionsContainer)

				if($.isFunction(that.options.onHide) && that.visible) {
					that.options.onHide.call(that.element, container)
				}

				that.visible = false
				that.selectedIndex = -1
				clearInterval(that.onChangeInterval)
				// Отменяем исчизание для мобильного устройска

				//$(that.suggestionsContainer).hide();
				that.signalHint(null)

			}
		} catch(e) {
			console.error(e)
		}
	},
}

$(document).ready(function() {
	fdkQuerySearch.initialize()
})