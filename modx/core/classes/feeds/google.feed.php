<?php
	include_once realpath(MODX_CORE_PATH . 'classes/feed.gen.class.php');


	class ymlGoogle extends feedGen
	{
		public $root = 'channel';
		public $offerRoot = 'item';
		public $namespace = [
			'g',
			'xmlns',
			'http://base.google.com/ns/1.0',
			'',
		];

		public function process()
		{
			$this->addElem('title', $this->data->shop['name']);
			$this->addElem('link', $this->data->shop['url']);
			$this->addElem('description', $this->data->shop['company']);
//			$this->data->config['products']['select'] = [
//				"`page`.`id` AS `page.id`,
//				`page`.`pagetitle` AS `page.pagetitle`,
//				`data`.`price` AS `data.price`,
//				`data`.`old_price` AS `data.old_price`,
//				`page`.`description` AS `page.description`,
//				`page`.`uri` AS `page.uri`,
//				`data`.`vendor_code` AS `data.vendor_code`,
//				`data`.`sub_category` AS `data.sub_category`,
//				`data`.`image` AS `data.image`,
//				`page`.`published` AS `page.published`,
//				`data`.`new` AS `data.new`
//				",
//			];
			$prod = $this->data->getProducts();
			if ($prod) {
				while ($row = $prod->fetch(PDO::FETCH_ASSOC)) {
					$this->addOffer($row);
				}

			} else {
			}
		}

		public function addOffer($product)
		{
			global $modx;
			if ((int)$product['page.id']) {
				if(!(bool)$product['data.is_price']){
					return false;
				}
				if(!(bool)$product['page.published']){
					return false;
				}
			}
			if(!(bool)$product['data.in_stock']){
				return false;
			}
			$this->xml->startElement('item');
			$this->addElem('g:id', $product['page.id']);
			$this->addElem('g:title', $product['page.pagetitle']);
			$this->addElem('g:description', $product['page.description']);
			$this->addElem('g:brand', $product['data.vendor_code']);
			$this->addElem('g:image_link', "https://artelamp.ru" . '/' . ltrim($product['data.image'], '/'));
			if ($product['data.new']) {
				$this->addElem('g:condition', 'новый');
			}
			$this->addElem('g:url', "https://artelamp.ru" . '/' . ltrim($product['page.uri'], '/'));
			$this->addElem('g:availability', $product['page.published'] ? 'in_stock' : 'out_of_stock');
			$this->addElem('g:price', round((int)$product['data.price']) . ' RUB');
			if ($product['data.barcode']) {
				$this->addElem('g:gtin', (int)$product['data.barcode']);
			}
			$this->addElem('g:condition', 'новый');
			$color = [];
			try {
				$plafond_color = json_decode($product['data.plafond_color'], 1);
				$armature_color = json_decode($product['data.armature_color'], 1);
				if (is_array($plafond_color) and is_array($armature_color)) {
					$color = array_merge($plafond_color, $armature_color);
				}
			} catch (Exception $e) {

			}
			if (is_array($color)) {
				$color = array_unique($color);
				$colors = array_chunk($color, 3);
				if ($colors) {
					$color = implode('/', $colors[0]);
					if ($color) {
						$this->addElem('g:color', $color);
					}
				}
			}
			$this->addElem('g:product_type', $product['data.sub_category']);
			$this->addElem('g:adult', 'no');
			$this->xml->endElement();

		}

	}