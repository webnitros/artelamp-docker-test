{var $ids = '!looked' | snippet : ['ids' => 1]}
{if $ids}
    <div class="listing_content_catalog_lastlook">
        <div class="the_title">
            Последние просмотренные товары
        </div>
        <div class="lastlook_slider lastlook_slider1">
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
                {var $count = $ids | split : ','}
                {if $count &&  count($count) > 3}
                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                {/if}
            </div>
        </div>
    </div>
{/if}