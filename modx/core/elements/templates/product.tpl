{extends 'file:templates/baseProduct.tpl'}
{block 'head'}
    {'css/card.css'|css}
    {'js/item.js'|script}
	<script defer src="/inc/modules/fancybox/jquery.fancybox.min.js"></script>
	<script defer src="/inc/modules/fancybox/default.js?v={$_modx->config.assets_version}"></script>
    {'modules/fancybox/jquery.fancybox.min.css'|css}
    {var $pagetitle = $_modx->resource.pagetitle|replace:$article:'' }
    {var $pagetitle = $pagetitle|replace:$data['vendor_code']:'' }
{/block}
{block 'title'}
	<section class="listing_title">
		<div class="jcont">
			<h1 class="title" itemprop="name">{$pagetitle}<span>{$article}</span></h1>
		</div>
	</section>
{/block}
{block 'section'}
    {var $data = '@FILE snippets/product/msData.php' | snippet}
    {var $collection = $data['collection'] ? $data['collection']:$data['collection_web']}
    {var $id = $data['id']}
    {var $files = '!msGallery'|snippet:[
    'tpl'=>"@FILE chunks/product/gallery.tpl"
    ] | fromJSON}
	<section class="card {if !$stock}card_no-store{/if}">
		<div class="jcont">
			<div class="card_main">
				<div class="card_main_sliders">
                    {if $files}
						<div class="card_main_sliders_left">
                            {*<button class="btn">test</button>*}
							<div class="swiper-container gallery-thumbs">
								<div class="swiper-wrapper">
                                    {foreach $files as $file}
										<div class="swiper-slide">
											<img loading=lazy data-url="{$file['url']}" src="{$file['small']|cdn}" alt="">
										</div>
                                    {/foreach}
                                    {if $data['video_link_new']}
										<div class="swiper-slide">
											<div class="gallery-top_video">
												<a data-fancybox href="{$data['video_link_new']}">
													<img src="https://i.imgur.com/CMXOa03.png" alt="">
												</a>
											</div>
										</div>
                                    {/if}
								</div>

								<div class="swiper-button_wrap">
									<div class="swiper-button-next swiper-button-blue"></div>
									<div class="swiper-button-prev swiper-button-blue"></div>
								</div>
							</div>
						</div>
						<div class="swiper-container gallery-top">
							<div class="swiper-wrapper">
                                {foreach $files as $index => $file}
									<div class="swiper-slide">
										<div class="gallery-top_img">
											<a data-fancybox="gallery" href="{$file['url']|cdn}" title="{$_modx->resource.pagetitle} {$index}">
												<img loading=lazy data-url="{$file['url']}" src="{$file['big']|cdn}" alt="{$_modx->resource.pagetitle} {$index}">
											</a>
										</div>
									</div>
                                {/foreach}
							</div>
                            {* <!-- Add Arrows -->
							 {if count($files) > 4}
								 <div class="swiper-button-next swiper-button-blue"></div>
								 <div class="swiper-button-prev swiper-button-blue"></div>
							 {/if}*}
						</div>
                    {else}
                    {/if}
				</div>
				<div class="card_main_content" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
					<meta itemprop="price" content="{$price}"/>
					<meta itemprop="priceCurrency" content="RUB"/>
					<meta itemprop="category" content="{$data['good_type_web']}"/>
					<meta itemprop="url" content="{$modx->resource->id|url}"/>
                    {if $stock}
						<link itemprop="availability" href="https://schema.org/InStock"/>
                    {else}
						<link itemprop="availability" href="https://schema.org/OutOfStock "/>
                    {/if}
					<div class="card_main_content_unit">
						<div class="card_main_content_unit_top">
							<div class="card_main_content_unit_img">
								<img loading=lazy src="{$thumb|cdn}" alt="">
							</div>
							<div class="card_main_content_unit_content">
								<div class="card_main_content_unit_content_title">{$pagetitle}</div>
								<div class="card_main_content_unit_content_article" itemprop="sku">{$article}</div>
								<div class="card_main_content_unit_content_stock" {if !$stock}style="color: #ff0000 !important"{/if}>
                                    {if $under_order}
										под заказ
                                    {else}
										В наличии: {$stock} шт.
                                    {/if}
								</div>
								<div class="like_buttons">
                                    {var $comparison = '!AddComparison' | snippet : ['id' => $id]}
                                    {var $favorite = 'msFavorites' | snippet : ['id' => $id]}
									<div
											class="comparison comparison-default {$comparison['added']?' added' :''}[[+can_compare]]"
											data-id="{$id}" data-list="default"
									>
										<button
												class="btn_link btn_link_weight comparison-add comparison-link"
												data-text=""
										></button>
										<button
												class="btn_link btn_link_weight comparison-remove comparison-link"
												data-text=""
										></button>
									</div>
									<!--comparison_can_compare  can_compare-->
									<!--comparison_added  added-->
									<span
											class="favorites favorites-default {$favorite['added']}"
											data-id="{$id}"
									>
                                        <button
												class="favorites-add favorites-link btn_link btn_link_like "
												data-text=""
										></button>
                                        <button
												class="favorites-remove favorites-link btn_link btn_link_like "
												data-text=""
										></button>
                                    </span>
								</div>
							</div>
						</div>
						<form method="post" class="ms2_form ">
							<div class="card_main_content_unit_bottom">
								<div class="card_main_content_unit_bottom_cost">
                                    {if $stock}
                                        {if $old_price}
											<del>{$old_price} р.</del>
                                        {/if}
										<i>{$price} р.</i>
                                    {/if}
								</div>
								<div class="cartcalc">
									<input type="hidden" name="max_count" value="{$stock}"/>
									<button class="calcbtn ccalc-minus">&mdash;</button>
									<input type="text" name="count" value="{$id|count_cart}">
									<button class="calcbtn ccalc-plus">+</button>
								</div>
							</div>
							<div class="card_main_content_unit_buttons">
								<input type="hidden" name="id" value="{$id}">
								<input type="hidden" id="ms2_form_article" name="article" value="{$article}">
								<input type="hidden" name="name" value="Уличный светильник  BREMEN ">
								<input type="hidden" name="price" value="{$price}" {if !$stock}disabled{/if}>
								<input type="hidden" name="old_price" value="{$old_price}" {if !$stock}disabled{/if}>
								<input type="hidden" name="sale" value="{$data['sale']}">
								<input type="hidden" name="new" value="{$data['new']}">
								<input type="hidden" name="url" value="{$data['url']}">
								<input type="hidden" name="thumb" value="/assets/components/minishop2/img/web/ms2_big.png">
								<button class="btn_buy btn btn_black" type="submit" name="ms2_action" value="cart/add" {if !$stock}disabled{/if}>
									добавить в корзину
								</button>
								[[!msOneClick@artelamp]]
							</div>
						</form>
						<div class="card_main_content_unit_links">
							<div class="card_main_content_unit_links_element delivery">
                                {set $location = ''|getUserLocation}
								<div class="card_main_content_unit_links_title">
									доставка в г. {$location['name']}
								</div>
								<div class="card_main_content_unit_links_text">
									<div class="element">
                                        {if $location['id'] == '1'}
											При заказе от 6 000 р. доставка по Москве БЕСПЛАТНО
                                        {else}
											ТК BoxBerry, PickPoint, Деловые линии. По тарифам выбранной компании.
											Передача в ТК (г. Москва) на следующий рабочий день
                                        {/if}
									</div>
                                    {* <div class="element">
										 <i class="element_date">20.20.20</i> - возможная дата доставки
									 </div>*}
									<div class="element">
										<a data-toggle="modal" data-target="#delivery_more" class="color_blue">Подробнее о доставке</a>
									</div>
								</div>
							</div>
							<div class="card_main_content_unit_links_element delivery_method">
								<div class="card_main_content_unit_links_title">Способы доставки</div>
								<div class="card_main_content_unit_links_text">
                                    {if $location['id'] == '1'}
										<div class="element">
											<a target="_blank" href="{"1327"|url}">Курьером по Москве</a>
										</div>
                                    {/if}
									<div class="element">
										<a target="_blank" href="{"1327"|url}">Транспортные компании</a>
									</div>
									<div class="element">
										<a target="_blank" href="{"1327"|url}" class="color_blue">Подробнее о способах доставке</a>
									</div>
								</div>
							</div>
							<div class="card_main_content_unit_links_element banks">
								<div class="card_main_content_unit_links_title">
									принимаем к оплате карты
								</div>
								<div class="card_main_content_unit_links_text">
									<div class="element">
										<a data-toggle="modal" data-target="#halva">Халва</a>
										<a data-toggle="modal" data-target="#halva">Совесть</a>
									</div>
								</div>
							</div>
                            {*                            <div class="card_main_content_unit_links_element ic_percent">*}
                            {*                                <div class="card_main_content_unit_links_title">*}
                            {*                                    рассрочка*}
                            {*                                </div>*}
                            {*                                <div class="card_main_content_unit_links_text">*}
                            {*                                    <div class="element">*}
                            {*                                        <a data-toggle="modal" data-target="#instalments">Тинькофф</a>*}
                            {*                                    </div>*}
                            {*                                </div>*}
                            {*                            </div>*}
						</div>
					</div>
				</div>
			</div>
			<div class="card_characters">
				<div class="card_characters_list {if $stock}active{/if}">
					<div class="card_characters_list_title">
						<button class="btn">
							<i class="ic"></i>
							<span>характеристики</span>
						</button>
					</div>
					<div class="card_characters_list_content active">
						<div class="card_characters_list_content_left">
							<div class="card_characters_list_content_title">
								Основные
							</div>
							<ul class="card_characters_list_content_block">
                                {if $article}
									<li>
										<div class="name">Артикул</div>
										<div class="value">{$article}</div>
									</li>
                                {/if}
                                {if $data['vendor_code']}
									<li>
										<div class="name">
											Бренд
										</div>
										<div class="value">
                                            {$data['vendor_code']}
										</div>
									</li>
                                {/if}
                                {if $data['country_orig']}
									<li>
										<div class="name">
											Страна бренда
										</div>
										<div class="value">
                                            {$data['country_orig']}
										</div>
									</li>
                                {/if}
                                {if $collection}
									<li>
										<div class="name">
											Коллекция
										</div>
										<div class="value">{$collection}</div>
									</li>
                                {/if}
                                {if $data['lamp_style'] | count}
									<li>
										<div class="name">
                                            {if $data['lamp_style'] | count > 1}
												Стили
                                            {else}
												Стиль
                                            {/if}
										</div>
										<div class="value">
                                            {$data['lamp_style'] |implode:', '}
										</div>
									</li>
                                {/if}

							</ul>
							<div class="card_characters_list_content_title">
								Источник света
							</div>
							<ul class="card_characters_list_content_block">
                                {if $data['lamp_socket'] | count}
									<li>
										<div class="name">
                                            {if $data['lamp_socket'] | count > 1}Патроны{else}Патрон{/if}
										</div>
										<div class="value">
                                            {$data['lamp_socket'] | implode: ', '}
										</div>
									</li>
                                {/if}
                                {if $num_of_socket}
									<li>
										<div class="name">
											Количество патронов
										</div>
										<div class="value">
                                            {$num_of_socket} шт
										</div>
									</li>
                                {/if}
                                {if $data['power'] }
									<li>
										<div class="name">
											<i class="name_ic_info"></i>
											<div class="name_inform">Потребляемое количество ватт</div>
											Мощность
										</div>
										<div class="value">
                                            {$data['power']} W
										</div>
									</li>
                                {/if}
                                {if $data['voltage']}
									<li>
										<div class="name">
											Напряжение
										</div>
										<div class="value">
                                            {$data['voltage']} V
										</div>
									</li>
                                {/if}
                                {if $data['lamp_type']  | count}
									<li>
										<div class="name">
                                            {if $data['lamp_type'] | count > 1}
												Типы ламп
                                            {else}
												Тип лампы
                                            {/if}
										</div>
										<div class="value">
                                            {$data['lamp_type'] |implode:', '}
										</div>
									</li>
                                {/if}
                                {if $data['light_temperatures'] | count}
									<li>
										<div class="name">
											Цветовая температура
										</div>
										<div class="value">
                                            {$data['light_temperatures'] |implode:', '}
										</div>
									</li>
                                {/if}
                                {if $data['ploshad_osvesheniya']}
									<li>
										<div class="name">
											Площадь освещения
										</div>
										<div class="value">
                                            {$data['ploshad_osvesheniya']} м<sup>2</sup>
										</div>
									</li>
                                {/if}
							</ul>
                            {if $data['lamp_socket2'] | count > 0}
								<div class="card_characters_list_content_title">
									Источник света 2
								</div>
								<ul class="card_characters_list_content_block">
                                    {if $data['lamp_socket2'] | count}
										<li>
											<div class="name">
                                                {if $data['lamp_socket2'] | count > 1}Патроны{else}Патрон{/if}
											</div>
											<div class="value">
                                                {$data['lamp_socket2'] | implode: ', '}
											</div>
										</li>
                                    {/if}
                                    {if $data['power2'] }
										<li>
											<div class="name">
												<i class="name_ic_info"></i>
												<div class="name_inform">Потребляемое количество ватт</div>
												Мощность
											</div>
											<div class="value">
                                                {$data['power2']} W
											</div>
										</li>
                                    {/if}
                                    {if $data['lamp_type2']  | count}
										<li>
											<div class="name">
                                                {if $data['lamp_type2'] | count > 1}
													Типы ламп
                                                {else}
													Тип лампы
                                                {/if}
											</div>
											<div class="value">
                                                {$data['lamp_type2'] |implode:', '}
											</div>
										</li>
                                    {/if}
								</ul>
                            {/if}
                            {if $data['lamp_socket3'] | count > 0}
								<div class="card_characters_list_content_title">
									Источник света 3
								</div>
								<ul class="card_characters_list_content_block">
                                    {if $data['lamp_socket2'] | count}
										<li>
											<div class="name">
                                                {if $data['lamp_socket3'] | count > 1}Патроны{else}Патрон{/if}
											</div>
											<div class="value">
                                                {$data['lamp_socket3'] | implode: ', '}
											</div>
										</li>
                                    {/if}
                                    {if $data['power3'] }
										<li>
											<div class="name">
												<i class="name_ic_info"></i>
												<div class="name_inform">Потребляемое количество ватт</div>
												Мощность
											</div>
											<div class="value">
                                                {$data['power3']} W
											</div>
										</li>
                                    {/if}
                                    {if $data['lamp_type3']  | count}
										<li>
											<div class="name">
                                                {if $data['lamp_type3'] | count > 1}
													Типы ламп
                                                {else}
													Тип лампы
                                                {/if}
											</div>
											<div class="value">
                                                {$data['lamp_type3'] |implode:', '}
											</div>
										</li>
                                    {/if}
								</ul>
                            {/if}
							<div class="card_characters_list_content_title">
								Габариты светильника
							</div>
							<ul class="card_characters_list_content_block">
                                {if $data['diameter']}
									<li>
										<div class="name">
											Диаметр
										</div>
										<div class="value">
                                            {$data['diameter']} см
										</div>
									</li>
                                {/if}
                                {if $data['width']}
									<li>
										<div class="name">
											Ширина
										</div>
										<div class="value">
                                            {$data['width']} см
										</div>
									</li>
                                {/if}
                                {if $data['length']}
									<li>
										<div class="name">
											Длина
										</div>
										<div class="value">
                                            {$data['length']} см
										</div>
									</li>
                                {/if}
                                {if $data['height']}
									<li>
										<div class="name">
											Высота
										</div>
										<div class="value">
                                            {$data['height']} см
										</div>
									</li>
                                {/if}
                                {if $data['length_shnura']}
									<li>
										<div class="name">
											<i class="name_ic_info"></i>
											<div class="name_inform">
												Длина цепи или шнура см
											</div>
											Длина цепи
										</div>
										<div class="value">
                                            {$data['length_shnura']} см
										</div>
									</li>
                                {/if}
                                {if $data['weight']}
									<li>
										<div class="name">
											Вес с упаковкой
										</div>
										<div class="value">
                                            {$data['weight']} кг
										</div>
									</li>
                                {/if}
                                {if $data['weight_netto']}
									<li>
										<div class="name">
											Вес без упаковки
										</div>
										<div class="value">
                                            {$data['weight_netto']} кг
										</div>
									</li>
                                {/if}
                                {if $data['box_width']}
									<li>
										<div class="name">
											Ширина коробки
										</div>
										<div class="value">
                                            {$data['box_width']} см
										</div>
									</li>
                                {/if}
                                {if $data['box_length']}
									<li>
										<div class="name">
											Длина коробки
										</div>
										<div class="value">
                                            {$data['box_length']} см
										</div>
									</li>
                                {/if}
                                {if $data['box_height']}
									<li>
										<div class="name">
											Высота коробки
										</div>
										<div class="value">
                                            {$data['box_height']} см
										</div>
									</li>
                                {/if}
							</ul>
						</div>
						<div class="card_characters_list_content_right">

							<div class="card_characters_list_content_title">
								Материалы и цвета
							</div>
							<ul class="card_characters_list_content_block">
                                {if $data['plafond_material'] | count}
									<li>
										<div class="name">
                                            {if $data['plafond_material'] | count > 1}
												Материалы плафона
                                            {else}
												Материал плафона
                                            {/if}
										</div>
										<div class="value">
                                            {$data['plafond_material'] |implode:', '}
										</div>
									</li>
                                {/if}
                                {if $data['plafond_color'] | count}
									<li>
										<div class="name">
                                            {if $data['plafond_color'] | count > 1}
												Цвета плафона
                                            {else}
												Цвет плафона
                                            {/if}
										</div>
										<div class="value">
                                            {$data['plafond_color'] |implode:', '}
										</div>
									</li>
                                {/if}
                                {if $data['armature_material'] | count}
									<li>
										<div class="name">
                                            {if $data['armature_material'] | count > 1}
												Материалы арматуры
                                            {else}
												Материал арматуры
                                            {/if}
										</div>
										<div class="value">
                                            {$data['armature_material'] |implode:', '}
										</div>
									</li>
                                {/if}
                                {if $data['armature_color'] | count}
									<li>
										<div class="name">
                                            {if $data['armature_color'] | count > 1}
												Цвета светильника
                                            {else}
												Цвет светильника
                                            {/if}
										</div>
										<div class="value">
                                            {$data['armature_color'] |implode:', '}
										</div>
									</li>
                                {/if}
							</ul>
							<div class="card_characters_list_content_title">
								Дополнительно
							</div>
							<ul class="card_characters_list_content_block">
								<li>
									<div class="name">
										Гарантийный срок
									</div>
									<div class="value">
                                        {if $data['garantiya']}
                                            {$data['garantiya']} мес.
                                        {else}
											Нет
                                        {/if}
									</div>
								</li>
                                {if $data['interer'] | count}
									<li>
										<div class="name">
                                            {if $data['interer'] | count > 1}
												Интерьеры
                                            {else}
												Интерьер
                                            {/if}
										</div>
										<div class="value">
                                            {$data['interer'] | implode :', '}
										</div>
									</li>
                                {/if}
                                {if $data['krepej'] | count}
									<li>
										<div class="name">
                                            {if $data['krepej'] | count > 1}
												Крепежы
                                            {else}
												Крепеж
                                            {/if}
										</div>
										<div class="value">
                                            {$data['krepej'] |implode:', '}
										</div>
									</li>
                                {/if}
                                {if $data['ip_class']}
									<li>
										<div class="name">
											<i class="name_ic_info"></i>
											<div class="name_inform">
												Степень пылевлагозащиты
											</div>
											IP
										</div>
										<div class="value">
                                            {$data['ip_class']}
										</div>
									</li>
                                {/if}
                                {if $data['mesto_prim'] | count}
									<li>
										<div class="name">
                                            {if $data['mesto_prim'] | count > 1}
												Места применения
                                            {else}
												Место применения
                                            {/if}
										</div>
										<div class="value">
                                            {$data['mesto_prim'] |implode:', '}
										</div>
									</li>
                                {/if}
								<li>
									<div class="name">
                                        {if $data['diffuser'] | count > 1}
											Виды рассеивателей
                                        {else}
											Вид рассеивателя
                                        {/if}
									</div>
									<div class="value">
                                        {if $data['diffuser'] | count}
                                            {$data['diffuser'] |implode:', '}
                                        {else}
											Нет
                                        {/if}
									</div>
								</li>
								<li>
									<div class="name">
										<i class="name_ic_info"></i>
										<div class="name_inform">
											Регулятор яркости
										</div>
										Диммер
									</div>
									<div class="value">
                                        {if $data['dimmer']}Да{else}Нет{/if}
									</div>
								</li>
							</ul>
							<div id="fileList">

							</div>
						</div>
					</div>
				</div>

				<div class="card_characters_list {if $stock}active{/if}">
					<div class="card_characters_list_title">
                        {*						[{$data['sub_category']}]*}
						<button class="btn">
							<i class="ic"></i>
                            {set $description_title = 'Описание светильника'}

                            {if in_array($data['sub_category'],['Светодиодные лампы'])}
                                {set $description_title = 'Описание светодиодной лампы'}
                            {/if}
                            {if in_array($data['sub_category'],['Аксессуары для подвесных и потолочных светильников'])}
                                {set $description_title = 'Описание аксессуара'}
                            {/if}
                            {if in_array($data['sub_category'],['Профили для лент'])}
                                {set $description_title = 'Описание профиля'}
                            {/if}
                            {if in_array($data['sub_category'],['Шинопроводы','Магнитные шинопроводы'])}
                                {set $description_title = 'Описание шинопровода'}
                            {/if}
                            {if in_array($data['sub_category'],['Комплектующие для трековых систем','Комплектующие для магнитных треков'])}
                                {set $description_title = 'Описание комплектующего'}
                            {/if}
                            {if $data['sub_category'] === 'Светодиодные лампы'}
                                {set $description_title = 'Описание светодиодной лампы'}
                            {/if}
							<span>{$description_title}</span>
						</button>
					</div>
					<div class="card_characters_list_description">
						<div class="card_characters_list_description_title">
                            {var $pagetitle = $_modx->resource.pagetitle|replace:$article:'' }
                            {var $pagetitle = $pagetitle|replace:$data['vendor_code']:'' }
							<span>{$pagetitle}</span>
							<span class="article">
								{$article}
							</span>
						</div>

						<div class="card_characters_list_description_flex">
							<div class="card_characters_list_description_left">
								<div class="card_characters_list_description_text">
                                    {$_modx->resource.description}
								</div>
								<div class="card_characters_list_description_blockquote">
									<ul itemprop="description" style="padding: 0">
                                        {if $data['lamp_style'] | count}
											<li>
                                                {if $data['lamp_style'] | count > 1}
													Стили
                                                {else}
													Стиль
                                                {/if}
												<b>{$data['lamp_style'] |implode:', '}</b>
											</li>
                                        {/if}
										<li>
											Коллекция <b>{$collection}</b>
										</li>
										<li>
											Артикул <b>{$article}</b>
										</li>
                                        {if $data['lamp_socket'] | count}
											<li>
                                                {if $data['lamp_socket'] | count > 1}
													Патроны
                                                {else}
													Патрон
                                                {/if}
												<b>{$data['lamp_socket'] |implode:', '}</b>
											</li>
                                        {/if}
									</ul>
								</div>

							</div>
							<div class="card_characters_list_description_right">
								<div class="card_characters_slider">
									<div class="swiper-container">
										<div class="swiper-wrapper" data-shuffle="{$files|shuffle}">
                                            {foreach $files as $index => $file}
												<div class="swiper-slide">
													<a data-fancybox="gallery_bottom" href="{$file['url']}" title="{$_modx->resource.pagetitle} {$index}">
														<img loading=lazy {if $index == 0}itemprop="image"{/if} data-url="{$file['url']}" src="{$file['big']|cdn}" alt="{$_modx->resource.pagetitle} {$index}">
													</a>
												</div>
                                            {/foreach}
										</div>
										<div class="swiper-button-next"></div>
										<div class="swiper-button-prev"></div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>

                {var $results = 'msProducts'|snippet:[
                'tpl'=>'@FILE chunks/catalog/product/looked.tpl',
                'parents' => 2,
                'where'=>[
                "Data.collection"=>$collection,
                "Data.in_stock" => true,
                ],
                'limit'=>0,
                ]}
                {if $results}
					<div class="card_characters_list card_slider_other card_slider_other_4 {if !$stock}active{/if}">
						<div class="card_characters_list_title">
							<button class="btn">
								<i class="ic"></i>
								<span>еще из той же коллекции</span>
							</button>
						</div>
						<div class="card_characters_list_content">
							<div class="swiper-container">
								<div class="swiper-wrapper">
                                    {$results}
								</div>
								<div class="swiper-scrollbar"></div>
							</div>
						</div>
					</div>
                {/if}

                {var $results = '@FILE snippets/product/analogs.php'|snippet:[
                'where'=>["Data.in_stock:!="=>"0"],
                'tpl'=>'@FILE chunks/catalog/product/looked.tpl',
                'link' => 1,
                'showZeroStock' => 0,
                'parents' => 2,
                'master' => $data['id'],
                'sortby'=>'{"Link.rank":"desc"}',
                'limit'=>30
                ]}
                {if $results}
					<div class="card_characters_list card_slider_other card_slider_other_5 {if !$stock}active{/if}">
						<div class="card_characters_list_title">
							<button class="btn">
								<i class="ic"></i>
								<span>Похожие товары</span>
							</button>
						</div>
						<div class="card_characters_list_content">
							<div class="swiper-container">
								<div class="swiper-wrapper">
                                    {$results}
								</div>
								<div class="swiper-scrollbar"></div>
							</div>
							<div class="more_units">
								<a href="{$_modx->resource.parent|url}" class="btn btn_white">
									показать все аналогичные товары
								</a>
							</div>
						</div>
					</div>
                {/if}
                {if $data['good_type_web'] != 'Лампочки'}
                    {var $optionFilters = 'criteria_lamp_soket' | placeholder}
                    {if $optionFilters}
                        {var $results ='!msProducts'|snippet:[
                        'tpl'=>'@FILE chunks/catalog/product/looked.tpl',
                        'parents' => 2,
                        'where'=>[
                        "Data.category:LIKE"=>"лампочки",
                        "Data.in_stock" => true,
                        ],
                        'optionFilters'=> $optionFilters
                        ]}

                        {if $results}
                            {var $lamp_socket = $_modx->resource.lamp_socket | join : ','}
							<div class="card_characters_list card_slider_other card_slider_other_6">
								<div class="card_characters_list_title">
									<button class="btn">
										<i class="ic"></i>
										<span>лампочки</span>
									</button>
								</div>
								<div class="card_characters_list_content">
									<div class="swiper-container">
										<div class="swiper-wrapper">{$results}</div>
										<div class="swiper-scrollbar"></div>
									</div>
									<div class="more_units">
										<a href="/catalog/lampyi?lamp_socket={$lamp_socket}" class="btn btn_white">
											показать все аналогичные товары
										</a>
									</div>
								</div>
							</div>
                        {/if}
                    {/if}
                {/if}

				<!-- Ссылку на все товары в катерии, добавлять внутрь блока с калссом .card_characters, полсединм элемнтом -->
                {if $data['vendor_code'] === "ARTE LAMP"}
					<div class="card__category-link-wrap">
                        {set $parent = $_modx->resource.parent|resource}
						<a class="btn_black card__category-link" href="{$_modx->resource.parent|url}">Все {$parent.pagetitle|lower} Arte Lamp</a>
					</div>
                {/if}
			</div>
		</div>
	</section>
    {var $ids = '!looked' | snippet : ['ids' => 1]}
    {if $ids}
		<section class="card_lastlook">
			<div class="jcont">
				<div class="listing_content_catalog_lastlook">
					<div class="the_title">
						Последние просмотренные товары
					</div>
					<div class="lastlook_slider lastlook_slider2">
						<div class="swiper-container">
							<div class="swiper-wrapper">
                                {'!msProducts' | snippet : [
                                'parents' => 2,
                                'limit' => 10,
                                'sortdir' => 'DESC',
                                'resources' => $ids,
                                'where'=>["Data.in_stock:!="=>"0"],
                                'tpl' => '@FILE chunks/catalog/product/looked.tpl'
                                ]}
							</div>
							<div class="swiper-scrollbar"></div>
							<div class="swiper-button-prev"></div>
							<div class="swiper-button-next"></div>
						</div>
					</div>
				</div>
			</div>
		</section>
    {/if}
    {'addLooked'|snippet}
{/block}
{block 'modals'}
	<div id="delivery_more" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<button type="button" class="close" data-dismiss="modal"></button>
				<div class="modal-body">
					<div class="modal_body_addcart mr0">

						<div class="the_content the_content_mobile">
							<div class="modal_title">
								<i class="ic_title"></i>
								<p class="the_text">подробнее о доставке</p>
							</div>
						</div>
						<div class="the_content mt-20">
							<div class="modal_title">
								<i class="ic_title"></i>
								<p class="the_text">подробнее о доставке</p>
							</div>
							<div class="modal_content">
								<div class="delivery_more">
									<div class="delivery_more_title">
										Стоимость доставки курьером по Москве и Московской области
									</div>
									<div class="delivery_more_container">

										<div class="delivery_more_line">
										<span class="delivery_more_name">
											В пределах МКАД, заказ от 6000 р.
										</span>
											<span class="delivery_more_value">
											Бесплатно
										</span>
										</div>

										<div class="delivery_more_line">
										<span class="delivery_more_name">
											В пределах МКАД заказ до 6000 р.
										</span>
											<span class="delivery_more_value">
											270 р.
										</span>
										</div>

										<div class="delivery_more_line">
										<span class="delivery_more_name">
											За МКАД
										</span>
											<span class="delivery_more_value">
											+ 25 р./км.
										</span>
										</div>

									</div>
									<div class="delivery_more_link">
										<a target="_blank" href="{"1327"|url}">
											Подробнее о доставке
										</a>
									</div>
								</div>

								<div class="delivery_more">
									<div class="delivery_more_title">
										Самовывоз
									</div>
									<div class="delivery_more_container">

										<div class="delivery_more_line">
										<span class="delivery_more_name">
											<b>Boxberry</b>
											самовывоз из 98 пунктов
										</span>
											<span class="delivery_more_value">
											<span class="delivery_more_value_date">
												4 дня
											</span>
											<span
													class="delivery_more_value_cost"
											>
												от 220 р.
											</span>
										</span>
										</div>

										<div class="delivery_more_line">
										<span class="delivery_more_name">
											<b>Pickpoint</b>
											самовывоз из 230 пунктов
										</span>
											<span class="delivery_more_value">
											<span class="delivery_more_value_date">
												4 дня
											</span>
											<span
													class="delivery_more_value_cost"
											>
												от 220 р.
											</span>
										</span>
										</div>

									</div>
									<div class="delivery_more_link mb20">
										<a target="_blank" href="{"1327"|url}">
											Подробнее о доставке
										</a>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="instalments" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<button type="button" class="close" data-dismiss="modal"></button>
				<div class="modal-body">
					<div class="modal_body_addcart mr0">

						<div class="the_content the_content_mobile">
							<div class="modal_title">
								<i class="ic_title"></i>
								<p class="the_text">Рассрочка тинькофф</p>
							</div>
						</div>
						<div class="the_content mt-20">
							<div class="modal_title">
								<i class="ic_title"></i>
								<p class="the_text">Рассрочка тинькофф</p>
							</div>
							<div class="modal_content">
								<div class="instalments">
									<div class="instalments_container">
										<div class="instalments_imgwr">
											<img src="/inc/images/instalments.png" alt="">
										</div>
										<div class="instalments_content">
											<p>
												Вы можете выбрать рассрочку платежа с помощью программы от Тинькофф. Для получения рассрочки, во время оформления нужно выбрать «Рассрочка от Тинькофф».
												В этом случае нужно будет заполнить данные для
												оформления заявки по беспроцентному кредиту и дождаться решения от банка, которое обычно приходит в течение нескольких минут.
											</p>
										</div>
									</div>
									<a
											target="_blank" rel="nofollow" href="https://www.tinkoff.ru/cards/credit-cards/tinkoff-platinum/faq/installment-plan/how-to-buy-in-installments/"
											class="btn btn_white"
									>
										подробнее на <span>официальном</span> сайте
									</a>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="halva" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<button type="button" class="close" data-dismiss="modal"></button>
				<div class="modal-body">
					<div class="modal_body_addcart mr0">

						<div class="the_content the_content_mobile">
							<div class="modal_title">
								<i class="ic_title"></i>
								<p class="the_text">Карты оплаты</p>
							</div>
						</div>
						<div class="the_content mt-20">
							<div class="modal_title">
								<i class="ic_title"></i>
								<p class="the_text">Карты оплаты</p>
							</div>
							<div class="modal_content">
								<div class="instalments">
									<div class="instalments_container">
										<div class="instalments_imgwr">
											<img src="/inc/images/instalments2.png" alt="">
											<img src="/inc/images/instalments3.png" alt="">
										</div>
										<div class="instalments_content">
											<p>
												При оплате заказа с оплатой
												картой «Халва» или «Совесть»
												доступна рассрочка на 4 месяца
												без переплат
											</p>
										</div>
									</div>
									<a target="_blank" rel="nofollow" href="https://halvacard.ru/shops/mebel-i-interer/fandeco" class="btn btn_white">
										подробнее на <span>официальном</span> сайте
									</a>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
{/block}
{block 'bottomJs'}
	<script src="{$assets_source}js/product.js?v={$_modx->config['assets_version']}"></script>
	<script class="mindbox">
		window.sendMindbox = function() {
			window.mindbox('async', {
				operation: 'Website.ViewProduct.ArteLamp',
				data     : {
					viewProduct: {
						product: {
							ids: {
								websiteArteLampRu: "{$_modx->resource.id}"
							}
						}
					}
				}
			})
		}
	</script>
{/block}
