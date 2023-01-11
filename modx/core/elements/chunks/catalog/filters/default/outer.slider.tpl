{*<fieldset id="mse2_[[+table]][[+delimeter]][[+filter]]">
	<h4 class="filter_title">[[%mse2_filter_[[+table]]_[[+filter]]]]</h4>
	<div class="mse2_number_slider"></div>
	<div class="mse2_number_inputs">
		[[+rows]]
	</div>
</fieldset>*}

{var $key = $table ~ $delimeter ~ $filter}
<div class="unit" id="mse2_{$key}" data-table="{$table}" data-filter="{$filter}">
    <button type="button" class="btn_opener js_btn">
        {('mse2_filter_' ~ $table ~ '_' ~ $filter) | lexicon}
    </button>
    <div class="the_list" >
		<fieldset id="mse2_[[+table]][[+delimeter]][[+filter]]">
			<div class="mse2_number_slider"></div>
			<div class="mse2_number_inputs polzun_input">
				[[+rows]]
			</div>
		</fieldset>
    </div>
</div>

{*
<div class="mse2_number_inputs polzun_input">
    <div class="form-group col-md-6 polzun_input_el">
        <label for="mse2_ms|ploshad_osvesheniya_0">
            <span  class="lab">От</span>
            <input type="text" name="ms|ploshad_osvesheniya" id="mse2_ms|ploshad_osvesheniya_0" value="0" class="form-control input-sm" data-original-value="0" data-decimal="0">
        </label>
    </div>
    <div class="form-group col-md-6 polzun_input_el">
        <label for="mse2_ms|ploshad_osvesheniya_1">
            <span class="lab">от</span>
            <input type="text" name="ms|ploshad_osvesheniya" id="mse2_ms|ploshad_osvesheniya_1" value="50" class="form-control input-sm" data-original-value="50" data-decimal="0">
        </label>
    </div>
</div>
*}