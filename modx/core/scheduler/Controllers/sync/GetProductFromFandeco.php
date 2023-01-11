<?php
	/** @noinspection ALL */

	use GuzzleHttp\Client;
	use GuzzleHttp\Psr7\Request;

	include_once __DIR__ . '/_default.php';

	/**
	 * Демонстрация контроллера
	 */
	class CrontabControllerSyncGetProductFromFandeco extends CrontabControllerSync
	{
		public function process()
		{
			$limit = 1000;
			$page  = 0;
			do {
				$page++;
				if ($page > 12) {
					break;
				}
				$response = $this->getInfo($page, $limit);
				$total    = $response['total'];
				$results  = $response['results'];

				foreach ($results as $product) {
					$article        = $product['article'];
					$video_link_new = $product['video_link_new'];
					if (empty($video_link_new) and !empty($product['video_link'])) {
						$video_link_new = $product['video_link'][0];
					}
					if ($video_link_new) {
						$re = '/.+watch\?v=(.+)/m';

						preg_match_all($re, $video_link_new, $matches, PREG_SET_ORDER, 0);
						if (strpos($video_link_new, 'watch') !== FALSE and $matches[1]) {
							$video_link_new = 'https://www.youtube.com/embed/'.$matches[1];
						}
					}
					$file_is_3d_model = $product['file_is_3d_model'];
					$this->modx->exec("UPDATE ara3_ms2_products set `video_link_new`='$video_link_new', `file_is_3d_model`='$file_is_3d_model'  where `article`='$article'");
				}
			}
			while ($total > $limit * $page);
		}

		function getInfo($page = 1, $limit = 1000)
		{
			$client  = new Client([
									  'timeout' => 30,
								  ]
			);
			$headers = [
				'User-Agent' => 'artelamp.ru/1.0',
			];
			$body    = '';
			$request = new Request('GET', 'https://fandeco.ru/rest/products?vendor=3,20&limit=' . $limit . '&page=' . $page, $headers, $body);
			$res     = $client->send($request);
			return json_decode($res->getBody()->getContents(), 1);
		}
	}
