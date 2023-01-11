{set $location = ''|getUserLocation}
<ul class="modal_city_links modal_city_links_default">
	<!-- приоритетные локации -->
	<!-- определяем локацию пользователя и получаем соседние локации -->
    {set $ids =[1,498817,2013348,1510853,473249,472757,1486209]}

    {foreach $ids as $id}
        {if $row = $locations[$id]}
			<li><a href="" class="userlocation-location-item {$location.id == $id? ' active':''}" data-userlocation-id="{$row.id}">{$row.name}</a></li>
        {else}
            {if $id == 498817}
                <li><a href="" class="userlocation-location-item" data-userlocation-id="498817">Санкт-Петербург</a></li>
            {/if}
        {/if}
    {/foreach}
</ul>