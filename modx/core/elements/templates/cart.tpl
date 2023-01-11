{extends 'file:templates/base.tpl'}
{block 'section_title'}
    {'css/cart.css'|css}
    {if !$.get.msorder}
        {$modx->runSnippet('msCart',[
        'tpl' => '@FILE pdo/msCart/form.tpl'
        ])}
    {else}
        {$modx->runSnippet('msGetOrder', [
        'tpl' => '@FILE pdo/msGetOrder/outer.tpl'
        ])}
    {/if}
    <pre>
    {$modx->runSnippet('test')}
        </pre>
    {var $ids = '!looked' | snippet : ['ids' => 1]}
    {set $t =''|getCart}
    {if (!$t or !$t['total_count']) && $ids}
        <section class="card_lastlook">
            <div class="jcont">
                <div class="listing_content_catalog_lastlook">
                    <div class="the_title">
                        Последние просмотренные товары
                    </div>
                    <div class="lastlook_slider lastlook_slider2">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                {'!msProducts' | snippet : [
                                'parents' => 2,
                                'limit' => 10,
                                'sortdir' => 'DESC',
                                'resources' => $ids,
                                'where'=>["Data.in_stock:!="=>"0"],
                                'tpl' => '@FILE chunks/catalog/product/looked.tpl'
                                ]}
                            </div>
                            <div class="swiper-scrollbar"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    {/if}
{/block}
