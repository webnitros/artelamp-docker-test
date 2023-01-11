// JavaScript autoComplete v1.0.4
// https://github.com/Pixabay/JavaScript-autoComplete
try {
    if(typeof UserLocationAutoComplete != 'function') {
        var UserLocationAutoComplete = function() {
            function e(e) {
                function t(e, t) {
                    return e.classList ? e.classList.contains(t) : new RegExp("\\b" + t + "\\b").test(e.className)
                }

                function o(e, t, o) {
                    e.attachEvent ? e.attachEvent("on" + t, o) : e.addEventListener(t, o)
                }

                function s(e, t, o) {
                    e.detachEvent ? e.detachEvent("on" + t, o) : e.removeEventListener(t, o)
                }

                function n(e, s, n, l) {
                    o(l || document, s, function(o) {
                        for(var s, l = o.target || o.srcElement; l && !(s = t(l, e));) l = l.parentElement;
                        s && n.call(l, o)
                    })
                }

                if(document.querySelector) {
                    var l = {
                        selector   : 0, source: 0, minChars: 3, delay: 150, offsetLeft: 0, offsetTop: 1, cache: 1, menuClass: "", renderItem: function(e, t) {
                            t = t.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&");
                            var o = new RegExp("(" + t.split(" ").join("|") + ")", "gi");
                            return '<div class="userlocation-suggestion" data-val="' + e + '">' + e.replace(o, "<b>$1</b>") + "</div>"
                        }, onSelect: function() {
                        }
                    };
                    for(var c in e) e.hasOwnProperty(c) && (l[c] = e[c]);
                    for(var a = "object" == typeof l.selector ? [l.selector] : document.querySelectorAll(l.selector), u = 0; u < a.length; u++) {
                        var i = a[u];
                        i.sc = document.createElement("div"), i.sc.className = "userlocation-suggestions " + l.menuClass, i.autocompleteAttr = i.getAttribute("autocomplete"), i.setAttribute("autocomplete", "off"), i.cache = {}, i.last_val = "", i.updateSC = function(e, t) {
                            var o = i.getBoundingClientRect();
                            if(i.sc.style.left = Math.round(o.left + (window.pageXOffset || document.documentElement.scrollLeft) + l.offsetLeft) + "px", i.sc.style.top = Math.round(o.bottom + (window.pageYOffset || document.documentElement.scrollTop) + l.offsetTop) + "px", i.sc.style.width = Math.round(o.right - o.left) + "px", !e && (i.sc.style.display = "block", i.sc.maxHeight || (i.sc.maxHeight = parseInt((window.getComputedStyle ? getComputedStyle(i.sc, null) : i.sc.currentStyle).maxHeight)), i.sc.suggestionHeight || (i.sc.suggestionHeight = i.sc.querySelector(".userlocation-suggestion").offsetHeight), i.sc.suggestionHeight)) if(t) {
                                var s = i.sc.scrollTop, n = t.getBoundingClientRect().top - i.sc.getBoundingClientRect().top;
                                n + i.sc.suggestionHeight - i.sc.maxHeight > 0 ? i.sc.scrollTop = n + i.sc.suggestionHeight + s - i.sc.maxHeight : 0 > n && (i.sc.scrollTop = n + s)
                            } else i.sc.scrollTop = 0
                        }, o(window, "resize", i.updateSC), document.body.appendChild(i.sc), n("userlocation-suggestion", "mouseleave", function() {
                            var e = i.sc.querySelector(".userlocation-suggestion.selected");
                            e && setTimeout(function() {
                                e.className = e.className.replace("selected", "")
                            }, 20)
                        }, i.sc), n("userlocation-suggestion", "mouseover", function() {
                            var e = i.sc.querySelector(".userlocation-suggestion.selected");
                            e && (e.className = e.className.replace("selected", "")), this.className += " selected"
                        }, i.sc), n("userlocation-suggestion", "mousedown", function(e) {
                            if(t(this, "userlocation-suggestion")) {
                                var o = this.getAttribute("data-val");
                                i.value = o, l.onSelect(e, o, this), i.sc.style.display = "none"
                            }
                        }, i.sc), i.blurHandler = function() {
                            try {
                                var e = document.querySelector(".userlocation-suggestions:hover")
                            } catch(t) {
                                var e = 0
                            }
                            e ? i !== document.activeElement && setTimeout(function() {
                                i.focus()
                            }, 20) : (i.last_val = i.value, i.sc.style.display = "none", setTimeout(function() {
                                i.sc.style.display = "none"
                            }, 350))
                        }, o(i, "blur", i.blurHandler);
                        var r = function(e) {
                            var t = i.value;
                            if(i.cache[t] = e, e.length && t.length >= l.minChars) {
                                for(var o = "", s = 0; s < e.length; s++) o += l.renderItem(e[s], t);
                                i.sc.innerHTML = o, i.updateSC(0)
                            } else i.sc.style.display = "none"
                        };
                        i.keydownHandler = function(e) {
                            var t = window.event ? e.keyCode : e.which;
                            if((40 == t || 38 == t) && i.sc.innerHTML) {
                                var o, s = i.sc.querySelector(".userlocation-suggestion.selected");
                                return s ? (o = 40 == t ? s.nextSibling : s.previousSibling, o ? (s.className = s.className.replace("selected", ""), o.className += " selected", i.value = o.getAttribute("data-val")) : (s.className = s.className.replace("selected", ""), i.value = i.last_val, o = 0)) : (o = 40 == t ? i.sc.querySelector(".userlocation-suggestion") : i.sc.childNodes[i.sc.childNodes.length - 1], o.className += " selected", i.value = o.getAttribute("data-val")), i.updateSC(0, o), !1
                            }
                            if(27 == t) i.value = i.last_val, i.sc.style.display = "none"; else if(13 == t || 9 == t) {
                                var s = i.sc.querySelector(".userlocation-suggestion.selected");
                                s && "none" != i.sc.style.display && (l.onSelect(e, s.getAttribute("data-val"), s), setTimeout(function() {
                                    i.sc.style.display = "none"
                                }, 20))
                            }
                        }, o(i, "keydown", i.keydownHandler), i.keyupHandler = function(e) {
                            var t = window.event ? e.keyCode : e.which;
                            if(!t || (35 > t || t > 40) && 13 != t && 27 != t) {
                                var o = i.value;
                                if(o.length >= l.minChars) {
                                    if(o != i.last_val) {
                                        if(i.last_val = o, clearTimeout(i.timer), l.cache) {
                                            if(o in i.cache) return void r(i.cache[o]);
                                            for(var s = 1; s < o.length - l.minChars; s++) {
                                                var n = o.slice(0, o.length - s);
                                                if(n in i.cache && !i.cache[n].length) return void r([])
                                            }
                                        }
                                        i.timer = setTimeout(function() {
                                            l.source(o, r)
                                        }, l.delay)
                                    }
                                } else i.last_val = o, i.sc.style.display = "none"
                            }
                        }, o(i, "keyup", i.keyupHandler), i.focusHandler = function(e) {
                            i.last_val = "\n", i.keyupHandler(e)
                        }, l.minChars || o(i, "focus", i.focusHandler)
                    }
                    this.destroy = function() {
                        for(var e = 0; e < a.length; e++) {
                            var t = a[e];
                            s(window, "resize", t.updateSC), s(t, "blur", t.blurHandler), s(t, "focus", t.focusHandler), s(t, "keydown", t.keydownHandler), s(t, "keyup", t.keyupHandler), t.autocompleteAttr ? t.setAttribute("autocomplete", t.autocompleteAttr) : t.removeAttribute("autocomplete"), document.body.removeChild(t.sc), t = null
                        }
                    }
                }
            }

            return e
        }();
        !function() {
            "function" == typeof define && define.amd ? define("UserLocationAutoComplete", function() {
                return UserLocationAutoComplete
            }) : "undefined" != typeof module && module.exports ? module.exports = UserLocationAutoComplete : window.UserLocationAutoComplete = UserLocationAutoComplete
        }();
    }

    (function($) {
        "use strict";

        var UserLocationConfig = {
            service  : 'userlocation',
            version  : document.head.querySelector('meta[name="userlocation:version"]').content,
            ctx      : document.head.querySelector('meta[name="userlocation:ctx"]').content,
            actionUrl: document.head.querySelector('meta[name="userlocation:actionUrl"]').content
        };

        var indexOf = [].indexOf || function(item) {
            for(var i = 0, l = this.length; i < l; i++) {
                if(i in this && this[i] === item) return i;
            }
            return -1;
        };
        var hasProp = {}.hasOwnProperty;
        var slice = [].slice;

        var bind = function(fn, me) {
            return function() {
                return fn.apply(me, arguments);
            };
        };

        var camelize = function(str) {
            return str.replace(/(-|\.)(\w)/g, function(match, symbol) {
                return symbol.toUpperCase();
            });
        };
        var uncamelize = function(str) {
            return str.replace(/[A-Z]/g, function(symbol, index) {
                return (index == 0 ? '' : '-') + symbol.toLowerCase();
            });
        };

        var setOptions = function($node, ns, options) {
            var prefix, userlocation;

            if(typeof ns == 'undefined') {
                ns = $.fn.userlocation.defaults.ns;
            }

            prefix = camelize(ns);
            userlocation = $node.data('UserLocation');

            $.each(options, function(index, value) {
                $node.data(prefix + '-' + index, value);
            });

            if(userlocation) {
                switch( prefix ) {
                    case 'data':
                        userlocation.data = $.extend(true, userlocation.data, options);
                        break;
                    case 'options':
                        userlocation.options = $.extend(true, userlocation.options, options);
                        break;
                }
            }

            return options;
        };

        var getOptions = function($node, ns) {
            var prefix, options;

            if(typeof ns == 'undefined') {
                ns = $.fn.userlocation.defaults.ns;
            }

            prefix = camelize(ns);
            options = $node.data(prefix) || {};

            $.each($node.data(), function(index, value) {
                if(index.indexOf(prefix) === 0) {
                    var key = uncamelize(index.replace(prefix, ''));
                    if(key.length > 0) {
                        options[key] = value;
                    }
                }
            });

            return options;
        };

        var newGuid = function() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        };

        var inArray = function(needle, haystack) {
            for(var key in haystack) {
                if(haystack[key] == needle) return true;
            }

            return false;
        };

        var runAction = function(action, bind) {
            if(typeof action == 'function') {
                return action.apply(bind, Array.prototype.slice.call(arguments, 2));
            } else if(typeof action == 'object') {
                for(var i in action) {
                    if(action.hasOwnProperty(i)) {
                        var response = action[i].apply(bind, Array.prototype.slice.call(arguments, 2));
                        if(response === false) {
                            return false;
                        }
                    }
                }
            }
            return true;
        };

        $.fn.userlocation = function() {

        };
        /* event constants */
        $.fn.userlocation.PROCESS_SUCCESS = 'userlocation:process-success';
        $.fn.userlocation.PROCESS_FAILURE = 'userlocation:process-failure';
        $.fn.userlocation.PROCESS_ACTION = 'userlocation:process-action';

        $.fn.userlocation.defaults = {
            ns     : 'userlocation',
            cls    : {
                hidden     : 'hidden',
                unconfirmed: 'unconfirmed',
            },
            timeout: 300,
        };

        $.fn.userlocation.selectors = {
            main                : '.' + $.fn.userlocation.defaults.ns,
            localSearch         : '.' + $.fn.userlocation.defaults.ns + '-location-search-input[data-userlocation-mode="local"]',
            remoteSearch        : '.' + $.fn.userlocation.defaults.ns + '-location-search-input[data-userlocation-mode="remote"]',
            locationItem        : '.' + $.fn.userlocation.defaults.ns + '-location-item[data-userlocation-id]',
            locationItemsGroup  : '.' + $.fn.userlocation.defaults.ns + '-location-items-group',
            locationConfirm     : '.' + $.fn.userlocation.defaults.ns + '-location-confirm',
            locationConfirmClose: '.' + $.fn.userlocation.defaults.ns + '-location-confirm-close',
        };

        $.fn.userlocation.methodActions = {
            success: {
                'reload_page': function(r, data) {
                    var result = r.data.result;

                    if(result.confirmed && !result.redirect) {
                        location.reload();
                    } else if(result.confirmed && result.redirect) {
                        location.href = result.redirect;
                    }
                }
            },
            failure: {},
        };

        var UserLocation = {

            init: function() {

                // lccal search
                $(document)
                    .off('submit change keyup', $.fn.userlocation.selectors.localSearch)
                    .on('submit change keyup', $.fn.userlocation.selectors.localSearch, function(e) {
                        var query = UserLocation.tools.clearName($(this).val().toString().toLocaleUpperCase());

                        $(this).closest($.fn.userlocation.selectors.main).find($.fn.userlocation.selectors.locationItemsGroup + ' ' + $.fn.userlocation.selectors.locationItem).each(function(e) {
                            if(query !== '') {
                                if(UserLocation.tools.clearName($(this).text().toLocaleUpperCase()).indexOf(query) === 0) {
                                    $(this).show().removeClass($.fn.userlocation.defaults.cls.hidden);
                                } else {
                                    $(this).hide().addClass($.fn.userlocation.defaults.cls.hidden);
                                }
                            } else {
                                $(this).show().removeClass($.fn.userlocation.defaults.cls.hidden);
                            }
                        });
                        $(this).closest($.fn.userlocation.selectors.main).find($.fn.userlocation.selectors.locationItemsGroup).each(function(e) {
                            if($(this).find($.fn.userlocation.selectors.locationItem).filter(':not(.' + $.fn.userlocation.defaults.cls.hidden + ')').length) {
                                $(this).show().removeClass($.fn.userlocation.defaults.cls.hidden);
                            } else {
                                $(this).hide().addClass($.fn.userlocation.defaults.cls.hidden);
                            }
                        });

                        e.preventDefault();
                        return true;
                    });

                // remote search
                $(document)
                    .find($.fn.userlocation.selectors.remoteSearch).each(function(e) {
                    if(typeof UserLocationAutoComplete == 'function') {
                        var $element = $(this);
                        var $template = getOptions($element, 'userlocationTemplate');
                        if(typeof $template !== 'string' || !$template) {
                            $template = '<div class=\'userlocation-suggestion\' data-userlocation-row=\'@row@\' data-userlocation-id=\'@id@\'>@name@ </div>';
                        }
                        var valueField = getOptions($element, 'userlocationValueField');
                        if(typeof valueField !== 'string' || !valueField) {
                            valueField = 'name';
                        }
                        var cacheQuery = {};

                        new UserLocationAutoComplete({
                                                         selector: this,
                                                         minChars: 1,
                                                         cache   : false,
                                                         source  : function(query, response) {
                                                             query = UserLocation.tools.clearName(query.toLocaleUpperCase());

                                                             if(cacheQuery && cacheQuery[query]) {
                                                                 response(cacheQuery[query]);
                                                             } else if(query) {
                                                                 var data = $.extend(true, {service: UserLocationConfig.service, ctx: UserLocationConfig.ctx},
                                                                                     getOptions($element, 'data'),
                                                                                     {method: 'getLocation', query: query}
                                                                 );

                                                                 e = $.Event($.fn.userlocation.PROCESS_ACTION);
                                                                 $element.trigger(e, [data]);
                                                                 if(e.isDefaultPrevented()) {
                                                                     return;
                                                                 }

                                                                 $.ajax({
                                                                            url     : UserLocationConfig.actionUrl,
                                                                            type    : 'post',
                                                                            dataType: 'json',
                                                                            cache   : false,
                                                                            data    : data,
                                                                        }).done((function(_this) {
                                                                     return function(r) {
                                                                         var result = r.data.result || {};
                                                                         cacheQuery[query] = result;
                                                                         response(result);
                                                                     };
                                                                 })(this)).fail((function(_this) {
                                                                     response({});
                                                                 })(this));
                                                             }
                                                         },

                                                         renderItem: function(row, search) {
                                                             row = row || {};
                                                             var $output = $template.replace(new RegExp("@row@", "g"), JSON.stringify(row));
                                                             for(var k in row) {
                                                                 $output = $output.replace(new RegExp("@" + k + "@", "g"), row[k]);
                                                             }
                                                             return $output;
                                                         },
                                                         onSelect  : function(e, term, item) {
                                                             var row = $(item).data('userlocation-row') || {};
                                                             var value = row[valueField] || row['id'];

                                                             setTimeout(function() {
                                                                 $element.val(value);
                                                             }, 100);

                                                             $(item).trigger('click');
                                                         }
                                                     });

                        // показать подсказки если есть
                        $element.on('focus', function(e) {
                            if(this.sc) {
                                this.sc.style.display = 'block';
                            }
                        });

                    }
                });

                // location select
                $(document)
                    .off('click touchend', $.fn.userlocation.selectors.locationItem)
                    .on('click touchend', $.fn.userlocation.selectors.locationItem, function(e) {
                        var $element = $(this);
                        if($element.is('a')) {
                            e.preventDefault();
                        }

                        var id = $element.data('userlocationId');
                        var data = $.extend(true, {service: UserLocationConfig.service, ctx: UserLocationConfig.ctx},
                                            getOptions($element, 'data'),
                                            {method: 'setLocation', id: id}
                        );
                        $.ajax({
                                   url     : UserLocationConfig.actionUrl,
                                   type    : 'post',
                                   dataType: 'json',
                                   cache   : false,
                                   data    : data,
                               }).done((function(_this) {
                            return function(r) {

                                e = $.Event($.fn.userlocation.PROCESS_SUCCESS);
                                $element.trigger(e, [r, data]);
                                if(e.isDefaultPrevented()) {
                                    return;
                                }

                                runAction($.fn.userlocation.methodActions['success'], this, r, data);
                            };
                        })(this)).fail((function(_this) {
                            return function(r) {
                                e = $.Event($.fn.userlocation.PROCESS_FAILURE);
                                $element.trigger(e, [r, data]);
                                if(e.isDefaultPrevented()) {
                                    return;
                                }

                                runAction($.fn.userlocation.methodActions['failure'], this, r, data);
                            };
                        })(this));
                    });

                // unconfirmed close
                $(document)
                    .off('click touchend', $.fn.userlocation.selectors.locationConfirmClose)
                    .on('click touchend', $.fn.userlocation.selectors.locationConfirmClose, function(e) {
                        $(this).closest($.fn.userlocation.selectors.locationConfirm).each(function(e) {
                            $(this).show().removeClass($.fn.userlocation.defaults.cls.unconfirmed);
                        });
                    });

            },

            addMethodAction: function(path, name, func) {
                if(typeof func != 'function') {
                    return false;
                }
                if(!$.fn.userlocation.methodActions[path]) {
                    $.fn.userlocation.methodActions[path] = {};
                }
                $.fn.userlocation.methodActions[path][name] = func;

                return true;
            },

            removeMethodAction: function(path, name) {
                if(!$.fn.userlocation.methodActions[path]) {
                    $.fn.userlocation.methodActions[path] = {};
                }

                delete $.fn.userlocation.methodActions[path][name];

                return true;
            },

        };

        UserLocation.tools = {
            clearName: function(str) {
                var pairs = {'Ё': 'Е', 'Й': 'И', 'ё': 'е', 'й': 'и'};
                return str.replace(RegExp(Object.keys(pairs).join('|'), 'gi'), function(letter) {
                    return pairs[letter];
                });
            }
        };

        UserLocation.init();
        window.UserLocation = UserLocation;

    }(window.jQuery));

}catch(e) {
    console.warn(e)
}
/* event example */
/*$(document).on('userlocation:process-success', function (e, data) {
    console.log(data);

    e.preventDefault();
    return false;
});*/

/* addMethodAction example */
/*$(document).ready(function () {
    if (typeof UserLocation != 'undefined') {
        UserLocation.addMethodAction('success', 'name_action', function (r) {
            console.log(r);
        });
    }
});*/

