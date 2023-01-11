<?php

define('MODX_API_MODE', true);
require dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/index.php';

$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

$class = 'mspPayAnyWayPaymentHandler';
$fqn = $modx->getOption('minishop2_class', null, 'minishop2.minishop2', true);
$corePath = $modx->getOption('minishop2_class_path', null, MODX_CORE_PATH . 'components/minishop2/', true);

/** @var miniShop2 $miniShop2 */
$miniShop2 = $modx->getService(
    $fqn,
    '',
    $corePath . 'model/minishop2/',
    array('core_path' => $corePath)
);
if (!$miniShop2) {
    exit("Error: could not load class 'miniShop2' ");
}
$miniShop2->loadCustomClasses('payment');
if (!class_exists($class)) {
    exit("Error: could not load payment class '{$class}'");
}

/** @var msPaymentInterface|mspPayAnyWayPaymentHandler $handler */
$handler = new $class($modx->newObject('msOrder'));

if ($handler->msppayanyway->getOption('payment_show_log', null)) {
    $handler->msppayanyway->log("[{$class}] Request", $_REQUEST, true);
}

if (!$handler->isPaymentParams($_REQUEST)) {
    $handler->msppayanyway->log("[{$class}] Failed to get the data", $_REQUEST, true);
    echo $handler->getPaymentFailureAnswer();
    die();
}

/** @var msOrder $order */
if ($order = $modx->getObject('msOrder', (int)$_REQUEST['MNT_TRANSACTION_ID'])) {
    $redirect = $handler->receive($order, $_REQUEST);
    echo $handler->getPaymentSuccessAnswer();
    if (!empty($redirect)) {
        $modx->sendRedirect($redirect);
    }
} else {
    echo $handler->getPaymentFailureAnswer();
    die();
}