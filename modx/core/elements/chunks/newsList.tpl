{'pdoPage'|snippet:[
'parents'=>2549,
'sortby'=>'menuindex',
'sortdir'=>'DESC',
'useWeblinkUrl'=>1,
'ajax' => '1',
'limit'=>3,
'includeTVs'=>'news_img',
'ajaxMode'=>'button',
'tplPage'=>'@INLINE <li class="page-item"><a href="[[+href]]">[[+page]]</a></li>',
'tplPageLast'=>'@INLINE <li class="page-item page-item-last"><a href="[[+href]]"></a></li>',
'tplPageLastEmpty'=>'@INLINE <li class="page-item page-item-last"><a href="[[+href]]"></a></li>',
'tplPageActive'=>'@INLINE <li class="page-item active"><a href="[[+href]]">[[+page]]</a></li>',
'tplPageFirst'=>'@INLINE <li class="page-item page-item-first"><a href="[[+href]]"></a></li>',
'tplPageFirstEmpty'=>'@INLINE <li class="page-item page-item-first"><a href="[[+href]]"></a></li>',
'tplPagePrev'=>'@INLINE <li class="page-item page-item-prev"><a href="[[+href]]"></a></li>',
'tplPageNext'=>'@INLINE <li class="page-item page-item-next"><a href="[[+href]]"></a></li>',
'ajaxTplMore'=>'@INLINE
<div class="more_units pagination">
    <button class="btn btn_black js_btn_more_news btn-more" id="showMore">
        <span class="active" >Показать больше</span>
        <span>Показать больше</span>
    </button>
</div>
',
'tpl'=>"@INLINE
                <div class='newspage_card_wrap'>
                    <a href='[[+link]]' class='newspage_card_img'>
                        <img src='[[+tv.news_img:cdn]]'/>
                    </a>
                    <div class='newspage_card_content'>
                        <a href='[[+link]]' class='newspage_card_title'>[[+pagetitle]]</a>
                        <div class='newspage_card_date'>[[+longtitle]]</div>
                        <div class='newspage_card_text'>
                            [[+description]]
                        </div>
                        <div class='newspage_card_buttons'>
                            <a href='[[+link]]' class='btn btn_white'>узнать больше</a>
                        </div>
                    </div>
                </div>",

]}
<div class="listing" style="width:100%">
    <div class="listing_content">
        <div class="listing_content_catalog">
            <div class="mse2_pagination listing_content_catalog_pager" id="mse2_pagination">
                [[!+page.nav]]
            </div>
        </div>
    </div>
</div>

