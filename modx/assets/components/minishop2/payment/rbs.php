<?php

define('MODX_API_MODE', true);

require dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/index.php';

$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

$miniShop2 = $modx->getService('minishop2');
$miniShop2->loadCustomClasses('payment');

if($_GET['error']) {
	echo '<div style="margin:50px auto 0; font-size:16px;max-width:600px; text-align:center;">';
		echo "Ошибка при переходе на форму оплаты";
		echo "<br>";
		echo "<br>";
		echo "errorCode: " . $_GET['code'];
		echo "<br>";
		echo "errorMessage: " . $_GET['message'];
		echo "<br>";
		echo "<br>";
		echo "Обратитесь к администратору";
		echo "<br>";
		echo "<br>";
		echo "<a href='/'>вернуться в магазин</a>";
	echo "</div>";
	return false;
}
if (!class_exists('RBS')) {
    exit('Error: could not load payment class "RBS".');
}
$context = '';
$params = array();

$handler = new RBS($modx->newObject('msOrder'));
if (isset($_GET['orderId'])) {
    $result = $handler->receiver($_GET['orderId']);
} else {
    $handler->returnMain();
}
die;