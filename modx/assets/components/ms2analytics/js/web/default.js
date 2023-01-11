if(window.hasOwnProperty('miniShop2')) {
  try {
    (function(window, document, $, miniShop2) {
      let yandexContainer = 'ms2aYandexLayer';

      function gtag() {
        window.dataLayer.push(arguments);
      }

      if(!miniShop2.hasOwnProperty('ms2a') || miniShop2.ms2a !== true) {
        miniShop2.send = function(data, callbacks, userCallbacks) {
          //переопределено ms2analytics
          //изменен порядок запуска callback и userCallbacks
          var runCallback = function(callback, bind) {
            if(typeof callback == 'function') {
              return callback.apply(bind, Array.prototype.slice.call(arguments, 2));
            } else if(typeof callback == 'object') {
              for(var i in callback) {
                if(callback.hasOwnProperty(i)) {
                  var response = callback[i].apply(bind, Array.prototype.slice.call(arguments, 2));
                  if(response === false) {
                    return false;
                  }
                }
              }
            }
            return true;
          };
          // set context
          if($.isArray(data)) {
            data.push({
              name: 'ctx',
              value: miniShop2Config.ctx
            });
          } else if($.isPlainObject(data)) {
            data.ctx = miniShop2Config.ctx;
          } else if(typeof data == 'string') {
            data += '&ctx=' + miniShop2Config.ctx;
          }

          // set action url
          var formActionUrl = (miniShop2.sendData.$form)
            ? miniShop2.sendData.$form.attr('action')
            : false;
          var url = (formActionUrl)
            ? formActionUrl
            : (miniShop2Config.actionUrl)
              ? miniShop2Config.actionUrl
              : document.location.href;
          // set request method
          var formMethod = (miniShop2.sendData.$form)
            ? miniShop2.sendData.$form.attr('method')
            : false;
          var method = (formMethod)
            ? formMethod
            : 'post';

          // callback before
          if(runCallback(callbacks.before) === false || runCallback(userCallbacks.before) === false) {
            return;
          }
          // send
          var xhr = function(callbacks, userCallbacks) {
            return $[method](url, data, function(response) {
              if(response.success) {
                if(response.message) {
                  miniShop2.Message.success(response.message);
                }
                runCallback(userCallbacks.response.success, miniShop2, response);
                runCallback(callbacks.response.success, miniShop2, response);
              } else {
                miniShop2.Message.error(response.message);
                runCallback(userCallbacks.response.error, miniShop2, response);
                runCallback(callbacks.response.error, miniShop2, response);
              }
            }, 'json').done(function() {
              runCallback(callbacks.ajax.done, miniShop2, xhr);
              runCallback(userCallbacks.ajax.done, miniShop2, xhr);
            }).fail(function() {
              runCallback(callbacks.ajax.fail, miniShop2, xhr);
              runCallback(userCallbacks.ajax.fail, miniShop2, xhr);
            }).always(function() {
              runCallback(callbacks.ajax.always, miniShop2, xhr);
              runCallback(userCallbacks.ajax.always, miniShop2, xhr);
            });
          }(callbacks, userCallbacks);
        };
        miniShop2.ms2a = true;
      }

      miniShop2.Callbacks.add('Cart.add.response.success', 'ms2a_cart_add', function(cart, a, b) {
        try {
          gtag('event', 'Add', {
            'event_category': 'Basket'
          });
          var quantity = 1;
          for(const dt in this.sendData.formData) {
            if(this.sendData.formData[dt].name == 'count') {
              quantity = parseInt(this.sendData.formData[dt].value);
              break;
            }
          }

          const jqxhr = $.post('/ms2analytics/getProductById', {
            key: String(cart.data.key)
          }).done(function(resp) {
            if(resp.success) {

              resp.object.product.quantity = quantity || 1;
              if(window.hasOwnProperty('ms2a_google') && ms2a_google === true) {
                var gSend = {
                  'items': [
                    resp.object.product
                  ]
                };
                gtag('event', 'add_to_cart', gSend);

              }
              if(window.hasOwnProperty('ms2a_yandex') && ms2a_yandex === true) {

                var ySend = {
                  'ecommerce': {
                    'currencyCode': resp.object.config.currency,
                    'add': [
                      resp.object.product
                    ]
                  }
                };
                if(typeof window[yandexContainer] != 'object') {
                  window[yandexContainer] = [];
                }
                window[yandexContainer].push(ySend);
              }
            }

          }).fail(function() {
            console.error('fail', arguments);
          });
        } catch(e) {
          console.warn(e);
        }
        return true;
      });

      miniShop2.Callbacks.add('Cart.remove.response.success', 'ms2a_cart_remove', function(cart, a, b) {
        try {

          this;
          var key = '';
          for(const dt in this.sendData.formData) {
            if(this.sendData.formData[dt].name == 'key') {
              key = this.sendData.formData[dt].value;
              break;
            }
          }

          var jqxhr = $.post('/ms2analytics/getProductById', {
            key: String(key)
          }).done(function(resp) {
            if(resp.success) {

              if(window.hasOwnProperty('ms2a_google') && ms2a_google === true) {
                var gSend = {
                  'items': [
                    resp.object.product
                  ]
                };

                gtag('event', 'add_to_cart', gSend);
              }
              if(window.hasOwnProperty('ms2a_yandex') && ms2a_yandex === true) {

                var ySend = {
                  'ecommerce': {
                    'currencyCode': resp.object.config.currency,
                    'remove': [
                      resp.object.product
                    ]
                  }
                };
                window[yandexContainer].push(ySend);
              }
            }

          }).fail(function() {
            console.error('fail', arguments);
          });
        } catch(e) {
          console.warn(e);
        }
        return true;
      });

      miniShop2.Callbacks.add('Order.submit.response.success', 'ms2a_order_submit', function(order, a, b) {
        try {
          modx.user.setSetting('ms2analytics.msOrder', order.data.msorder);
        } catch(e) {
          console.warn(e);
        }
        return true;
      });

      if(modx.user.getSetting('ms2analytics.msOrder')) {

        const jqxhr = $.post('/ms2analytics/getOrderId', {
          key: modx.user.getSetting('ms2analytics.msOrder')
        }).done(function(resp) {
            try {
              try {
                yandexContainer = resp?.object?.config?.container ?? 'yandexLayer';
              } catch(e) {
                yandexContainer = 'yandexLayer';
              }
            } catch(e) {
            }
            if(resp.success) {
              if(window.hasOwnProperty('ms2a_google') && ms2a_google === true) {
                const gSend = {};
                gSend['transaction_id'] = resp.object.order['transaction_id'];
                if(resp.object.config['affiliation']) {
                  gSend['affiliation'] = resp.object.config['affiliation'];
                }
                gSend['value'] = resp.object.order['value'];
                gSend['currency'] = resp.object.config['currency'];
                if(resp.object.config['tax']) {
                  gSend['tax'] = resp.object.config['tax'];
                }
                gSend.items = resp.object.products;

                gtag('event', 'purchase', gSend);

              }

              if(window.hasOwnProperty('ms2a_yandex') && ms2a_yandex === true) {
                var ySend = {
                  'ecommerce': {
                    'purchase': {
                      'actionField': {
                        'id': resp.object.order['transaction_id']
                      },
                      'products': resp.object.products
                    }
                  }
                };
                if(typeof window[yandexContainer] != 'object') {
                  window[yandexContainer] = [];
                }
                window[yandexContainer].push(ySend);

              }

            }
          }
        ).fail(function() {
          console.error('fail', arguments);
        });
        modx.user.delSetting('ms2analytics.msOrder');
      }
    })
    (window, document, jQuery, miniShop2);
  } catch(e) {
    console.warn(e);
  }
} else {
  console.warn('not miniShop2');
}

