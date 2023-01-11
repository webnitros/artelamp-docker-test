(function() {
	function msPromoCode2Main(options) {
		var self = this
		self['initialized'] = false
		self['msopLoaded'] = false
		self['running'] = false
		self['fatal'] = false;
		['assetsUrl', 'actionUrl'].forEach(function(val, i, arr) {
			if(typeof (options[val]) === 'undefined' || options[val] === '') {
				console.error('[msPromoCode2Main] Bad config.', arr)
				self['fatal'] = true
			}
		})
		if(self['fatal']) {
			return
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
			initialize: function(options) {
				if(!self['initialized']) {
					self['config'] = {}
					self['classes'] = {
						loading: 'is-loading',
						active : 'is-active',
					}
					self['selectors'] = {
						wrap  : '.js-mspc2',
						form  : '.js-mspc2-form',
						input : '.js-mspc2-input',
						submit: '.js-mspc2-submit',
						cancel: '.js-mspc2-cancel',

						messageInfo   : '.js-mspc2-message-info',
						messageError  : '.js-mspc2-message-error',
						messageSuccess: '.js-mspc2-message-success',

						discountAmount: '.js-mspc2-discount-amount',

						couponDescription: '.js-mspc2-coupon-description',

						productWrap          : '.js-mspc2-product',
						productPrice         : '.js-mspc2-product-price',
						productOldPrice      : '.js-mspc2-product-old-price',
						productDiscountAmount: '.js-mspc2-product-discount-amount',

						cartProduct      : '[data-mspc2-id]',
						cartProductPrices: '.js-mspc2-cart-product-prices',
					}
					self['sendDataTemplate'] = {
						formData: null,
					}
					self['sendData'] = $.extend({}, self['sendDataTemplate'])

					//
					Object.keys(options).forEach(function(key) {
						if(['selectors'].indexOf(key) !== -1) {
							return
						}
						self.config[key] = options[key]
					});
					['selectors'].forEach(function(key) {
						if(options[key]) {
							Object.keys(options[key]).forEach(function(i) {
								self.selectors[i] = options.selectors[i]
							})
						}
					})

					// Require dependecies
					if(typeof (md5) !== 'function') {
						$.getScript(self.config['assetsUrl'] + 'js/vendor/md5.min.js', function() {
						})
					}
				}
				self['initialized'] = true

				return self['initialized']
			},

			/**
			 * Запускает основные действия.
			 * @returns {boolean}
			 */
			run: function() {
				if(self['initialized'] && !self['running']) {
					//
					self.Form.initialize()
					self.miniShop.initialize()
				}
				self['running'] = true

				return self['running']
			},
		}

		/**
		 *
		 * @type {{initialize: (function(): boolean)}}
		 */
		self.miniShop = {
			pricesTimeoutInstance: null,
			messageInstance      : null,
			$cartProduct         : null,
			$cartProductForm     : null,
			cartProductKey       : null,
			restart              : true,
			/**
			 * @returns {boolean}
			 */
			initialize: function() {
				//
				var ms2Interval = window.setInterval(function() {
					if(typeof (miniShop2) !== 'undefined') {
						clearInterval(ms2Interval)

						//
						var onCartChangeProducts = function(response) {
							self.miniShop['pricesTimeoutInstance'] && window.clearTimeout(self.miniShop['pricesTimeoutInstance'])
							self.miniShop['pricesTimeoutInstance'] = window.setTimeout(function() {
								// var $wrap = $(document).find(self.selectors['wrap']);
								// if ($wrap.length) {
								//     if ($wrap.hasClass(self.classes['active'])) {
								//         var $submit = $wrap.find(self.selectors['submit']);
								//         if ($submit.length) {
								//             $submit.click();
								//         }
								//     }
								// }

								self.Prices.refresh({
														form    : true,
														cart    : true,
														products: true,
													})
							}, 250)
						}
						miniShop2.Callbacks.add('Cart.add.response.success', 'msPromoCode2Main', onCartChangeProducts)
						miniShop2.Callbacks.add('Cart.change.response.success', 'msPromoCode2Main', onCartChangeProducts)
						miniShop2.Callbacks.add('Cart.remove.response.success', 'msPromoCode2Main', onCartChangeProducts)

						//
						var fixCartChangedProductKey = function(action, step) {
							return function(response) {
								switch( step ) {
									case 1:
										if(self.miniShop['restart']) {
											self.miniShop['messageInstance'] = miniShop2.Message['error']
											miniShop2.Message['error'] = function(msg) {}

											self.miniShop['$cartProductForm'] = miniShop2.sendData['$form']
											self.miniShop['$cartProduct'] = self.miniShop['$cartProductForm']
												.closest('[data-mspc2-id]')
											self.miniShop['cartProductKey'] = self.miniShop['$cartProduct']
												.data('mspc2-id')
										}
										break

									case 2:
										if(self.miniShop['restart']) {
											self.Prices.refresh({
																	form: true,
																	cart: true,
																}, function() {
												if(self.miniShop['$cartProduct'].length) {
													self.miniShop['$cartProduct']
														.find('button[type="submit"][value="cart/' + action + '"]')
														.click()
													self.miniShop['restart'] = false
												}
											})
										}
										break

									case 3:
										if(self.miniShop['restart']) {
											miniShop2.Message['error'] = self.miniShop['messageInstance']
										} else {
											self.miniShop['restart'] = true
											self.miniShop['$cartProduct'] = null
											self.miniShop['$cartProductForm'] = null
											self.miniShop['cartProductKey'] = null
											self.miniShop['messageInstance'] = null
										}
										break
								}
							}
						}
						miniShop2.Callbacks.add('Cart.change.before', 'msPromoCode2Main', fixCartChangedProductKey('change', 1))
						miniShop2.Callbacks.add('Cart.change.response.error', 'msPromoCode2Main', fixCartChangedProductKey('change', 2))
						miniShop2.Callbacks.add('Cart.change.ajax.always', 'msPromoCode2Main', fixCartChangedProductKey('change', 3))
						miniShop2.Callbacks.add('Cart.remove.before', 'msPromoCode2Main', fixCartChangedProductKey('remove', 1))
						miniShop2.Callbacks.add('Cart.remove.response.error', 'msPromoCode2Main', fixCartChangedProductKey('remove', 2))
						miniShop2.Callbacks.add('Cart.remove.ajax.always', 'msPromoCode2Main', fixCartChangedProductKey('remove', 3))

						// Refresh prices
						self.Prices.refresh({
												products: true,
											})
					}
				}, 500)
				window.setTimeout(function() {
					clearInterval(ms2Interval)
				}, 7000)

				return true
			},
		}

		/**
		 *
		 * @type {{initialize: (function(): boolean)}}
		 */
		self.Form = {
			/**
			 * @returns {boolean}
			 */
			initialize: function() {
				/**
				 * Set promo code
				 */
				$(document).on('click', self.selectors['submit'], function(e) {
					e.preventDefault()

					// var propkey = self.config['propkey'];
					var $submit = $(this)
					if(!$submit['length']) {
						return false
					}
					var $wrap = $submit.closest(self.selectors['wrap'])
					var $input = $wrap.find(self.selectors['input'])

					// Prepare query params
					var sendData = $.extend({}, self['sendDataTemplate'])
					sendData['formData'] = [{
						name : 'ctx',
						value: self.config['ctx'],
					}, {
						name : 'action',
						value: 'coupon/set',
					}, {
						name : 'code',
						value: $input.val(),
					}]
					// console.log(sendData);

					// Callbacks
					var callbackBefore = function(response) {
						var $wrap = $(document).find(self.selectors['wrap'])
						if($wrap.length) {
							// Add loading class
							$wrap.addClass(self.classes['loading'])
						}
					}
					var callbackAfter = function(response) {
						// console.log('coupon/set callbackAfter response', response);

						// Refresh prices
						self.Prices.refresh({
												cart    : true,
												products: true,
											})

						//
						var $wrap = $(document).find(self.selectors['wrap'])
						var $form = $wrap.find(self.selectors['form'])
						if($form.length) {
							var $input = $form.find(self.selectors['input'])
							// var $discountAmount = $wrap.find(self.selectors['discountAmount']);
							var $couponDescription = $wrap.find(self.selectors['couponDescription'])

							// Show message
							if(response.data['info']) {
								self.Message.info(response.data['info'])
							} else {
								self.Message
									[response.success ? 'success' : 'error']
								(response['message'])
							}

							//
							if(response['success']) {
								// Add active class
								$form.addClass(self.classes['active'])

								// Add disable attribute
								$input.prop('disabled', true)

								// Coupon info
								var coupon = response.data['coupon']
								if(coupon['id']) {
									$input.val(coupon['code'])
									$couponDescription.html(coupon['description'] || '')
								}

								// // Set discount amount
								// $discountAmount.html(miniShop2.Utils.formatPrice(response.data['discount_amount'] || '0'));
							} else {
								// Remove active class
								$form.removeClass(self.classes['active'])

								// Remove disable attribute
								$input.prop('disabled', false)

								// Clear of description
								$couponDescription.html('')

								// // Set discount amount
								// $discountAmount.html('0');
							}

							// Remove loading class
							$wrap.removeClass(self.classes['loading'])
						}

						//
						miniShop2.Order.getcost()

						//
						$(document).trigger('mspc2_set', response)
					}

					// Submit
					self.sendData = $.extend({}, sendData)
					self.Submit.post(callbackBefore, callbackAfter)
				})

				/**
				 * Unset promo code
				 */
				$(document).on('click', self.selectors['cancel'], function(e) {
					e.preventDefault()

					var $cancel = $(this)
					if(!$cancel['length']) {
						return false
					}

					// Prepare query params
					var sendData = $.extend({}, self['sendDataTemplate'])
					sendData['formData'] = [{
						name : 'ctx',
						value: self.config['ctx'],
					}, {
						name : 'action',
						value: 'coupon/unset',
					}]

					// Callbacks
					var callbackBefore = function() {
						var $wrap = $(document).find(self.selectors['wrap'])
						if($wrap.length) {
							// Add loading class
							$wrap.addClass(self.classes['loading'])
						}
					}
					var callbackAfter = function(response) {
						// console.log('coupon/unset callbackAfter response', response);

						// Refresh prices
						self.Prices.refresh({
												cart    : true,
												products: true,
											})

						//
						var $wrap = $(document).find(self.selectors['wrap'])
						var $form = $wrap.find(self.selectors['form'])
						if($form.length) {
							var $input = $form.find(self.selectors['input'])
							// var $discountAmount = $wrap.find(self.selectors['discountAmount']);
							var $couponDescription = $wrap.find(self.selectors['couponDescription'])

							// Show message
							self.Message['error'](response['message'])

							//
							if(response['success']) {
								// Remove active class
								$form.removeClass(self.classes['active'])

								// Remove disable attribute
								$input.prop('disabled', false)

								// Clear of description
								$couponDescription.html('')

								// // Set discount amount
								// $discountAmount.html(miniShop2.Utils.formatPrice(response.data['discount_amount'] || '0'));
							} else {
								// Is there any error at all when canceling the promo code?
							}

							// Remove loading class
							$wrap.removeClass(self.classes['loading'])
						}

						//
						miniShop2.Order.getcost()

						//
						$(document).trigger('mspc2_unset', response)
					}

					// Submit
					self.sendData = $.extend({}, sendData)
					self.Submit.post(callbackBefore, callbackAfter)
				})

				/**
				 * On touch Enter on keyboard
				 */
				$(document).on('keypress', self.selectors['input'], function(e) {
					var key = e['which']
					if(key === 13) {
						var $input = $(this)
						if(!$input['length']) {
							return false
						}
						var $wrap = $input.closest(self.selectors['wrap'])
						var $submit = $wrap.find(self.selectors['submit'])

						$submit.trigger('click')
						e.preventDefault()
					}
				})

				/**
				 * On msOptionsPrice trigger
				 */
				$(document).on('msoptionsprice_product_action', function(e, action, form, response) {
					// console.log(action, form, response);

					// Refresh prices
					self.Prices.refresh({
											products: true,
										}, function() {
						self['msopLoaded'] = true
					}, (self['msopLoaded'] ? 0 : 500))
				})

				return true
			},
		}

		/**
		 *
		 * @type {{refresh: (function(): boolean)}}
		 */
		self.Prices = {
			/**
			 *
			 * @returns {boolean}
			 */
			refresh: function(services, callback, timeout) {
				if(typeof (services) === 'undefined') {
					services = {
						form    : true,
						cart    : true,
						products: true,
					}
				}

				// Check miniShop2
				if(typeof (miniShop2) === 'undefined') {
					return
				}

				// Form / Cart
				if(services['form'] || services['cart']) {
					var $wrap = $(document).find(self.selectors['wrap'])
					var $cart = $(document).find(miniShop2.Cart['cart'])
					if((services['form'] && $wrap['length']) ||
					   (services['cart'] && $cart.length)) {
						var url = document.location['origin'] + document.location['pathname']
								  + (document.location['search'] ? document.location['search'] + '&' : '?')
								  + 'mspc2_load=1'
                        console.log(url)
						var settings = {
							"url": url,
							"method": "GET",
							"timeout": 0,
							"dataType":'HTML',
						};
						$.ajax(settings).done( function(data) {
							const parser = new DOMParser();
							const doc = parser.parseFromString(data, "text/html");
							console.log(doc)
							if(services['cart']) {
								var $dataCart = $(doc).find(miniShop2.Cart['cart'])
								var dataCart = $dataCart.html()

								if(dataCart) {
									//
									var $dataCartProducts = $(data).find(self.selectors['cartProduct'])

									if($dataCartProducts.length) {
										$dataCartProducts.each(function(idx, el) {
											//
											var $dataCartProduct = $(el)
											var cartProductKey = $dataCartProduct.data('mspc2-id')
											var $cartProduct = $(document)
												.find(miniShop2.Cart['cart'] + ' [data-mspc2-id="' + cartProductKey + '"]')
											if(!$cartProduct.length) {
												return
											}

											//
											var $dataCartProductMs2Key = $dataCartProduct.find('input[type="hidden"][name="key"]')
											var cartProductMs2Key = $dataCartProductMs2Key.val() || undefined
											if(!cartProductMs2Key) {
												return
											}
											var $cartProductMs2Key = $cartProduct.find('input[type="hidden"][name="key"]')
											$cartProductMs2Key.val(cartProductMs2Key)
											$cartProduct.attr('id', cartProductMs2Key)
											//
											var $cartPrices = $cartProduct.find(self.selectors['cartProductPrices'])
											var $dataCartPrices = $dataCartProduct.find(self.selectors['cartProductPrices'])
											if(!$cartPrices.length || !$dataCartPrices.length) {
												return
											}
											console.log($cartPrices)
											$cartPrices.each(function(cartPricesItemIdx) {
												console.log(cartPricesItemIdx)
												$($cartPrices[cartPricesItemIdx]).html(
													$($dataCartPrices[cartPricesItemIdx]).html()
												)
											})
										})
									}

									//
									var $dataCartCost = $(dataCart).find(miniShop2.Cart['totalCost'])
									var $cartCost = $(document).find(miniShop2.Cart['totalCost'])
									if($cartCost.length && $dataCartCost.length) {
										$cartCost.text($dataCartCost.text())
									}
								}
							}

							//
							if(services['form']) {
								var $dataWrap = $(data).find(self.selectors['wrap'])
								var dataWrap = $dataWrap.html()
								if(dataWrap) {
									$wrap.html(dataWrap)
								}
							}

							if(!services['products']) {
								// Callback
								if(callback && $.isFunction(callback)) {
									callback.call(this, data)
								}
							}
						})

						// $cart.load(document.location.href + ' ' + miniShop2.Cart.cart + ' > *');
					}
				}

				// Product
				if(services['products']) {
					var sendData = $.extend({}, self['sendDataTemplate'])
					sendData['formData'] = [{
						name : 'ctx',
						value: self.config['ctx'],
					}, {
						name : 'action',
						value: 'prices/refresh',
					}]
					sendData['elements'] = {}

					//
					var $products = $(document).find(self.selectors['productWrap'])
					if($products['length']) {
						$products.each(function(idx, el) {
							var $productWrap = $(el)
							var $productForm = $productWrap.is(miniShop2.form)
											   ? $productWrap : $productWrap.find(miniShop2.form)
							if(!$productWrap['length'] || !$productForm['length']) {
								return
							}

							// Get product id
							var product_id = parseInt($productWrap.data('id')) || undefined
							if(typeof (product_id) === 'undefined') {
								var $productId = $productForm.find('input[name="id"]')
								product_id = ($productId['length'] && parseInt($productId.val())) || undefined
							}
							if(product_id) {
								// Prepare options for msOptionsPrice
								if(typeof (msOptionsPrice) !== 'undefined') {
									msOptionsPrice.Product.processOptions(this)
								}

								// Collect form data
								var formData = $productForm.serializeArray()
								if(!formData['length']) {
									return
								}
								var md5key = self.Tools.md5(JSON.stringify(formData))
								formData.forEach(function(row) {
									var key = [
										'products[' + md5key + '][',
										row.name.replace('[', ']['),
										row.name.indexOf(']') === -1 ? ']' : '',
									].join('')
									sendData['formData'].push({
																  name : key,
																  value: row.value,
															  })
								})

								//
								sendData['elements'][md5key] = $productWrap
							}
						})
						// console.log(sendData);
					}

					// Callbacks
					var callbackBefore = function(response) {
						// console.log('prices/refresh callbackBefore response', response);
					}
					var callbackAfter = function(response) {
						console.log('prices/refresh callbackAfter response', response)

						if(response['success']) {
							// Set discount amount
							var $discountAmount = $(document).find(self.selectors['discountAmount'])
							if($discountAmount.length) {
								$discountAmount.html(miniShop2.Utils.formatPrice(response.data['discount_amount'] || '0'))
							}

							self.Prices['waiting'] = false
						}

						if(response['success'] && 'products' in response['data']) {
							// Each products / Variable 1
							$products.each(function(idx, el) {
								var $productWrap = $(el)
								var $productForm = $productWrap.is(miniShop2.form)
												   ? $productWrap : $productWrap.find(miniShop2.form)
								if(!$productWrap['length'] || !$productForm['length']) {
									return
								}
								var $productPrice = $productWrap.find(self.selectors['productPrice'])
								var $productOldPrice = $productWrap.find(self.selectors['productOldPrice'])
								var $productDiscountAmount = $productWrap.find(self.selectors['productDiscountAmount'])

								// Get product id
								var product_id = parseInt($productWrap.data('id')) || undefined
								if(typeof (product_id) === 'undefined') {
									var $productId = $productForm.find('input[name="id"]')
									product_id = ($productId['length'] && parseInt($productId.val())) || undefined
								}
								if(product_id) {
									var formData = $productForm.serializeArray()
									if(!formData['length']) {
										return
									}
									var md5key = self.Tools.md5(JSON.stringify(formData))

									//
									var productData = response.data.products[md5key] || undefined
									if(productData) {
										// Update product prices
										if($productPrice['length'] && productData['price']) {
											$productPrice.html(miniShop2.Utils.formatPrice(productData['price']))
										}

										// Update product old prices
										if($productOldPrice['length']) {
											if(productData['old_price']) {
												$productOldPrice.html(miniShop2.Utils.formatPrice(productData['old_price']))
												$productOldPrice.parent().show()
											} else {
												$productOldPrice.html('0')
												$productOldPrice.parent().hide()
											}
										}

										// Update product discount amount
										if($productDiscountAmount['length']) {
											if(productData['discount_amount']) {
												$productDiscountAmount.html(miniShop2.Utils.formatPrice(productData['discount_amount']))
												$productDiscountAmount.parent().show()
											} else {
												$productDiscountAmount.html('0')
												$productDiscountAmount.parent().hide()
											}
										}
									}
								}
							})

							/**
							 * // Not used because if md5key is duplicated previous products are excluded
							 *
							 * $.each(sendData['elements'], function(md5key, $productWrap) {
							 *     var $productForm = $productWrap.is(miniShop2.form)
							 *         ? $productWrap : $productWrap.find(miniShop2.form);
							 *     var $productPrice = $productWrap.find(self.selectors['productPrice']);
							 *     if (!$productWrap['length'] || !$productForm['length'] || !$productPrice['length']) {
							 *         return;
							 *     }
							 *
							 *     // Get product id
							 *     var product_id = parseInt($productWrap.data('id')) || undefined;
							 *     if (typeof (product_id) === 'undefined') {
							 *         var $productId = $productForm.find('input[name="id"]');
							 *         product_id = ($productId['length'] && parseInt($productId.val())) || undefined;
							 *     }
							 *     if (product_id) {
							 *         var productData = response.data.products[md5key] || undefined;
							 *         if (productData && 'price' in productData) {
							 *             // Update product prices
							 *             $productPrice.html(miniShop2.Utils.formatPrice(productData['price']));
							 *         }
							 *     }
							 * });
							 */
						}

						// Callback
						if(callback && $.isFunction(callback)) {
							callback.call(this, response)
						}
					}

					// Submit
					self.sendData = $.extend({}, sendData)
					self.Submit.post(callbackBefore, callbackAfter, timeout)
				}

				return true
			},
		}

		/**
		 * Отсылает запрос на сервер.
		 *
		 * @type {{post: post, timeoutInstance: *, timeout: number}}
		 */
		self.Submit = {
			timeout        : 0, // замираем на N секунд перед отсылкой запроса
			timeoutInstance: undefined,
			post           : function(beforeCallback, afterCallback, timeout) {

				if(!self.sendData['formData']) {
					return
				}
				if(typeof (timeout) === 'undefined') {
					timeout = self.Submit['timeout']
				}
				timeout = parseInt(timeout) || 0

				//
				self.Submit['timeoutInstance'] && window.clearTimeout(self.Submit['timeoutInstance'])
				self.Submit['timeoutInstance'] = window.setTimeout(function() {
					// Запускаем колбек перед отсылкой запроса
					if(beforeCallback && $.isFunction(beforeCallback)) {
						beforeCallback.call(this, self.sendData['formData'])
					}

					$.post(self.config['actionUrl'], self.sendData['formData'], function(response) {
						// Запускаем колбек после отсылки запроса
						if(afterCallback && $.isFunction(afterCallback)) {
							afterCallback.call(this, response, self.sendData['formData'])
						}

						if(response['success']) {
							//
						} else {
							// self.Message.error(response['message']);
						}
					}, 'json')
					 .fail(function() {
						 console.error('[msPromoCode2Main] Bad request.', self['sendData'])
					 })
					 .done(function() {
					 })
				}, timeout)
			},
		}

		/**
		 * Сообщения.
		 *
		 * @type {{success: success, handle: handle, error: error, info: info}}
		 */
		self.Message = {
			handle : function(type, message) {
				['success', 'error', 'info'].forEach(function(val) {
					var $message = $(self.selectors['message' + self.Tools.ucFirst(val)])
					if($message.length) {
						$message.html(type === val ? message : '')
					}
				})
			},
			success: function(message) {
				self.Message.handle('success', message)
			},
			error  : function(message) {
				self.Message.handle('error', message)
			},
			info   : function(message) {
				self.Message.handle('info', message)
			},
		}

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
			ucFirst: function(string) {
				return string.charAt(0).toUpperCase() + string.slice(1)
			},

			/**
			 * @param string
			 * @returns {string}
			 */
			md5: function(string) {
				var output = undefined
				if(typeof (md5) === 'function') {
					output = md5(string)
				}
				return output
			},
		}

		/**
		 * Initialize and run
		 */
		self.Base.initialize(options) && self.Base.run()
	}

	window['msPromoCode2Main'] = msPromoCode2Main
})()