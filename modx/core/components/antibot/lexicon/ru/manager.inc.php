<?php
include_once 'setting.inc.php';

$_lang['antibot'] = 'antiBot';
$_lang['antibot_menu_desc'] = 'Блокировщик парсеров и ботов';
$_lang['antibot_intro_msg'] = 'Вы можете выделять сразу несколько предметов при помощи Shift или Ctrl.';

$_lang['antibot_grid_search'] = 'Поиск';
$_lang['antibot_grid_actions'] = 'Действия';


$_lang['antibot_rule_grid_ips'] = 'Список IP адресов';
$_lang['antibot_hit_search'] = 'Поиск по URL и UserAgent';
$_lang['antibot_combo_select_method'] = 'Все методы';
$_lang['antibot_combo_select_code_response'] = 'Все коды';
$_lang['antibot_all_methods'] = 'Все методы';
$_lang['antibot_all_code_response'] = 'Все коды';
$_lang['antibot_all_guest'] = 'Все гости';
$_lang['antibot_guest_change'] = 'Выберите посетителя';

$_lang['antibot_search_code'] = 'Поиск по xКод';
$_lang['antibot_search_method'] = 'Поиск по Методу';
$_lang['antibot_search_ip'] = 'Поиск по IP';
$_lang['antibot_guest_form_begin'] = 'Выбрать с';
$_lang['antibot_guest_form_end'] = 'Выбрать по';
$_lang['antibot_hit_form_begin'] = 'Выбрать хиты с';
$_lang['antibot_hit_form_end'] = 'Выбрать хиты по';
$_lang['antibot_btn_reset'] = 'Сбросить';

$_lang['antibot_hits'] = 'Список хитов';
$_lang['antibot_hit_id'] = 'Id';
$_lang['antibot_hit_hits'] = 'Хитов';
$_lang['antibot_hit_url'] = 'Переход';
$_lang['antibot_hit_context'] = 'Контекст';
$_lang['antibot_hit_url_from'] = 'Откуда';
$_lang['antibot_hit_method'] = 'Метод';
$_lang['antibot_hit_guest_id'] = 'Id гостя';
$_lang['antibot_hit_user_id'] = 'Id пользователь';
$_lang['antibot_hit_username'] = 'Пользователь';
$_lang['antibot_hit_ip'] = 'IP';
$_lang['antibot_hit_user_agent'] = 'USER AGENT';
$_lang['antibot_hit_blocked'] = 'Заблокирован';
$_lang['antibot_hit_code_response'] = 'Код XXX';
$_lang['antibot_hit_remove'] = 'Удалить Хит';
$_lang['antibot_hits_remove'] = 'Удалить Хиты';
$_lang['antibot_hit_remove_confirm'] = 'Вы уверены, что хотите удалить этот Хит?';
$_lang['antibot_hits_remove_confirm'] = 'Вы уверены, что хотите удалить эти Хиты?';

$_lang['antibot_hit_err_nf'] = 'Хит не найден.';
$_lang['antibot_hit_err_ns'] = 'Хит не указан.';
$_lang['antibot_hit_err_remove'] = 'Ошибка при удалении Хита.';
$_lang['antibot_hit_err_save'] = 'Ошибка при сохранении Хита.';

$_lang['antibot_hit_createdon'] = 'Первый заход';
$_lang['antibot_hit_updatedon'] = 'Последний заход';

$_lang['antibot_hit_all_remove'] = 'Отчистить все хиты';
$_lang['antibot_hit_all_remove_confirm'] = 'Вы уверены, что хотите удалить все хиты?';
$_lang['antibot_hit_btn_remove_all'] = 'Отчистить все хиты';



$_lang['antibot_guests'] = 'Список посетителей';
$_lang['antibot_guest_id'] = 'Id';
$_lang['antibot_guest_hits'] = 'Хитов';
$_lang['antibot_guest_user_id'] = 'Id пользователя';
$_lang['antibot_guest_username'] = 'Пользователь';
$_lang['antibot_guest_ip'] = 'IP';
$_lang['antibot_guest_user_agent'] = 'USER AGENT';
$_lang['antibot_guest_createdon'] = 'Первый заход';
$_lang['antibot_guest_updatedon'] = 'Последний заход';
$_lang['antibot_guest_fake'] = 'Фэйковый бот';
$_lang['antibot_guest_blocked'] = 'Заблокирован';


