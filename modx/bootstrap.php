<?php
define('BASE_DIR', dirname(__FILE__) . '/');
require_once BASE_DIR . 'vendor/autoload.php';

$appClass = 'modY';

\App\Helpers\Env::loadFile(BASE_DIR . '.env');

if (!defined('MODX_CONFIG_KEY')) {
    define('MODX_CONFIG_KEY', 'config');
}

if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', BASE_DIR . 'core/');
}
