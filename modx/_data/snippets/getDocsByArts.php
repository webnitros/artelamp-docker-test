id: 53
source: 1
name: getDocsByArts
properties: 'a:0:{}'
static_file: core/elements/snippets/getDocsByArts.php

-----

	$article = rawurlencode($_GET['article']);

	$url = "https://fandeco.ru/rest/skeleton/file/$article?name=files&frame=file&format=json";

// timeout of one second
	$context = stream_context_create(array('http' => array(
		'timeout' => 5.0,
		'ignore_errors' => true,
	)));
	$response = $modx->cacheManager->get($article . '_docs');
	if(empty($response)){
		$response = @file_get_contents($url, false, $context);
		$response = !empty($response) ? $modx->fromJSON($response) : $response;
		$modx->cacheManager->set($art . '_docs', $response, 7200);
	}
	if (empty($response)) {
		exit(json_encode(['success' => false,'a'=>1]));
	}
	if (!empty($response['object'])) {
		return json_encode($response,256);
	}
	exit(json_encode(['success' => false,'a'=>2],256));