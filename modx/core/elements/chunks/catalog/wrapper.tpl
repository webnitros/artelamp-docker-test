<div class="listing_filters">
    <div class="listing_filters_units">
        <div class="listing_filters_units_headline">
            <p>Фильтры</p>
            <button class="btn the_close"></button>
        </div>


        {'!pdoResources'|snippet: [
            'element' => 'pdoResources',
            'cacheKey' => 'sidebar_category_menu',
            'cacheElementKey' => 'sidebar_category_menu',
            'cacheExpires' => 10600,
            'parents'=> 2,
            'depth'=>0,
            'limit'=>0,
            'tpl' => '@FILE chunks/catalog/menu/row.tpl',
            'tplWrapper' => '@FILE chunks/catalog/menu/outer.tpl',
            'sortby'=>'menuindex',
            'sortdir'=>'ASC',
            'useWeblinkUrl'=> 1,
            'select'=> '{ "modResource":"id, pagetitle, parent", "Children":"COUNT(Children.id) as count" }',
            'leftJoin' => '{ "Children":{"class":"modResource", "on":"modResource.id = Children.parent AND (Children.deleted != 1 AND Children.published = 1)"} }',
            'where'=>'{"class_key":"msCategory"}',
            'groupby'=>'modResource.id'
        ]}

        {* Для видов светильников меняются *}
        {var $parents = $_modx->resource.id}
        {if $parents != 2}
            {if $_modx->resource.parent != 2}
                {set $parents = $_modx->resource.parent}
            {/if}
            {'!pdoResources'|snippet: [
                'element' => 'pdoResources',
                'cacheKey' => 'sidebar_category_menu',
                'cacheElementKey' => 'sidebar_category_menu',
                'cacheExpires' => 10600,
                'parents'=> $parents,
                'depth'=>0,
                'limit'=>0,
                'tpl' => '@FILE chunks/catalog/menu/row.tpl',
                'tplWrapper' => '@FILE chunks/catalog/menu/outer_sub_category.tpl',
            'sortby'=>'menuindex',
                'sortdir'=>'ASC',
                'useWeblinkUrl'=> 1,
                'select'=> '{ "modResource":"id, pagetitle, parent", "Children":"COUNT(Children.id) as count" }',
                'leftJoin' => '{ "Children":{"class":"modResource", "on":"modResource.id = Children.parent AND (Children.deleted != 1 AND Children.published = 1)"} }',
                'where'=>'{"class_key":"msCategory"}',
                'groupby'=>'modResource.id'
            ]}
        {/if}
        <form action="[[~[[*id]]]]" method="post" id="mse2_filters">
            {$filters}
            <br/>
            <div class="clean_filters" style="left: -20px; width: 39px;">
                <div>
                    <button type="reset" class="btn btn-default hidden">Очистить все<span> фильтры</span></button>
                </div>
                <div>
                    <button class="btn btn_mob_val"><span>Показать </span><i id="mse2_total_mobile">{$total}</i> <i id="mse2_total_text_mobile">{'mse2_total'|placeholder | declension : 'товар|товара|товаров'}</i></button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="listing_content">
        <div class="listing_content_filter">
            <div class="el el0 top_filter_mobile_button">
                <button class="btn js_open_filter_mobile">
                    Фильтры
                </button>
            </div>
            <div class="el el1">
                <p class="lab">Сортировка</p>
                <div class="top_filter_menu_select">
                    {* TODO сортировка *}

                    <div style="display:none;">
                    <div id="mse2_sort" >
                        [[%mse2_sort]]
                        <a data-id="resource|menuindex:desc" href="#" data-sort="resource|menuindex" data-dir="[[+mse2_sort:is=`resource|menuindex:desc`:then=`desc`]]" data-default="desc" class="sort"><span></span></a>
                        <a data-id="ms|price:desc" href="#" data-sort="ms|price" data-dir="[[+mse2_sort:is=`ms|price:desc`:then=`desc`]]" data-default="desc" class="sort"><span></span></a>
                        <a data-id="ms|price:asc" href="#" data-sort="ms|price" data-dir="[[+mse2_sort:is=`ms|price:asc`:then=`asc`]]" data-default="asc" class="sort"><span></span></a>
                        <a data-id="resource|pagetitle:desc" href="#" data-sort="resource|pagetitle" data-dir="[[+mse2_sort:is=`resource|pagetitle:desc`:then=`desc`]]" data-default="desc" class="sort"><span></span></a>
                    </div>
                    </div>
                    <div class="option_select">
                        <select class="option_select_sort">
                            <option class="option" data-ic="opt1" data-sort="resource|menuindex" data-default="desc" value="resource|menuindex:desc" [[+mse2_sort:is=`resource|menuindex:desc`:then=`selected`]]>По умолчанию</option>
                            <option class="option" data-ic="opt1" data-sort="ms|price" data-default="asc" value="ms|price:asc" [[+mse2_sort:is=`ms|price:asc`:then=`selected`]]>Дешевле</option>
                            <option class="option" data-ic="opt2" data-sort="ms|price" data-default="desc" value="ms|price:desc" [[+mse2_sort:is=`ms|price:desc`:then=`selected`]]>Дороже</option>
                            <option class="option" data-ic="opt1" data-sort="resource|pagetitle" data-default="desc" value="resource|pagetitle:desc" [[+mse2_sort:is=`resource|pagetitle:desc`:then=`selected`]]>По наименованию</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="el el2 blk_total_limit">
                <p class="lab">
                    Количество
                </p>
                <select name="mse_limit" class="option_select_limit val_goods_select" id="mse2_limit">
                    <option value="12" [[+limit:is=`12`:then=`selected`]]>12</option>
                    <option value="24" [[+limit:is=`24`:then=`selected`]]>24</option>
                    <option value="48" [[+limit:is=`48`:then=`selected`]]>48</option>
                    <option value="96" [[+limit:is=`96`:then=`selected`]]>96</option>
                </select>
            </div>
        </div>
    <div class="listing_content_catalog">
        <div class="listing_content_catalog_units" id="mse2_results">
            {$results}
        </div>
        <div class="mse2_pagination listing_content_catalog_pager" id="mse2_pagination">
            [[!+page.nav]]
        </div>
        <div class="footer-content">
            {var $content = $_modx->getPlaceholder('_content')}
            {if empty($content)}
                {$_modx->resource.content}
            {else}
                {$content}
            {/if}
        </div>
        {include 'file:chunks/catalog/looked.tpl'}
    </div>
</div>
