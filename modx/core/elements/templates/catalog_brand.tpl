{extends 'file:templates/base.tpl'}
{block 'head'}
{*    {'css/index_page.css'|css}*}
{*    {'css/catalog_brand.css'|css}*}
    {'css/animate.min.css'|css}
    {'css/main.min.css'|css}
    {'js/catalog_brand.js'|script}
    {'js/wow.js'|script}
    <link rel="stylesheet" href="{$assets_source}css/_catalog.css">
{/block}
{block 'section_title'}
    {* сладерый на html*}
    {include 'file:chunks/catalog_brand/slider.tpl'}
    <section>
        <div class="catalog">
            <div class="catalog_container">
                <div class="catalog_headline">
                    <div class="catalog_headline_title">
                        ЭЛЕКТРОННЫЙ КАТАЛОГ ARTE Lаmp 2021/22
                    </div>
                    <div class="catalog_headline_content">
                        Итальянский бренд интерьерного освещения Arte Lamp ежегодно пополняет свой ассортимент
                        актуальными новинками, в соответствии со всеми последними трендами и направлениями на рынке
                        интерьерного освещения. Для Вашего удобства ежегодно мы выпускаем объемный каталог со всеми
                        новинками сезона, а так же с постоянной ассортиментной линейкой. Благодаря удобному рубрикатору
                        в каталоге Вы легко найдете, то что Вам нужно,а яркие фото и интерьеры позволят легко
                        представить понравившийся светильник у вас дома.
                    </div>
                </div>
                <div class="catalog_img">
                    <img src="{$assets_source}images/about.png" alt="">
                </div>
            </div>

            <div class="catalog_slider">
                <div class="catalog_slider_container">
                    <div class="catalog_slider_container_dots">
                        <div class="catalog_slider_container_dots_title">
                            КАТАЛОГ 2021/22
                        </div>
                        <div class="catalog_slider_container_dots_value">
                            Содержание
                        </div>
                        <div class="swiper-container gallery-thumbs2">
                            <div class="swiper-wrapper">

                                <div class="swiper-slide">
                                    в классическом стиле
                                </div>

                                <div class="swiper-slide">
                                    в современном стиле
                                </div>

                                <div class="swiper-slide">
                                    светодиодные модели
                                </div>

                                <div class="swiper-slide">
                                    оригинальные
                                </div>

                                <div class="swiper-slide">
                                    споты
                                </div>

                                <div class="swiper-slide">
                                    для ванной комнаты
                                </div>

                                <div class="swiper-slide">
                                    для улицы
                                </div>
                            </div>
                        </div>
                        <a target="_blank" download="" href="{'url_link_catalog'|config}" class="btn btn_black">
                            скачать каталог
                        </a>
                    </div>

                    <div class="catalog_slider_container_linked">
                        <div class="swiper-container gallery-top2">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			в классическом стиле
					      		</span>
                                        <img src="{$assets_source}images/cimg1.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			в современном стиле
					      		</span>
                                        <img src="{$assets_source}images/cimg2.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			светодиодные модели
					      		</span>
                                        <img src="{$assets_source}images/cimg3.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			оригинальные
					      		</span>
                                        <img src="{$assets_source}images/cimg4.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			споты
					      		</span>
                                        <img src="{$assets_source}images/cimg5.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			для ванной комнаты
					      		</span>
                                        <img src="{$assets_source}images/cimg6.jpg" alt="">
                                    </a>
                                </div>

                                <div class="swiper-slide">
                                    <a href="" class="cat_sl_img">
					      		<span class="cat_name">
					      			для улицы
					      		</span>
                                        <img src="{$assets_source}images/cimg7.jpg" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-button-next swiper-button-white"></div>
                            <div class="swiper-button-prev swiper-button-white"></div>
                        </div>
                        <a target="_blank" download="" href="{'url_link_catalog'|config}"
                           class="btn btn_black btn_download">
                            скачать каталог
                        </a>
                    </div>


                </div>
            </div>

            <div class="catalog_container" id="certificate">
                <div class="catalog_headline">
                    <div class="catalog_headline_title" >
                        СЕРТИФИКАТЫ
                    </div>
                    <div class="catalog_headline_content">
                        Arte Lamp – это итальянский бренд интерьерного освещения, ассортиментный портфель которого
                        ежегодно пополняется сотнями новинок различных стилей и направлений.<br>
                        Мы всегда держим руку на пульсе современных тенденций и актуальных течений на светотехническом
                        рынке. <br>
                        Продукция Arte Lamp представлена в более, чем в 20 странах по всему миру и ежегодно география
                        присутствия бренда расширяется.<br>
                        Вся продукция проходит обязательную сертификацию в соответствии со всеми российскими и
                        европейскими стандартами.
                    </div>
                </div>
                <div class="catalog_img catalog_img_double">
                    <div class="catalog_img_wrap">
                        <img src="{$assets_source}images/catalog2.png" alt="">
                    </div>
                    <div class="catalog_img_wrap">
                        <img src="{$assets_source}images/catalog3.png" alt="">
                    </div>
                </div>
                <a target="_blank" download="" href="{'url_link_sertificate'|config}" class="btn btn_black">
                    скачать СЕРТИФИКАТЫ
                </a>
            </div>

        </div>
    </section>
{/block}