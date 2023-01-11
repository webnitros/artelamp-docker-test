{* Избранное *}
<div id="liked" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal"></button>
            <div class="modal-body">
                <div class="modal_body_addcart">
                    <div class="good_marker modal_product_marker">
                        <span class="modal_product_marker_new the_marker_el the_marker_el_new">
                            <i>new</i>
                        </span>

                        <span class="modal_product_marker_sale the_marker_el the_marker_el_sale">
                            <i>sale</i>
                        </span>

                    </div>
                    <div class="the_content the_content_mobile">
                        <div class="modal_title">
                            <i class="ic_title"></i>
                            <p class="the_text">избранные товары</p>
                        </div>
                    </div>
                    <div class="imgwr">
                        <img loading=lazy class="modal_product_thumb" src="/inc/images/good1.png" alt="">
                    </div>
                    <div class="the_content">
                        <div class="modal_title">
                            <i class="ic_title"></i>
                            <p class="the_text">избранные товары</p>
                        </div>
                        <p class="tit modal_product_name"></p>
                        <p class="article">
                            <a href="" class="modal_product_article"></a>
                        </p>
                        <div class="the_df">
                            <div class="the_cost js_modal_price">
                                <del class="modal_product_sale"><span class="modal_product_old_price"></span> р.</del>
                                <p><span class="modal_product_price"></span> р.</p>
                            </div>
                        </div>


                        {* Корзина *}
                        <div class="modal_block modal_block_basket">
                            <div class="the_buttons the_buttons_df">
                                <button class="btn btn_white" data-dismiss="modal">Продолжить<span> покупки</span></button>
                                <a href="/basket/" class="btn btn_black">Оформить<span> заказ</span></a>
                            </div>
                        </div>

                        {* Избранное *}
                        <div class="modal_block modal_block_favorites">
                            <div class="the_buttons">
                                <a href="/favorites/" class="btn btn_black"><span>Посмотреть </span>список пожеланий</a>
                            </div>
                            <div class="godaddlike">
                                Изделие добавлено в список пожеланий
                            </div>
                        </div>

                        {* Сравнение *}
                        <div class="modal_block modal_block_comparison">
                            <div class="the_buttons">
                                <a href="/comparison/" class="btn btn_black"><span>Посмотреть </span>товары в сравнении</a>
                            </div>
                            <div class="godaddlike">
                                Изделие добавлено в список сравнений
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

