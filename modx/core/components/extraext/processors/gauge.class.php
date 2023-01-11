<?php

	class TestProcessor extends modProcessor
	{

		public function process()
		{
			return $this->outputArray(
				[
					'columns'=>[
						'Label'=>'string',
						'Value'=>'number',
					],
					'data' => [
						['Memory', rand(0, 100)],
						['CPU', rand(0, 100)],
						['Network', rand(0, 100)]
					],
				]
			);
		}

		public function outputArray(array $array, $count = FALSE)
		{
			if ($count === FALSE) {
				$count = count($array);
			}
			$output = json_encode([
				'success' => TRUE,
				'total' => $count,
				'results' => $array,
			]);
			if ($output === FALSE) {
				$this->modx->log(modX::LOG_LEVEL_ERROR, 'Processor failed creating output array due to JSON error ' . json_last_error());
				return json_encode(['success' => FALSE]);
			}
			return $output;
		}
	}

	return 'TestProcessor';