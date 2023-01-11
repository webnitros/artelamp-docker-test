<?php
	include(__DIR__ . '/_default.php');

	class CrontabControllerFeedsFacebook extends feedCrontabController
	{
		public function process()
		{
			/** @var pdoTools $pdoTools */
			$pdoTools = $this->modx->getParser()->pdoTools;
			$products = $this->getProducts();
			$items = [];
			foreach ($products as $product) {
				if(!(bool)$product['in_stock']){
					continue;
				}
				$items[] = $pdoTools->getChunk('@FILE chunks/feed/facebook/item.tpl', $product);
			}
			$items = implode("\n", $items);
			$txt = chr(239) . chr(187) . chr(191);
			$txt .= <<<XML
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
	<channel>
		<title>Artelamp</title>
		<link>https://artelamp.ru/</link>
		<description>Интернет-магазин светильников и люстр Artelamp </description>
		{$items}
	</channel>
</rss>
XML;
			$txt         = str_replace(['dev1.artelamp.massive.ru', 'artelamp.it', 'http://artelamp.ru'], ['artelamp.ru', 'artelamp.ru', 'https://artelamp.ru'], $txt);
			file_put_contents(MODX_BASE_PATH . 'media/facebook.xml', $txt);
			echo '<a href="/media/facebook.xml">ссылка</a>';
		}
	}