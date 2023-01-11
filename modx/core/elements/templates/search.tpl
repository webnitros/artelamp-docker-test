{extends 'file:templates/base.tpl'}
{block 'title'}{/block}
{block 'section'}
    <section class="search listing">
        <div class="jcont">
            {if $.get['query']}
                {var $resources = '!mSearch2' | snippet : ['returnIds' => true,'limit' => 0]}
                {var $total = 0}
                {if !empty($resources)}
                    {set $total = count($resources|split:',')}
                {/if}
            {/if}
            <div class="search_page_line">
                <form action="/search/" class="search_page_line_form">
                    <input type="text" name="query" value="{$.get['query']?:'пустой запрос'}">
                    <button class="btn"></button>
                </form>
                <div class="search_page_line_info">
                    Результаты поиска по запросу «<span class="quest">{$.get['query']?:'пустой запрос'}</span>»: найдено {$total} {$total| declension : 'товар|товара|товаров'}
                </div>
            </div>
            <div class="listing_content" >
                <div class="listing_content_catalog">
                    <div class="listing_content_catalog_units rows">
                        {if $resources}
                            {'!pdoPage' | snippet : [
                                'element' => 'msProducts',
                                'tpl' => '@FILE chunks/catalog/product/row.tpl',
                                'ajax' => false,
                                'pageLimit' => 7,
                                'limit' => 20,
                                'parents' => 2,
                                'resources' => $resources,
                                'where' => [
                                    'template' => 6,
                                    'published' => 1,
                                ],
                                'sortdir' => 'DESC',
                                'sortby' => 'ids',
                                'ajaxMode' => 'default',
                                'ajaxTplMore' => '@INLINE <div class="search_products_more_units"><button class="btn btn_black btn-more search_products_more_btn">Показать больше</button></div>',


                                'tplPageLast' => '@INLINE ',
                                'tplPageLastEmpty' => '@INLINE ',
                                'tplPageFirst' => '@INLINE ',
                                'tplPageFirstEmpty' => '@INLINE ',
                            ]}
                        {/if}
                    </div>
                </div>
                <div class="search_pagination">
                    [[!+pageCount:ne=`1`:then=`[[!+page.nav]]`]]
                </div>
            </div>
        </div>
    </section>
{/block}