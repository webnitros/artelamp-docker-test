<?php
require 'common.inc.php';
require 'setting.inc.php';
require 'frontend.inc.php';
require 'minishop2.inc.php';

// Основное
$_lang['mspromocode2'] = 'msPromoCode2';
$_lang['mspc2_menu_desc'] = 'Промо-коды на товары';

// Табы
$_lang['mspc2_tab_coupons'] = 'Промо-коды';
$_lang['mspc2_tab_config'] = 'Конфиг';
$_lang['mspc2_tab_joins'] = 'Связи';
$_lang['mspc2_tab_joins_categories'] = 'Разделы';
$_lang['mspc2_tab_joins_products'] = 'Товары';

// Названия столбцов
$_lang['mspc2_grid_list'] = 'Список';
$_lang['mspc2_grid_code'] = 'Код';
$_lang['mspc2_grid_coupon'] = 'Купон';
$_lang['mspc2_grid_discount'] = 'Скидка';
$_lang['mspc2_grid_count'] = 'Кол-во';
$_lang['mspc2_grid_orders'] = 'Заказов';
$_lang['mspc2_grid_name'] = 'Название';
$_lang['mspc2_grid_resource'] = 'Ресурс';
$_lang['mspc2_grid_description'] = 'Описание';
$_lang['mspc2_grid_lifetime'] = 'Время жизни';
$_lang['mspc2_grid_startedon'] = 'Начало';
$_lang['mspc2_grid_stoppedon'] = 'Конец';
$_lang['mspc2_grid_createdon'] = 'Создано';
$_lang['mspc2_grid_updatedon'] = 'Обновлено';
$_lang['mspc2_grid_active'] = 'Вкл';
$_lang['mspc2_grid_clipboard'] = '&nbsp;';

// Название полей
$_lang['mspc2_field_list'] = 'Список';
$_lang['mspc2_field_code'] = 'Код';
$_lang['mspc2_field_count'] = 'Кол-во';
$_lang['mspc2_field_count_desc'] = '∞';
$_lang['mspc2_field_discount'] = 'Скидка';
$_lang['mspc2_field_name'] = 'Название';
$_lang['mspc2_field_description'] = 'Описание';
$_lang['mspc2_field_startedon'] = 'Начало';
$_lang['mspc2_field_stoppedon'] = 'Конец';
$_lang['mspc2_field_createdon'] = 'Создано';
$_lang['mspc2_field_updatedon'] = 'Обновлено';
$_lang['mspc2_field_showinfo'] = 'Показывать предупреждения';
$_lang['mspc2_field_showinfo_desc'] = 'Отображать "жёлтые" предупреждения при применении промо-кода.';
$_lang['mspc2_field_allcart'] = 'Скидка на всю корзину';
$_lang['mspc2_field_allcart_desc'] = 'Применять скидку ко всей корзине. Можно ограничить применение купона присутствием в корзине только конкретных товаров (на вкладке «Связи»).<br><i>При активации отключается часть функционала промо-кода.</i>';
$_lang['mspc2_field_oneunit'] = 'На одну единицу товара';
$_lang['mspc2_field_oneunit_desc'] = 'Применять скидку только к одной единице товара.';
$_lang['mspc2_field_onlycart'] = 'Только в корзине';
$_lang['mspc2_field_onlycart_desc'] = 'Отображать цену со скидкой только в корзине.';
$_lang['mspc2_field_unsetifnull'] = 'Не применять без скидки';
$_lang['mspc2_field_unsetifnull_desc'] = 'Отменять, если в корзине нет товаров, соответствующих этому промо-коду.';
$_lang['mspc2_field_unsetifnull_msg'] = 'Текст при отмене';
$_lang['mspc2_field_unsetifnull_msg_desc'] = 'использовать стандартный';
$_lang['mspc2_field_oldprice'] = 'Без старой цены';
$_lang['mspc2_field_oldprice_desc'] = 'Применять только к товарам без старой цены.';
$_lang['mspc2_field_active'] = 'Включено';

// Заголовки окон
$_lang['mspc2_window_coupon_create'] = 'Добавить промо-код';
$_lang['mspc2_window_coupon_update'] = 'Редактировать промо-код';

// Кнопки
$_lang['mspc2_button_config'] = 'Конфигурация';
$_lang['mspc2_button_joins'] = 'Связи';

// Подтверждения
// $_lang['mspc2_confirm_'] = '';

// Ошибки конкретизированные
$_lang['mspc2_err_code_required'] = 'Необходимо указать код';
$_lang['mspc2_err_code_unique'] = 'Код купона должен быть уникальным';
$_lang['mspc2_err_code_characters'] = 'Код купона содержит запрещённые символы';

// Успехи
// $_lang['mspc2_success_'] = '';

// Сообщения
$_lang['mspc2_message_allcart_joins_disabled'] = 'Данная функция не работает при включённой <b>скидке на всю корзину</b>.';

// ComboBox
$_lang['mspc2_combo_joins_select'] = 'Выбрать...';

// Другое
// $_lang['mspc2_'] = '';