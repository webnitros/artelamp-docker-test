var modelFilter = {
    // Наши селекторы
    options: {
        category: '#filter_category',
        sub_category: '#filter_sub_category',
    },
    // Запуск функции
    initialize: function () {
        $this = this

        // Получаем нужные элементы исохраняем как свойства объекта
        this.category = $(this.options['good_type_web'])
        this.sub_category = $(this.options['sub_category'])

        if (this.category.length) {
            this.category.niceSelect('update')
            // Вешаем обработчик на изменение марки
            this.category.on('change', function (e) {
                modelFilter.redirectCategory($(this))
            })
        }

        if (this.sub_category.length) {
            this.sub_category.niceSelect('update')
            this.sub_category.on('change', function (e) {
                modelFilter.redirectCategory($(this))
            })
        }

    },

    // Функция отключения моделей
    redirectCategory: function ($this) {
        mSearch2.loading = true;
        var values = mSearch2.getFilters()
        delete values.category
        var $args = this.mFilter2HashSet(values)
        var next = $this.next()
        var li = next.find('ul li.selected')
        if (li.length > 0) {
            var pathname = '/' + li.data('value')
            this.changeUrl(pathname,$args)
            document.location.search = $args +'&open_filter=1';
        }
        return true
    },
    changeUrl: function (url,hash) {
        if(!this.oldbrowser()) {
            if(typeof(mSearch2) !== 'undefined') {
                window.history.pushState({mSearch2: url}, '', url);
            } else {
                window.history.pushState({SeoFilter: url}, '', url);
            }
        } else if(!hash) {
            window.location = url;
        } else {
            window.location.hash = hash.substr(1);
        }
    },

    loadMeta: function (params,hash,action) {
        if (action === undefined) {
            return
        }
        if(!action) {action = 'getmeta';}
        var pageUrl = this.config.url || document.location.pathname;
        var data = {data: params, sf_action: action, pageId: this.config.page};
        $('body').addClass('sf_loading');
        $.post(this.config.actionUrl,data,function (response) {
            $('body').removeClass('sf_loading');
            response.params = params;
            response.action = action;
            $(document).trigger('seofilter_load', response);
            if(response.success) {
                var url = response.data.url;
                SeoFilter.prepareResponseData(response.data);
                switch(action) {
                    case 'metabyurl':
                        var filters = params['params'];
                        if(response.data.params) {
                            filters = $.extend(filters, response.data.params);
                        }

                        if (filters === undefined) {
                            filters = {}
                        }

                        // TODO добавление по умолчанию
                        //filters['availability'] = "1";


                        if(typeof(mSearch2) !== 'undefined') {
                            mSearch2.setFilters(filters);
                            mSearch2.load(filters);
                        } else {
                            this.setFilters(filters);
                        }

                        $('#available').prop('checked',true)


                        break;
                    case 'getmeta':
                        SeoFilter.changeUrl(pageUrl + url);
                        break;
                    case 'meta_results':
                        var all_url = pageUrl + url;
                        SeoFilter.changeUrl(all_url);
                        if (typeof(pdoPage) != 'undefined') {
                            var tmp = all_url.split('?');
                            // if(!params['page']) {
                            pdoPage.keys.page = 0;
                            // }
                            var pdo_config = pdoPage.configs.page;
                            if(response.data.full_url) {
                                // pdo_config = $.extend(pdo_config,{q:response.data.full_url});
                            }
                            pdoPage.loadPage(tmp[0], pdo_config);
                        }
                        break;
                }
            } else {
                SeoFilter.changeUrl(document.location.pathname + hash,hash);
            }
        });
    },

    mFilter2HashSet: function (vars) {
        var hash = ''
        var i
        for (i in vars) {
            if (vars.hasOwnProperty(i)) {
                hash += '&' + i + '=' + vars[i].toString()
            }
        }
        if (!this.oldbrowser()) {
            if (hash.length !== 0) {
                hash = '?' + hash.substr(1)
                var specialChars = {'%': '%25', '+': '%2B'} //add last char
                //var specialChars = {'%': '%25', '+': '%2B', '&': '%26'} //add last char
                for (i in specialChars) {
                    if (specialChars.hasOwnProperty(i) && hash.indexOf(i) !== -1) {
                        hash = hash.replace(new RegExp('\\' + i, 'g'), specialChars[i])
                    }
                }
            }
        }
        return hash
    },
    oldbrowser: function () {
        return !(window.history && history.pushState)
    },
    // Функция отключения моделей
    disableModel: function () {
        // Ищем все поля с непустым value
        $this.sub_category.find('option[value!=""]').attr('selected', false).attr('disabled', true)
        // И прячем весь блок
        $this.sub_category.hide()
    },

    // Функция включения моделей
    enableModel: function () {
        // Получаем марку автомобиля
        var marka = this.marka.find(':selected').text().replace(/\(.*?\)$/, '').replace(/\s+$/, '')
        var re = new RegExp('^' + marka)
        // Пробегаем по всем моделям и проверяем имя
        $this.sub_category.find('option').each(function () {
            var $this = $(this)
            // Имя не совпадает - нужно отключить эту модель
            if (!$this.text().match(re) && $this.prop('value') != '') {
                $this.attr('disabled', true)
                $this.hide()
            }
            // В противном случае - включить
            else {
                $this.attr('disabled', false)
                $this.show()
            }
        })
        // И показываем весь блок с моделями
        $this.sub_category.show()
    },
}

// Скрипт запускается после полной загрузки документа
$(document).ready(function () {
    // И если на странице есть фильтры
    if ($('#mse2_mfilter').length > 0) {
        modelFilter.initialize()
    }
})