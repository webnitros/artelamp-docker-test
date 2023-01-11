<?php
	include(__DIR__ . '/_default.php');

	class CrontabControllerFeedsVk extends modCrontabController
	{
		public function process()
		{
			$q = $this->modx->query("SELECT artikul_1c AS sku ,good_type_web AS category,sub_category,stock,price,old_price,sale FROM ara3_ms2_products");
			if ($q) {
				$data    = $q->fetchAll(PDO::FETCH_ASSOC);
				$content = json_encode($data, 256 | 128);
				file_put_contents(MODX_BASE_PATH . "media/vk.json", $content);
				echo '<a href="/media/vk.json">ссылка</a>';
			}
		}
	}