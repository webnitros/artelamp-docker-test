<?php
/**
 * Settings Russian Lexicon Entries for msoneclick
 *
 * @package msoneclick
 * @subpackage lexicon
 */
$_lang['area_msoneclick'] = 'Основные';
$_lang['area_msoneclick_main'] = 'Основные';
$_lang['area_msoneclick_user'] = 'Новый пользовател без email адреса';
$_lang['area_msoneclick_phone'] = 'Обработка телефонного номера';
$_lang['setting_msoneclick_display_field'] = 'Поля в форме';
$_lang['setting_msoneclick_display_field_desc'] = 'Перечислите поля необходимые для заполнения в форме для отправки заказа';

$_lang['setting_msoneclick_required_fields'] = 'Обязательные поля для заполнения пользователем';
$_lang['setting_msoneclick_required_fields_desc'] = 'Без эти поле заказ не отправиться';
$_lang['setting_msoneclick_frontend_css'] = 'Css для фронтенда';
$_lang['setting_msoneclick_frontend_css_desc'] = 'По-умолчанию assets/components/msoneclick/css/web/default.css';
$_lang['setting_msoneclick_frontend_js'] = 'Js для фронтенда';
$_lang['setting_msoneclick_frontend_js_desc'] = 'По-умолчанию assets/components/msoneclick/js/web/default.js';
$_lang['setting_msoneclick_payments'] = 'Метод оплаты по-умолчанию';
$_lang['setting_msoneclick_payments_desc'] = 'Укажите метод оплаты по-умолчанию для отправки быстрого заказ';
$_lang['setting_msoneclick_deliverys'] = 'Метод доставки по-умолчанию';
$_lang['setting_msoneclick_deliverys_desc'] = 'Укажите метод доставки по-умолчанию для отправки быстрого заказ';
$_lang['setting_msoneclick_mask_phone'] = 'Включить маску телефонного номера';
$_lang['setting_msoneclick_mask_phone_desc'] = 'На поле с телефоном будет добавлена обяательная маска +7 (999) 999 9999';
$_lang['setting_msoneclick_mask_phone_format'] = 'Маска телефона';
$_lang['setting_msoneclick_mask_phone_format_desc'] = 'Формат ввода долже: +9 (999) 999-9999';
$_lang['setting_msoneclick_framework'] = 'Подключить framework';
$_lang['setting_msoneclick_framework_desc'] = 'По умолчанию default будет подключен свой скрипт для запуска модельного окна. Можно указать default,bootstrap,semantic,materialize,uIkit для этих фрейм ворково определены функци запуска модельного окна';


$_lang['setting_msoneclick_email_generate'] = 'Автоматически генерировать имя пользователя';
$_lang['setting_msoneclick_email_generate_desc'] = 'По умолчанию "Да". Если email адрес не обязателен для заполнения то для пользователя будет сгенерирован свой email';

$_lang['setting_msoneclick_email_site'] = 'Имя сайт для нового пользователя';
$_lang['setting_msoneclick_email_site_desc'] = 'По умолчанию пусто. Если оставить пустым то будет автоматически подставлено значение из $_SERVER["HTTP_HOST"]. Пример заполнения: "domain.ru"';

$_lang['setting_msoneclick_email_prefix'] = 'Префикс для нового пользователя';
$_lang['setting_msoneclick_email_prefix_desc'] = 'По умолчанию "msoneclick". Префик необходим для создания нового пользователя если email адрес является не обязательным. Пример: prefix@domain.ru';


$_lang['setting_msoneclick_email_own_name'] = 'Создавать заказ на одного пользователя';
$_lang['setting_msoneclick_email_own_name_desc'] = 'По умолчанию пусто. Вы можете указать email адрес на который будут создаваться все заказы оформленные через msOneClick. Если указать то емаил не будет генерироваться.';


$_lang['setting_msoneclick_prefix_enabled'] = 'Вырезать код страны из телефона';
$_lang['setting_msoneclick_prefix_enabled_desc'] = 'По умолчанию <b>Нет</b>. Если вы установите <b>Да</b> то необходимо прописать коды стран а так же добавить select в форму с кодами стран и установить маску телефона <b>(999) 999-9999</b>.';


$_lang['setting_msoneclick_prefix_phone'] = 'Коды стран для телефонов';
$_lang['setting_msoneclick_prefix_phone_desc'] = 'По умолчанию пусто. Можно указать код страны в формате "7:11,8:11,380:12" где первая цифра это код страны, а вторая после <b>:</b> это длина номера телефона. Если длина телефона совпадет и первая цифры будет кодом страны то телефон будет обрезан';

$_lang['setting_msoneclick_copy_count'] = 'Копировать количество со страницы';
$_lang['setting_msoneclick_copy_count_desc'] = 'По умолчанию <b>Да</b>. Если для поля количесво заказываемых товаров указать на поле < input type="number" name="count" class="msoclick_count_{$id}" > префикс для класса, то количество товаров введенное на странице автоматически будет передаваться в модельное окно';



$_lang['setting_msoneclick_base64_encode'] = 'Включить кодирования html';
$_lang['setting_msoneclick_base64_encode_desc'] = 'По умолчанию <b>Нет</b>. Эта возможность кодирует в base64 html модельного окна получаемого через ajax, иногда бывают конфликты из за бесплатных SSL сертификатов, антивирус думает что ему возвращается какой то вирус в место формы и начинает блокировать запрос.';



$_lang['setting_msoneclick_clear_order_date'] = 'Стирать данные из сессии';
$_lang['setting_msoneclick_clear_order_date_desc'] = 'По умолчанию <b>Да</b>. Автоматически стирает такие данные как phone,receiver,email и тд. сразу после отправки сообщения';


