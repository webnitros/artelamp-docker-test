<?php
	include_once __DIR__ . '/_default.php';

	/**
	 * Демонстрация контроллера
	 */
	class CrontabControllerSyncPrices extends CrontabControllerSync
	{

		public function process()
		{
			$data  = [
				'full'         => 1,
				'vendors'      => '25,140',
				'storage_code' => 1,
			];
			$query = http_build_query($data);
			$url   = 'https://ms.fandeco.ru/rest/sync1c/prices?' . $query;
			$this->print_msg('URL: ' . $url);
			$content = file_get_contents($url);

			$prices = $this->modx->fromJSON($content);


			$tableProduct  = $this->modx->getTableName('msProductData');
			$tableProduct2 = $this->modx->getTableName('msProduct');

			$products = $this->modx->query("SELECT lower(`article`),id FROM {$tableProduct}")->fetchAll(PDO::FETCH_KEY_PAIR);
			/* // если нашли товар то обновляем остаток



			 // Массовое обновление остатка
			 $table = $this->modx->getTableName('fdkPreparePrices');
			 $this->modx->exec("UPDATE {$table} SET is_change = '0'");*/


			$countUpdate = 0;
			$is_price    = 0;
			$countHidden = 0;
			$publishedon = time();
			foreach ($prices['object'] as $row) {
				$article    = mb_strtolower($row['article']);
				$product_id = NULL;

				if (array_key_exists($article, $products)) {
					$product_id = $products[$article];
				} else {
					$countSkip++;
					continue;
				}
				$price      = (float)$row['price'];
				$price_sale = (float)$row['price_sale'];
				$sale       = (int)$row['sale'] ? 1 : 0;
				if (($sale === 1) && $price_sale > 0 && $price_sale < $price) {
					$old_price = $price;
					$price     = $price_sale;
				} else {
					$old_price = 0;
				}
				if ($price > 0) {
					$is_price++;
					$SQL  = "UPDATE {$tableProduct} SET `price` = '{$price}', `old_price` = '{$old_price}', `sale` = '{$sale}',`is_price` ='1'  WHERE id = '{$product_id}'";
//					$SQL2 = "UPDATE {$tableProduct2} SET `published` = '1',`publishedon` = '{$publishedon}'  WHERE `id` = '{$product_id}'";
					if ($this->modx->exec($SQL)) {
						$this->modx->exec($SQL2);
						$cache_file = MODX_CORE_PATH . 'cache/resource/web/resources/' . $product_id . '.cache.php';
						if (file_exists($cache_file)) {
							unlink($cache_file);
						}
						$countUpdate++;
					} else {
						$this->print_msg('no update: ' . $SQL);
					}
				}
			}
			$sql    = "SELECT {$tableProduct}.id FROM {$tableProduct} 
LEFT JOIN {$tableProduct2} on {$tableProduct2}.id = {$tableProduct}.id
WHERE {$tableProduct}.price =0 and {$tableProduct2}.published = 1
";
			$hidden = $this->modx->query($sql)->fetchAll(PDO::FETCH_COLUMN);
			foreach ($hidden as $product_id) {
				$SQL2 = "UPDATE {$tableProduct2} SET published = 0,publishedon = '{$publishedon}'  WHERE id = {$product_id}";
				$SQL3 = "UPDATE {$tableProduct} SET is_price = 0  WHERE id = {$product_id}";
				if ($this->modx->exec($SQL2)) {
					$this->modx->exec($SQL3);
					$cache_file = MODX_CORE_PATH . 'cache/resource/web/resources/' . $product_id . '.cache.php';
					if (file_exists($cache_file)) {
						unlink($cache_file);
					}
					$countHidden++;
				}
			}
			$this->print_msg('Update product: ' . $countUpdate);
			$this->print_msg('Update hidden: ' . $countHidden);
			$this->print_msg('is_price: ' . $is_price);
			return TRUE;
		}

	}
