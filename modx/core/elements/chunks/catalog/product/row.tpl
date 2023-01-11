{var $title = $pagetitle | strtrFenom : $article}
{var $isAjax = 'isAjax'|placeholder}
{if $medium}
    {set $thumb = $medium}
{/if}
<div class="unit product_modal_{$id} {!$in_stock?'unit_no_store':''}">
	<div class="element">
        {if ($new || $sale) && $in_stock}
			<div class="the_marker">
                {if $new}
					<span class="the_marker_el the_marker_el_new">
						<i>new</i>
					</span>
                {/if}
                {if $sale}
					<span class="the_marker_el the_marker_el_sale">
						<i>sale</i>
					</span>
                {/if}
			</div>
        {/if}
		<div class="imgwr">
			<a href="/{$uri}">
                {if $isBig == true}
                    {if $image?}
						<img loading=lazy src="{$image|cdn}?v=1" alt="{$pagetitle | striptags}"/>
                    {else}
                        {if $thumb?}
							<img loading=lazy src="{$thumb|cdn}?v=1" alt="{$pagetitle | striptags}"/>
                        {else}
							<img loading=lazy src="{'assets_url' | option}components/minishop2/img/web/ms2_big.png" alt="Нет фото"/>
                        {/if}
                    {/if}
                {else}
                    {if $thumb?}
						<img loading=lazy src="{$thumb|cdn}?v=1" alt="{$pagetitle | striptags}"/>
                    {else}
						<img loading=lazy src="{'assets_url' | option}components/minishop2/img/web/ms2_big.png" alt="Нет фото"/>
                    {/if}
                {/if}
				<div class="card-badges">
                    {if $isTV}
						<span class="card-badges__item card-badges__item_tv"></span>
                    {/if}
                    {if intval(strval($ip_class)[1]) >= 4}
						<span class="card-badges__item card-badges__item_water-drop"></span>
                    {/if}
				</div>
				<div class="lighting-area">
                    {if intval($ploshad_osvesheniya)}
                        {intval($ploshad_osvesheniya) | number_price} м
						<span style="font-family: Helvetica, Arial, sans-serif !important;">²</span>
                    {/if}
				</div>
			</a>
		</div>
		<div class="the_content">
			<p class="tit">
				<a href="/{$uri}">{$pagetitle | strtrFenom : $article}</a>
			</p>
			<p class="article">
                {if $file_is_3d_model}
					<a href="/{$uri}"><i class="icon-3d"></i></a>
                {/if}
				<a href="/{$uri}">{$article}</a>
			</p>
			<div class="d_flex">
				<div class="el el1">
                    {if $under_order}
						<div class="on_store">под заказ</div>
                    {else}
						<div class="on_store">В наличии: {$stock} шт.</div>
                    {/if}
				</div>
				<div class="el el2">
                    {var $favorite = 'msFavorites' | snippet : ['id' => $id]}
                    {var $comparison = '!AddComparison' | snippet : ['id' => $id]}
					<div class="like_buttons">
						<div class="comparison comparison-default {$comparison['added']?' added' :''}[[+can_compare]]" data-id="{$id}" data-list="default">
							<button class="btn_link btn_link_weight comparison-add comparison-link" data-text=""></button>
							<button class="btn_link btn_link_weight comparison-remove comparison-link" data-text=""></button>
						</div>
						<!--comparison_can_compare  can_compare-->
						<!--comparison_added  added-->

						<span class="favorites favorites-default {$favorite['added']}" data-id="{$id}">
                             <button class="favorites-add favorites-link btn_link btn_link_like " data-text=""></button>
                             <button class="favorites-remove favorites-link btn_link btn_link_like " data-text=""></button>
                        </span>
						<button class="btn_link btn_buy btn_link_add_to_cart"></button>
					</div>
				</div>
			</div>
			<div class="d_flex dflex_cost">
				<div class="el el1">
                    {if $in_stock}
						<div class="the_cost">
							<p data-price="{$price}">{$price} р.</p>
                            {if $old_price?}
								<del>{$old_price} р.</del>
                            {/if}
						</div>
                    {/if}
				</div>
				<div class="el el2">
					<form method="post" class="ms2_form">
						<input type="hidden" name="id" value="{$id}">
						<input type="hidden" name="article" value="{$article}">
						<input type="hidden" name="name" value="{$title}">
						<input type="hidden" name="price" value="{$price}">
						<input type="hidden" name="old_price" value="{$old_price}">
						<input type="hidden" name="sale" value="{$sale}">
						<input type="hidden" name="new" value="{$new}">
						<input type="hidden" name="url" value="/{$uri}">
						<input type="hidden" name="thumb" value="{$medium ? $medium : $thumb}">
						<input type="hidden" name="count" value="1">
                        {* {'msOneClick@artelamp'|snippet : [
							 'id' => $id,
						 ]}*}
                        {if $in_stock}
							<button class="btn_buy" type="submit" name="ms2_action" value="cart/add" data-toggle="modal" data-target="#one_click">Купить</button>
                        {else}
							<button class="btn_buy" type="button" name="ms2_action" value="cart/add">Купить</button>
                        {/if}
                        {* <button class="btn_buy" data-toggle="modal" data-target="#good_cart">Купить</button>*}
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
