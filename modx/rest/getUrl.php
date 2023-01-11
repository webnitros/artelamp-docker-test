<?php

	class MyControllerGetUrl extends ApiInterface
	{

		public function post()
		{
			$this->get();
		}

		public function get()
		{
			$art = $_REQUEST['art'];
			if($art) {
				$q = $this->modx->newQuery('msProductData');
				$q->where(['artikul_1c' => $art]);
				$q->limit(1);
				$p = $this->modx->getObject('msProductData', $q);
				if ($p) {
					$id = $p->get('id');
					echo $this->modx->makeUrl($id, 'web', [], 'https');
					return TRUE;
				}
				http_response_code(404);
				return FALSE;
			}
			http_response_code(400);
			return FALSE;
		}
	}