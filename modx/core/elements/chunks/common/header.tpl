<section class="header header_large-menu">
    <div class="jcont" style="border-top: 5px solid #000000;">
        <div class="header_top">
            <div class="header_logo">
                <a href="">
                    <img src="{$assets_source}images/logo.svg" alt="">
                </a>
            </div>
            <div class="header_search">
                <div class="header_search_controll">
                    <form action="/search.html">
                        <input type="text" placeholder="Поиск по каталогу" value="{$.get['query']}" name="query">
                        <button class="btn btn_submit"></button>
                        <button class="btn btn_close_search"></button>
                    </form>
                </div>
                <button class="btn js_btn_open"></button>
            </div>

            <div class="header_icons">
              {*  <div class="el">
                    <button href="" class="but but1"></button>
                </div>*}
                <div class="el">
                    <a href="" class="but but2"></a>
                </div>
                <div class="el">
                    <a href="" class="but but3"></a>
                    <div class="val">11</div>
                </div>
                <div class="el el_mob">
                    <button href="" class="but but4"></button>
                    <div class="val">11</div>
                </div>
                <div class="el el_mob el_desk_no">
                    <button href="" class="but but5 but_bars"></button>
                </div>
            </div>

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
                        'tplInner' => '@INLINE <div><ul class="{$classnames}">{$wrapper}</ul></div>',
                    ]}
                </div>
            </div>
        </div>
    </div>
    <div class="header_menu_point">
        <div class="ic"></div>
    </div>
</section>