<?php

	class AnnotationProcessor extends modProcessor
	{

		public function process()
		{
			return $this->outputArray(
				[
					'dateFormat' => 'YYYYMMDD',
					'columns' => [
						'Date' => 'date',
						'Kepler-22b mission' => 'number',
						'Kepler title' => 'string',
						'Kepler text' => 'string',
						'Gliese 163 mission' => 'number',
						'Gliese title' => 'string',
						'Gliese text' => 'string',
					],
					'data' => [
						[20210401, rand(0, 12400), undefined, undefined,
							rand(0, 35022), undefined, undefined],
						[20210402, rand(0, 10645), 'Lalibertines', 'First encounter',
							rand(0, 35022), undefined, undefined],
						[20210403, rand(0, 24045), 'Lalibertines', 'They are very tall',
							rand(0, 35022), 'Gallantors', 'First Encounter'],
						[20210404, rand(0, 35022), 'Lalibertines', 'Attack on our crew!',
							rand(0, 35022), 'Gallantors', 'Statement of shared principles'],
						[20210405, rand(0, 35022), 'Lalibertines', 'Heavy casualties',
							rand(0, 35022), 'Gallantors', 'Mysteries revealed'],
						[20210406, rand(0, 35022), 'Lalibertines', 'All crew lost',
							rand(0, 35022), 'Gallantors', 'Omniscience achieved'],
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

	return 'AnnotationProcessor';