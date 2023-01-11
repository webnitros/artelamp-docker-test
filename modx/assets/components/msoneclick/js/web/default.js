var msOneClick = {
    link: null,
    form: null,
    count: 1,
    loading: false,
    isDirty: false,
    isDirtyForm: null,
    hasHash: [],
    options: {
        selector: 'oneClick',
        selectorForm: '.msOnclick_form_id_',
        modal: '',
        body: '',
        footer: '',
        price: '',
        form: '',
        captcha: '.msoc_norobot',
        show_loader: msOneClickConfig.show_loader || true
    },
    init: function (sel) {
        var selector = '.' + sel

        if (!$(selector).length) {return false}
        msOneClick.body = $('body')
        msOneClick.loaderImage = null

        // Показать модельное окно
        $(document).on('click', selector, function (e) {
            e.preventDefault()
            msOneClick.spinner.show()
            msOneClick.link = $(this)
            if (!msOneClick.link.hasClass('disabled')) {
                msOneClick.link.addClass('disabled')

                msOneClick.form = null

                // Set params and get form
                msOneClick.Form.loader()
                msOneClick.Product.setParams(msOneClick.link)

                var options = {}
                var formOption = $(msOneClick.options.selectorForm + msOneClick.Product.product_id)
                if (formOption.length) {
                    options = msOneClick.Form.data(formOption)
                }

                msOneClick.send({
                    msc_action: 'form/get',
                    ctx: msOneClickConfig.ctx,
                    pageId: msOneClickConfig.pageId,
                    product_id: msOneClick.Product.product_id,
                    hash: msOneClick.Product.hash,
                    options: options,
                }, msOneClick.Form.callbacks.get)

                // Сброс блокировки при новом открытии окна
                msOneClick.Form.enabled()
            }
            return true
        })

        // Управлени количеством
        $(document).on('click', '.count-field-control', function (e) {
            var prnt = $(this).parents('.count-field')
            var impt = prnt.find('.count-field-input')
            var i = parseInt(impt.val()) || 0

            $(this).hasClass('count-field-control-up') ? i - 1 > 0 && impt.val(i - 1) : impt.val(i + 1)
            $(this).hasClass('count-field-control-down') ? i + 1 > 0 && impt.val(i + 1) : impt.val(i - 1)

            i = parseInt(impt.val()) || 0
            if (i === 0) {
                i = 1
                impt.val(i)
            }

            $(document).trigger('msoneclick_count', i)
        })

        this.setOptions(sel)


        $(document).ready(function () {
            var url = window.location.href
            var ancor = window.location.hash.replace('#', '')
            if (ancor == 'run_modal') {
                var linkAutoClick = document.createElement('a')
                linkAutoClick.setAttribute('href', '#')
                linkAutoClick.setAttribute('class', 'oneClick')
                linkAutoClick.setAttribute('data-run-modal', true)
                var search = window.location.search.substr(1),
                  keys = {}
                search.split('&').forEach(function (item) {
                    item = item.split('=')
                    keys[item[0]] = item[1]
                    linkAutoClick.setAttribute('data-' + item[0], item[1])
                })

                msOneClick.runModal(linkAutoClick)
            }
        })

        msOneClick.Lib.initialize()
    },
    Form: {
        init: function (formid) {
            var selector = '#' + formid
            msOneClick.form = $(selector)

            if (!msOneClick.form.length) return false

            // Отправляет данные формы автоматически в заказ
            $(document).on('change click', selector + ' input', function (event) {
                // Блокировка кнопки
                msOneClick.Order.add($(this))
            })

            // Отправка заказа
            /*   mouseleave$(document).on('mouseleave', selector + ' input', function (e) {

                  return false
              })*/

            // Отправляет данные формы автоматически в заказ
            $(document).on('change click', selector + ' select[name=phone_prefix]', function (event) {
                msOneClick.Order.add($(this))
            })

            // Обновить изображение капчи
            $(document).on('click', selector + ' ' + msOneClick.options.captcha, function (event) {
                msOneClick.captcha()
            })

            //$(msOneClick.options.selectorForm + ' .msoc_norobot')

            // Отправка заказа
            $(document).on('submit', selector, function (e) {
                e.preventDefault()
                var $this = $(this)
                if (!msOneClick.isDirty) {
                    msOneClick.Order.send($this)
                } else {
                    msOneClick.isDirtyForm = $this
                }
                return false
            })

            this.afterInit()
        },
        callbacks: {
            get: function (response, params) {
                $(document).trigger('msoneclick_before_set_model', response)
                if (response.success) {
                    msOneClick.Form.check(response.object)

                    var modelBody = response.object.model
                    // Раскодирование формы
                    if (msOneClickConfig.base64_encode) {
                        function b64_to_utf8 (str) {
                            return decodeURIComponent(escape(window.atob(str)))
                        }

                        modelBody = b64_to_utf8(modelBody)
                    }
                    msOneClick.Form.setModel(modelBody)
                    msOneClick.hasHash.push(msOneClick.Product.hash)
                    msOneClick.Form.init(response.object.formid)
                    msOneClick.Form.setCount(params.product_id)

                    msOneClick.captcha()

                    msOneClick.Modal.show()

                    // unlocking a locked button
                    msOneClick.link.removeClass('disabled')
                    msOneClick.link = null

                } else {
                    msOneClick.Message.error(response.message)
                }
                $(document).trigger('msoneclick_after_set_model', response)
                return false
            }
        },
        setCount: function (product_id) {
            if (msOneClickConfig.copy_count === true) {
                var elementCount = $('.msoneclick_count_' + product_id)
                var elementModalCount = msOneClick.form.find('input[name=count]')
                if (elementCount.length && elementModalCount.length) {
                    var valueCount = parseInt(elementCount.val())
                    if (isNaN(valueCount)) {
                        valueCount = 1
                    }
                    msOneClick.count = valueCount
                    elementModalCount.val(valueCount)
                }
            }
        },
        enabled: function () {
            if (msOneClick.form) {
                msOneClick.form.find('input, button, textarea, a').attr('disabled', false)
            }
        },
        disabled: function () {
            if (msOneClick.form) {
                msOneClick.form.find('input, button, textarea, a').attr('disabled', true)
            }
        },
        data: function ($elem) {
            var formData = null
            if ($elem) {
                if ($elem.length) {
                    formData = $elem.serializeArray()
                }
            }
            return formData
        },
        check: function (data) {
            var $form = msOneClick.form
            if ($form) {
                if (data !== undefined) {
                    if (data.field !== undefined) {
                        msOneClick.Error.reset($form.find('#msoc_' + data.field))
                    } else {

                        $form.find('input').each(function (index) {
                            $(this).removeClass('invalid')
                            $(this).parent().find('.errorNotic').remove()
                        })
                    }
                    if (data.errors) {
                        var errors = data.errors
                        var name, message
                        for (var i in errors) {
                            name = errors[i]['name']
                            message = errors[i]['message']
                            msOneClick.Error.set(name, message)
                        }
                    }
                }
            }
        },
        enabledMask: function () {
            if (msOneClickConfig.mask_phone === true) {
                if ($.fn.inputmask !== undefined) {
                    msOneClick.form.find('input[name=phone]').inputmask('mask', {'mask': msOneClickConfig.mask_phone_format}) //specifying fn & options
                }
            }
        },
        afterInit: function () {
            this.enabledMask()

            msOneClick.Product.DynamicCart()

            // Automatic focus city
            setTimeout(function () {
                msOneClick.form.find('input#msoc_city').focus()
            }, 500)

            $(document).trigger('msoneclick_after_init', msOneClick.form)
        },
        setModel: function (html) {

            if ($(msOneClick.options.modal).length) {
                $(msOneClick.options.modal).remove()
            }

            $('body').append(html)
        },
        setBody: function (html) {
            $(msOneClick.options.body).html(html)
        },
        loader: function () {
            // show modal
            //msOneClick.Modal.show()
            $(msOneClick.options.footer).show()
        }
    },
    Error: {
        input: null,
        callback: function (response, params) {
            msOneClick.Error.reset(msOneClick.Error.input)
            if (response.success) {
                msOneClick.Form.check(response.data)
            }
            else {
                msOneClick.Form.check(response.data)
            }

            this.input = null
        },
        reset: function (field) {
            if (field.hasClass('invalid')) {
                field.removeClass('invalid')
                field.parent().find('.errorNotic').remove()
            }
        },
        set: function (name, message) {
            var el = msOneClick.form.find('#msoc_' + name)
            if (el.length) {
                el.addClass('invalid')
                el.parent().append('<div id="msoneclick_form_' + name + '-error" class="invalid errorNotic"><span class="errorBlok">' + message + '</span></div>')
            }
        }
    },
    Order: {
        callbacks: {
            send: function (response, params) {
                msOneClick.Form.enabled()
                if (response.success) {

                    var isRedirect = false;
                    var redirectToPage = false;
                    if (response.data) {
                        if (response.data.redirectToPage != undefined) {
                            isRedirect = true;
                            redirectToPage = response.data.redirectToPage;
                        }

                        if (response.data.refresh) {
                            isRedirect = true;
                            redirectToPage = response.data.refresh
                        }
                    }

                    if (isRedirect) {
                        document.location.href = response.data.redirectToPage
                    } else {
                        msOneClick.Message.success(response.message)
                        msOneClick.Form.setBody(response.data.body)
                    }
                }
                else {
                    msOneClick.Form.check(response.data)
                    msOneClick.Message.error(response.message, false)
                }

                $(document).trigger('msoneclick_after_sendorder', response)
            }
        },
        send: function ($this) {
            msOneClick.spinner.show()
            var params = msOneClick.Form.data($this)
            params.push({
                name: 'msc_action',
                value: 'form/sendform'
            })
            params.push({
                name: 'fast_order',
                value: 1
            })
            msOneClick.Form.disabled()
            msOneClick.send(params, this.callbacks.send)
        },
        add: function ($this) {
            msOneClick.isDirty = true
            msOneClick.Error.input = $this
            var value = msOneClick.Error.input.val()
            var field = msOneClick.Error.input.attr('name')

            switch (field) {
                case 'phone':
                    var prefix = msOneClick.form.find('[name="phone_prefix"]')
                    if (prefix.length) {
                        var prefix_value = prefix.find('option:selected').val()
                        value = prefix_value + value
                    }
                    break
                default:
                    break
            }
            msOneClick.send({
                msc_action: 'form/add',
                value: value,
                field: field
            }, msOneClick.Error.callback)
        }
    },
    Product: {
        method: 'MS',
        product_id: null,
        hash: null,
        setParams: function ($btn) {
            if (!$btn) return

            var $data = $btn.data()

            // set params
            this.product_id = $data.product
            this.hash = $data.hash
            this.method = $data.method

        },
        DynamicCart: function () {
            // change input count
            $(document).on('change keypress', '#product_change', function (event) {
                $(this).submit()
            })
        },
        add: function (field) {

        },
        setTotal: function (name, message) {
        }
    },
    send: function (params, callback) {
        if (this.loading) {
            return false
        } else {
            this.loading = true
        }

        this.beforeLoad()
        $.post(msOneClickConfig.actionUrl, params, function (response) {
            msOneClick.loading = false
            msOneClick.afterLoad()

            if (callback && $.isFunction(callback)) {
                callback.call(this, response, params)
            } else {
                if (response.success) {
                    msOneClick.Message.success(response.message)
                } else {
                    msOneClick.Message.error(response.message, false)
                }
                msOneClick.Form.check(response.data)
            }

            response.params = params
            $(document).trigger('msoneclick_load', response)
        }, 'json')
    },
    runModal: function (a) {
        msOneClick.spinner.show()
        msOneClick.link = $(a)
        if (!msOneClick.link.hasClass('disabled')) {
            msOneClick.link.addClass('disabled')

            msOneClick.form = null

            // Set params and get form
            msOneClick.Form.loader()
            msOneClick.Product.setParams(msOneClick.link)

            var options = {}
            var formOption = $(msOneClick.options.selectorForm + msOneClick.Product.product_id)
            if (formOption.length) {
                options = msOneClick.Form.data(formOption)
            }

            msOneClick.send({
                msc_action: 'form/get',
                ctx: msOneClickConfig.ctx,
                pageId: msOneClickConfig.pageId,
                product_id: msOneClick.Product.product_id,
                hash: msOneClick.Product.hash,
                options: options,
            }, msOneClick.Form.callbacks.get)

            // Сброс блокировки при новом открытии окна
            msOneClick.Form.enabled()
        }
        return true
    },
    beforeLoad: function () {},
    afterLoad: function () {
        msOneClick.Form.enabled()
        this.spinner.hide()

        // Запускае функци
        if (msOneClick.isDirtyForm) {
            msOneClick.Order.send(msOneClick.isDirtyForm)
        }
        msOneClick.isDirty = false
        msOneClick.isDirtyForm = null
    },
    setOptions: function (selector) {
        var el

        selector = '#' + selector
        var elements = ['modal', 'body', 'footer', 'form', 'price']
        for (var i in elements) {
            if (elements.hasOwnProperty(i)) {
                el = elements[i]
                this.options[el] = selector + '_' + el
                if (!this.options[el].length) {
                }
            }
        }
    },
    reset: function () {
        msOneClick.Modal.hide()
        msOneClick.Form.enabled()
        msOneClick.loading = false
    },
    captcha: function () {
        var image = msOneClick.form.find(msOneClick.options.captcha)
        if (image.length) {
            var src = msOneClickConfig.captchaPath + '?hash=' + msOneClick.Product.hash + '&timestamp=' + Math.random()
            image.attr('src', src)
            var inputCaptha = msOneClick.form.find('#msoc_norobot')
            if (inputCaptha.length) {
                inputCaptha.val('')
            }
        }
    },
    spinner: {
        show: function () {
            if (msOneClick.options.show_loader) {
                msOneClick.loaderImage = msOneClick.loaderImage || $('<div class="msoneclick-loader"></div>')
                msOneClick.body.append(msOneClick.loaderImage)
                msOneClick.loaderImage.show()
            }
        },
        hide: function () {
            if (msOneClick.loaderImage) {
                msOneClick.loaderImage.remove()
            }
        },
    },
    Message: {
        initialize: function () {
            if (typeof $.fn.jGrowl != 'undefined') {
                $.jGrowl.defaults.closerTemplate = '<div>[ ' + msOneClickConfig.close_all_message + ' ]</div>'
                msOneClick.Message.close = function () {
                    $.jGrowl('close')
                }

                msOneClick.Message.show = function (message, options) {
                    if (!message) return
                    $.jGrowl(message, options)
                }
            }
            else {
                msOneClick.Message.close = function () {}
                msOneClick.Message.show = function (message, options) {
                    if (!message) return
                    $.jGrowl(message, options)
                }
            }
        }
        , success: function (message) {
            msOneClick.Message.show(message, {
                theme: 'msoc-message-success',
                sticky: false
            })
        }
        , error: function (message) {
            msOneClick.Message.show(message, {
                theme: 'msoc-message-error',
                sticky: false
            })
        }
        , info: function (message) {
            msOneClick.Message.show(message, {
                theme: 'msoc-message-info',
                sticky: false
            })
        }
    },
    Modal: {
        initialize: function () {
            switch (msOneClickConfig.framework) {
                case 'default':
                case 'bootstrap':
                case 'semantic':
                    break
                case 'materialize':
                    msOneClick.Modal.show = function () {
                        $(msOneClick.options.modal).openModal()
                    }
                    msOneClick.Modal.hide = function () {
                        $(msOneClick.options.modal).closeModal()
                    }
                    break
                case 'uIkit':
                    msOneClick.Modal.show = function () {
                        UIkit.modal($(msOneClick.options.modal)).show()
                    }
                    msOneClick.Modal.hide = function () {
                        UIkit.modal($(msOneClick.options.modal)).hide()
                    }
                    break
                default:
                    break
            }

            // Close modal window
            $(document).on('click', msOneClick.options.modal + ' .modal-closed', function (e) {
                e.preventDefault()
                msOneClick.Modal.hide()
                return false
            })

        },
        show: function () {
            $(msOneClick.options.modal).modal('show')
            // После закрытия автоматически стерам данные
            $(msOneClick.options.modal).on('hidden.bs.modal', function (e) {
                msOneClick.Modal.remove()
            })
        },
        hide: function () {
            $(msOneClick.options.modal).modal('hide')
        },
        remove: function () {
            msOneClick.loading = false
            msOneClick.afterLoad()
            $(msOneClick.options.modal).remove()
        }
    },
    Lib: {
        initialize: function () {
            if (!jQuery().jGrowl) {
                document.write('<script src="' + msOneClickConfig.jsUrl + 'web/lib/jquery.jgrowl.min.js"><\/script>')
            }

            if (msOneClickConfig.framework === 'default') {
                if (!jQuery().modal) {
                    document.write('<script src="' + msOneClickConfig.jsUrl + 'web/lib/jquery.modal.js"><\/script>')
                }
            }

            if (!jQuery().inputmask) {
                document.write('<script src="' + msOneClickConfig.jsUrl + 'web/lib/jquery.maskedinput.min.js"><\/script>')
            }
            msOneClick.Modal.initialize()
        }
    }
}

msOneClick.init(msOneClickConfig.selector)
msOneClick.Message.initialize()