<?php
include_once 'setting.inc.php';
$_lang['msexportordersexcel_console_profile'] = 'Профиль: <b>[[+name]]</b> <br>Класс экспорта: <b>[[+classExport]]</b><br>Получения данных<br><b>ожидание..........</b>';

$_lang['msexportordersexcel_console_max_execution_time'] = 'Максимальное время исполнения php: <b>[[+time]]</b> секунд.';
$_lang['msexportordersexcel_console_total_export'] = 'Выгружено записей: <b>[[+total_export]]</b> из <b>[[+total]]</b><br>Фоматирование данных<br><b>ожидание..........</b>';
$_lang['msexportordersexcel_console_step_export'] = 'Экспорт<br><b>ожидание..........</b>';
$_lang['msexportordersexcel_console_download'] = 'Скачивание файла...';
$_lang['msexportordersexcel_console_link'] = '<a href="[[+download_link]]">Скачать [[+filename]]</a>';
$_lang['msexportordersexcel_console_end'] = 'Затрачено времени [[+time]]';
$_lang['msexportordersexcel_console_ini_get'] = 'max_execution_time: [[+max_execution_time]]: memory_limit: [[+memory_limit]]<br>';
$_lang['msexportordersexcel_console_error_export'] = 'Произошла ошибка во время экспорт. Подробная информация в логах';
$_lang['msexportordersexcel_console_error_testins_sql'] = 'Произошла ошибка во время отправки запроса SQL<br><br>[[+message]]<br><br>Необходимо исправить SQL в дополнительных запросах запрос';
$_lang['msexportordersexcel_console_error_loadclass'] = 'Ошибка во время загрузки класса.';
$_lang['msexportordersexcel_console_error_exists_file'] = 'Ошибка. Не удалось получить файл<br> [[+path]].';
$_lang['msexportordersexcel_console_error_handler'] = 'Ошибка во время экспорта<br><br>Ответ сервера: <br> [Error] [[+message]] <br> [File] [[+file]] <br> [Line] [[+line]] <br> <br> Необхомдимо уменьшить количество выгружаемых записей. <br>Текущий limit: <b>[[+limit]]</b> <br>Чтобы скрипт успевал получать все записи из базы данных и экспортировать их.<br>Для выгрузки всей базы, используйте пропуск start для того чтобы выгрузить частями. <br>';
