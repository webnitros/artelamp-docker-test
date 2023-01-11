{set $location = ''|getUserLocation}

<form class="jcont ms2_form" id="msOrder" method="post">
	<section class="cart_order_form">
		<div class="jcont">
			<div class="cart_order_form_wrap p110">
				<div class="cart_order_form_subtitle ic_person">
					<span>персональная информация</span>
				</div>
				<div class="cart_order_form_controll_long">
                    {foreach ['receiver','phone','email'] as $field}
                        {var $value = $form[$field]}
                        {if strpos($value,'msoneclick') !== false}
                            {var $value = ''}
                        {/if}
						<div class="cart_order_form_controll input-parent required">
							<label for="{$field}">
								<span class="required-star">*</span>
                                {('ms2_frontend_' ~ $field) | lexicon}
							</label>
							<input type="text" id="{$field}"
								   placeholder="{('ms2_frontend_' ~ $field~'_pls') | lexicon}"
								   name="{$field}"
								   value="{$value}"
								   class="{($field in list $errors) ? ' error' : ''}">
							<div class="error_controll error_field_{$field}"></div>
						</div>
                    {/foreach}
					<div class="cart_order_form_controll">
						<div class="cart_order_form_links">
							<label for="">
								<span>*</span>Данные поля обязательны для заполнения
							</label>
						</div>
					</div>
				</div>
				<div class="cart_order_form_controll_long">
					<div class="cart_order_form_controll input-parent">
						<label for="adress">
							Адрес доставки
						</label>
						<input oninput="commentConcat()" data-maxlength="100" name="street" id="adress">
						<div class="error_controll error_field_{'adress'}"></div>
					</div>
					<div class="cart_order_form_controll input-parent">
						<label for="note">
							Комментарий
						</label>
						<textarea oninput="commentConcat()" data-maxlength="195" id="note"></textarea>
						<div class="error_controll error_field_{$field}"></div>
						<input type="hidden"
							   name="comment"
							   value=""
							   data-maxlength="300"
							   id="comment"
							   class="hide">
					</div>

					<div class="cart_order_form_controll">
						<input type="checkbox" class="checkbox" value="1" id="msoc_confirmation" name="confirmation"
							   checked/>
						<label for="msoc_confirmation" class="cart_order_policy">
							<b>Подтверждаю согласие на обработку своих персональных данных в соответствии с <a
										href="{'1328'|url}">Условиями</a></b>
						</label>
					</div>
				</div>
			</div>
			<div class="cart_order_form_wrap p110">
				<div class="cart_order_form_wrap_df">

					<div class="cart_order_form_wrap_elem">

						<div class="cart_order_form_subtitle ic_delivery">
							<span>выберите варианты доставки</span>
						</div>
						<div class="cart_order_form_controll">
                            {if $location.id == 1}
								<div class="moscow">
									<input class='radio'
										   type="radio"
										   name="delivery"
										   value="6"
										   id="delivery_6"
										   data-payments="[1,11,3]"
										   style="display:none"
										   onchange="if($(this).prop('checked')){ cartUtil.delivery(this,'ДОСТАВКА КУРЬЕРОМ ПО МОСКВЕ'); }"/>
									<label for='delivery_6'>
										Курьер (только по Москве и области)
									</label>
								</div>
                            {/if}
							<div class="noMoscow">
								<input class='radio'
									   type="radio"
									   name="delivery"
									   value="7"
									   required
									   checked
									   id="delivery_7"
									   data-payments="[1,11,3]"
									   style="display:none"
									   onchange="if($(this).prop('checked')){ cartUtil.delivery(this,'ДОСТАВКА ДО ТК'); }"/>
								<label for='delivery_7'>
									Транспортная компания - Доставка до двери (оплата за доставку на терминале ТК).
								</label>

							</div>
						</div>

						<div class="cart_order_form_subtitle ic_pay">
							<span>выберите варианты оплаты</span>
						</div>
						<div class="cart_order_form_controll">
                            {if $location.id == 1}
								<input type='radio' class='radio' id="payment_1" value="1" name='payment'/>
								<label for='payment_1'>
									Наличные или карта курьеру (только по Москве и МО)
								</label>
                            {/if}
							<input type='radio' class='radio' id="payment_11" value="11" name='payment'/>
							<label for='payment_11'>
								Оплата онлайн (карты, электронные платежные системы)
							</label>
							<input type='radio' class='radio' id="payment_3" value="3" name='payment'/>
							<label for='payment_3'>
								Выставить счет
							</label>
						</div>

					</div>

					<div class="cart_order_form_wrap_elem">
						<div class="cart_order_form_controll">
							<div class="cart_order_form_summ">
								<div class="cart_order_form_subtitle">
									<span>СУММА К ОПЛАТЕ</span>
								</div>

								<div class="cart_order_form_summ_line">
									<span class="name">ТОВАРЫ В КОРЗИНЕ НА СУММУ</span>
									<div>
										<span class="value ms3_total_cost">{$order.cost}</span>
										<b class="rub" style="line-height: 38px;   font-family: 'fb',serif;font-size: 18px;display: none"> р.</b>
									</div>
								</div>
								<div class="cart_order_form_summ_line" id="delivery_pay">
									<span class="name"></span>
									<div>
										<span class="value"></span>
										<b class="rub" style="line-height: 38px;   font-family: 'fb',serif;font-size: 18px;display: none"> р.</b>
									</div>
								</div>
								<div class="cart_order_form_summ_total">
									<span class="name">ИТОГО:</span>
									<div>
										<span class="value ms3_total_cost">{$order.cost}</span>
										<b class="rub" style="line-height: 38px;   font-family: 'fb',serif;font-size: 18px;display: none"> р.</b>
									</div>
								</div>
							</div>
							<div class="info_text" id="tk-pay-msg" style="display:none;">
								<i class="red">**</i>Стоимость доставки ТК оплачивается отдельно
							</div>
							<div class="cart_order_form_orderphone">
								<div class="cart_order_form_orderphone_title">
									<span>Заказ также можно оформить по телефону</span>
								</div>
								<div class="cart_order_form_orderphone_text">
									Наши операторы работают с 9:00 до 21:00 ежедневно
								</div>
								<div class="cart_order_form_orderphone_text">
									<a href="tel:{$_modx->config['phone']}">{$_modx->config['phone']}</a>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="cart_order_form_send">
					<button class="btn btn_black ms2_link" type="submit" name="ms2_action" value="order/submit">
						отправить
					</button>
				</div>
			</div>
		</div>
	</section>
