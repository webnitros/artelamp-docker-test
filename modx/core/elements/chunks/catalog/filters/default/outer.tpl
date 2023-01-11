{var $key = $table ~ $delimeter ~ $filter}
<div class="unit" id="mse2_{$key}" data-table="{$table}" data-filter="{$filter}">
    <button type="button" class="btn_opener js_btn">
        {('mse2_filter_' ~ $table ~ '_' ~ $filter) | lexicon}
    </button>
    <div class="the_list the_list_scroll">
    	{*<div class="list_search">
			<input type="text" placeholder="Я ищу...">
		</div>*}
        {$rows}
    </div>
</div>