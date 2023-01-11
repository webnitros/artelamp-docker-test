{* кажое чтное*}
{if $idx % 2 !== 0}
    {$idx}
    </div>
    <div class="swiper-slide">
{else}
    </div>
    <div class="swiper-slide">
        {$idx}
{/if}


{*
{if $idx == 7 || $idx == 14 || $idx == 21 || $idx == 27}
    <div class="swiper-slide{$idx == 7?' unit_double':''}">
        {include 'file:chunks/catalog/product/row.tpl'}
    </div>
{/if}*}
