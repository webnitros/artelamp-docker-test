<?php
include_once 'setting.inc.php';
$_lang['msexportusersexcel_console_profile'] = 'Profile: <b> [[+name]] </b> <br> Export Class: <b> [[+classExport]] </b> <br> Getting Data<br><b>expectation..........</b>';

$_lang['msexportusersexcel_console_max_execution_time'] = 'Max run time php: <b>[[+time]]</b> секунд.';
$_lang['msexportusersexcel_console_total_export'] = 'Uploaded records: <b>[[+total_export]]</b> of <b> [[+total]] </b> <br> Data formatting<br><b>expectation..........</b>';
$_lang['msexportusersexcel_console_step_export'] = 'Export<br><b>expectation..........</b>';
$_lang['msexportusersexcel_console_download'] = 'Downloading a file...';
$_lang['msexportusersexcel_console_link'] = '<a href="[[+download_link]]">Download [[+filename]]</a>';
$_lang['msexportusersexcel_console_end'] = 'Spent time [[+time]]';
$_lang['msexportusersexcel_console_ini_get'] = 'max_execution_time: [[+max_execution_time]]: memory_limit: [[+memory_limit]]<br>';
$_lang['msexportusersexcel_console_error_export'] = 'An error occurred while exporting. Detailed information in the logs';
$_lang['msexportusersexcel_console_error_testins_sql'] = 'An error occurred while sending the SQL query <br> <br> [[+message]] <br> <br> You need to fix SQL in additional queries query';
$_lang['msexportusersexcel_console_error_loadclass'] = 'An error occurred while loading the class.';
$_lang['msexportusersexcel_console_error_exists_file'] = 'Error. Failed to get file [[+path]].';
$_lang['msexportusersexcel_console_error_handler'] = 'Error while exporting <br> <br> Server response: <br> [Error] [[+message]] <br> [File] [[+file]] <br> [Line] [[+line]] < br> <br> It is necessary to reduce the number of unloaded records. <br> Current limit: <b> [[+limit]] </b> <br> To have the script manage to receive all records from the database and export them. <br> To unload the entire database, use the start pass to unload parts. <br>';
