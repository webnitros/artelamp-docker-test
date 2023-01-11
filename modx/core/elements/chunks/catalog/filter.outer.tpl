<div class="listing_filters">
    <div class="listing_filters_units">
        <div class="listing_filters_units_headline">
            <p>
                Фильтры
            </p>
            <div class="option_select2">
                <select name="" id="" class="">
                    <option value="" data-ic="opt1">Дешевле</option>
                    <option value="" data-ic="opt2">Дороже</option>
                    <option value="" data-ic="opt1">По популярности</option>
                    <option value="" data-ic="opt2">По наименованию</option>
                </select>
            </div>
            <button class="btn the_close"></button>
        </div>
        <div class="unit unit_open">
            <button class="btn_opener js_btn active">Тип светильника</button>
            <div class="the_list" data-open="y">
                {'!pdoResources'|snippet: [
                    'element' => 'pdoResources',
                    'cacheKey' => 'sidebar_category_menu',
                    'cacheElementKey' => 'sidebar_category_menu',
                    'cacheExpires' => 10600,
                    'parents'=>2,
                    'depth'=>0,
                    'limit'=>0,
                    'tpl' => '@FILE chunks/catalog/menu/row.tpl',
                    'sortby'=>'id',
                    'sortdir'=>'ASC',
                    'useWeblinkUrl'=> 1,
                    'select'=> '{ "modResource":"id, pagetitle", "Children":"COUNT(Children.id) as count" }',
                    'leftJoin' => '{ "Children":{"class":"modResource", "on":"modResource.id = Children.parent AND (Children.deleted != 1 AND Children.published = 1)"} }',
                    'where'=>'{"parent":2}',
                    'groupby'=>'modResource.id'
                ]}
            </div>
        </div>
        <!--
        <div class="unit unit_open">
            <button class="btn_opener js_btn active">Вид светильника</button>
            <div class="the_list" data-open="y">

            </div>
        </div>
        -->
        <form action="[[~[[*id]]]]" method="post" id="mse2_filters">
            {$filters}
            <br/>
            [[+filters:isnot=``:then=`
                <div class="clean_filters hidden" type="reset">
                    <button type="reset" class="btn btn-default hidden">Очистить все фильтры</button>
                </div>
            `]]
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
                    
                    <div id="mse2_sort"  style="display:none;">
                        [[%mse2_sort]]
                        <a data-id="ms|price:desc" href="#" data-sort="ms|price" data-dir="[[+mse2_sort:is=`price|price:desc`:then=`desc`]]" data-default="desc" class="sort"><span></span></a>
                        <a data-id="ms|price:asc" href="#" data-sort="ms|price" data-dir="[[+mse2_sort:is=`price|price:desc`:then=`asc`]]" data-default="asc" class="sort"><span></span></a>
                        <a data-id="resource|pagetitle:desc" href="#" data-sort="resource|pagetitle" data-dir="[[+mse2_sort:is=`resource|pagetitle:desc`:then=`desc`]]" data-default="desc" class="sort"><span></span></a>
                    </div>
                  
                    <div class="option_select">
                        <select class="option_select_sort">
                            <option class="option" data-ic="opt1" data-sort="ms|price" data-default="desc" value="ms|price:desc">Дешевле</option>
                            <option class="option" data-ic="opt2" data-sort="ms|price" data-default="asc" value="ms|price:asc">Дороже</option>
                            <option class="option" data-ic="opt1" data-sort="resource|pagetitle" data-default="desc" value="resource|pagetitle:desc">По наименованию</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="el el2">
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
            {*{include 'file:chunks/catalog/looked.tpl'}*}
        </div>
    </div>
