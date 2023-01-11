{set $location = ''|getUserLocation}
{var $confirmed = $location.confirmed}
<li class="por">
    <button class="city js_open_city" data-toggle="modal" data-target="#cities">{$location.name}</button>
    <div class="popblock userlocation userlocation-location-confirm  {$location.confirmed?'':'unconfirmed'}">
        <button class="js_close_popblock"></button>
        <div class="the_content userlocation-location-confirm-popover">
            <p class="title ic_city">
                <span  class="the_city_label">ваш город:</span>
                <span class="the_city">{$location.name}</span>
            </p>
            <div class="the_buttons">
                <div class="el">
                    <button class="btn btn_black userlocation-location-item" data-userlocation-id="{$location.id}">Да</button>
                </div>
                <div class="el">
                    <button class="btn btn_white" data-toggle="modal" data-target="#cities">Выбрать другой город</button>
                </div>
            </div>
        </div>
    </div>
</li>