<?php

	class MyControllerQr extends modRestController
	{
		public $protected = TRUE;

		public function get()
		{
			return $this->post();
		}

		public function post()
		{
			$art = $_GET['art'];
			if ($art) {
				$art = urldecode($art);
				$q = $this->modx->newQuery('msProductData');
				$q->select('id');
				$q->where([
							  'article' => $art,
						  ]);
				if ($q->prepare() && $q->stmt->execute()) {
					$id = $q->stmt->fetch(PDO::FETCH_COLUMN);
					if ($id) {
						$this->modx->sendRedirect($this->modx->makeUrl($id,'web','','absolute'));
						return;
					}
					$this->failure("Unknown article ", $art);
					return;

				}
				$this->failure("sql error ", $art);
				return;
			}
			$this->failure("Missing article ", $art);
			return;
		}
	}