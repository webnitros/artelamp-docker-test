Favorites = {
    add: {
        options: {
            add: '.favorites-add',
            remove: '.favorites-remove',
            go: '.favorites-go',
            total: '.favorites-total',
            added: 'added',
            loading: 'loading'
        },
        initialize: function(selector, params) {
            if (!$(selector).length) {return;}

            var options = this.options,
                list = params.list,
                list_id = params.list_id;

            $(document).on('click', selector + ' ' + options.add + ',' + selector + ' ' + options.remove, function() {
                var $this = $(this);
                var $parent = $this.parents(selector);
                var text = $this.data('text');
                var id = $parent.data('id');
                var action = $this.hasClass(options.add.substr(1))
                    ? 'add'
                    : 'remove';

                if ($this.hasClass(options.loading)) {return false;}
                else {$this.addClass(options.loading);}
                if (text.length) {
                    $this.attr('data-text', $this.text()).text(text);
                }
                //
                var ms2_product = $this.parents('.ms2_product'),
                    prop = {};
                if ((ms2_product.length == 1) && (action == 'add'))  {
                    var formData = ms2_product.find('.ms2_form').serializeArray();
                    $.each(formData, function(key, value) {
                       prop[value.name.replace('[', '.').replace(']', '')] = value.value;
                    });
                }
                //
                $.post(document.location.href, {msf_action: action, resource: id, list: list, list_id: list_id, properties: prop}, function(response) {
                    if (text.length) {
                        text = $this.attr('data-text');
                        $this.attr('data-text', $this.text()).text(text);
                    }
                    $this.removeClass(options.loading);
                    if (response.success) {
                        if (action === 'add') {
                            showModalProduct (id,'favorites')
                        }
                        $(options.total).text(response.data.total).show();

                        if (response.data.link) {
                            $(options.go, selector).attr('href', response.data.link);
                        }

                        if (action == 'add') {$parent.addClass(options.added);}
                        else {$parent.removeClass(options.added);}

                        if((typeof miniShop2 != 'undefined') && (response.info != 'undefined')){
                            if (response.info !== undefined) {
                                miniShop2.Message.error(response.info);
                            }
                        }
                        else {alert(response.info);}

                    }
                    else {
                        if (typeof miniShop2 != 'undefined') {
                            miniShop2.Message.error(response.message);
                        }
                        else {alert(response.message);}
                    }
                }, 'json');
                return false;
            });
        }
    },


   list: {
        options: {
            product: '.ms2_product',
            remove: '.favorites-remove',
            go: '.favorites-go',
            total: '.favorites-total',
            loading: 'loading'
        },
        initialize: function(selector, params) {
            if (!$(selector).length) {return;}
            var options = this.options,
                list = params.list;
            //display hide
            $(options.go).hide();
            $(options.total).hide();

            // Remove from list
            $(document).on('click', selector + ' ' + options.remove, function(e) {
                var $this = $(this);
                var $parent = $this.parents(selector);
                var text = $this.data('text');
                var id = $this.parent().data('id');
                var index = $(options.remove, selector).index(this);

                if (text.length) {
                    $this.attr('data-text', $this.text()).text(text);
                }
                $.post(document.location.href, {msf_action: 'remove', resource: id, list: list}, function(response) {
                    if (text.length) {
                        text = $this.attr('data-text');
                        $this.attr('data-text', $this.text()).text(text);
                    }
                    $this.removeClass(options.loading);
                    if (response.success) {
                        if (response.data.total === 0 ) {
                            document.location.reload();
                        }
                        $parent.each(function() {
                            $(this).find(options.product+':eq('+index+')').remove();
                        });
                    }
                    else {
                        if (typeof miniShop2 != 'undefined') {
                            if (response.message !== '') {
                                miniShop2.Message.error(response.message);
                            }
                        }
                        else {alert(response.message);}
                    }
                }, 'json');

                return false;
            });
        }
    }

};