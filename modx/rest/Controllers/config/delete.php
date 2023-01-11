<?php


	class MyControllerConfigDelete extends modRestController
	{
		public $protected = TRUE;

		public function get()
		{
			return $this->post();
		}

		public function post()
		{
//			$id = (int)$_GET['id'];
//			if (!$id) {
//				$this->failure('empty id', [], 400);
//				return;
//			}
//			if ($this->modx->user) {
//				$uid    = (int)$this->modx->user->get('id');
//				$userId = (int)$this->modx->query("SELECT userId FROM modx_configurators WHERE id = $id")->fetch(PDO::FETCH_COLUMN);
//				if ($userId) {
//					if ($userId === $uid) {
//						$this->modx->exec("DELETE FROM modx_configurators WHERE id = $id");
//						$this->success('ok', $id, 200);
//						return;
//					}
//					$this->failure('not allowed', [$uid, $userId], 403);
//					return;
//				}
//				$this->success('already', $id, 201);
//				return;
//			}
			$this->failure('is not auth', [], 403);
			session_write_close();
		}
	}