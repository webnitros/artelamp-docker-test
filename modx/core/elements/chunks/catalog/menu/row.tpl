<a href="{$link}" class="el filter_category_link{$_modx->resource.id == $id? ' active' :''}{$_modx->resource.parent == $id? ' active' :''}">
	{var $v = $pagetitle|lower}
	{var $v = $v|trim}
	{var $v = $v|replace:'торшеры ':' '}
	<span class="el_option">{$v}</span>
</a>