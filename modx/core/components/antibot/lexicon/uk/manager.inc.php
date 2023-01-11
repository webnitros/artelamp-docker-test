<?php
include_once 'setting.inc.php';

$_lang['antibot'] = 'antiBot';
$_lang['antibot_menu_desc'] = 'Блокування парсеров і ботів';
$_lang['antibot_intro_msg'] = 'Ви можете виділяти відразу кілька предметів за допомогою Shift або Ctrl.';

$_lang['antibot_grid_search'] = 'Пошук';
$_lang['antibot_grid_actions'] = 'Дії';


$_lang['antibot_hits'] = 'Список хітів';
$_lang['antibot_hit_id'] = 'Id';
$_lang['antibot_hit_hits'] = 'Хітів';
$_lang['antibot_hit_url'] = 'Перехід';
$_lang['antibot_hit_context'] = 'Контекст';
$_lang['antibot_hit_url_from'] = 'Звідки';
$_lang['antibot_hit_method'] = 'Метод';
$_lang['antibot_hit_guest_id'] = 'Id гостя';
$_lang['antibot_hit_user_id'] = 'Id користувач';
$_lang['antibot_hit_username'] = 'Користувач';
$_lang['antibot_hit_ip'] = 'IP';
$_lang['antibot_hit_code_response'] = 'Код XXX';
$_lang['antibot_hit_user_agent'] = 'USER AGENT';
$_lang['antibot_hit_blocked'] = 'Заблоковано';
$_lang['antibot_hit_createdon'] = 'Перший візит';
$_lang['antibot_hit_updatedon'] = 'Останній візит';

$_lang['antibot_hit_remove'] = 'Видалити Хіт';
$_lang['antibot_hits_remove'] = 'Видалити Хіти';
$_lang['antibot_hit_remove_confirm'] = 'Ви впевнені, що хочете видалити цей Хіт?';
$_lang['antibot_hits_remove_confirm'] = 'Ви впевнені, що хочете видалити ці Хіти?';

$_lang['antibot_hit_err_nf'] = 'Хіт не знайдено.';
$_lang['antibot_hit_err_ns'] = 'Хіт не вказано.';
$_lang['antibot_hit_err_remove'] = 'Помилка при видаленні Хіта.';
$_lang['antibot_hit_err_save'] = 'Помилка при збереженні Хіта.';


$_lang['antibot_hit_all_remove'] = 'Відчистити все хіти';
$_lang['antibot_hit_all_remove_confirm'] = 'Ви впевнені, що хочете видалити всі хіти?';
$_lang['antibot_hit_btn_remove_all'] = 'Відчистити все хіти';

$_lang['antibot_guests'] = 'Список відвідувачів';
$_lang['antibot_guest_id'] = 'Id';
$_lang['antibot_guest_hits'] = 'Хітів';
$_lang['antibot_guest_user_id'] = 'Id користувача';
$_lang['antibot_guest_username'] = 'Користувач';
$_lang['antibot_guest_ip'] = 'IP';
$_lang['antibot_guest_user_agent'] = 'USER AGENT';
$_lang['antibot_guest_createdon'] = 'Перший візит';
$_lang['antibot_guest_updatedon'] = 'Останній візит';
$_lang['antibot_guest_fake'] = 'Фейковий бот';


$_lang['antibot_guest_remove'] = 'Видалити Користувача';
$_lang['antibot_guests_remove'] = 'Видалити користуватись';
$_lang['antibot_guest_remove_confirm'] = 'Ви впевнені, що хочете видалити цього Користувача?';
$_lang['antibot_guests_remove_confirm'] = 'Ви впевнені, що хочете видалити цих Користувача?';
$_lang['antibot_guest_active'] = 'Включено';

$_lang['antibot_guest_err_nf'] = 'Користувач не знайдений.';
$_lang['antibot_guest_err_ns'] = 'Користувач не вказано.';
$_lang['antibot_guest_err_remove'] = 'Помилка при видаленні Користувача.';
$_lang['antibot_guest_err_save'] = 'Помилка при збереженні Користувача.';

