<div class="userlocation">

    <!-- приоритетные локации -->
    {set $ids = ['7700000000000','7800000000000']}

    <!-- определяем локацию пользователя и получаем соседние локации -->
    {set $location = ''|detectUserLocation}
    {if $location}
        {set $tmp = '!UserLocation.location'|snippet:[
        'returnIds' => 1,
        'limit' => 10,
        'where' => ['parent:IN' => [$location.parent,$location.id],'OR:id:IN' => [$location.parent,$location.id]]
        ]|split}
        {if $tmp && count($tmp)}
            {set $ids = $tmp}
        {/if}
    {/if}

    {if $typeSearch =='remote'}
        <div>
            <input type="text" autofocus class="userlocation-location-search-input "
                   data-userlocation-mode="remote"
                   data-userlocation-template="<div class='userlocation-suggestion userlocation-location-item' data-userlocation-row='@row@' data-userlocation-id='@id@'>@name@</div>"
                   data-userlocation-value-field="name"
                   data-data-type="city"
                   placeholder="Поиск...">

            <div>
                {foreach $ids as $id}
                    {if $row = $locations[$id]}
                        <a href="" class="userlocation-location-item" data-userlocation-id="{$row.id}">{$row.name}</a>
                    {/if}
                {/foreach}
            </div>
        </div>
    {else}
        <div>
            <input type="text" class="userlocation-location-search-input " data-userlocation-mode="local" placeholder="Поиск...">

            <div>
                {foreach $ids as $id}
                    {if $row = $locations[$id]}
                        <a href="" class="userlocation-location-item" data-userlocation-id="{$row.id}">{$row.name}</a>
                    {/if}
                {/foreach}
            </div>
        </div>
        <div class="userlocation-location-items-scroll">
            <div class="userlocation-location-items">
                {if $letters}
                    <!-- по первым буквам локации -->
                    {foreach $letters as $letter => $ids}
                        <div class="userlocation-location-items-group">
                            <span class="userlocation-location-items-group-letter">{$letter}</span>
                            <ul>
                                {foreach $ids as $id}
                                    {if ($row = $locations[$id])}
                                        <li><a href="" class="userlocation-location-item" data-userlocation-id="{$row.id}" data-userlocation-name="{$row.name}">{$row.name}</a></li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </div>
                    {/foreach}
                {else}
                    <!--  -->
                    <div>
                        <ul>
                            {foreach $locations as $id => $row}
                                <li><a href="" class="userlocation-location-item" data-userlocation-id="{$row.id}" data-userlocation-name="{$row.name}">{$row.name}</a></li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}
            </div>
        </div>
    {/if}

</div>
