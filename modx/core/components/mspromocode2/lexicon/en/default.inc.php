<?php
require dirname(__FILE__) . '/../ru/default.inc.php';

require 'common.inc.php';
require 'setting.inc.php';
require 'frontend.inc.php';
require 'minishop2.inc.php';

// Основное
$_lang['mspromocode2'] = 'msPromoCode2';
$_lang['mspc2_menu_desc'] = 'Promo codes for products';

// Табы
$_lang['mspc2_tab_coupons'] = 'Promo-codes';
$_lang['mspc2_tab_config'] = 'Config';
$_lang['mspc2_tab_joins'] = 'Joins';
$_lang['mspc2_tab_joins_categories'] = 'Categories';
$_lang['mspc2_tab_joins_products'] = 'Products';

// Названия столбцов
$_lang['mspc2_grid_list'] = 'List';
$_lang['mspc2_grid_code'] = 'Code';
$_lang['mspc2_grid_coupon'] = 'Coupon';
$_lang['mspc2_grid_discount'] = 'Discount';
$_lang['mspc2_grid_count'] = 'Count';
$_lang['mspc2_grid_orders'] = 'Orders';
$_lang['mspc2_grid_name'] = 'Title';
$_lang['mspc2_grid_resource'] = 'Resource';
$_lang['mspc2_grid_description'] = 'Description';
$_lang['mspc2_grid_lifetime'] = 'Lifetime';
$_lang['mspc2_grid_startedon'] = 'Started';
$_lang['mspc2_grid_stoppedon'] = 'Stopped';
$_lang['mspc2_grid_createdon'] = 'Created on';
$_lang['mspc2_grid_updatedon'] = 'Updated on';
$_lang['mspc2_grid_active'] = 'Active';
$_lang['mspc2_grid_clipboard'] = '&nbsp;';

// Название полей
$_lang['mspc2_field_list'] = 'List';
$_lang['mspc2_field_code'] = 'Code';
$_lang['mspc2_field_count'] = 'Count';
$_lang['mspc2_field_count_desc'] = '∞';
$_lang['mspc2_field_discount'] = 'Discount';
$_lang['mspc2_field_name'] = 'Title';
$_lang['mspc2_field_description'] = 'Description';
$_lang['mspc2_field_startedon'] = 'Started';
$_lang['mspc2_field_stoppedon'] = 'Stopped';
$_lang['mspc2_field_createdon'] = 'Created';
$_lang['mspc2_field_updatedon'] = 'Updated';
$_lang['mspc2_field_showinfo'] = 'Показывать предупреждения';
$_lang['mspc2_field_showinfo_desc'] = 'Отображать "жёлтые" предупреждения при применении промо-кода';
$_lang['mspc2_field_allcart'] = 'Discount on all cart';
$_lang['mspc2_field_allcart_desc'] = 'Apply the discount to the whole basket. You can limit the application of a coupon to the presence in the cart only of specific items (on the tab «Joins»).<br><i>When activated, part of the promo-code functionality is disabled.</i>';
$_lang['mspc2_field_oneunit'] = 'На одну единицу товара';
$_lang['mspc2_field_oneunit_desc'] = 'Применять скидку только к одной единице товара';
$_lang['mspc2_field_onlycart'] = 'Только в корзине';
$_lang['mspc2_field_onlycart_desc'] = 'Отображать цену со скидкой только в корзине';
$_lang['mspc2_field_unsetifnull'] = 'Do not apply without discount';
$_lang['mspc2_field_unsetifnull_desc'] = 'Cancel if there are no products in the cart that match this promotion code';
$_lang['mspc2_field_unsetifnull_msg'] = 'Canceling text';
$_lang['mspc2_field_unsetifnull_msg_desc'] = 'use standard';
$_lang['mspc2_field_oldprice'] = 'Without the old price';
$_lang['mspc2_field_oldprice_desc'] = 'Apply only to goods without the old price';
$_lang['mspc2_field_active'] = 'Active';

// Заголовки окон
$_lang['mspc2_window_coupon_create'] = 'Create promo code';
$_lang['mspc2_window_coupon_update'] = 'Edit promo code';

// Кнопки
$_lang['mspc2_button_config'] = 'Configuration';
$_lang['mspc2_button_joins'] = 'Joins';

// Подтверждения
// $_lang['mspc2_confirm_'] = '';

// Ошибки конкретизированные
$_lang['mspc2_err_code_required'] = 'Необходимо указать код';
$_lang['mspc2_err_code_unique'] = 'Код купона должен быть уникальным';
$_lang['mspc2_err_code_characters'] = 'Код купона содержит запрещённые символы';

// Успехи
// $_lang['mspc2_success_'] = '';

// Сообщения
$_lang['mspc2_message_allcart_joins_disabled'] = 'This function does not work when <b>discount for the whole basket is enabled</b>.';

// ComboBox
$_lang['mspc2_combo_joins_select'] = 'Select...';

// Другое
// $_lang['mspc2_'] = '';