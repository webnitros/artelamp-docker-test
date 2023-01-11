<?php
	if (!class_exists('GuzzleHttp')) {
		include_once dirname(MODX_BASE_PATH) . '/vendor/autoload.php';
	}

	use GuzzleHttp\Promise;

	class fdkNewDownloadImages
	{
		/* @var modX $modx */
		private $modx;
		/* @var modCacheManager $cacheManager */
		private   $cacheManager;
		protected $url = 'http://ms.fandeco.ru';
		/* @var boolean $async */
		protected $async     = TRUE;
		protected $temp;
		public    $errorMsg  = NULL;
		protected $target;
		protected $clearTemp = TRUE; // Запрещаем отчищать тэп для фотографий чтобы можно было сравнивать что изменилось
		public    $source;

		function __construct(modX $modx)
		{
			$this->modx   = $modx;
			$config       = [
				'verify'   => FALSE,
				'timeout'  => 30.0,
				#'debug' => true,
				'base_uri' => $this->url . '/rest/',
			];
			$this->client = new GuzzleHttp\Client($config);
			/* @var modCacheManager $cacheManager */
			$this->cacheManager = $modx->getCacheManager();
			$this->source       = 'artelamp.ru';
			$this->modx->getOption('media_server_images_source', NULL, MODX_HTTP_HOST);

		}

		public function asyncDisabled()
		{
			$this->async = FALSE;
		}

		/**
		 * @param msProductData $product
		 * @param               $article
		 * @param bool          $compareChanges
		 * @return false|int|mixed
		 * @throws Exception
		 */
		public function getImages($product, $article, $compareChanges = FALSE)
		{
			$product_id = $product->get('id');
			if (empty($product_id)) {
				return FALSE;
			}

			#$this->asyncDisabled(); // При отключение скорость загрузки падает в два раза
			#$start = microtime(true);
			$temp_media_server = MODX_ASSETS_PATH . 'images/media_server_fandeco/';
			if (!file_exists($temp_media_server)) {
				if (!mkdir($temp_media_server, 0777, TRUE) && !is_dir($temp_media_server)) {
					throw new \RuntimeException(sprintf('Directory "%s" was not created', $temp_media_server));
				}
			}

			// Установка временной директории для загузки
			$this->temp = MODX_ASSETS_PATH . 'images/media_server_fandeco/' . $product_id . '/';
			if (!file_exists($this->temp)) {
				if (!mkdir($this->temp, 0777, TRUE) && !is_dir($this->temp)) {
					throw new \RuntimeException(sprintf('Directory "%s" was not created', $this->temp));
				}
			}


			$this->target = MODX_ASSETS_PATH . 'images/products/' . $product_id . '/';
			// Если директории нету то создаем её
			if (!file_exists($this->target)) {
				if (!mkdir($this->target, 0777, TRUE) && !is_dir($this->target)) {
					throw new \RuntimeException(sprintf('Directory "%s" was not created', $this->target));
				}
			}
			$oldCount = count(scandir($this->temp)) - 2;

			$imagesProducts = NULL;
			$response       = $this->request($article);

			try {
				$status_code = $response['status'];
				if ($status_code !== 200) {
					return $status_code;
				}

				// Чистим временную директорию

				$imagesProducts = $response['results'];
				if ($oldCount !== count($imagesProducts)) {
					$this->downloadClearTemp();
				}
				if (!empty($imagesProducts)) {
					$arrays = [];
					foreach ($imagesProducts as $image) {
						$arrays[] = $this->getImagesAllPath($image, $product_id);
					}

					if (!empty($arrays) && $this->downloadImages($arrays) === 'downloaded') {
						echo "Обновление файлов" . PHP_EOL;
						// Если установлена сравнивать изменения то можно будет пропустить фотки которые не изменили
						if ($compareChanges) {
							if (!$this->compareChanges($arrays, $product_id)) {
								return 200;
							}
						}

						// Чиcтим директории и грузим новые фотографии
						$this->clearImagesDirs();
						$this->removeAllImages($product_id);
						$this->addAllImages($product_id, $arrays);
					}
				}
			} catch (Exception $e) {
				$this->errorMsg = $e->getMessage();
				return 1000;
			}
			return 200;
		}

		/**
		 * Вернет true если были какие изменения в файлах
		 * @param $images
		 * @param $product_id
		 * @return bool
		 */
		private function compareChanges($images, $product_id)
		{
			$countImages = count($images);

			$q = $this->modx->newQuery('msProductFile');
			$q->where([
						  'product_id' => $product_id,
						  'parent'     => 0,
					  ]);
			$count = $this->modx->getCount('msProductFile', $q);

			$isChange = FALSE;
			if ($countImages != $count) {
				$isChange = TRUE;
			}

			if (!$isChange) {
				if ($images) {
					foreach ($images as $image) {
						$source = $this->temp . $image['file'];
						$target = $this->target . $image['file'];
						if (!file_exists($target) or filesize($source) != filesize($target)) {
							$isChange = TRUE;
							break;
						}
					}
				}
			}
			return $isChange;
		}


		private function addAllImages($product_id, $imagesProducts)
		{

			foreach ($imagesProducts as $imagesProduct) {
				$url           = $imagesProduct['url'];
				$name          = basename($url);
				$source        = $this->temp . rtrim($name, '/');
				$processorPath = MODX_CORE_PATH . 'components/minishop2/processors/mgr/';
				$response      = $this->modx->runProcessor('gallery/upload', [
					'id'           => $product_id,
					'file'         => $source,
					'media_source' => $this->modx->getOption('ms2_product_source_default'),
				],                                         ['processors_path' => $processorPath]);
				if ($response->isError()) {
					throw new Exception($response->getMessage());
				}
			}
		}


		public function getImagesAllPath($image = [], $product_id)
		{
			$url       = $image['url'];
			$filesize  = $image['filesize'];
			$file      = $image['file'];
			$hash      = $image['hash'];
			$pathInfo  = pathinfo($file);
			$filename  = $pathInfo['filename'];
			$pathInfo  = pathinfo($image['name']);
			$extension = $pathInfo['extension'];
			$name      = $pathInfo['filename'];
			$url_path  = dirname($url);
			$thumb     = $filename . '.jpg';
			$arrays    = [
				'' => [
					'url'        => '/assets/images/products/' . $product_id . '/' . $file,
					'download'   => $url_path . '/' . $file,
					'path'       => $product_id . '/',
					'file'       => $file,
					'ext'        => $extension,
					'hash'       => $hash,
					'filesize'   => $filesize,
					'properties' => [
						'size'   => 0,
						'width'  => 700,
						'height' => 461,
						'bits'   => 8,
						'mime'   => "image\/jpeg",
					],
				],
			];
			$default   = [
				'rank'      => $image['rank'],
				'name'      => $name,
				'type'      => 'image',
				'source'    => 2,
				'createdon' => time(),
				'createdby' => 1,
				'parent'    => 0,
			];
			$result    = [];
			foreach ($arrays as $size => $array) {
				$result = array_merge($default, $array);
			}
			return $result;
		}

		/**
		 * @param $fullPath
		 * @param $ext
		 * @return bool
		 */
		public function broken($fullPath, $ext)
		{
			$broken = FALSE;
			switch ($ext) {
				case 'jpeg':
				case 'jpg':
					if (exif_imagetype($fullPath) != IMAGETYPE_JPEG) {
						$broken = TRUE;
					}
					break;
				case 'png':
					if (exif_imagetype($fullPath) != IMAGETYPE_PNG) {
						$broken = TRUE;
					}
					break;
				case 'gif':
					if (exif_imagetype($fullPath) != IMAGETYPE_GIF) {
						$broken = TRUE;
					}
					break;
				default:
					break;
			}
			return $broken;
		}


		/**
		 * Вставляем записи в базу данных
		 * @param $arrays
		 * @param $product_id
		 * @throws Exception
		 */
		protected function insertImagesDb($arrays, $product_id)
		{

			/* @var msProductFile $Parent */
			/* @var msProductFile $Children */
			foreach ($arrays as $array) {
				$Parent = NULL;
				foreach ($array as $size => $data) {
					$Children = NULL;
					if ($size === '') {
						$Parent = $this->modx->newObject('msProductFile');
						$Parent->set('product_id', $product_id);
						$Parent->fromArray($data);
					} else {
						$Children = $this->modx->newObject('msProductFile');
						$Children->set('product_id', $product_id);
						$Children->fromArray($data);
					}


					if ($Children) {
						if ($Parent) {
							$Parent->addMany($Children, 'Children');
						} else {
							throw new Exception('Не удалось получить  $Parent');
						}
					}

				}
				if ($Parent) {
					if (!$Parent->save()) {
						throw new Exception('Не удалось сохранить  $Parent');
					}
				} else {
					throw new Exception('Не передан $Parent');
				}
			}
		}

		private function clearImagesDirs()
		{
			if (file_exists($this->target)) {
				$this->cacheManager->deleteTree($this->target, ['deleteTop' => FALSE, 'extensions' => ['jpg', 'gif', 'jpeg', 'png']]);
			}
		}


		private function removeAllImages($product_id)
		{
			// Если больше 0 то удаляем заиписи
			if (!empty($product_id)) {
				$table = $this->modx->getTableName('msProductFile');
				$this->modx->exec("DELETE FROM {$table} WHERE product_id = {$product_id}");
			}
		}

		/**
		 * Перемещает изображения и временно папки в боевую
		 */
		protected function downloadClearTemp()
		{

			if ($this->clearTemp) {
				if (file_exists($this->temp)) {
					if (!$this->cacheManager->deleteTree($this->temp, ['deleteTop' => TRUE, 'extensions' => []])) {
						throw new Exception('Не удалось отчистить папку temp ' . $this->temp);
					}
				}
			}
		}

		/**
		 * Перемещает изображения и временно папки в боевую
		 * @param $product_media
		 */
		protected function downloadMoving()
		{
			if (!$this->cacheManager->copyTree($this->temp, $this->target)) {
				throw new Exception('Не удалось переместить изображения из папки temp ' . $this->temp);
			}
		}

		public function triggerStatus($article)
		{
			#$this->client->requestAsync();
		}


		/**
		 * @param $product_id
		 * @param $article
		 */
		public function request($article)
		{
			try {
				$response = $this->client->get('images/' . $article . '?source=' . $this->source);
				$status   = $response->getStatusCode();
				$content  = $response->getBody()->getContents();
				$data     = $this->modx->fromJSON($content);
				$results  = !empty($data['results']) ? $data['results'] : NULL;
			} catch (GuzzleHttp\Exception\ClientException $e) {
				$response = $e->getResponse();
				$message  = $e->getMessage();
				$status   = $response->getStatusCode();
			} catch (GuzzleHttp\Exception\ConnectException $e) {
				$response = $e->getResponse();
				$message  = $e->getMessage();
				$status   = 'TIMEOUT';
			} catch (GuzzleHttp\Exception\ServerException $e) {
				$response = $e->getResponse();
				$message  = $e->getMessage();
				$status   = $response->getStatusCode();
			}
			if ($status === 200) {
				if (!is_array($data) || !array_key_exists('results', $data)) {
					throw new Exception('Произошла ошибка, не вернулся массив ' . $results);
				}
			}
			$this->response = [
				'results' => $results,
				'status'  => $status,
				'message' => $message,
			];
			return $this->response;
		}

		/**
		 * Скачивание изображений через проммисы
		 * @param array $downloads
		 * @throws Exception
		 */
		protected function aSyncRequest($downloads = [])
		{
			$promises = [];
			foreach ($downloads as $k => $array) {
				$source     = $array['source'];
				$target     = $array['target'];
				$promises[] = $this->client->getAsync($source, ['sink' => $target]);
			}

			// Дождемся завершения запросов, даже если некоторые из них завершатся неудачно
			$results = Promise\settle($promises)->wait();
			foreach ($results as $k => $result) {
				$data   = $downloads[$k];
				$target = $data['target'];
				$source = $data['source'];
				if ($result['state'] !== 'fulfilled') {
					throw new Exception('Не удалось скачать изображение' . $source);
				}
				$code = $result['value']->getStatusCode();
				if ($code !== 200) {
					throw new Exception('Error download ' . $source);
				}
				$ext = $data['ext'];
				if (!file_exists($target)) {
					throw new Exception('Изображение не загружено ' . $target);
				}
				if ($this->broken($target, $ext)) {
					throw new Exception('Битое изображение ' . print_r($data, 1));
				}
			}
		}

		protected function downloadImages($arrays = [])
		{

			$downloads = [];
			foreach ($arrays as $array) {
				$ext      = $array['ext'];
				$download = $array['download'];
				$path     = $this->temp;
				$target   = $path . $array['file'];
				// Проверяем чтобы папка существовала
				if (!file_exists($path)) {
					if (!mkdir($path, 0777, TRUE) && !is_dir($path)) {
						throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
					}
				}
				if (file_exists($target) && filesize($target) === (int)$array['filesize']) {
//					echo 'Фото актуально -' . $target . PHP_EOL;
					continue;
				}
				if (file_exists($target)) {
					unlink($target);
				}
				$download    = $this->url . $download;
				$downloads[] = [
					'source' => $download,
					'target' => $target,
					'ext'    => $ext,
					#'size' => $size,
					'url'    => $array['url'],
				];

				if (!$this->async) {
					$res = $this->client->request('GET', $download, ['sink' => $target]);
					if ($res->getStatusCode() !== 200) {
						throw new Exception("Не удалось скачать или записать изображение {$download}");
					}
					if ($this->broken($target, $ext)) {
						throw new Exception('Битое изображение ' . print_r($data, 1));
					}
				}
			}
			if (empty($downloads)) {
				return "ok";
			}
			if ($this->async) {
				try {
					// Выкачиваем картинки одновременно
					$this->aSyncRequest($downloads);
				} catch (\Exception $e) {
					echo $e->getMessage() . PHP_EOL;
				}
			}
			return "downloaded";
		}
	}
