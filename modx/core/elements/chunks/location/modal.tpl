<div id="cities" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal"></button>
            <div class="modal-body">
                <div class="modal_title">
                    <i class="ic_title"></i>
                    <p class="the_text">выберите город</p>
                </div>
                <div class="modal_city">
                    <div class="modal_city_search">
                        <input type="text" placeholder="Ваш город" id="cities-query">
                        {*<button class="btn_reset"></button>*}
                    </div>
                    <div class="modal_city_links_auto">
                        {'UserLocation.location'|snippet:[
                        'typeSearch' => 'local',
                        'tpl' => '@FILE chunks/location/locations.tpl'
                        ]}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>