$_lang['antibot_guest_remove'] = 'Удалить Пользователя';
$_lang['antibot_guests_remove'] = 'Удалить Пользоватей';
$_lang['antibot_guest_remove_confirm'] = 'Вы уверены, что хотите удалить этого Пользователя?';
$_lang['antibot_guests_remove_confirm'] = 'Вы уверены, что хотите удалить этих Пользователе?';
$_lang['antibot_guest_active'] = 'Включено';

$_lang['antibot_guest_err_nf'] = 'Пользователь не найден.';
$_lang['antibot_guest_err_ns'] = 'Пользователь не указан.';
$_lang['antibot_guest_err_remove'] = 'Ошибка при удалении Пользователя.';
$_lang['antibot_guest_err_save'] = 'Ошибка при сохранении Пользователя.';

$_lang['antibot_guest_all_remove'] = 'Отчистить все хиты';
$_lang['antibot_guest_all_remove_confirm'] = 'Вы уверены, что хотите удалить всех пользователей? Вместе с пользователями будут удалены и все хиты.';
$_lang['antibot_guest_btn_remove_all'] = 'Отчистить всех пользователей';


// Stop List
$_lang['antibot_stoplists'] = 'Стоп-листы';
$_lang['antibot_stoplist_id'] = 'Id';
$_lang['antibot_stoplist_user_agent'] = 'USER AGENT';
$_lang['antibot_stoplist_user_agent_desc'] = 'Можно вести часть текста из user agent бота/пользователя. Например "compatible; AhrefsBot/" или "compatible; SemrushBot"';
$_lang['antibot_stoplist_user_id'] = 'Id пользователя';
$_lang['antibot_stoplist_context'] = 'Контекст';
$_lang['antibot_stoplist_context_desc'] = 'Выберите контекст для которого необходимо проводить проверку. Или оставьте все чтобы учет шел для всех контекстов';
$_lang['antibot_stoplist_comment'] = 'Комментарий для администратора';
$_lang['antibot_stoplist_message'] = 'Возвращаемое сообщение';
$_lang['antibot_stoplist_redirect_url'] = 'Страница редиректа';
$_lang['antibot_stoplist_username'] = 'Пользователь';
$_lang['antibot_stoplist_ip_bloks'] = 'Блокировать IP';
$_lang['antibot_stoplist_ip_bloks_desc'] = 'Введите IP адрес который необходимо заблокировать. Например 222.221.221.121';
$_lang['antibot_stoplist_ip_1'] = 'IP';
$_lang['antibot_stoplist_ip_2'] = 'IP';
$_lang['antibot_stoplist_ip_3'] = 'IP';
$_lang['antibot_stoplist_ip_4'] = 'IP';
$_lang['antibot_stoplist_context_all'] = 'Все контексты';
$_lang['antibot_stoplist_active'] = 'Активный';
$_lang['antibot_stoplist_message_value'] = 'Доступ запрещен';
$_lang['antibot_stoplist_comment_value'] = 'Блокировка бота';
$_lang['antibot_stoplist_redirect_url_desc'] = 'Если указать страницу для редиректа то в место сообщения будет выводится эта страница';
$_lang['antibot_stoplist_recaptcha'] = 'Пройти проверку через капчу google';
$_lang['antibot_stoplist_recaptcha_desc'] = 'Установите галочку если хотите снять ограничения при прохождении проверки через google капчу. Внимание!!! предварительно вам необходимо создать капчу и указать секретный и публичный ключи в системных настройках';

$_lang['antibot_stoplist_create'] = 'Добавить стоп-лист';
$_lang['antibot_stoplist_remove'] = 'Удалить Стоп-лист';
$_lang['antibot_stoplists_remove'] = 'Удалить Стоп-листы';
$_lang['antibot_stoplist_remove_confirm'] = 'Вы уверены, что хотите удалить этот Стоп-лист?';
$_lang['antibot_stoplists_remove_confirm'] = 'Вы уверены, что хотите удалить эти Стоп-листы?';
$_lang['antibot_stoplist_active'] = 'Включено';
$_lang['antibot_stoplist_enable'] = 'Включить стоп-лист';
$_lang['antibot_stoplists_enable'] = 'Включить стоп-листы';
$_lang['antibot_stoplist_disable'] = 'Отключить стоп-листп';
$_lang['antibot_stoplists_disable'] = 'Отключить стоп-листы';

