(function () {
    function msPromoCode2Generate(options) {
        var self = this;
        self['initialized'] = false;
        self['msopLoaded'] = false;
        self['running'] = false;
        self['fatal'] = false;
        ['assetsUrl', 'actionUrl'].forEach(function (val, i, arr) {
            if (typeof(options[val]) === 'undefined' || options[val] === '') {
                console.error('[msPromoCode2Generate] Bad config.', arr);
                self['fatal'] = true;
            }
        });
        if (self['fatal']) {
            return;
        }

        /**
         *
         * @type {{run: (function(): *), initialize: (function(*=): *)}}
         */
        self.Base = {
            /**
             * Инициализирует класс.
             * @returns {boolean}
             */
            initialize: function (options) {
                if (!self['initialized']) {
                    self['config'] = {
                    };
                    self['classes'] = {
                        loading: 'is-loading',
                        active: 'is-active',
                        copied: 'is-copied',
                        expired: 'is-expired',
                    };
                    self['selectors'] = {
                        wrap: '.js-mspc2-generate',
                        close: '.js-mspc2-generate-close',

                        code: '.js-mspc2-generate-code',
                        clipboard: '.js-mspc2-generate-clipboard',

                        clockWrap: '.js-mspc2-generate-clock',
                        clockHours: '.js-mspc2-generate-clock-hours',
                        clockMinutes: '.js-mspc2-generate-clock-minutes',
                        clockSeconds: '.js-mspc2-generate-clock-seconds',
                    };
                    self['sendDataTemplate'] = {
                        formData: null,
                    };
                    self['sendData'] = $.extend({}, self['sendDataTemplate']);

                    //
                    Object.keys(options).forEach(function (key) {
                        if (['selectors'].indexOf(key) !== -1) {
                            return;
                        }
                        self.config[key] = options[key];
                    });
                    ['selectors'].forEach(function (key) {
                        if (options[key]) {
                            Object.keys(options[key]).forEach(function (i) {
                                self.selectors[i] = options.selectors[i];
                            });
                        }
                    });

                    // Require dependecies
                    if (typeof(ClipboardJS) === 'undefined') {
                        $.getScript(self.config['assetsUrl'] + 'js/vendor/clipboard.min.js', function () {
                        });
                    }
                }
                self['initialized'] = true;

                return self['initialized'];
            },

            /**
             * Запускает основные действия.
             * @returns {boolean}
             */
            run: function () {
                if (self['initialized'] && !self['running']) {
                    //
                    self.Modal.initialize();
                }
                self['running'] = true;

                return self['running'];
            },
        };

        /**
         *
         * @type {{initialize: (function(): boolean)}}
         */
        self.Modal = {
            /**
             * @returns {boolean}
             */
            initialize: function () {
                /**
                 * Copy code to clipboard
                 */
                var clipboardTimeout = null;
                var clipboardInterval = window.setInterval(function () {
                    if (typeof(ClipboardJS) !== 'undefined') {
                        clearInterval(clipboardInterval);

                        var clipboard = new ClipboardJS(self.selectors['clipboard']);
                        clipboard.on('success', function(e) {
                            var $button = $(e.trigger);
                            var $wrap = $button.closest(self.selectors['wrap']);
                            if (!$wrap.length) {
                                return;
                            }

                            $wrap.addClass(self.classes['copied']);
                            clipboardTimeout && window.clearTimeout(clipboardTimeout);
                            clipboardTimeout = window.setTimeout(function () {
                                $wrap.removeClass(self.classes['copied']);
                            }, 3000);
                        });
                        clipboard.on('error', function(e) {
                            console.error(e);
                        });
                    }
                }, 500);
                window.setTimeout(function () {
                    clearInterval(clipboardInterval);
                }, 7000);

                /**
                 * Timer
                 */
                var $wraps = $(document).find(self.selectors['wrap']);
                if ($wraps.length) {
                    $wraps.each(function () {
                        let $wrap = $(this);
                        let propkey = $wrap.data('propkey');
                        let seconds = $wrap.data('seconds');
                        if (!propkey) {
                            return;
                        }
                        if (typeof(localStorage) !== 'undefined') {
                            let timerInterval = setInterval(function () {
                                if ($wrap.is(':hidden')) {
                                    let storagekey = 'mspc2-timer-' + propkey;
                                    let timer = parseInt(localStorage.getItem(storagekey));
                                    timer = isNaN(timer) ? 0 : ++timer;
                                    // console.log('timer', timer);

                                    //
                                    if (timer >= seconds) {
                                        // Prepare query params
                                        var sendData = $.extend({}, self['sendDataTemplate']);
                                        sendData['formData'] = [{
                                            name: 'action',
                                            value: 'generate/coupon',
                                        }, {
                                            name: 'propkey',
                                            value: propkey,
                                        }];
                                        // console.log(sendData);

                                        // Submit
                                        self.sendData = $.extend({}, sendData);
                                        self.Submit.post(
                                            function (response) {
                                            },
                                            function (response) {
                                                // console.log('generate/coupon callbackAfter response', response);

                                                if (response.data['wrap']) {
                                                    var $wrapNew = $(response.data['wrap']);
                                                    $wrap.html($wrapNew.html());
                                                    $wrap.show();

                                                    // self.Modal.show(propkey);

                                                    // Init clock
                                                    if (!!response.data.coupon['stoppedon']) {
                                                        //
                                                        self.Clock.initialize(propkey, response.data.coupon['stoppedon'])
                                                    }
                                                }
                                            }
                                        );
                                    }

                                    //
                                    localStorage.setItem(storagekey, timer);
                                } else {
                                    timerInterval && window.clearInterval(timerInterval);
                                }
                            }, 1000);
                        }
                    });
                }

                /**
                 * Close modal
                 */
                $(document).on('click', self.selectors['close'], function (e) {
                    e.preventDefault();

                    var $button = $(this);
                    var $wrap = $button.closest(self.selectors['wrap']);
                    if (!$wrap.length) {
                        return;
                    }
                    var propkey = $wrap.data('propkey');

                    // Prepare query params
                    var sendData = $.extend({}, self['sendDataTemplate']);
                    sendData['formData'] = [{
                        name: 'action',
                        value: 'generate/close',
                    }, {
                        name: 'propkey',
                        value: propkey,
                    }];
                    // console.log(sendData);

                    // Submit
                    self.sendData = $.extend({}, sendData);
                    self.Submit.post(
                        function (response) {
                        },
                        function (response) {
                            // console.log('coupon/set callbackAfter response', response);
                            $wrap.remove();
                        }
                    );
                });

                return true;
            },
        };

        /**
         * Отсылает запрос на сервер.
         *
         * @type {{post: post, timeoutInstance: *, timeout: number}}
         */
        self.Submit = {
            timeout: 0, // замираем на N секунд перед отсылкой запроса
            timeoutInstance: undefined,
            post: function (beforeCallback, afterCallback, timeout) {
                if (!self.sendData['formData']) {
                    return;
                }
                if (typeof(timeout) === 'undefined') {
                    timeout = self.Submit['timeout'];
                }
                timeout = parseInt(timeout) || 0;

                //
                self.Submit['timeoutInstance'] && window.clearTimeout(self.Submit['timeoutInstance']);
                self.Submit['timeoutInstance'] = window.setTimeout(function () {
                    // Запускаем колбек перед отсылкой запроса
                    if (beforeCallback && $.isFunction(beforeCallback)) {
                        beforeCallback.call(this, self.sendData['formData']);
                    }

                    $.post(self.config['actionUrl'], self.sendData['formData'], function (response) {
                        // Запускаем колбек после отсылки запроса
                        if (afterCallback && $.isFunction(afterCallback)) {
                            afterCallback.call(this, response, self.sendData['formData']);
                        }

                        if (response['success']) {
                            //
                        } else {
                            // self.Message.error(response['message']);
                        }
                    }, 'json')
                        .fail(function () {
                            console.error('[msPromoCode2Generate] Bad request.', self['sendData']);
                        })
                        .done(function () {
                        });
                }, timeout);
            },
        };

        /**
         * Сообщения.
         *
         * @type {{success: success, handle: handle, error: error, info: info}}
         */
        self.Message = {
            handle: function (type, message) {
                ['success', 'error', 'info'].forEach(function (val) {
                    var $message = $(self.selectors['message' + self.Tools.ucFirst(val)]);
                    if ($message.length) {
                        $message.html(type === val ? message : '');
                    }
                });
            },
            success: function (message) {
                self.Message.handle('success', message);
            },
            error: function (message) {
                self.Message.handle('error', message);
            },
            info: function (message) {
                self.Message.handle('info', message);
            },
        };

        /**
         * Обратный отсчёт
         */
        self.Clock = {
            initialize: function (propkey, endTime) {
                let $wrap = $(document).find(self.selectors['wrap'])
                    .filter('[data-propkey="' + propkey + '"]');
                let $clockWrap = $wrap.find(self.selectors['clockWrap']);
                if (!$clockWrap.length) {
                    return;
                }
                let $clockHours = $clockWrap.find(self.selectors['clockHours']);
                let $clockMinutes = $clockWrap.find(self.selectors['clockMinutes']);
                let $clockSeconds = $clockWrap.find(self.selectors['clockSeconds']);
                if (!$clockHours.length || !$clockMinutes.length || !$clockSeconds.length) {
                    return;
                }

                endTime = parseInt(endTime);
                endTime = endTime < 1000000000000 ? endTime * 1000 : endTime;
                let endDate = new Date(endTime);

                //
                function getTimeRemaining(endDate) {
                    let total = Date.parse(endDate) - Date.parse(new Date());
                    return {
                        total: total,
                        days: Math.floor(total / (1000 * 60 * 60 * 24)),
                        hours: Math.floor((total / (1000 * 60 * 60)) % 24),
                        minutes: Math.floor((total / 1000 / 60) % 60),
                        seconds: Math.floor((total / 1000) % 60),
                    };
                }

                //
                const refreshClock = function() {
                    let total = getTimeRemaining(endDate);

                    //
                    $clockHours[0].innerHTML = ('0' + total.hours).slice(-2);
                    $clockMinutes[0].innerHTML = ('0' + total.minutes).slice(-2);
                    $clockSeconds[0].innerHTML = ('0' + total.seconds).slice(-2);

                    //
                    if (total.hours > 0) {
                        $clockHours.show();
                    } else {
                        $clockHours.hide();
                    }

                    //
                    if (total.days > 0) {
                        $clockWrap.hide();
                    } else {
                        $clockWrap.show();
                    }

                    //
                    if (total.total <= 0) {
                        $wrap.addClass(self.classes['expired']);

                        setTimeout(function () {
                            let $closeButton = $wrap.find(self.selectors['close']);
                            $closeButton.length && $closeButton.click();
                        }, 5000);

                        clearInterval(timeinterval);
                    }
                };
                refreshClock();
                var timeinterval = setInterval(refreshClock, 1000);
            },
        };

        /**
         * Инструменты.
         *
         * @type {Object}
         */
        self.Tools = {
            /**
             * @param string
             * @returns {string}
             */
            ucFirst: function (string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
        };

        /**
         * Initialize and run
         */
        self.Base.initialize(options) && self.Base.run();
    }

    window['msPromoCode2Generate'] = msPromoCode2Generate;
})();