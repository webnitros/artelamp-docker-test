<li class="{$attributes}">
    <a href="{$link}" {$menutitle=='Дизайнерам'? ' rel="nofollow" target="_blank"':''}>
        {if $attributes =='open ic_products'}
            <span class="main_menu__burger"></span>
        {/if}
        {if $menutitle =='Избранное'}
            {var $favorite_total = '!msfGetCount'| snippet}

                <span class="main_menu_marker val favorites-total favorites-default" {$favorite_total == 0?' style="display: none"':''}>{$favorite_total}</span>
           </span>
        {/if}
        {if $menutitle =='Сравнить'}
            {var $comparison = '!GetComparison'| snippet}
        <span class="comparison-default">
                <span class="main_menu_marker val comparison-total comparison-default" {$comparison['count'] == 0?' style="display: none"':''}>{$comparison['count']}</span>
            </span>
       {/if}
        {$menutitle}
    </a>
    {if $id == 12}
        {include 'file:chunks/common/megamenu/catalog.tpl'}
    {else}
        {$wrapper}
    {/if}
</li>