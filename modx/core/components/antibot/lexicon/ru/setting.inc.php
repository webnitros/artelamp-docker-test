<?php
$_lang['area_antibot_main'] = 'Основные';
$_lang['area_antibot_notification'] = 'Уведомления по привышении';

$_lang['setting_antibot_enable_statistics'] = 'Включить статистику';
$_lang['setting_antibot_enable_statistics_desc'] = 'По умолчанию Да. Если хотите отключить ведение статистики и проверку по стоп-листам установить Нет ';
$_lang['setting_antibot_disable_remove_statistics'] = 'Отключить очистку статистику';
$_lang['setting_antibot_disable_remove_statistics_desc'] = 'По умолчанию Нет. Если хотите чтобы статистика очищалась в установленный период установите ДА';
$_lang['setting_antibot_keep_block_user'] = 'Включить статистику заблокированных';
$_lang['setting_antibot_keep_block_user_desc'] = 'По умолчанию Да. Если не хотите записывать переходы заблокированных по стоп-листу, установите Нет';
$_lang['setting_antibot_max_day'] = 'Хранить статистику в днях';
$_lang['setting_antibot_max_day_desc'] = 'По умолчанию 3 дня. Укажите в течении скольки дней хранить статистику. Если указать 0 то статистика будет хранится в течении 3-х дней. Используйте enable_statistics для отключения ведения статистики';
$_lang['setting_antibot_keep_statistics_authorized_users'] = 'Включить учет авторизированных пользователей';
$_lang['setting_antibot_keep_statistics_authorized_users_desc'] = 'По умолчанию Да. Если вы не хотите учитывать авторизированных пользователей в статистике то установите Нет';
$_lang['setting_antibot_last_date_remove'] = 'Последняя дата удаления статистики';
$_lang['setting_antibot_last_date_remove_desc'] = 'Дата устанавливается автоматически по наступлению дня для удаления статистики';
$_lang['setting_antibot_keep_statistics'] = 'Включить запись переходов';
$_lang['setting_antibot_keep_statistics_desc'] = 'По умолчанию Да. Если установить Нет то статистика по переходам не будет записыватся, но стоп-листы будут работать.';
$_lang['setting_antibot_keep_statistics_context'] = 'Вести статистику в контексте "mgr"';
$_lang['setting_antibot_keep_statistics_context_desc'] = 'По умолчанию Да. Если установить Нет то переходы в административной части не будут записыватся';
$_lang['setting_antibot_ip_definition'] = 'Определение IP по';
$_lang['setting_antibot_ip_definition_desc'] = 'По умолчанию REMOTE_ADDR. Оставьте поле пусты для автоматического определени IP адреса. Или можете указать имя переменной из массива $_SERVER';


$_lang['setting_antibot_notification_max_hits'] = 'Максимальное кол-во хитов';
$_lang['setting_antibot_notification_max_hits_desc'] = 'По умолчанию 5000. Если в течении указанного периода у гостя привысится лимит, администратору сайта будет отправлено уведомление';

$_lang['setting_antibot_notification_period_check'] = 'Период проверки';
$_lang['setting_antibot_notification_period_check_desc'] = 'По умолчанию 1 days. Укажите период проверки гостя. (можно указать в днях, часах или минутах: 1 days,1 hours,2 minutes)';
