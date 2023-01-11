<?php

	class TestProcessor extends modProcessor
	{

		public function process()
		{
			return $this->outputArray(
				[
					'columns'=>[
						'Name'=>'string',
						'value'=>'number',
					],
					'data' => [
						['1',  rand(0, 100)],
						['2',  rand(0, 100)],
						['3',  rand(0, 100)],
						['4',  rand(0, 100)],
						['5',  rand(0, 100)],
						['6',  rand(0, 100)],
						['7',  rand(0, 100)],
						['8',  rand(0, 100)],
						['9',  rand(0, 100)],
						['10', rand(0, 100)],
						['11', rand(0, 100)],
						['12', rand(0, 100)],
						['13', rand(0, 100)],
						['14', rand(0, 100)]
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