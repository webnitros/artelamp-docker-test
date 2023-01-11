{*@formatter:off*}
<!-- Для тоглера используется стандартный элемент с классом card_characters_list-promo -->
<div class="card_characters_list card_characters_list-promo">
	<div class="card_characters_list_title">
		<button class="btn">
			<i class="ic"></i>
			<span>применить промокод</span>
		</button>
	</div>
	<div class="card_characters_list_content active  [ js-mspc2 ]">
		<div class="promocode [ js-mspc2-form ] {$is_active ? 'is-active' : ''}" >
			<h4 class="promocode__title">применен промокод</h4>
			<div class="promocode__form">
				<div class="promocode__wrap">
					<div class="promocode__body">
						<input class="promocode__value [ js-mspc2-input ]" {$is_active ? 'disabled="true"' : ''}  type="text" name="mspc2_code" value="{$coupon['code'] ?: ''}" placeholder="Промо-код">
						<span class="mspc2-message__info promocode__result promocode__result_accent [ js-mspc2-message-info ]">{$message_info}</span>
						<span class="mspc2-message__error promocode__result promocode__result_accent [ js-mspc2-message-error ]">{$message_error}</span>
						<span class="mspc2-message__success promocode__result promocode__result_accent [ js-mspc2-message-success ]">{$message_success}</span>
					</div>
				</div>
				<div class="promocode__controlleers ">
						<span class="promocode__benefit" {!$coupon['discount'] ? 'style="display: none;"' : ''}>- {$coupon['discount']}</span>
						<button class="btn_black promocode__button mspc2-form__button_submit [ js-mspc2-submit ]">Применить</button>
						<button class="btn_black promocode__button mspc2-form__button_cancel [  js-mspc2-cancel ]">Отменить</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(document).on('mspc2_unset', function(e, response) {
			console.log(e,response)
			$('.promocode__benefit').fadeOut(0);
		});
		$(document).on('mspc2_set', function(e, response) {
			console.log(e,response)
			$('.promocode__benefit').html('-'+response.data.coupon.discount).fadeIn(0)
		});
	});
</script>