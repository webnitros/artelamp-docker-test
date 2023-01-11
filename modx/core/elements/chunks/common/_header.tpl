<section class="header header_large-menu header_points">
    <div class="jcont" style="border-top: 5px solid #000000;">
        <div class="header_top">
            <div class="header_logo">
                <a href="">
                    <img src="{$assets_source}images/logo.svg" alt="">
                </a>
            </div>


            <div class="header_search_mobile_opener">
                <button class="btn"></button>
            </div>
            <div class="header_search ">
                <div class="header_search_mob_title">
                    <div class="header_search_mob_title_headline">
                        Поиск
                    </div>
                    <div class="header_search_mob_title_close">
                        <button class="btn"></button>
                    </div>
                </div>
                <div class="header_search_controll">
                    <form action="/search/">
                        <input id="searchQuery" type="text" placeholder="Поиск по каталогу" value="{$.get['query']}"
                               name="query">
                        <button class="btn btn_submit"><span class="mob_only">Найти</span></button>
                        <button class="btn btn_close_search"></button>
                    </form>
                </div>
                <button class="btn js_btn_open"></button>
                {include 'file:chunks/common/_autocomplete.tpl'}
            </div>

            <div class="header_icons">
                {*  <div class="el">
                      <button href="" class="but but1"></button>
                  </div>*}
                <div class="el comparison-default">
                    {var $comparison = '!GetComparison'| snippet}
                    <a href="/comparison/" class="but but2"></a>
                    <div class="val comparison-total comparison-default" {$comparison['count'] == 0?' style="display: none"':''}>{$comparison['count']}</div>
                </div>
                <div class="el">
                    {var $favorite_total = '!msfGetCount'| snippet}
                    <a href="/favorites/" class="but but3"></a>
                    <div class="val favorites-total favorites-default" {$favorite_total == 0?' style="display: none"':''}>{$favorite_total}</div>
                </div>
                {'!msMiniCart'| snippet : [
                'tpl' => '@FILE chunks/cart/mini_cart.tpl'
                ]}
                <div class="el el_mob el_desk_no">
                    <button href="" class="but but5 but_bars"></button>
                </div>
                {if $_modx->resource.id != 3}
                    <div id="flyCart_block">
                        {$modx->runSnippet('msCart',[
                        'tpl' => '@FILE chunks/cart/flycart.tpl'
                        ])}
                    </div>
                {/if}
            </div>
            {include 'file:chunks/common/_moblie_menu.tpl'}
            <div class="header_menu">
                <div class="main_menu">
                    {'pdoMenu11' | cache : [
                    'cacheSnippet'=>'pdoMenu',
                    'cacheExpire'=>600,
                    'element' => 'pdoMenu',
                    'cacheKey' => 'menu_page',
                    'cacheElementKey' => 'menu_page',
                    'cacheExpires' => 0,
                    'parents' => 11,
                    'level' => 1,
                    'namespace' => 'header',
                    'hereClass' => '',
                    'tpl' => '@FILE chunks/common/megamenu/row.tpl',
                    'parentClass' => '',
                    'tplOuter' => '@FILE chunks/common/megamenu/outer.tpl',
                    'tplInner' => '@INLINE <div><ul class="{$classnames}">{$wrapper}</ul></div>',
                    ]}
                    {include 'file:chunks/location/location.tpl'}
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header_menu_point">
        <div class="ic"></div>
    </div>
</section>