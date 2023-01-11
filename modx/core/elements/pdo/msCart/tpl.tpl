
{*

    <div class="forder-popup__goods " >
        <div id="msCart">
            <div id="dynamicmodal" id="[[+product.key]]">
                <div class="msoc_product_line" >
                    <div class="msoc_product_line_image">
                        <img src="[[+product.thumb]]" />
                        [[*id:is=`1`:then=``:else=``]]
                    </div>
                    <div class="msoc_product_line_pagetitle">
                        <span>[[+product.pagetitle]]</span>
                    </div>


                    <div class="msoc_product_line_count">
                        <div class="product__add-cart ">
                            <div class="text-right">
                                <span class="forder-popup__price">
                                    <span id="[[+selector]]_price" class="msoptionsprice-cost msoptionsprice-[[+product.id]]">[[+product.price]]</span> руб.
                                    [[+product.old_price:is=`0`:then=``:else=`<span  id="[[+selector]]_price_old" class="old_price msoptionsprice-old-cost msoptionsprice-[[+product.id]]">[[+product.old_price]]</span>  руб.`]]
                                </span>
                            </div>
                            <div class="text-right">
                                <input type="hidden" name="price" value="[[+product.price]]">
                                <input type="hidden" name="product_id" value="[[+product.id]]">
                                <div class="count-field input-group input-prepend">
                                    <span class="count-field-control count-field-control-down" onselectstart="return false" onmousedown="return false">+</span>
                                    <input value="[[+product.count]]" placeholder="0" type="text" autocomplete="off" name="count" class="count-field-input">
                                    <span class="count-field-control count-field-control-up" onselectstart="return false" onmousedown="return false">-</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="msoneclick_form" >
        <div class="forder-popup__block forder-popup__block--grey">

            <div class="msoneclick_form-group">
                <label for="msoc_city" class="msoneclick_form-label [[+city_required]]">[[%msoc_field_city]]</label>
                <div class="msoneclick_form-field">
                    <input type="text" value="[[!+order.city]]" name="city" tabindex="1" id="msoc_city" placeholder="[[%msoc_field_city_ple]]">
                </div>
            </div>

            <div class="msoneclick_form-group">
                <label for="msoc_addr_country" class="msoneclick_form-label [[+addr_country_required]]">[[%msoc_field_country]]</label>
                <div class="msoneclick_form-field">
                    <input type="text" value="[[!+order.addr_country]]" tabindex="2" name="addr_country" id="msoc_addr_country" placeholder="[[%msoc_field_country_ple]]">
                </div>
            </div>
        </div>
        <div class="forder-popup__block forder-popup__block--grey">
            <div class="msoneclick_form-group">
                <label for="msoc_receiver" class="msoneclick_form-label [[+receiver_required]]">[[%msoc_field_receiver]]</label>
                <div class="msoneclick_form-field">
                    <input type="text" value="[[!+order.receiver]]" tabindex="3" name="receiver" id="msoc_receiver" placeholder="[[%msoc_field_receiver_ple]]">
                </div>
            </div>
            <div class="msoneclick_form-group">
                <label for="msoc_phone" class="msoneclick_form-label [[+phone_required]]">[[%msoc_field_phone]]</label>
                <div class="msoneclick_form-field">
                    <input type="text" name="phone" value="[[!+order.phone]]" tabindex="5" autocomplete="off" id="msoc_phone" placeholder="[[%msoc_field_phone_ple]]">
                </div>
            </div>

            <div class="msoneclick_form-group">
                <label for="msoc_email" class="msoneclick_form-label [[+email_required]]">[[%msoc_field_email]]</label>
                <div class="msoneclick_form-field">
                    <input type="email" name="email" value="[[!+order.email]]" tabindex="6" id="msoc_email" placeholder="[[%msoc_field_email_ple]]">
                </div>
            </div>
        </div>

        <div class="forder-popup__block forder-popup__block--grey">
            <div class="msoneclick_form-group">
                <label for="msoc_comment" class="msoneclick_form-label">[[%msoc_field_comment]]</label>
                <div class="msoneclick_form-field">
                    <textarea autocomplete="off" placeholder="[[%msoc_field_comment_ple]]" tabindex="7" id="msoc_comment" name="comment">[[!+order.comment:default=``]]</textarea>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit"  name="msoc_send_from" class="mso_button btn_send">[[%msoc.button]]</button>
            <p class="msoc-muted">
                [[%msoc_form_footer_text]]
            </p>
        </div>
    </div>*}