</form>

<script defer>
	const cartUtil = {
		price        : 0,
		deliveryPrice: 0,
		location     : "{$location.id}",
		deliveries   : {$deliveries|toJSON},
		minCartCost  : "{$_modx->config['minCartCost']}",
		delivery     : function(elem, name = '') {
			const delivery = cartUtil.deliveries[elem.value]
			if(name) {
				if(delivery.id === 7) {
					$('#delivery_pay .name').html(name + '<i class="red">**</i>')

					$('#tk-pay-msg').fadeIn()
				} else {
					$('#delivery_pay .name').html(name)

					$('#tk-pay-msg').fadeOut(0)
				}
			}
			const rules = delivery.description.split('\n')
			for(const rule in rules) {
				const r = rules[rule].split('|')
				const limit = parseInt(r[0])
				const price = parseInt(r[1])
				if(cartUtil.price > limit) {
					$('#delivery_pay .value').html(price ? price : 'Бесплатно')
					cartUtil.deliveryPrice = price
				}
			}
			// if(cartUtil.price >= delivery.weight_price) {
			// 	$('#delivery_pay .value').html('Бесплатно')
			// 	cartUtil.deliveryPrice = 0
			// } else {
			// 	$('#delivery_pay .value').html(number_format(delivery.price, 0, ',', ' ') + ' р.')
			// 	cartUtil.deliveryPrice = parseInt(delivery.price.replace(' ', ''))
			// }
		},
		init         : function() {
			this.location = parseInt(this.minCartCost)
			this.minCartCost = parseInt(this.location)
			cartUtil.price = parseInt($('.ms2_total_cost')[0].innerHTML.replace(' ', ''))
			cartUtil.costCheck()
			if(cartUtil.location === 1) {
				$('.moscow input').prop('checked', true)
			} else {
				$('.noMoscow input').prop('checked', true)
			}
			const render = () => {
				$('.cart_order_form_summ_line .value').text(number_format(cartUtil.price, 0, ',', ' '))
				cartUtil.delivery($('input[name=delivery]:checked')[0])
				$('.cart_order_form_summ_total .value').text(number_format(cartUtil.price + cartUtil.deliveryPrice, 0, ',', ' '))
				$('.cart_order_form_summ_total .rub,.cart_order_form_summ_line .rub').fadeIn()
				let delivery_pay = parseInt($('#delivery_pay .value').text().toLowerCase())
				if(isNaN(delivery_pay)) {
					delivery_pay = 0
				}
				if(!delivery_pay) {
					$('#delivery_pay .rub').fadeOut()
				} else {
					$('#delivery_pay .rub').fadeIn()
				}
			}
			render()
		},
		costCheck    : function() {
			const cost = cartUtil.price
			const btn = $('.cart_line_links button,.cart_order_form_send button')
			btn.attr('data-toggle', 'tooltip')
			if(cost < this.minCartCost) {
				btn.attr('title', 'Сумма заказа должна превышать ' + this.minCartCost)
				btn.attr('data-original-title', 'Сумма заказа должна превышать ' + this.minCartCost)
				btn.addClass('disabled')
			} else {
				btn.removeAttr('title')
				btn.removeAttr('data-original-title')
				btn.removeAttr('onclick')
				btn.removeClass('disabled')
			}
		}
	}

	function commentConcat() {
		var adress = $('#adress').val()
		var note = $('#note').val()
		$('#comment').val(adress + ', ' + note)
	}

	$(document).ready(function() {
		miniShop2.Callbacks.add('Cart.change.ajax.done', 'zzz', function() {
			cartUtil.init()
			return true
		})
		miniShop2.Callbacks.add('Cart.remove.ajax.done', 'zzz2', function() {
			cartUtil.init()
			return true
		})
		cartUtil.init()
	})
</script>