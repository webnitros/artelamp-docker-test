<div class="unit">
    <div class="element">
        {if $new?}
            <div class="the_marker">
                <img src="images/list_new.png" alt="">
            </div>
        {/if}
        <div class="imgwr">
            {if $thumb?}
                <img loading=lazy src="{$medium}" alt="{$pagetitle | striptags}" />
            {else}
                <img loading=lazy src="{'assets_url' | option}components/minishop2/img/web/ms2_big.png" alt="Нет фото" />
            {/if}
        </div>
        <div class="the_content">
            <p class="tit">
                <a href="/{$uri}">{$pagetitle | strtrFenom : $article}</a>
            </p>
            <p class="article">
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
                    {var $favorite = 'msFavorites' | snippet : [
                        'list' => 'list',
                        'id' => $id
                    ]}



                    <div class="like_buttons">
                        {var $comparison = '!AddComparison' | snippet : [
                            'list_id' =>15
                            'id' => $id
                        ]}

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
                        {*<button class="btn_link btn_buy" data-toggle="modal" data-target="#good_cart"></button>*}
                    </div>
                </div>
            </div>
            <div class="d_flex dflex_cost">
                <div class="el el1">
                    <div class="the_cost">
                        <p>{$price} р.</p>
                        {if $old_price?}
                            <del>{$old_price} р.</del>
                        {/if}
                    </div>
                </div>
                <div class="el el2">
                    <form method="post" class="ms2_form">
                        <input type="hidden" name="id" value="{$id}">
                        <input type="hidden" name="count" value="1">
                        <button class="btn_buy" type="submit" name="ms2_action" value="cart/add">Купить</button>
                       {* <button class="btn_buy" data-toggle="modal" data-target="#good_cart">Купить</button>*}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
