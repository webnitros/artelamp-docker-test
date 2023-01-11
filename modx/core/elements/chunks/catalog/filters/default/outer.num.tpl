{var $key = $table ~ $delimeter ~ $filter}
<div class="unit" id="mse2_{$key}" data-table="{$table}" data-filter="{$filter}">
    <button type="button" class="btn_opener js_btn">
        {('mse2_filter_' ~ $table ~ '_' ~ $filter) | lexicon}
    </button>
    <div class="the_list the_list_scroll">
		<div class="the_list" style="display: block;">
			<div class="polzun_wrap">
				<div id="slider-value" class="js-range noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr" data-min="10" data-max="1000" data-minval="0" data-maxval="1800"><div class="noUi-base"><div class="noUi-connects"><div class="noUi-connect" style="transform: translate(0.555556%) scale(0.55, 1);"></div></div><div class="noUi-origin" style="transform: translate(-994.444%); z-index: 5;"><div class="noUi-handle noUi-handle-lower" data-handle="0" tabindex="0" role="slider" aria-orientation="horizontal" aria-valuemin="0.0" aria-valuemax="1000.0" aria-valuenow="10.0" aria-valuetext="10"><div class="noUi-touch-area"></div></div></div><div class="noUi-origin" style="transform: translate(-444.444%); z-index: 4;"><div class="noUi-handle noUi-handle-upper" data-handle="1" tabindex="0" role="slider" aria-orientation="horizontal" aria-valuemin="10.0" aria-valuemax="1800.0" aria-valuenow="1000.0" aria-valuetext="1000"><div class="noUi-touch-area"></div></div></div></div></div>
			</div>
			<div class="polzun_input">
				<div class="polzun_input_el">
					<span class="lab">от</span>
					<input type="text" id="input0">
				</div>
				<div class="polzun_input_el">
					<span class="lab">до</span>
					<input type="text" id="input1">
				</div>
			</div>
			<div class="el">
				<input type="checkbox" class="checkbox" id="ccost1">
				<label for="ccost1">
					<span>
						<img src="images/list_sale.png" alt="">
						Распродажа
					</span>
					<i>4327</i>
				</label>
			</div>
			<div class="el">
				<input type="checkbox" class="checkbox" id="ccost2">
				<label for="ccost2">
					<span>
						<img src="images/list_new.png" alt="">
						Новый
					</span>
					<i>4327</i>
				</label>
			</div>
			<div class="el">
				<input type="checkbox" class="checkbox" id="ccost3">
				<label for="ccost3">
					<span>В наличии</span>
					<i>4327</i>
				</label>
			</div>
		</div>
    </div>
</div>