$_lang['antibot_guest_all_remove'] = 'Відчистити все хіти';
$_lang['antibot_guest_all_remove_confirm'] = 'Ви впевнені, що хочете видалити всіх користувачів? Разом з користувачами будуть видалені і всі хіти. ';
$_lang['antibot_guest_btn_remove_all'] = 'Відчистити всіх користувачів';


// Stop List
$_lang['antibot_stoplists'] = 'Стоп-листи';
$_lang['antibot_stoplist_id'] = 'Id';
$_lang['antibot_stoplist_user_agent'] = 'USER AGENT';
$_lang['antibot_stoplist_user_agent_desc'] = 'Можна вести частину тексту з user agent бота / користувача. Наприклад "compatible; AhrefsBot /" або "compatible; SemrushBot" ';
$_lang['antibot_stoplist_user_id'] = 'Id користувача';
$_lang['antibot_stoplist_context'] = 'Контекст';
$_lang['antibot_stoplist_context_desc'] = 'Виберіть контекст для якого необхідно проводити перевірку. Або залиште все щоб облік йшов для всіх контекстів ';
$_lang['antibot_stoplist_comment'] = 'Коментар для адміністратора';
$_lang['antibot_stoplist_message'] = 'повертається повідомлення';
$_lang['antibot_stoplist_redirect_url'] = 'Сторінка редиректу';
$_lang['antibot_stoplist_username'] = 'Користувач';
$_lang['antibot_stoplist_ip_bloks'] = 'Блокувати IP';
$_lang['antibot_stoplist_ip_bloks_desc'] = 'Введіть IP адресу який потрібно заблокувати. Наприклад 222.221.221.121 ';
$_lang['antibot_stoplist_ip_1'] = 'IP';
$_lang['antibot_stoplist_ip_2'] = 'IP';
$_lang['antibot_stoplist_ip_3'] = 'IP';
$_lang['antibot_stoplist_ip_4'] = 'IP';
$_lang['antibot_stoplist_context_all'] = 'Всі контексти';
$_lang['antibot_stoplist_active'] = 'Активний';
$_lang['antibot_stoplist_message_value'] = 'Доступ заборонений';
$_lang['antibot_stoplist_comment_value'] = 'Блокування бота';
$_lang['antibot_stoplist_create'] = 'Додати стоп-лист';
$_lang['antibot_stoplist_remove'] = 'Видалити Стоп-лист';
$_lang['antibot_stoplists_remove'] = 'Видалити Стоп-листи';
$_lang['antibot_stoplist_remove_confirm'] = 'Ви впевнені, що хочете видалити цей Стоп-лист?';
$_lang['antibot_stoplists_remove_confirm'] = 'Ви впевнені, що хочете видалити ці Стоп-листи?';
$_lang['antibot_stoplist_active'] = 'Включено';
$_lang['antibot_stoplist_enable'] = 'Включити стоп-лист';
$_lang['antibot_stoplists_enable'] = 'Включити стоп-листи';
$_lang['antibot_stoplist_disable'] = 'Відключити стоп-лістп';
$_lang['antibot_stoplists_disable'] = 'Відключити стоп-листи';

$_lang['antibot_stoplist_err_nf'] = 'Стоп-лист не знайдено.';
$_lang['antibot_stoplist_err_ns'] = 'Стоп-лист не вказано.';
$_lang['antibot_stoplist_err_remove'] = 'Помилка при видаленні Стоп-листа.';
$_lang['antibot_stoplist_err_save'] = 'Помилка при збереженні Стоп-листа.';
$_lang['antibot_stoplist_err_ae'] = 'USER AGENT з такими ж параметрами вже існує';
$_lang['antibot_stoplist_username_guest'] = 'Гість';
