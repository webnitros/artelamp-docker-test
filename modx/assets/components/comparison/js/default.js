Comparison = {
	add: {
		options: {
			add: '.comparison-add',
			remove: '.comparison-remove',
			go: '.comparison-go',
			total: '.comparison-total',
			added: 'added',
			can_compare: 'can_compare',
			loading: 'loading'
		},
		initialize: function(selector, params) {
			if (!$(selector).length) {return;}

			var options = this.options;
			var minItems = !params.minItems
				? 2
				: params.minItems;
			$(document).on('click', selector + ' ' + options.add + ',' + selector + ' ' + options.remove, function() {
				var $this = $(this);
				var $parent = $this.parents(selector);
				var text = $this.data('text');
				var list = $parent.data('list');
				var id = $parent.data('id');
				var action = $this.hasClass(options.add.substr(1))
					? 'add'
					: 'remove';

				if ($this.hasClass(options.loading)) {return false;}
				else {$this.addClass(options.loading);}
				if (text.length) {
					$this.attr('data-text', Comparison.utils.encode($this.html())).html(text);
				}
				$.post(document.location.href, {cmp_action: action, list: list, resource: id}, function(response) {
					if (text.length) {
						text = Comparison.utils.decode($this.attr('data-text'));
						$this.attr('data-text', Comparison.utils.encode($this.html())).html(text);
					}
					$this.removeClass(options.loading);
					if (response.success) {

						if (action === 'add') {
							showModalProduct (id,'comparison')
						}

						$(options.total, selector).text(response.data.total).show();

						if (response.data.link) {
							$(options.go, selector).attr('href', response.data.link);
						}
						if (response.data.total >= minItems) {
							$(selector).addClass(options.can_compare);
						}
						else {$(selector).removeClass(options.can_compare);}

						if (action == 'add') {$parent.addClass(options.added);}
						else {$parent.removeClass(options.added);}
					}
					else {
						if (typeof miniShop2 != 'undefined') {miniShop2.Message.error(response.message);}
						else {alert(response.message);}
					}
				}, 'json');
				return false;
			});
		}
	},

	list: {
		options: {
			all: '.comparison-params-all',
			unique: '.comparison-params-unique',
			same_class: 'same',
			active_class: 'active'
		},
		initialize: function(selector, params) {
			if (!$(selector).length) {return;}

			var options = {};
			for (var option in Comparison.add.options) { options[option] = Comparison.add.options[option]; }
			for (var option in this.options) { options[option] = this.options[option]; }
			var minItems = !params.minItems ? 1 : params.minItems;

			// Switch parameters
			$(document).on('click', selector + ' ' + options.all + ',' + selector + ' ' + options.unique, function() {
				var $this = $(this);
				var $parent = $this.parents(selector);

				if ($this.hasClass(options.active_class)) {
					return false;
				}
				else if ($this.hasClass(options.all.substr(1))) {
					$(options.unique, $parent).removeClass(options.active_class);
					$this.addClass(options.active_class);
					$('.'+options.same_class, $parent).show();
				}
				else if ($this.hasClass(options.unique.substr(1))) {
					$(options.all, $parent).removeClass(options.active_class);
					$this.addClass(options.active_class);
					$('.'+options.same_class, $parent).hide();
				}
				return false;
			});

			// Remove from list
			$(document).on('click', selector + ' ' + options.remove, function(e) {
				var $this = $(this);
				var $parent = $this.parents(selector);
				var text = $this.data('text');
				var list = $this.parent().data('list');
				var id = $this.parent().data('id');
				var index = $(options.remove, selector).index(this) + 1;

				if (text.length) {
					$this.attr('data-text', Comparison.utils.encode($this.html())).html(text);
				}
				$.post(document.location.href, {cmp_action: 'remove', list: list, resource: id}, function(response) {
					if (text.length) {
						text = Comparison.utils.decode($this.attr('data-text'));
						$this.attr('data-text', Comparison.utils.encode($this.html())).html(text);
					}
					$this.removeClass(options.loading);
					if (response.success) {
						if (response.data.total < minItems) {
							document.location = document.location.pathname;
						}
						$(options.total).text(response.data.total);

						$parent.find('tr').each(function() {
							$(this).find('th:eq('+index+'), td:eq('+index+')').remove();
						});
					}
					else {
						if (typeof miniShop2 != 'undefined') {miniShop2.Message.error(response.message);}
						else {alert(response.message);}
					}
				}, 'json');

				return false;
			});
		}
	},

	utils: {
		encode: function(string) {
			return $('<pre/>').text(string).html();
		},
		decode: function(string) {
			return $("<pre/>").html(string).text();
		}
	}
};
