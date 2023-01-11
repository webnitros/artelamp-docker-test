<?php

	abstract class feedGen
	{
		public $root = 'root';
		public $encoding = 'UTF-8';
		public $namespace = [];

		/**
		 * @var XMLWriter
		 */
		public $xml;

		public function __construct(FeedData $data)
		{
			$this->xml = new XMLWriter();
			$this->xml->openMemory();
			$this->xml->startDocument('1.0', $this->encoding);
			if (!empty($this->namespace)) {
				$this->xml->writeAttributeNs(...$this->namespace);
			}
			$this->xml->startElement($this->root);
			$this->data = $data;
		}


		public function generate($filename = '')
		{
			$this->xml->endElement();
			$out = $this->xml->outputMemory();
			if (!file_exists(dirname($filename))) {
				if (!mkdir($concurrentDirectory = dirname($filename)) && !is_dir($concurrentDirectory)) {
					throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
				}
			}
			if ($filename) {
				file_put_contents($filename, $out);
			}
			return $out;
		}

		public function addElem($name, $content = NULL, $attrs = [])
		{
			if (empty($attrs)) {
				$this->xml->writeElement($name, $content);
			} else {
				$this->xml->startElement($name);
				foreach ($attrs as $key => $value) {
					$this->xml->writeAttribute($key, $value);
				}
				if (!empty($content)) {
					$this->xml->text($content);
				}
				$this->xml->endElement();
			}
		}

		public function getProduct()
		{

		}

		abstract public function process();
	}

	class FeedData
	{
		public $currency;
		public $category;
		public $products;
		public $size_images = 'medium';
		/**
		 * @var array
		 */
		public $shop = [];

		public function __construct(modX &$modx)
		{
			$this->modx = $modx;
			$this->config = [
				'products' => [
					'conditions' => [],
					'select' => NULL,
					'limit' => 50000,
				],
				'categories' => [
					'conditions' => [],
					'select' => NULL,
				],
			];
		}

		public function setShop($name, $company, $url)
		{
			$this->shop['name'] = $name;
			$this->shop['company'] = $company;
			$this->shop['url'] = $url;
		}

		public function setCurrency($Currency)
		{
			$this->urrency = $Currency;
		}

		public function setCategory($Category)
		{
			$this->category = $Category;
		}

		public function getProducts()
		{
			$q = $this->modx->newQuery('msProduct');
			$q->setClassAlias('page');
			if ($this->config['products']['select']) {
				$q->select($this->config['products']['select']);
			} else {
				$q->select($this->modx->getSelectColumns('msProduct', 'page', 'page.'));
				$q->select($this->modx->getSelectColumns('msProductData', 'data', 'data.'));
				$q->select($this->modx->getSelectColumns('msVendor', 'vendor', 'vendor.'));
				$q->select('`data`.`is_price` AS `data.is_price`');
				$q->select('GROUP_CONCAT(img.url) AS imgs');
			}
			foreach ($this->config['products']['conditions'] as $where) {
				$q->andCondition($where);
			}
			$q->innerJoin('msProductData', 'data', 'data.id = page.id');
			$q->leftJoin('msVendor', 'vendor', 'vendor.id = data.vendor');
			$q->leftJoin('msProductFile', 'img', 'img.product_id = data.id and img.path LIKE "%' . $this->size_images . '%"');
			$q->groupby('data.id');
			$q->limit($this->config['products']['limit']);
			if ($q->prepare() && $q->stmt->execute()) {
				return $q->stmt;
			}
			return FALSE;
		}

		public function getCategories()
		{
			$q = $this->modx->newQuery('msCategory');
			if ($this->config['products']['select']) {
				$q->select($this->config['products']['select']);
			} else {
				$q->select('id,pagetitle');
			}
			foreach ($this->config['categories']['conditions'] as $where) {
				$q->orCondition($where);
			}
			if ($q->prepare() && $q->stmt->execute()) {
				return $q->stmt;
			}
			return FALSE;
		}
	}