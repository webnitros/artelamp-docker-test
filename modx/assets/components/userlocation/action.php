<?php

//ini_set('display_errors', 1);
//ini_set('error_reporting', -1);

if (empty($_REQUEST['service']) OR $_REQUEST['service'] !== 'userlocation') {
    die('Access denied');
}
if (empty($_REQUEST['method'])) {
    die('Access denied');
}
if (empty($_REQUEST['method'])) {
    $_REQUEST['method'] = 'getLocation';
}

/** @noinspection PhpIncludeInspection */
require dirname(dirname(dirname(__DIR__))) . '/index.php';