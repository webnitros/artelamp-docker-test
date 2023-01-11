<?php
ini_set('display_errors', 1);
ini_set("max_execution_time", 50);
define('MODX_API_MODE', true);
require dirname(__FILE__) . '/public_html/index.php';

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'artelamp',
        #'default_database' => 'development',
        'artelamp' => [
            'name' => 'artelamp',
            'connection' => $modx->pdo
            #'connection' => $pdo
        ]
    ],
    'version_order' => 'creation'
];
