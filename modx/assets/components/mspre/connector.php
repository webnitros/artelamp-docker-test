<?php
/**
 * mspre Connector
 * @package mspre
 */
$file = dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
if (!file_exists($file)) {
    $file = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
require_once $file;
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('mspre.core_path', null, $modx->getOption('core_path') . 'components/mspre/');
require_once $corePath . 'model/mspre.class.php';
$modx->mspre = new mspre($modx);

$modx->lexicon->load('mspre:default');
if (!$modx->getAuthenticatedUser('mgr')) {
    exit(array(
        'success' => false,
        'message' => 'Access closed',
    ));
}

/* handle request */
$path = $modx->getOption('processorsPath', $modx->mspre->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));
