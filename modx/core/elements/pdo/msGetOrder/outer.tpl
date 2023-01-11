<section class="listing_title">
    <div class="jcont">
        {if $.get.pay == 'confirm'}
            <p class="title">Заказ успешно оплачен</p>
        {elseif $.get.pay == 'error'}
            <p class="title">Ошибка оплаты</p>
        {else}
            <p class="title">Заказ успешно оформлен</p>
        {/if}
    </div>
</section>
{var $st = 0}
{foreach $products as $product}
    {if $product.stock <= 2}
        {var $st = 1}
        {break}
    {/if}
{/foreach}
<section class="cart">
    <div class="jcont">
        <div class="cart_block">

            <div class="cart_ready">
                <div class="cart_ready_numb">
                    номер <span>вашего </span>заказа <b>{$order.num}</b>
                </div>
                {if !$.get.pay}
                    <div class="cart_ready_title">
                        {if !$address.street}
                            Ваш заказ принят в обработку. Вскоре с Вами свяжется менеджер для уточнения адреса доставки и других деталей.
                        {else}
                            Заказ успешно оформлен
                        {/if}
                    </div>
                    <div class="cart_ready_text">
                        <div>Данные по вашему заказу отправлены по почте.</div>
{*                        <div>Вы можете оплатить заказ сейчас <b>онлайн</b>, или сделать это позднее.</div>*}
                    </div>
                {elseif $.get.pay == 'confirm'}
                    <div class="cart_ready_title">
                        {if !$address.street}
                            Ваш заказ оплачен и принят в обработку. Вскоре с Вами свяжется менеджер для уточнения адреса доставки и других деталей.
                        {else}
                            Заказ успешно оформлен
                        {/if}
                    </div>
                    <div class="cart_ready_text">
                        <div>Данные по вашему заказу отправлены по почте.</div>
                    </div>
                {elseif $.get.pay == 'error'}
                    <div class="cart_ready_title">
                        {if $.get.msg}{$.get.msg}{else}Ошибка оплаты{/if}
                    </div>
                {/if}
                {if !$.get.pay}
                    {if $order.payment == 10}
                        <div class="cart_ready_pay">
                            <a href="{'rbsLink'|snippet:['id'=>$order.id]}" id="online_pay"
                               class='btn btn_black btn_mb20' {if $st}disabled{/if}>оплатить заказ онлайн
                            </a>
                            <div class="cart_ready_pay_check">
                                {if $st}
                                    <input type='checkbox'
                                           onchange="if($(this).prop('checked')){ $('#online_pay').removeAttr('disabled',1)}else{ $('#online_pay').attr('disabled',1)} "
                                           class='checkbox' id='c_pay'/>
                                    <label for='c_pay'>
                                        <span>Подтверждаю, что менеджер подтвердил и забронировал мой заказ</span>
                                    </label>
                                {/if}
                            </div>
                        </div>
                    {/if}
                {/if}
                <a href="" class="btn btn_white">
                    вернуться на главную страницу
                </a>

            </div>

        </div>
    </div>
</section>
<script>
	$(`a[disabled]`).on('click', function(event) {
		if($(this).attr('disabled')) {
			event.preventDefault()
		}
	})
</script>