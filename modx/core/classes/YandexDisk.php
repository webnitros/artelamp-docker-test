<?php
	/**
	 * Created by Andrey Stepanenko.
	 * User: webnitros
	 * Date: 12.04.2022
	 * Time: 13:11
	 * Установить зависимость
	 * https://packagist.org/packages/arhitector/yandex-disk-flysystem
	 * AQAEA7qjYtuQAAfSXkvb0hWg9kpMnCTfEpPM09o
	 */
	if (!defined('MODX_BASE_PATH')) {
		define('MODX_BASE_PATH', '');
	}
	include_once dirname(MODX_BASE_PATH) . '/vendor/autoload.php';

	use Arhitector\Yandex\Disk;
	use GuzzleHttp\Client;
	use League\Flysystem\FileNotFoundException;
	use League\Flysystem\Filesystem;
	use traineratwot\Yandex\Disk\Adapter\Flysystem;

	class YandexDisk
	{
		private $filesystem;
		/**
		 * @var array|mixed|string
		 */
		private $accessToken;

		public function __construct(modX $modx)
		{
			$this->modx        = $modx;
			$this->accessToken = $modx->getOption('YANDEX_DISK_TOKEN', NULL, 'AQAEA7qjYtuQAAfSXkvb0hWg9kpMnCTfEpPM09o');
			$client            = new Disk();
			$client->setAccessToken($this->accessToken);
			$adapter          = new Flysystem($client);
			$filesystem       = new Filesystem($adapter);
			$this->filesystem = $filesystem;
		}

		public function listContents(string $path = '/')
		{
			return $this->filesystem->listContents($path);
		}

		public function downloadPreview(string $source, string $target)
		{

			$source = str_ireplace('size=S', 'size=XL', $source);

			$client = new Client([
									 'headers' => [
										 'Authorization' => 'OAuth ' . $this->accessToken,
									 ],
									 'verify'  => FALSE,
								 ]);

			$Response = $client->get($source);
			$content  = $Response->getBody()->getContents();
			if (!empty($content)) {
				$this->createPath($target);
				if (file_exists($target)) {
					unlink($target);
				}
				return file_put_contents($target, $content);
			}
			return FALSE;

		}

		/**
		 * @throws FileNotFoundException
		 */
		public function downloadFile($source, string $target)
		{
			if (file_exists($target) and md5_file($target) === $source['md5']) {
				return TRUE;
			}
			$content = $this->filesystem->read($source['path']);
			if (!empty($content)) {
				$this->createPath($target);
				if (file_exists($target)) {
					unlink($target);
				}
				return file_put_contents($target, $content);
			}
			return FALSE;

		}

		public function filesystem()
		{
			return $this->filesystem;
		}

		/**
		 * @throws FileNotFoundException
		 */
		public function getMetadata(string $path = '/')
		{
			return $this->filesystem->getMetadata($path);
		}

		public function createPath($path)
		{
			$dir = dirname($path);
			if (!file_exists($dir)) {
				$cacheManager = $this->modx->getCacheManager();
				$cacheManager->writeTree($dir);
			}
		}

		public function vendors($v = '')
		{
			$lifetime = 3600;
			$cache    = empty($_GET['cache']);
			$vendors  = $this->cacheValuesSite($this->modx, 'yandex_disk_vendors_2', function () use ($v) {
				return $this->getVendors($v);
			},                                 $cache, $lifetime);
			if (!empty($v)) {
				return $vendors[$v]['files'];
			}
			return $vendors;
		}

		private function cacheValuesSite(modX $modx, $key, $callback, $cache = TRUE, $lifetime = 600)
		{
			$optionsCache = [
				xPDO::OPT_CACHE_KEY     => 'default/site_cache/',
				xPDO::OPT_CACHE_HANDLER => 'xPDOFileCache',
			];

			$newValues = NULL;
			if ($cache) {
				$cacheManager = $modx->getCacheManager();
				$newValues    = $cacheManager->get($key, $optionsCache);
			}
			if (empty($newValues)) {
				$newValues = $callback($modx);
				if ($cache and !empty($newValues)) {
					if (!$response = $cacheManager->set($key, $newValues, $lifetime, $optionsCache)) {
						$modx->log(xPDO::LOG_LEVEL_ERROR, "Error save " . $key . ' values ' . print_r($newValues, 1), '', __METHOD__, __FILE__, __LINE__);
					}
				}
			}
			return $newValues;
		}

		/**
		 * @throws FileNotFoundException
		 */
		private function getVendors($v = '')
		{
			ini_set("memory_limit", 1024 * 1024 * 1024 * 10);
			$vendors = $this->listContents('Fandeco');

			$arraysVendors = [];
			$relevantePath = 'assets/YandexDisk/';
			$basePath      = MODX_BASE_PATH . $relevantePath;
			foreach ($vendors as $vendor) {
				if (!empty($v) and $vendor['name'] != $v) {
					continue;
				}
				$array       = [
					'name' => $vendor['name'],
				];
				$vendor_path = $vendor['path'];
				$files       = $this->listContents($vendor_path);
				$arrays      = [];
				foreach ($files as $file) {
					$path     = $file['path'];
					$filename = $file['filename'];
					$basename = $file['basename'];
					//убираем fandeco
					$rele    = str_replace('Fandeco/', '', $vendor_path) . '/' . $filename . '.jpeg';
					$rele2   = str_replace('Fandeco/', '', $vendor_path) . '/' . $basename;
					$target  = $basePath . $rele;
					$target2 = $basePath . $rele2;

					if (array_key_exists('public_url', $file) && $file['extension'] === 'pdf') {
						$this->downloadPreview($file['preview'], $target);
						$this->downloadFile($file, $target2);
						$arrays[] = [
							'name'      => $file['name'],
							'base_name' => $file['filename'],
							'ext'       => $file['extension'],
							'preview'   => $relevantePath . $rele,
							'save_path' => $target2,
							'save_url'  => '/' . str_replace(MODX_BASE_PATH, '', $target2),
							'url'       => $file['public_url'],
							'file'      => $file['file'],
							'size'      => $this->get_size($file['size']),
							'$file'     => $file,
						];
					}

				}
				$array['files']                 = $arrays;
				$arraysVendors[$vendor['name']] = $array;
				if (!empty($v) and $vendor['name'] === $v) {
					break;
				}
			}
			return $arraysVendors;
		}

		/**
		 * @param $bytes
		 * @return string
		 */
		function get_size($bytes)
		{
			if ($bytes < 1000 * 1024) {
				return number_format($bytes / 1024, 2) . " KB";
			}
			if ($bytes < 1000 * 1048576) {
				return number_format($bytes / 1048576, 2) . " MB";
			}
			if ($bytes < 1000 * 1073741824) {
				return number_format($bytes / 1073741824, 2) . " GB";
			}
			return number_format($bytes / 1099511627776, 2) . " TB";
		}
	}