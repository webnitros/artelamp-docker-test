<?php

	class feedCrontabController extends modCrontabController
	{
		public $categoryGoogle = [
			"Бра" => 6073,
			"Комплектующие" => 2425,
			"Лампочки" => 2425,
			"Люстры" => 2249,
			"Потолочный светильник" => 2249,
			"Настольные лампы" => 4636,
			"Подсветки для картин" => 2370,
			"Споты" => 3006,
			"Трековые" => 3006,
			"Светильник" => 3006,
			"Светильники" => 3006,
			"Торшеры" => 7401,
			"Уличное освещение" => 7400,
			"Встраиваемые светильники" => 2809,
		];

		public function getProducts()
		{
			$site_url = $this->modx->getOption('site_url');
			$content = ['web'];
			$size_images = 'medium';
			$categories = [];
			$q = $this->modx->newQuery('msCategory');
			$q->select('id,pagetitle');
			$q->where([
				['context_key:IN' => $content, 'published' => 1, 'deleted' => 0, 'class_key' => 'msCategory'],
			]);
			if ($q->prepare() && $q->stmt->execute()) {
				while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
					$categories[$row['id']] = $row['pagetitle'];
				}
			}
			$products = [];
			$q = $this->modx->newQuery('msProduct');
			$q->select($this->modx->getSelectColumns('msProduct', 'msProduct', '', []));
			$q->select($this->modx->getSelectColumns('msProductData', 'Data'));
			$q->select('Vendor.name as vendor_name');
			$q->where([
				['context_key:IN' => $content, 'published' => 1, 'deleted' => 0, 'class_key' => 'msProduct', 'parent:IN' => array_keys($categories)],
			]);
			$q->innerJoin('msProductData', 'Data', 'Data.id = msProduct.id');
			$q->leftJoin('msVendor', 'Vendor', 'Vendor.id = Data.vendor');
			if ($q->prepare() && $q->stmt->execute()) {
				while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
					foreach ($row as $key => $v) {
						$row[$key] = trim($v);
						if ($v = $this->modx->util->jsonValidate($row[$key])) {
							if (is_array($v)) {
								$row[$key] = $v;
							}
						}
					}
					$row['pictures'] = [];
					$row['link'] = $this->modx->makeUrl($row['id'], 'web', '', 'full');
					$row['google_category'] = $this->categoryGoogle[$row['good_type_web']] ?? 3006;
					$products[$row['id']] = $row;
				}
			}
			if (!empty($products)) {
				$q = $this->modx->newQuery('msProductFile');
				$q->select('product_id,url,rank');
				$q->where([
					'product_id:IN' => array_column($products, 'id'),
					'path:LIKE' => "%{$size_images}%",
				]);
				$q->sortby('rank');
				if ($q->prepare() && $q->stmt->execute()) {
					while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
						$products[$row['product_id']]['pictures'][$row['rank']] = $site_url . $row['url'];
					}
				}
			}
			return $products;
		}
	}