{var $ids = ''|comparison_ids}
{if !$ids}
    <section class="cart">
        <div class="jcont">
            <div class="cart_block">
                <div class="cart_ready">
                    <div class="cart_ready_title">
                        Вы еще ничего не добавили в сравнение. Давайте это исправим
                    </div>
                </div>
                <div class="cart_empty">
                    <a href="/catalog/" class="btn btn_black btn_custom">
                        вернуться к покупкам
                    </a>
                </div>
            </div>
        </div>
    </section>
{else}
    <section class="listing">
        <div class=" jcontnopad">
            <div class="dflex">
                <div class="listing_content">
                    <div class="listing_content_catalog">
                        <div class="listing_content_catalog_units">
                            {'!msProducts' | snippet : [
                                'parents' => 2,
                                'includeThumbs' => 'medium',
                                'limit' => 10,
                                'resources' => $ids | splite :',',
                                'tpl' => '@FILE chunks/catalog/product/row.tpl'
                            ]}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
{/if}
