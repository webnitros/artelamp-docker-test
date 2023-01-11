var fdkQuerySearch = {
	initializeAutocomplete: false,
	clean                 : false,
	$area                 : null,
	$inputQuery           : null,
	changeAlias           : 'catalog',

	// Запуск функции
	initialize: function() {
		try {

			var configAutocomplete = {
				serviceUrl    : '/assets/components/userlocation/action.php',
				onSelect      : function(suggestion) {
					//window.location.href = suggestion.data.url
				},
				noCache       : true,
				containerClass: 'modal_city_links autocomplete-suggestions',
				formatResult  : function(suggestion, currentValue) {
					var product = suggestion.data
					return '<a href="#" class="userlocation-location-item" data-userlocation-id="' + product.id + '">' + product.name + '</a>'
				},
				/* onSearchStart: function (params) {
				 return params
				 },*/
				minChars              : 3,
				maxHeight             : 560,
				dataType              : 'json',
				params                : {
					service: 'userlocation',
					method : 'GetLocation',
				},
				showNoSuggestionNotice: true,
				noSuggestionNotice    : 'По вашему запросу ничего не найдено',
				paramName             : 'query',
				appendTo              : $('.modal_city_links_auto'),
				transformResult       : function(response) {
					if(!fdkQuerySearch.clean) {
						fdkQuerySearch.clean = true
						$('.modal_city_links_default').remove()
					}

					return {
						suggestions: $.map(response.data.result, function(dataItem) {
							return {
								value: dataItem.id,
								data : dataItem
							}
						})
					}
				}
			}
			$('#cities-query').autocomplete(configAutocomplete).data('autocomplete').hide = function() {

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