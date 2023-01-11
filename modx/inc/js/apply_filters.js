var fdkButtonFilterShow = {
    params: {},
    class: 'apply-filters-float-btn',
    hideTimer: true,
    main_filters: null,
    button: null,
    loader: null,
    init: function () {
        var main_filters = $('.listing_filters_units')
        if (main_filters.length) {
            this.main_filters = main_filters[0]
        }

        // Ищим все чекбоксы на странице
        $(document).on('click', '.apply_filters input[type="checkbox"]', function (e) {
            fdkButtonFilterShow.fdkButton(this)
        })

        // Ищим все чекбоксы на странице
        $(document).on('change', '.polzun_input_el input[type="text"]', function (e) {
            fdkButtonFilterShow.fdkButton(this)
        })

        // После клика окно закрывать
        $(document).on('click', '.' + this.class, function (e) {
            fdkButtonFilterShow.click()
        })

        // После загрузки результатов показываем количество и скрываем кнопку по таймауту
        $(document).on('mse2_load', function (e, response) {
            var element = $('.' + fdkButtonFilterShow.class)
            if (element.length) {
                var skware = element.find('.apply-filters-total')
                if (skware.length) {
                    skware.html(response.data.total + ' шт.')
                }
            }

            // Скрываем кнопку через 3000 секунд в случае если загрузка не успела завершиться
            if ($(fdkButtonFilterShow.button).length > 0) {
                fdkButtonFilterShow.hideTimer = setTimeout(fdkButtonFilterShow.hide.bind(), fdkButtonFilterShow.params.hideDelay)
            }
            $('.mse2_total').text(response.data.total)
        })
        this.setLoader()
    },
    click: function (e) {

        var topScroll = $(mSearch2.options.wrapper).position().top
        topScroll = topScroll - 50
        $('html, body').animate({
            scrollTop: topScroll || 0
        }, 'slow')

        var newfilterj = $('.newfilterj')
        if (mSearch2.filterModalOpen) {
            mSearch2.filterModalOpen = false
            mSearch2.submit()
            setTimeout(function () {
                if (newfilterj.length && newfilterj.hasClass('active')) {
                    newfilterj.removeClass('active')
                }
            }, 300)
        } else {
            if (newfilterj.length && newfilterj.hasClass('active')) {
                newfilterj.removeClass('active')
            }
        }

        this.hide()
    },
    setLoader: function () {
        this.loader = document.createElement('div'),
            this.loader.classList.add('sk-wave'),
            this.loader.classList.add('apply-filters-total')
        skrect1 = document.createElement('div'),
            skrect1.classList.add('sk-rect'),
            skrect1.classList.add('sk-rect-1')

        skrect2 = document.createElement('div'),
            skrect2.classList.add('sk-rect'),
            skrect2.classList.add('sk-rect-2')

        skrect3 = document.createElement('div'),
            skrect3.classList.add('sk-rect'),
            skrect3.classList.add('sk-rect-3')
        skrect4 = document.createElement('div'),
            skrect4.classList.add('sk-rect'),
            skrect4.classList.add('sk-rect-4')

        this.loader.append(skrect1),
            this.loader.append(skrect2),
            this.loader.append(skrect3),
            this.loader.append(skrect4)

    },
    fdkButton: function (e) {
        if (this.button) {
            this.hide()
        }
        this.setLoader()

        this.hideTimer = null,
            e.hideDelay = e.hideDelay || 3e3, this.params = e,
            this.button = document.createElement('div'),
            this.button.classList.add(this.class),

            text = document.createElement('div'),
            text.classList.add('apply-filters-text'),
            text.innerHTML = 'Показать',
            this.button.append(text),
            this.button.append(this.loader)
        this.show(e)
    },

    show: function (element) {
        this.params.parent = this.params.closest('.unit')
        var slider = $(element).closest('.the_list')

        var $isSlider = false
        if (slider.find('.mse2_number_inputs').length) {
            $isSlider = true
        }


        if (null !== this.hideTimer) {
            clearTimeout(this.hideTimer)
        } else {
            this.main_filters.appendChild(this.button)
            var parentTop = this.main_filters.getBoundingClientRect().top
            var elementTop = this.params.getBoundingClientRect().top

            var n = elementTop - parentTop - this.button.clientHeight / 2

            if ($isSlider) {
                n = n + 20
            }
            this.button.style.top = n + 'px'
            //this.hideTimer = setTimeout(this.hide.bind(this),this.params.hideDelay)
            //this.hideTimer = setTimeout(this.hide.bind(this),this.params.hideDelay)
        }
    },

    hide: function () {
        fdkButtonFilterShow.button.remove()
            , clearTimeout(fdkButtonFilterShow.hideTimer),
            fdkButtonFilterShow.hideTimer = null
    },
}
fdkButtonFilterShow.init()
