<?php

	if (!class_exists('modMenuGetListProcessor')) {
		require_once(MODX_CORE_PATH . "model/modx/processors/system/menu/getlist.class.php");
	}

	class EasypackGetMenuProcessor extends modMenuGetListProcessor
	{
		public function prepareQueryBeforeCount($c)
		{
			$c   = parent::prepareQueryBeforeCount($c);
			$key = $this->getProperty('key');
			if ($key) {
				$c->where([
							  "OR:action:LIKE"      => "%{$key}%",
							  "OR:parent:LIKE"      => "%{$key}%",
							  "OR:description:LIKE" => "%{$key}%",
							  "OR:text:LIKE"        => "%{$key}%",
						  ]);
			}
			return $c;
		}
	}

	return 'EasypackGetMenuProcessor';