<?php
ini_set('display_errors', 1);
ini_set("max_execution_time", 50);
define('MODX_API_MODE', true);
require '../../index.php';


ini_set('display_errors', 0);
error_reporting(E_ALL);

$article = $_GET['article'];
$url = 'http://ms.fandeco.ru/rest/doc/' . $article;

// timeout of one second
$context = stream_context_create(array('http' => array(
    'timeout' => 1.0,
    'ignore_errors' => true,
)));

$response = @file_get_contents($url, false, $context);
if ($response === false && count($http_response_header) === 0) {
    // ТУТ ошибку о том что сервер недоступен
}
$response = !empty($response) ? $modx->fromJSON($response) : $response;
if (empty($response)) {
    exit(json_encode(['success' => false]));
} else {
    if (!empty($response['code']) and $response['code'] === 200) {
        if ($response['success'] === true) {
            exit($modx->toJSON($response));
        }
    }
}
exit($modx->toJSON(['success' => false]));