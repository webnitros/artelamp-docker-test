{extends 'file:templates/base.tpl'}
{block 'head'}
    <link rel="stylesheet" href="{$assets_source}css/catalog_text.css">
    <link rel="stylesheet" href="{$assets_source}css/animate.min.css">
    {'js/catalog_text.js'|script}
{/block}
{block 'crumbs'}{/block}
{block 'section_title'}
    {* сладерый на html*}
    {include 'file:chunks/index/slider.tpl'}
    <section>
        <div class="catalog">
            <div class="catalog_container">
                <div class="catalog_headline">
                    <div class="catalog_headline_title">
                        ЭЛЕКТРОННЫЙ КАТАЛОГ ARTE Lаmp 2020/21
                    </div>
                    <div class="catalog_headline_content">
                        Итальянский бренд интерьерного освещения Arte Lamp ежегодно пополняет свой ассортимент актуальными новинками, в соответствии со всеми последними трендами и направлениями на рынке интерьерного освещения. Для Вашего удобства ежегодно мы выпускаем объемный каталог со всеми новинками сезона, а так же с постоянной ассортиментной линейкой. Благодаря удобному рубрикатору в каталоге Вы легко найдете, то что Вам нужно,а яркие фото и интерьеры позволят легко представить понравившийся светильник у вас дома.
                    </div>
                </div>
                <div class="catalog_img">
                    <img src="images/about.png" alt="">
                </div>
            </div>

            <div class="catalog_slider">
                <div class="catalog_slider_container">
                    <div class="catalog_slider_container_dots">
                        <div class="catalog_slider_container_dots_title">
                            КАТАЛОГ 2020/21
                        </div>
                        <div class="catalog_slider_container_dots_value">
                            Содержание
                        </div>
                        <div class="swiper-container gallery-thumbs2 swiper-container-initialized swiper-container-vertical swiper-container-free-mode swiper-container-thumbs">
                            <div class="swiper-wrapper" style="transition-duration: 0ms;">

                                <div class="swiper-slide swiper-slide-visible swiper-slide-active swiper-slide-thumb-active" style="height: 51.4286px; margin-bottom: 10px;">
                                    в классическом стиле
                                </div>

                                <div class="swiper-slide swiper-slide-visible swiper-slide-next" style="height: 51.4286px; margin-bottom: 10px;">
                                    в современном стиле
                                </div>

                                <div class="swiper-slide swiper-slide-visible" style="height: 51.4286px; margin-bottom: 10px;">
                                    светодиодные модели
                                </div>

                                <div class="swiper-slide swiper-slide-visible" style="height: 51.4286px; margin-bottom: 10px;">
                                    оригинальные
                                </div>

                                <div class="swiper-slide swiper-slide-visible" style="height: 51.4286px; margin-bottom: 10px;">
                                    споты
                                </div>

                                <div class="swiper-slide swiper-slide-visible" style="height: 51.4286px; margin-bottom: 10px;">
                                    для ванной комнаты
                                </div>

                                <div class="swiper-slide swiper-slide-visible" style="height: 51.4286px; margin-bottom: 10px;">
                                    для улицы
                                </div>
                            </div>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                        <a href="" class="btn btn_black">
                            скачать каталог
                        </a>
                    </div>

                    <div class="catalog_slider_container_linked">
                        <div class="swiper-container gallery-top2 swiper-container-initialized swiper-container-horizontal">
                            <div class="swiper-wrapper" style="transition-duration: 0ms; transform: translate3d(-1063px, 0px, 0px);"><div class="swiper-slide swiper-slide-duplicate swiper-slide-prev" data-swiper-slide-index="6" style="width: 1063px;">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			для улицы
					      		</span>
                                        <img src="images/cimg7.jpg" alt="">
                                    </a>
                                </div>
                                <div class="swiper-slide swiper-slide-active" data-swiper-slide-index="0" style="width: 1063px;">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			в классическом стиле
					      		</span>
                                        <img src="images/cimg1.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide swiper-slide-next" data-swiper-slide-index="1" style="width: 1063px;">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			в современном стиле
					      		</span>
                                        <img src="images/cimg2.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide" data-swiper-slide-index="2" style="width: 1063px;">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			светодиодные модели
					      		</span>
                                        <img src="images/cimg3.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide" data-swiper-slide-index="3" style="width: 1063px;">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			оригинальные
					      		</span>
                                        <img src="images/cimg4.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide" data-swiper-slide-index="4" style="width: 1063px;">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			споты
					      		</span>
                                        <img src="images/cimg5.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide" data-swiper-slide-index="5" style="width: 1063px;">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			для ванной комнаты
					      		</span>
                                        <img src="images/cimg6.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide swiper-slide-duplicate-prev" data-swiper-slide-index="6" style="width: 1063px;">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			для улицы
					      		</span>
                                        <img src="images/cimg7.jpg" alt="">
                                    </a>
                                </div>
                                <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-active" data-swiper-slide-index="0" style="width: 1063px;">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			в классическом стиле
					      		</span>
                                        <img src="images/cimg1.jpg" alt="">
                                    </a>
                                </div></div>
                            <div class="swiper-button-next swiper-button-white" tabindex="0" role="button" aria-label="Next slide"></div>
                            <div class="swiper-button-prev swiper-button-white" tabindex="0" role="button" aria-label="Previous slide"></div>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                        <a href="" class="btn btn_black btn_download">
                            скачать каталог
                        </a>
                    </div>


                </div>
            </div>

            <div class="catalog_container">
                <div class="catalog_headline">
                    <div class="catalog_headline_title">
                        СЕРТИФИКАТЫ
                    </div>
                    <div class="catalog_headline_content">
                        Arte Lamp – это итальянский бренд интерьерного освещения, ассортиментный портфель которого ежегодно пополняется сотнями новинок различных стилей и направлений.<br>
                        Мы всегда держим руку на пульсе современных тенденций и актуальных течений на светотехническом рынке. <br>
                        Продукция Arte Lamp представлена в более, чем в 20 странах по всему миру и ежегодно география присутствия бренда расширяется.<br>
                        Вся продукция проходит обязательную сертификацию в соответствии со всеми российскими и европейскими стандартами.
                    </div>
                </div>
                <div class="catalog_img catalog_img_double">
                    <div class="catalog_img_wrap">
                        <img src="images/catalog2.png" alt="">
                    </div>
                    <div class="catalog_img_wrap">
                        <img src="images/catalog3.png" alt="">
                    </div>
                </div>
                <a href="" class="btn btn_black">
                    скачать СЕРТИФИКАТЫ
                </a>
            </div>

        </div>
    </section>
{/block}