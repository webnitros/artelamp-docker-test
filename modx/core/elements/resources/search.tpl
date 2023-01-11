<section class="listing">
    <div class=" jcontnopad">
        <div class="dflex">
            <div class="listing_content">
                <div class="listing_content_catalog">
                    {if $.get['query']}
                    <div class="listing_content_catalog_units">
                        {var $resources = '!mSearch2' | snippet : ['returnIds' => true,'limit' => 0]}
                        {if !$resources}
                            <div class="col-md-12">По вашему запросу "<b>{$.get['query']}</b>" ничего не найдено</div>
                        {else}
                            {'!pdoPage' | snippet : [
                                'element' => 'msProducts',
                                'tpl' => '@FILE chunks/catalog/product/row.tpl',
                                'pageLimit' => 7,
                                'limit' => 18,
                                'parents' => 2,
                                'resources' => $resources,
                                'where' => [
                                'template' => 6
                                ],
                                'sortdir' => 'DESC',
                                'ajaxMode' => 'default',
                                'ajaxElemWrapper' => '#catalog',
                                'ajaxElemRows' => '#catalog > .row',
                                'ajaxElemPagination' => '#catalog .pagination',
                                'ajaxElemLink' => '#catalog .pagination a',
                                'tplPageLast' => '@INLINE ',
                                'tplPageLastEmpty' => '@INLINE ',
                                'tplPageFirst' => '@INLINE ',
                                'tplPageFirstEmpty' => '@INLINE ',
                            ]}
                        {/if}
                    </div>
                    [[!+pageCount:ne=`1`:then=`[[!+page.nav]]`]]
                        {else}
                        пустой запрос
                    {/if}
                </div>
            </div>
        </div>
    </div>
</section>