$_lang['antibot_stoplist_err_nf'] = 'Стоп-лист не найден.';
$_lang['antibot_stoplist_err_ns'] = 'Стоп-лист не указан.';
$_lang['antibot_stoplist_err_remove'] = 'Ошибка при удалении Стоп-листа.';
$_lang['antibot_stoplist_err_save'] = 'Ошибка при сохранении Стоп-листа.';
$_lang['antibot_stoplist_err_ae'] = 'USER AGENT с такими же параметрами уже существует';
$_lang['antibot_stoplist_username_guest'] = 'Гость';


// Фэйковы бот
$_lang['antibot_guest_fake_yandex'] = 'Проверить Yandex бота';
$_lang['antibot_guest_fake_mail'] = 'Проверить Mail бота';
$_lang['antibot_guest_fake_google'] = 'Проверить Google бота';

$_lang['antibot_stoplist_download_btn'] = 'Скачать стоп-листы ботов';
$_lang['antibot_stoplist_download'] = 'Скачать стоп-листы ботов';
$_lang['antibot_stoplist_download_confirm'] = 'Вы уверены что хотите скачать стоп-листы с ботами?';
$_lang['antibot_could_not_load_service_request'] = 'Не удалось загрузить сервис проверки бота';



$_lang['antibot_blocked_success_message'] = 'Новые заблокированные:<br> [[+list]]';
$_lang['antibot_blocked_title_ip'] = 'Блокировать IP';
$_lang['antibot_blockeds_title_ip'] = 'Блокировать IP';
$_lang['antibot_blocked_confirm_ip'] = 'Вы уверены что хотите заблокировать этот IP?';
$_lang['antibot_blockeds_confirm_ip'] = 'Вы уверены что хотите заблокировать эти IP?';


$_lang['antibot_blocked_title_useragent'] = 'Блокировать USER AGENT';
$_lang['antibot_blockeds_title_useragent'] = 'Блокировать USER AGENTS';
$_lang['antibot_blocked_confirm_useragent'] = 'Вы уверены что хотите заблокировать этот USER AGENT?';
$_lang['antibot_blockeds_confirm_useragent'] = 'Вы уверены что хотите заблокировать эти USER AGENTS?';




$_lang['antibot_hit_blocked_success'] = 'Заблокированы';
$_lang['antibot_action_blocked'] = 'Заблокировать по IP';
$_lang['antibot_action_blockeds'] = 'Заблокировать по IP';
$_lang['antibot_message_blocked'] = 'Доступ запрещен';
$_lang['antibot_comment_blocked'] = 'Блокировка из списка [[+name]]';
$_lang['antibot_blocked_is_have'] = ' (Уже был в списке)';
$_lang['antibot_action_blocked_user_agent'] = 'Заблокировать по USER AGENT';
$_lang['antibot_action_blockeds_user_agent'] = 'Заблокировать по USER AGENT';


$_lang['antibot_action_check_bot'] = 'Проверить бота';
$_lang['antibot_all_boots'] = 'Все боты';
$_lang['antibot_bot_yandex'] = 'Yandex.ru';
$_lang['antibot_bot_google'] = 'Google.com';
$_lang['antibot_bot_mail'] = 'Mail.ru';
$_lang['antibot_bot_bing'] = 'Bing.com';



$_lang['antibot_action_check_ip'] = 'Проверить обратные DNS';
$_lang['antibot_bots_change'] = 'Выберите бота для проверки';
$_lang['antibot_bots_change_description'] = 'для этого бота будут проверены обратные DNS адреса';






$_lang['antibot_api_get_empty_hostname'] = 'Не удалось получить имя хоста по IP [[+hostname]]';
$_lang['antibot_api_get_could_not_found'] = 'В имени хоста [[+hostname]] c IP [[+ipbota]] не найден домен бота <b>[[+bot]]</b>';
$_lang['antibot_api_get_could_not_ip'] = 'Не удалось получить ip по имени хоста [[+hostname]] для бота [[+bot]]';
$_lang['antibot_api_get_could_not_ip_ip'] = 'IP бота [[+ipbota]] отличается от IP';

