<form class="ms2_form msoptionsprice-product msoc_mobile_version modal_body_addcart modal_body_addcart_fast" id="[[+formid]]" method="post" >
    <input type="hidden" name="method" value="[[+method]]">
    <input type="hidden" name="pageId" value="[[+pageId]]">
    <input type="hidden" name="ctx" value="[[+ctx]]">
    <input type="hidden" name="hash" value="[[+hash]]">
    <input type="hidden" name="payment" value="[[+payment]]">
    <input type="hidden" name="delivery" value="[[+delivery]]">
    <input type="hidden" name="id" value="[[+product.id]]"/>
    <input type="hidden" name="mssetincart_set" value="[[+product.id]]">
    <input type="hidden" name="key" class="key-product" value="">
    <input type="hidden" name="options" value="[]">
    <input type="hidden" name="count" value="1">
    <input type="hidden" name="price" value="[[+product.price]]">
    <input type="hidden" name="product_id" value="[[+product.id]]">


    {if $product.new || $product.sale}

        <div class="good_marker">
            {if $product.new}
                <span class="the_marker_el the_marker_el_new">
                    <i>new</i>
                </span>
            {/if}
            {if $product.sale}
                <span class="the_marker_el the_marker_el_sale">
                    <i>sale</i>
                </span>
            {/if}
        </div>
    {/if}




    <div class="the_content the_content_mobile">
        <div class="modal_title">
            <i class="ic_title"></i>
            <p class="the_text">Быстрый заказ</p>
        </div>
    </div>
    <div class="imgwr">
        <img loading=lazy src="{$product.thumb}" alt="">
    </div>
    <div class="the_content">
        <div class="modal_title">
            <i class="ic_title"></i>
            <p class="the_text">Быстрый заказ</p>
        </div>
        <p class="tit">{$product.pagetitle | strtrFenom : $product.article}</p>
        <p class="article">
            <a href="{$product.id|url}">{$product.article}</a>
        </p>
        <div class="the_df">
            <div class="the_cost">
                {if $product.old_price?}
                    <del>{$product.old_price} р.</del>
                {/if}
                <p>{$product.price} р.</p>
            </div>
        </div>





        <div class="modal_content">
            <div class="modal_forms">


                <div class="modal_forms_controll msoneclick_form-group">
                    <label for="msoc_receiver" class="msoneclick_form-label [[+receiver_required]]">
                        <span class="labstar">*</span> Ваше имя
                    </label>
                    <div class="msoneclick_form-field">
                        <input type="text" value="[[!+order.receiver]]" tabindex="3" name="receiver" id="msoc_receiver" placeholder="Иванов Иван Иванович">
                    </div>
                </div>


                <div class="modal_forms_controll msoneclick_form-group">
                    <label for="msoc_phone" class="msoneclick_form-label [[+phone_required]]">
                        <span class="labstar">*</span> Ваш телефон
                    </label>
                    <div class="msoneclick_form-field">
                        <input type="text" value="[[!+order.phone]]" tabindex="3" name="phone" id="msoc_phone" placeholder="Телефон">
                    </div>
                </div>


                <div class="modal_forms_controll mb20">
                    <label for="">
                        <span class="labstar">*</span>
                        Данные поля обязательны для заполнения
                    </label>
                </div>
                {* <div class="the_delivery">
                     <i class="ic"></i>
                     <p>
                         Cумма в корзине позволяет получить в Москве бесплатную доставку
                     </p>
                 </div>*}
            </div>
            <div class="the_buttons">
                <button type="submit" name="msoc_send_from" class="mso_button btn_send btn btn_black">Оформить заказ</button>
            </div>
            <div class="form_check one_click_confirmation">
                <input type="checkbox" name="confirmation" value="1" class="checkbox" id="msoc_confirmation" checked>
                <label for="msoc_confirmation">
                    Подтверждаю согласие на обработку своих персональных данных в соответствии с <a target="_blank" href="{1328|url}">Условиями</a>
                </label>
            </div>
        </div>
    </div>
</form>
