<section class="main_slider">
    <div class="main_slider_swiper swiper-container-initialized swiper-container-horizontal">
        <div class="swiper-wrapper">

                {include 'file:chunks/index/slider/new_year_2023.tpl'}

            {include 'file:chunks/index/slider/new_arte_2022.tpl'}


            {if time() < 1663891200}
                <div class="swiper-slide">
                    <div class="main_slider_swiper_img">
                        <img loading=lazy src="/inc/images/sldef.jpg" alt="" class="desk">
                        <img loading=lazy src="/inc/images/sldefm.jpg" alt="" class="mob">
                        <div class="main_slider_swiper_img_show">
                            <img loading=lazy src="/inc/images/main_slider/23.jpg?v=1" alt="" class="desk">
                            <img loading=lazy src="/inc/images/main_slider/23_mob.jpg?v=1" alt="" class="mob">
                        </div>
                    </div>
                </div>
            {/if}

            {*			<!-- Слайд с одной кнопкой Участвовать --- START -->*}
            {*			<div class="swiper-slide">*}
            {*				<div class="main_slider_swiper_img">*}
            {*					<img src="https://9d2c73b6-1b6d-45e9-9faa-7fb794647734.selcdn.net/i/414/bfe90c66ac.jpg" class="desk" alt="">*}
            {*					<img src="https://9d2c73b6-1b6d-45e9-9faa-7fb794647734.selcdn.net/i/415/aebb96629c.jpg" class="mob" alt="">*}
            {*				</div>*}
            {*				<div class="main_slider_content main_slider_content_custom-btn">*}
            {*					<div class="jcont wow fadeInUp" data-wow-duration="3s">*}
            {*						<div class="main_slider_button wow fadeInUp" data-wow-delay=".4s" data-wow-duration=".8s"*}
            {*							 style="visibility: visible; animation-duration: 0.8s; animation-delay: 0.4s; animation-name: fadeInUp;">*}
            {*							<a href="https://t.me/artelamp_official" class="btn">*}
            {*								Участвовать*}
            {*							</a>*}
            {*						</div>*}
            {*					</div>*}
            {*				</div>*}
            {*			</div>*}
            {*			<!-- Слайд с одной кнопкой Участвовать --- FINISH -->*}

            <div class="swiper-slide">
                <div class="main_slider_swiper_img">
                    <img loading=lazy src="https://9d2c73b6-1b6d-45e9-9faa-7fb794647734.selcdn.net/i/240/867cbf1ed2.jpg" alt="" class="desk">
                    <img loading=lazy src="https://9d2c73b6-1b6d-45e9-9faa-7fb794647734.selcdn.net/i/241/26a07ea893.jpg" alt="" class="mob">
                    <div class="main_slider_swiper_img_d">
                        <img loading=lazy src="https://9d2c73b6-1b6d-45e9-9faa-7fb794647734.selcdn.net/i/242/6d41f50b6e.png" alt="" class="mob">
                    </div>
                </div>
                <div class="main_slider_content">
                    <div
                            class="jcont wow fadeInUp jcont_NY" data-wow-duration="3s"
                            style="visibility: visible; animation-duration: 3s; animation-name: fadeInUp;"
                    >
                        <p
                                class="wow fadeInUp main_slider_title" data-wow-delay=".1s" data-wow-duration=".8s"
                                style="visibility: visible; animation-duration: 0.8s; animation-delay: 0.1s; animation-name: fadeInUp;text-shadow:none;"
                        >
                            Магнитные трековые светильники
                        </p>
                        <div
                                class="main_slider_button pt63 wow fadeInUp" data-wow-delay=".4s"
                                data-wow-duration=".8s"
                                style="visibility: visible; animation-duration: 0.8s; animation-delay: 0.4s; animation-name: fadeInUp;"
                        >
                            <a href="https://artelamp.ru/catalog/magnitnyie-trekovyie-sistemyi" class="btn">
                                узнать больше
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {*			<div class="swiper-slide">*}
            {*				<div class="main_slider_swiper_img">*}
            {*					<div class="js_wow wow_pos301 wow fadeInLeft" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf301.png" class="desk" alt="">*}
            {*						<img src="{$assets_source}images/main_slider/mf301m.png" class="mob" alt="">*}
            {*					</div>*}
            {*					<div class="js_wow wow_pos302 wow fadeInLeft" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf302.png" class="desk" alt="">*}
            {*						<img src="{$assets_source}images/main_slider/mf302m.png" class="mob" alt="">*}
            {*					</div>*}
            {*					<div class="js_wow wow_pos303 wow fadeInLeft" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf303.png" class="desk" alt="">*}
            {*						<img src="{$assets_source}images/main_slider/mf303m.png" class="mob" alt="">*}
            {*					</div>*}
            {*					<div class="js_wow wow_pos304 wow fadeInLeft" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf304.png" class="desk" alt="">*}
            {*					</div>*}
            {*					<div class="js_wow wow_pos305 wow fadeInLeft" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf305.png" class="desk" alt="">*}
            {*					</div>*}
            {*					<div class="js_wow wow_pos306 wow fadeInLeft" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf306.png" class="desk" alt="">*}
            {*					</div>*}
            {*					<div class="js_wow wow_pos307 wow fadeInRight" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf307.png" class="desk" alt="">*}
            {*					</div>*}
            {*					<div class="js_wow wow_pos308 wow fadeIn fadeInRight" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf308.png" class="desk" alt="">*}
            {*						<img src="{$assets_source}images/main_slider/mf304m.png" class="mob" alt="">*}
            {*					</div>*}
            {*					<div class="js_wow wow_pos309 wow fadeIn fadeInRight" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf309.png" class="desk" alt="">*}
            {*						<img src="{$assets_source}images/main_slider/mf305m.png" class="mob" alt="">*}
            {*					</div>*}
            {*					<div class="js_wow wow_pos310 wow fadeIn fadeInRight" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf310.png" class="desk" alt="">*}
            {*						<img src="{$assets_source}images/main_slider/mf306m.png" class="mob" alt="">*}
            {*					</div>*}
            {*					<div class="js_wow wow_pos311 wow fadeIn fadeInRight" data-wow-duration="1.5s">*}
            {*						<img src="{$assets_source}images/main_slider/mf311.png" class="desk" alt="">*}
            {*						<img src="{$assets_source}images/main_slider/mf311m.png" class="mob" alt="">*}
            {*					</div>*}
            {*					<img src="{$assets_source}images/main_slider/m30.jpg" alt="" class="desk">*}
            {*					<img src="{$assets_source}images/main_slider/m30_mob.jpg" alt="" class="mob">*}
            {*				</div>*}
            {*				<div class="js_wow wow_pos30 wow fadeIn" data-wow-duration="1.5s">*}
            {*					<img src="{$assets_source}images/main_slider/mf301.png" class="desk" alt="">*}
            {*					<img src="{$assets_source}images/main_slider/mf301m.png" class="mob" alt="">*}
            {*				</div>*}
            {*				<div class="main_slider_content">*}
            {*					<div class="jcont wow fadeInUp jcont_NY" data-wow-duration="3s"*}
            {*						 style="visibility: visible; animation-duration: 3s; animation-name: fadeInUp;">*}
            {*						<p class="wow fadeInUp main_slider_title" data-wow-delay=".1s" data-wow-duration=".8s"*}
            {*						   style="visibility: visible; animation-duration: 0.8s; animation-delay: 0.1s; animation-name: fadeInUp;">*}
            {*							Новый ГОД*}
            {*						</p>*}
            {*						<p class="wow fadeInUp main_slider_text" data-wow-delay=".4s" data-wow-duration=".8s"*}
            {*						   style="visibility: visible; animation-duration: 0.8s; animation-delay: 0.4s; animation-name: fadeInUp;">*}
            {*							с Artelamp*}
            {*						</p>*}
            {*						<div class="main_slider_button pt63 wow fadeInUp" data-wow-delay=".4s"*}
            {*							 data-wow-duration=".8s"*}
            {*							 style="visibility: visible; animation-duration: 0.8s; animation-delay: 0.4s; animation-name: fadeInUp;">*}
            {*							<a href="/catalog/svetilniki?sale=1&in_stock=1" class="btn bg_orny no_bef">*}
            {*								узнать больше*}
            {*							</a>*}
            {*						</div>*}
            {*					</div>*}
            {*				</div>*}
            {*			</div>*}

            <div class="swiper-slide">
                <div class="main_slider_swiper_img">
                    <div class="js_wow wow_pos141 wow fadeInRight" data-wow-duration="2s">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf141.png" class="desk" alt="">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf141m.png" class="mob" alt="">
                    </div>
                    <div class="js_wow wow_pos142 wow fadeInLeft" data-wow-duration="2s">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf142.png" class="desk" alt="">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf142m.png" class="mob" alt="">
                    </div>
                    <img loading=lazy src="{$assets_source}images/main_slider/m1.jpg" alt="" class="desk">
                    <img loading=lazy src="{$assets_source}images/main_slider/m1_mob.jpg" alt="" class="mob">
                </div>
                <div class="main_slider_content">
                    <div class="jcont wow fadeInUp" data-wow-duration="3s">
                        <p class="wow fadeInUp main_slider_title" data-wow-delay=".1s" data-wow-duration=".8s">
                            Новый каталог
                        </p>
                        <p class="wow fadeInUp main_slider_text" data-wow-delay=".4s" data-wow-duration=".8s">
                            {'' | date : 'Y'}
                        </p>
                        <div class="main_slider_button wow fadeInUp" data-wow-delay=".4s" data-wow-duration=".8s">
                            <a href="/catalog_brand/" class="btn bg_rose no_bef">
                                скачать
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="swiper-slide">
                <div class="main_slider_swiper_img">
                    <div class="js_wow wow_active wow_pos9 wow fadeInRight" data-wow-duration="2s">
                        <img loading=lazy src="{$assets_source}images/main_slider/l9.png" alt="">
                    </div>
                    <img loading=lazy src="{$assets_source}images/main_slider/ny.jpg" alt="" class="desk">
                    <img loading=lazy src="{$assets_source}images/main_slider/ny_mob.png" alt="" class="mob">
                </div>
                <div class="main_slider_content">
                    <div class="jcont wow fadeInUp" data-wow-duration="3s">
                        <p
                                class="wow fadeInUp wow_active main_slider_title" data-wow-delay=".1s"
                                data-wow-duration=".8s"
                        >
                                <span class="bg_yellow">
                                    необычный
                                </span>
                        </p>
                        <p
                                class="wow fadeInUp wow_active main_slider_text" data-wow-delay=".4s"
                                data-wow-duration=".8s"
                        >
                                <span class="bg_yellow">
                                    свет
                                </span>
                        </p>
                        <div class="main_slider_info main_slider_info_white">
                            <span style="height: 20px;"></span>
                        </div>
                        <div
                                class="main_slider_button wow fadeInUp pt10 wow_active" data-wow-delay=".7s"
                                data-wow-duration=".8s"
                        >
                            <a href="https://partnership.fandeco.ru" class="btn btn_blackGold">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="main_slider_swiper_img">
                    <div class="js_wow wow_pos152 wow fadeInLeft" data-wow-duration="2s">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf152.png" class="desk" alt="">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf152m.png" class="mob" alt="">
                    </div>
                    <div class="js_wow wow_pos151 wow fadeInLeft" data-wow-duration="2s">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf152.png" class="desk" alt="">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf152m.png" class="mob" alt="">
                    </div>
                    <img loading=lazy src="{$assets_source}images/main_slider/m2.jpg" alt="" class="desk">
                    <img loading=lazy src="{$assets_source}images/main_slider/m2_mob.jpg" alt="" class="mob">
                </div>
                <div class="main_slider_content">
                    <div class="jcont wow fadeInUp" data-wow-duration="3s">
                        <p class="wow fadeInUp main_slider_title" data-wow-delay=".1s" data-wow-duration=".8s">
                            ТРЕКОВЫЕ
                        </p>
                        <p class="wow fadeInUp main_slider_text pb40" data-wow-delay=".4s" data-wow-duration=".8s">
                            светильники
                        </p>
                        <div class="main_slider_button wow fadeInUp" data-wow-delay=".4s" data-wow-duration=".8s">
                            <a href="{54|url}" class="btn">
                                Узнать больше
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="main_slider_swiper_img">
                    <img loading=lazy src="{$assets_source}images/main_slider/m3.jpg" alt="" class="desk">
                    <img loading=lazy src="{$assets_source}images/main_slider/m3_mob.jpg" alt="" class="mob">
                </div>
                <div class="main_slider_content main_slider_content_170">
                    <div class="jcont wow fadeInUp" data-wow-duration="3s">
                        <p class="wow fadeIn wow_ic" data-wow-delay=".1s" data-wow-duration=".8s">
                            <img loading=lazy src="{$assets_source}images/ic_3d.svg" alt="" style="width: 50px;">
                        </p>
                        <p class="wow fadeIn main_slider_title" data-wow-delay=".4s" data-wow-duration=".8s">
                            3D модели
                        </p>
                        <p class="wow fadeIn main_slider_text" data-wow-delay=".7s" data-wow-duration=".8s">
                            350 светильников
                        </p>
                        <div class="main_slider_info" data-wow-delay="1s" data-wow-duration=".8s">
                            <p>
                                серия SOLEN коллекция MODERN
                            </p>
                        </div>
                        <div class="main_slider_button wow fadeIn" data-wow-delay="1.3s" data-wow-duration=".8s">
                            <a href="{39|url}" class="btn bg_black">
                                Узнать больше
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="main_slider_swiper_img">
                    <div class="js_wow wow_pos162 wow fadeInRight" data-wow-duration="2s">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf161.png" class="desk" alt="">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf161m.png" class="mob" alt="">
                    </div>
                    <div class="js_wow wow_pos161 wow fadeInLeft" data-wow-duration="2s">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf162.png" class="desk" alt="">
                        <img loading=lazy src="{$assets_source}images/main_slider/mf162m.png" class="mob" alt="">
                    </div>
                    <img loading=lazy src="{$assets_source}images/main_slider/m4.jpg" alt="" class="desk">
                    <img loading=lazy src="{$assets_source}images/main_slider/m4_mob.jpg" alt="" class="mob">
                </div>
                <div class="main_slider_content pt40">
                    <div class="jcont wow fadeInUp" data-wow-duration="3s">
                        <p class="wow fadeInUp main_slider_title" data-wow-delay=".1s" data-wow-duration=".8s">
                            <span class="bg_black">уникальный</span>
                        </p>
                        <p class="wow fadeInUp main_slider_text pb40" data-wow-delay=".4s" data-wow-duration=".8s">
                            <span class="bg_white">дизайн</span>
                        </p>
                        <div class="main_slider_button wow fadeInUp" data-wow-delay=".4s" data-wow-duration=".8s">
                            <a href="https://partnership.fandeco.ru" class="btn bg_black">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="swiper-button-next" tabindex="0" role="button" aria-label="Next slide" aria-disabled="false"></div>
        <div
                class="swiper-button-prev" tabindex="0" role="button" aria-label="Previous slide"
                aria-disabled="true"
        ></div>

        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
</section>
