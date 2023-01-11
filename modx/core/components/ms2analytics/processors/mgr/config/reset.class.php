<?php

	class ms2AnalyticsResetConfigProcessor extends modProcessor
	{

		public function process()
		{
			$classKey = "Ms2aConfigData";

			$bu = __DIR__ . '/config.json';
			switch ($this->getProperty('do', FALSE)) {
				case 'bu':
					$a = $this->modx->getIterator();
					$c = [];
					foreach ($a as $b) {
						$_t = $b->toArray();
						unset($_t['id']);
						$_t['value'] = !empty($_t['default']) ? $_t['default'] : NULL;
						$_t['default'] = !empty($_t['default']) ? $_t['default'] : NULL;
						$c[] = $_t;
					}
					file_put_contents($bu, json_encode($c, 256));
					return $c;

				case 'reset':
					$config = json_decode(file_get_contents($bu), 1);
					foreach ($config as $k => $fields) {
						/** @var Ms2aConfigData $a */
						$a = $this->modx->getObject('Ms2aConfigData', ['key' => $fields['key']]) ?: $this->modx->newObject('Ms2aConfigData');
						$a->fromArray($fields);
						$a->save();
					}
					return TRUE;
				case 'insert':
					$config = json_decode(file_get_contents($bu), 1);
					foreach ($config as $k => $fields) {
						/** @var Ms2aConfigData $a */
						if (!$this->modx->getObject('Ms2aConfigData', ['key' => $fields['key']])) {
							$a = $this->modx->newObject('Ms2aConfigData');
							$a->fromArray($fields);
							$a->save();
						}
					}
					return TRUE;

			}

		}
	}

	return "ms2AnalyticsResetConfigProcessor";