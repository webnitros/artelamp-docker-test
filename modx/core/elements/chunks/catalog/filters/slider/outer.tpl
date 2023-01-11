{var $key = $table ~ $delimeter ~ $filter}
<div class="unit unit_filter_{$filter}" id="mse2_{$key}" data-table="{$table}" data-filter="{$filter}">
    <button type="button" class="btn_opener js_btn{$showFilter?' active' : ''}">
        {('mse2_filter_' ~ $table ~ '_' ~ $filter) | lexicon}
    </button>
    <div class="the_list" {$showFilter?' style="display: block"' : ''}>
        <fieldset id="mse2_[[+table]][[+delimeter]][[+filter]]">
            <div class="mse2_number_slider"></div>
            <div class="mse2_number_inputs polzun_input">
                [[+rows]]
            </div>
        </fieldset>
    </div>
</div>