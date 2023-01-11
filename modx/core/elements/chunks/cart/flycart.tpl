{var $total_cost = $total.cost}
{var $total_count = $total.count}
{var $total_count_text = $total_count | declension : 'товар|товара|товаров'}
{if !$total_count}
    {set $total_count = 0}
{/if}
{if !$total_cost}
    {set $total_cost = 0}
{/if}
{if $products && count($products) > 0}
    <div class="jheader_popup_cart" >
        <div class="jheader_popup_scroll" id="msCart">
            {foreach $products as $product}
                {var $true_price = $product.id|true_price|float}
                <div id="{$product.key}" data-product="{$product.id}"
                     class="flyCart-item jheader_cart {if !$product.stock}cart_line_nostore{/if} {if $true_price != $product.price|float}jheader_cart_newcost{/if}">
                    <div class='jheader_cart_wrap'>
                        <a href="{$product.id | url}" class='jheader_cart_img'>
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
                        </a>
                        <div class='jheader_cart_content'>
                            {if $product.id?}
                                <a href="{$product.id | url}" class='jheader_cart_title'>{$product.pagetitle}</a>
                            {else}
                                {$product.name}
                            {/if}
                        </div>
                        <div class="jheader_buttons">
                            {var $favorite = 'msFavorites' | snippet : [
                            'list' => 'list',
                            'id' => $product.id
                            ]}
                            <span class="favorites favorites-default {$favorite['added']}" data-id="{$product.id}">
                             <button class="favorites-add favorites-link btn_link btn_link_like btn btn_like"
                                     data-text=""></button>
                             <button class="favorites-remove favorites-link btn_link btn_link_like btn btn_like"
                                     data-text=""></button>
                             </span>
                            <form method="post" class="ms2_form">
                                <input type="hidden" name="key" value="{$product.key}">
                                <button class="btn btn_reset" type="submit" name="ms2_action"
                                        value="cart/remove" onclick="flyCart.block()"></button>
                            </form>
                        </div>
                    </div>
                    <div class="jheader_results">
                        <div class="jheader_results_line">
                            <div class="name">
                                <span>СТОИМОСТЬ</span> ЗА 1 ШТ.
                            </div>
                            <div class="value">
                                {if $true_price != $product.price|float}
                                    <del>{$true_price} р.</del>
                                    <b>{$product.price} р.</b>
                                {else}
                                    {if $product.old_price}
                                        <del>{$product.old_price} р.</del>
                                    {/if}
                                    <b>{$product.price} р.</b>
                                {/if}
                            </div>
                        </div>
                        <div class="jheader_results_line">
                            <div class="name">
                                КОЛ-ВО
                            </div>
                            <div class="value">
                                <form method="post" class="ms2_form cartcalc" role="form">
                                    <input type="hidden" name="max_count" value="{$product.stock}"/>
                                    <input type="hidden" name="key" value="{$product.key}"/>
                                    <button style="display: none" type="submit" name="ms2_action"
                                            value="cart/change"></button>
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
                        </div>
                        <div class="jheader_results_line">
                            <div class="name">
                                ОБЩАЯ ЦЕНА
                            </div>
                            <div class="value">
                                <b><span class="total_product_one">{$product|sum_price}</span> р.</b>
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}

            {*            <div class="jheader_cart jheader_cart_newcost">*}
            {*                <div class='jheader_cart_wrap'>*}
            {*                    <a href="" class='jheader_cart_img'>*}
            {*                        <div class="the_marker">*}
            {*										<span class="the_marker_el the_marker_el_new">*}
            {*											<i>new</i>*}
            {*										</span>*}
            {*                            <span class="the_marker_el the_marker_el_sale">*}
            {*											<i>sale</i>*}
            {*										</span>*}
            {*                        </div>*}
            {*                        <img src="images/good1.png" alt="">*}
            {*                    </a>*}
            {*                    <div class='jheader_cart_content'>*}
            {*                        <a href="" class='jheader_cart_title'>*}
            {*                            Люстра Arte Lamp Rosaria*}
            {*                            если будет длинное название*}
            {*                            по ховеру подчеркивание*}
            {*                            выделение серым*}
            {*                        </a>*}
            {*                    </div>*}
            {*                    <div class="jheader_buttons">*}
            {*                        <button class="btn btn_like"></button>*}
            {*                        <button class="btn btn_reset"></button>*}
            {*                    </div>*}
            {*                </div>*}
            {*                <div class="jheader_results">*}
            {*                    <div class="jheader_results_change">*}
            {*                        <p>ЦЕНА ТОВАРА ИЗМЕНИЛАСЬ</p>*}
            {*                        <button class='btn'>понятно</button>*}
            {*                    </div>*}
            {*                    <div class="jheader_results_line">*}
            {*                        <div class="name">*}
            {*                            <span>СТОИМОСТЬ</span> ЗА 1 ШТ.*}
            {*                        </div>*}
            {*                        <div class="value">*}
            {*                            <del>1 458 120 р.</del>*}
            {*                            <b>1 454 120 р.</b>*}
            {*                        </div>*}
            {*                    </div>*}
            {*                    <div class="jheader_results_line">*}
            {*                        <div class="name">*}
            {*                            КОЛ-ВО*}
            {*                        </div>*}
            {*                        <div class="value">*}
            {*                            <div class='cartcalc'>*}
            {*                                <button class='ccalc-minus'></button>*}
            {*                                <input type='text' value='1'>*}
            {*                                <button class='ccalc-plus'></button>*}
            {*                            </div>*}
            {*                        </div>*}
            {*                    </div>*}
            {*                    <div class="jheader_results_line">*}
            {*                        <div class="name">*}
            {*                            ОБЩАЯ ЦЕНА*}
            {*                        </div>*}
            {*                        <div class="value">*}
            {*                            <b>1 454 120 р.</b>*}
            {*                        </div>*}
            {*                    </div>*}
            {*                </div>*}
            {*            </div>*}

            {*            <div class="jheader_cart jheader_cart_nostore">*}
            {*                <div class='jheader_cart_wrap'>*}
            {*                    <a href="" class='jheader_cart_img'>*}
            {*                        <div class="the_marker">*}
            {*										<span class="the_marker_el the_marker_el_new">*}
            {*											<i>new</i>*}
            {*										</span>*}
            {*                            <span class="the_marker_el the_marker_el_sale">*}
            {*											<i>sale</i>*}
            {*										</span>*}
            {*                        </div>*}
            {*                        <img src="images/good1.png" alt="">*}
            {*                    </a>*}
            {*                    <div class='jheader_cart_content'>*}
            {*                        <a href="" class='jheader_cart_title'>*}
            {*                            Люстра Arte Lamp Rosaria*}
            {*                            если будет длинное название*}
            {*                            по ховеру подчеркивание*}
            {*                            выделение серым*}
            {*                        </a>*}
            {*                    </div>*}
            {*                    <div class="jheader_buttons">*}
            {*                        <button class="btn btn_like"></button>*}
            {*                        <button class="btn btn_reset"></button>*}
            {*                    </div>*}
            {*                </div>*}
            {*                <div class="jheader_results">*}
            {*                    <div class="jheader_results_change">*}
            {*                        <p>ТОВАРА НЕТ В НАЛИЧИИ</p>*}
            {*                        <button class='btn'>понятно</button>*}
            {*                    </div>*}
            {*                    <div class="jheader_results_line">*}
            {*                        <div class="name">*}
            {*                            <span>СТОИМОСТЬ</span> ЗА 1 ШТ.*}
            {*                        </div>*}
            {*                        <div class="value">*}
            {*                            <del>1 458 120 р.</del>*}
            {*                            <b>1 454 120 р.</b>*}
            {*                        </div>*}
            {*                    </div>*}
            {*                    <div class="jheader_results_line">*}
            {*                        <div class="name">*}
            {*                            КОЛ-ВО*}
            {*                        </div>*}
            {*                        <div class="value">*}
            {*                            <div class='cartcalc'>*}
            {*                                <button class='ccalc-minus'></button>*}
            {*                                <input type='text' value='1'>*}
            {*                                <button class='ccalc-plus'></button>*}
            {*                            </div>*}
            {*                        </div>*}
            {*                    </div>*}
            {*                    <div class="jheader_results_line">*}
            {*                        <div class="name">*}
            {*                            ОБЩАЯ ЦЕНА*}
            {*                        </div>*}
            {*                        <div class="value">*}
            {*                            <b>1 454 120 р.</b>*}
            {*                        </div>*}
            {*                    </div>*}
            {*                </div>*}
            {*            </div>*}

        </div>

        <div class="jheader_popup_butlinks">
            <a href='/basket' class='btn btn_white'><span>перейти </span>в корзину</a>
            {*            <button class='btn btn_black'>*}
            {*                купить в 1 клик*}
            {*            </button>*}
        </div>
    </div>
{/if}
{'js/flyCart.js'|script}

