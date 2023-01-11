<?php
	include_once __DIR__ . '/_default.php';

	/**
	 * Демонстрация контроллера
	 */
	class CrontabControllerSyncStocks extends CrontabControllerSync
	{
		public function process()
		{
			// Массовое обновление остатка
			# $table = $this->modx->getTableName('fdkPrepareStocks');
			# $this->modx->exec("UPDATE {$table} SET is_change = '0'");
			//https://ms.fandeco.ru//rest/sync1c/stocks?vendors=25,140&full=1

			// Получаем остатки для брендов Arte Lamp и GAUSS

			$data    = [
				'full'         => 1,
				'vendors'      => '25,140',
				'storage_code' => 1,// Вернуть с кодом склада
			];
			$query   = http_build_query($data);
			$content = file_get_contents('https://ms.fandeco.ru/rest/sync1c/stocks?' . $query);
			$data    = $this->modx->fromJSON($content);
			if (empty($data['success'])) {
				throw new Exception('Не удалось получить остатки');
			}

			$arrays = $data['object'];


			/*   // Кэшируем по умолчанию
			   $tmp = cacheValuesSite($this->modx, 'stocks_artelamp_it_arte_lamp_1' . $this->cachePrefix, function (modX $modx) {
				   $arrays = [];
				   foreach ($this->sync_vendors as $vendor) {
					   $response = $this->send('stocks', [
						   'vendor_uuid' => $vendor,
						   #'site' => 'artelamp.ru'
					   ]);
					   $arrays = empty($arrays) ? $response : array_merge($arrays, $response);
				   }

				   return $arrays;
			   }, true);*/

			$this->setStocks($arrays);
			return TRUE;
		}


		public function setStocks($tmp)
		{

			// если нашли товар то обновляем остаток
			$tableProduct = $this->modx->getTableName('msProductData');
			$products     = $this->modx->query("SELECT lower(`article`),id FROM {$tableProduct}")->fetchAll(PDO::FETCH_KEY_PAIR);
			$under_orders = [
				TRUE  => [],
				FALSE => [],
			];
			$stocks       = [];
			$virual       = [];
			
			foreach ($tmp as $stock) {
				if ($stock['storage'] === '000000001' || $stock['storage'] === '000000163') {
					$stocks[] = $stock;
				}
				if ($stock['storage'] === '000000245') {
					$stocks[] = $stock;
					if ((int)$stock['stock']) {
						$virual[$stock['article']] = $stock['stock'];
					}
				}
			}


			$countUpdate = 0;

			/* @var fdkPrepareStocks $Stock */
			foreach ($stocks as $row) {
				$under_order = FALSE;
				$article     = $row['article'];


				$criteria = [
					'article' => $article,
					'shop_id' => $row['storage'],
				];


				/* @var fdkPreparePrices $object */
				if (!$Stock = $this->modx->getObject('fdkPrepareStocks', $criteria)) {
					$Stock = $this->modx->newObject('fdkPrepareStocks');
				}


				$update_error = FALSE;

				$Stock->fromArray($row);
				$Stock->set('update_error', $update_error);
				$Stock->set('updatedon', time());
				$Stock->set('is_change', TRUE);

				$product_id = NULL;
				if (array_key_exists(mb_strtolower($article), $products)) {
					$product_id = $products[mb_strtolower($article)];
				}

				if (isset($virual[$article]) && $virual[$article] > 0) {
					$under_order = TRUE;
					var_dump('VIRTUAL:' . $article);
				}
				if ($under_order && $product_id) {
					$under_orders[TRUE][] = $product_id;
				} else {
					$under_orders[FALSE][] = $product_id;
				}
				if ($Stock->isNew() || $Stock->isDirty('stock')) {
					if ($Stock->save() && $product_id) {
						if (!$Stock->isUpdateError()) {
							unset($products[mb_strtolower($article)]);
							$stock    = $Stock->getStock();
							$in_stock = $Stock->inStock() ? '1' : '0';
							$SQL = "UPDATE {$tableProduct} SET stock = '{$stock}', in_stock = '{$in_stock}'  WHERE id = {$product_id}";
							$this->modx->exec($SQL);
							$countUpdate++;
						}
					}
				} else {
					unset($products[mb_strtolower($article)]);
				}

			}

			$this->print_msg('Update product: ' . $countUpdate);


			// Всем остальным устанавливаем остаток 0
			if (!empty($products)) {
				$ids = implode(',', $products);
				$this->modx->exec("UPDATE {$tableProduct} SET stock = '0', in_stock = '0'  WHERE id IN ({$ids})");
			}

			$under_orders[TRUE]  = array_unique($under_orders[TRUE]);
			$under_orders[FALSE] = array_unique($under_orders[FALSE]);
			if (!empty($under_orders[TRUE])) {
				$ids = implode(',', $under_orders[TRUE]);
				$this->modx->exec("UPDATE {$tableProduct} SET `under_order` = '1'  WHERE id IN ({$ids})");

			}
			if (!empty($under_orders[FALSE])) {
				$ids = implode(',', $under_orders[FALSE]);
				$this->modx->exec("UPDATE {$tableProduct} SET `under_order` = '0'  WHERE id IN ({$ids})");

			}
		}
	}
