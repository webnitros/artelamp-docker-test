<?php

//ini_set('display_errors', 1);
//ini_set('error_reporting', -1);

if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH.'index.php';

/** @var UserLocation $UserLocation */
$UserLocation = $modx->getService('userlocation.UserLocation', '', MODX_CORE_PATH.'components/userlocation/model/');
$modx->lexicon->load('userlocation:default');
$modx->lexicon->load('userlocation:manager');
$modx->lexicon->load('userlocation:errors');

// handle request
$corePath = $modx->getOption('userlocation_core_path', null, $modx->getOption('core_path').'components/userlocation/');
$path = $modx->getOption('processorsPath', $UserLocation->config, $corePath.'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location'        => '',
]);