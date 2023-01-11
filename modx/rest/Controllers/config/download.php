<?php

	class MyControllerConfigDownload extends modRestController
	{
		public $protected = TRUE;

		public function get()
		{
			return $this->post();
		}

		public function post()
		{
//			$arts   = $this->modx->query('SELECT `value` AS art,contentid AS id FROM modx_site_tmplvar_contentvalues WHERE tmplvarid = 3')->fetchAll(PDO::FETCH_KEY_PAIR);
//			$prices = $this->modx->query('SELECT contentid AS id,`value` AS price FROM modx_site_tmplvar_contentvalues WHERE tmplvarid = 23')->fetchAll(PDO::FETCH_KEY_PAIR);
//			if ($id = (int)$_GET['id']) {
//				$cart = $this->modx->query("SELECT cart FROM modx_configurators WHERE id = $id")->fetch(PDO::FETCH_COLUMN);
//				if (empty($cart)) {
//					$this->failure("id not found", [], 404);
//					return;
//				}
//				$cart     = json_decode($cart, 1);
//				$cartData = [];
//				foreach ($cart["items"] as $cat => $list) {
//					foreach ($list as $art => $count) {
//						$cartData[$art] = $count;
//					}
//				}
//				$csv = $this->modx->util->csv();
//				$csv->setHead('Артикул', "Колличество", "Цена за штуку", "Цена");
//				$total = 0;
//				foreach ($cartData as $art => $count) {
//					$total += $prices[$arts[$art]] * $count;
//					$csv->addRow($art, $count, $prices[$arts[$art]], $prices[$arts[$art]] * $count);
//				}
//				$csv->addRow("", "", "Итог:", $total);
//				$file = MODX_ASSETS_PATH . 'tmp/config.csv';
//				if (!mkdir($concurrentDirectory = MODX_ASSETS_PATH . 'tmp', 0777, TRUE) && !is_dir($concurrentDirectory)) {
//					throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
//				}
//				file_put_contents($file, $csv->toCsv());
//				if (ob_get_level()) {
//					ob_end_clean();
//				}
//				// заставляем браузер показать окно сохранения файла
//				header('Content-Description: File Transfer');
//				header('Content-Type: ' . mime_content_type($file));
//				header('Content-Disposition: attachment; filename=' . basename($file));
//				header('Content-Transfer-Encoding: binary');
//				header('Expires: 0');
//				header('Cache-Control: must-revalidate');
//				header('Pragma: public');
//				header('Content-Length: ' . filesize($file));
//				// читаем файл и отправляем его пользователю
//				readfile($file);
//				unlink($file);
//				exit;
//				return;
//			}
			$this->failure("empty id", [], 400);
			session_write_close();
		}
	}