// verifyAuthentication
$_lang['antibot_api_user_package_key_error'] = 'Не указан ключ версии компонента. Необходимо прописать купленный API KEY приложения для провадера modstore.pro для использования сервиса.';
$_lang['antibot_api_user_package_version_error'] = 'Не указана версия компонента.';
$_lang['antibot_api_verify_authentication_error'] = 'Вы импользуюте не активный API KEY для работы с компонентом. Вам необходимо купить приложение на modstore.pro или сгенерировать ключ для вашего домена и прописать его у провайдера для репазитория modstore.pro';
$_lang['antibot_api_verify_authentication_error_log'] = 'Попытка получить доступ без ключа [[+hostname]] key package [[+user_package_key]]';
$_lang['antibot_api_hostname_error'] = 'Не указан hostname сайта откуда отправлен запрос';


$_lang['antibot_check_bot_ip'] = 'IP адрес бота';
$_lang['antibot_btn_blocked_yes'] = 'Заблокировать';
$_lang['antibot_btn_blocked_no'] = 'Закрыть';
$_lang['antibot_blocked_title'] = 'Проверка бота';
$_lang['antibot_btn_blocked_is_check'] = 'Бот прошел проверку';



$_lang['antibot_rules'] = 'Правила авто блокировки';
$_lang['antibot_rule_id'] = 'id';
$_lang['antibot_rule_name'] = 'Наименование';
$_lang['antibot_rule_hit_method'] = 'Метод';
$_lang['antibot_rule_core_response'] = 'xКод ответа';
$_lang['antibot_rule_hour'] = 'Искать всех кто за последние n часов сделал переходы';
$_lang['antibot_rule_hits_per_minute'] = 'Максимальное количество переходов';
$_lang['antibot_rule_captcha'] = 'Показать капчу';
$_lang['antibot_rule_createdon'] = 'Создано';
$_lang['antibot_rule_updatedon'] = 'Обновлено';
$_lang['antibot_rule_active'] = 'Включено';


$_lang['antibot_rule_create'] = 'Добавить правило';

$_lang['antibot_rule_time'] = 'Временной промежуток';
$_lang['antibot_rule_time_desc'] = '';


$_lang['antibot_rule_create'] = 'Добавить правило';
$_lang['antibot_rule_remove'] = 'Удалить правило';
$_lang['antibot_rules_remove'] = 'Удалить правила';
$_lang['antibot_rule_remove_confirm'] = 'Вы уверены, что хотите удалить это правило?';
$_lang['antibot_rules_remove_confirm'] = 'Вы уверены, что хотите удалить эти правила?';
$_lang['antibot_rule_active'] = 'Включено';
$_lang['antibot_rule_enable'] = 'Включить правило';
$_lang['antibot_rules_enable'] = 'Включить правила';
$_lang['antibot_rule_disable'] = 'Отключить правило';
$_lang['antibot_rules_disable'] = 'Отключить правила';

$_lang['antibot_rule_err_nf'] = 'Правило не найден.';
$_lang['antibot_rule_err_ns'] = 'Правило не указан.';
$_lang['antibot_rule_err_remove'] = 'Ошибка при удалении правила.';
$_lang['antibot_rule_err_save'] = 'Ошибка при сохранении правила.';


$_lang['antibot_rule_err_name'] = 'Укажите имя';
$_lang['antibot_rule_err_hit_method'] = 'Укажите метод';
$_lang['antibot_rule_err_core_response'] = 'Укажите xКод ответ';
$_lang['antibot_rule_err_hour'] = 'Укажите n часов';
$_lang['antibot_rule_err_hits_per_minute'] = 'Укажите максимальное количество переходов';


$_lang['antibot_rule_collection'] = 'Получить список IP';
$_lang['antibot_guest_happy'] = 'Благополучный';
$_lang['antibot_action_happy_enable'] = 'Благополучный';
$_lang['antibot_action_happy_disable'] = 'Не благополучный';




$_lang['antibot_ips_user_id'] = 'Пользователь';
$_lang['antibot_ips_methods'] = 'Методы';
$_lang['antibot_ips_codes_response'] = 'Код XXX';
$_lang['antibot_ips_guest_id'] = 'Гость';
$_lang['antibot_ips_ip'] = 'IP адрес';
$_lang['antibot_ips_total'] = 'Количество переходов';
$_lang['antibot_ips_user_agent'] = 'UserAgent';



/**
********************
 * Поля поиска
********************
 */
$_lang['antibot_search_ip'] = 'По IP';
$_lang['antibot_search_url'] = 'По url';
$_lang['antibot_search_url_from'] = 'По url_from';
$_lang['antibot_search_user_agent'] = 'По user_agent';

