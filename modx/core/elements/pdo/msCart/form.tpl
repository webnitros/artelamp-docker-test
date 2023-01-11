{var $total_cost = $total.cost}
{var $total_count = $total.count}
{var $total_count_text = $total_count | declension : 'товар|товара|товаров'}
{if !$total_count}
    {set $total_count = 0}
{/if}
{if !$total_cost}
    {set $total_cost = 0}
{/if}
{set $mspc2 ='!msPromoCode2' | snippet :[
'tpl'=>'@FILE chunks/msPromoCode2.form.tpl'
]}
<section class="listing_title">
	<div class="jcont">
		<p class="title">
			Корзина
			<span>
				<span>В корзине </span>
				<i class="ms2_total_count">{$total_count}</i>
				<i class="ms2_total_count_text">{$total_count_text}</i>
				<span> на сумму </span>
				<i class="ms2_total_cost">{$total_cost}</i> р.
			</span>
		</p>
	</div>
</section>

{if $products && !count($products)}
	<section class="cart" id="msCart">
		<div class="jcont">
			<div class="cart_block">
				<div class="cart_ready">
					<div class="cart_ready_title">
						В вашей корзине пока нет товаров. Давайте это исправим
					</div>
				</div>
				<div class="cart_empty">
					<a href="/catalog/" class="btn btn_black">
						вернуться к покупкам
					</a>
				</div>
			</div>
		</div>
	</section>
{else}
	<section class="cart" id="msCart">
		<div class="jcont">
			<div class="cart_block">
				<div class="cart_title">
					<div class="cart_title_element el1">ПРОДУКТ</div>
					<div class="cart_title_element el2">НАЛИЧИЕ</div>
					<div class="cart_title_element el3">МОДЕЛЬ</div>
					<div class="cart_title_element el4">КОЛ-ВО</div>
					<div class="cart_title_element el5">СТОИМОСТЬ <span> ЗА 1 ШТ.</span></div>
					<div class="cart_title_element el6">ОБЩАЯ СТОИМОСТЬ</div>
					<div class="cart-title_element el7">
						<div class="cart_title_element_buttons">
							<span class="btn_img btn_img_or">
								<img src="/inc/images/btn_link_like.svg" alt="">
							</span>
							<span class="btn_img">
								<img src="/inc/images/modclose.svg" alt="">
							</span>
						</div>
					</div>
				</div>

                {foreach $products as $product}
                    {var $true_price = $product.id|true_price|float}
					<div id="{$product.key}" class="cart_line {if !$product.stock}cart_line_nostore{/if}" data-mspc2-id="{$product | mspc2CartKey}">
						<div class="cart_line_product el1">
							<a href="{$product.id | url}">
								<div class="cart_line_product_imgwr">
                                    {if $product.new || $product.sale}
										<div class="the_marker">
                                            {if $product.new}
												<span class="the_marker_el the_marker_el_new">
                                            <i>new</i>
                                        </span>
                                            {/if}
                                            {if $product.sale}
												<span class="the_marker_el the_marker_el_sale">
                                            <i>sale</i>
                                        </span>
                                            {/if}
										</div>
                                    {/if}
									<img src="{$product.thumb}" alt="">
								</div>
							</a>
							<div class="cart_line_product_content">
                                {if $product.id}
									<a href="{$product.id | url}">{$product.pagetitle}</a>
                                {else}
                                    {$product.name}
                                {/if}
                                {if !$product.stock}
									<div class="cart_line_product_nostore block">
										<p class="cart_line_product_nostore_title">
											Товара нет в наличии
										</p>
									</div>
                                {/if}
                                {if $product.count|float > $product.stock|float}
									<div class="cart_line_product_nostore block">
										<p class="cart_line_product_nostore_title">
											Не достаточно товаров на складе
										</p>
										<button class="btn" onclick="cart_block(this,'stock', {$product.stock})">
											уменьшить до {$product.stock}
										</button>
									</div>
                                {/if}
                                {if $true_price != $product.price|float}
									<div class="cart_line_product_nostore block">
										<p class="cart_line_product_nostore_title">
											Цена товара изменилась
										</p>
										<button class="btn" onclick="cart_block(this,'price')">
											понятно
										</button>
									</div>
                                {/if}
							</div>
						</div>
						<div class="cart_line_presence el2">
							<div class="lab_mobile">НАЛИЧИЕ</div>
                            {if $product.under_order}
								<div class="value">под заказ</div>
                            {else}
								<div class="value">{$product.stock} шт.</div>
                            {/if}
						</div>
						<div class="cart_line_article el3">
							<div class="value">
								<a href="{$product.id | url}">{$product.article}</a>
							</div>
						</div>
						<div class="cart_line_calc el4">
							<div class="lab_mobile">КОЛ-ВО</div>
							<form method="post" class="ms2_form cartcalc" role="form">
								<input type="hidden" name="max_count" value="{$product.stock}"/>
								<input type="hidden" name="key" value="{$product.key}"/>
								<button
										style="display: none" type="submit" name="ms2_action"
										value="cart/change"
								></button>
								<button class="calcbtn ccalc-minus">—</button>
								<input type="text" name="count" value="{$product.count}"/>
								<button class="calcbtn ccalc-plus">+</button>
                                {if $product.stock > 0 and $product.stock < $product.count}
									<div class="cartcalc_error"><span>Вы уже указали максимальное количество по наличию на складе</span>
										<button class="btn_close"></button>
									</div>
                                {/if}
							</form>
						</div>
						<div class="cart_line_cost el5">
							<div class="lab_mobile"><i>ЗА 1 ШТ.</i></div>
							<div class="cart_line_cost_wrap  [ js-mspc2-cart-product-prices ]">
                                {if $true_price != $product.price|float}
									<span><span class="cart_line_cost_wrap_price">{$product.price}</span> {'ms2_frontend_currency' | lexicon}</span>
									<del><span class="cart_line_cost_wrap_del_price">{$true_price}</span> {'ms2_frontend_currency' | lexicon}</del>
                                {else}
									<span><span class="cart_line_cost_wrap_price">{$product.price}</span> {'ms2_frontend_currency' | lexicon}</span>
                                    {if $product.old_price}
										<del><span class="cart_line_cost_wrap_del_price">{$product.old_price}</span> {'ms2_frontend_currency' | lexicon}</del>
                                    {/if}
                                {/if}
							</div>
						</div>
						<div class="cart_line_cost el6">
							<div class="lab_mobile">ОБЩАЯ ЦЕНА</div>
							<div class="cart_line_cost_wrap [ js-mspc2-cart-product-prices ]">
								<b>
									<span class="total_product_one">
										<span class="total_product_one_sum_price">{$product|sum_price}</span> {'ms2_frontend_currency' | lexicon}
									</span>
								</b>
							</div>
						</div>
						<div class="cart_line_buttons el7">
                            {var $favorite = 'msFavorites' | snippet : [
                            'list' => 'list',
                            'id' => $product.id
                            ]}
							<span class="favorites favorites-default {$favorite['added']}" data-id="{$product.id}">
                             <button class="favorites-add favorites-link btn_link btn_link_like btn btn_like" data-text=""></button>
                             <button class="favorites-remove favorites-link btn_link btn_link_like btn btn_like" data-text=""></button>
                             </span>


							<form method="post" class="ms2_form">
								<input type="hidden" name="key" value="{$product.key}">
								<button class="btn btn_reset" type="submit" name="ms2_action" value="cart/remove" onclick="cart_block()" ></button>
							</form>

						</div>
					</div>
                {/foreach}
                {$mspc2}

				<div class="cart_line_total_wrap">
					<div class="cart_line_total">
						<span class="cart_line_total_name">ИТОГО</span>
						<span class="cart_line_total_value "><span class="ms2_total_cost">{$total_cost}</span> {'ms2_frontend_currency' | lexicon}</span>
					</div>
				</div>


				<div class="cart_line_links">
					<a href="/catalog/" class="btn btn_white">продолжить<span> покупки</span></a>
					<button class="btn btn_black js_btn_open_form">
						ОФОРМИТЬ<span> ЗАКАЗ</span>
					</button>
				</div>

			</div>
		</div>
	</section>
	<div class="cart_order ">
		<section class="listing_title">
			<div class="jcont">
				<p class="title">
					Подтвердите отправку заказа
					<span>В корзине
						<i class="ms2_total_count">{$total_count}</i>
						<i class="ms2_total_count_text">{$total_count_text}</i> на сумму
						<i class="ms2_total_cost">{$total_cost}</i> р.
					</span>
				</p>
			</div>
		</section>
        {$modx->runSnippet('msOrder',[
        'tpl' => '@FILE pdo/msOrder/form.tpl'
        ])}
	</div>
{/if}
<script>
	cart_block()

	function cart_block(elem = false, type = '', data = null) {
		if(elem) {
			$(elem).parent('.cart_line_product_nostore').removeClass('block').fadeOut()
		}
		if(type == 'stock') {
			var form = $(elem).parents('div.cart_line').find('form.ms2_form.cartcalc')
			$(form).find('input[name="count"]').val(data)
			$(form).find('button[name="ms2_action"]').click()
		}
		var a = $('.cart_line_product_nostore.block')
		if(a.length == 0) {
			$('button.js_btn_open_form').removeAttr('disabled', 'true')
		} else {
			$('button.js_btn_open_form').attr('disabled', 'true')

		}

	}

	$(document).ready(function() {
		miniShop2.Callbacks.add('Cart.remove.ajax.done', 'zzz', function() {
			cart_block()
		})
	})
</script>