<?php

	class MyControllerConfigGet extends modRestController
	{
		public $protected = TRUE;

		public function get()
		{
			return $this->post();
		}

		public function post()
		{
//			$this->properties = json_decode(file_get_contents('php://input'), 1);
//			$config_id        = $this->getProperty('id');
//			if ($config_id) {
//				$data = $this->modx->query("select * from `modx_configurators` where `id`={$config_id}");
//				if ($data && $data = $data->fetch(PDO::FETCH_ASSOC)) {
//					$data['data'] = json_decode($data['data'], 1);
//					$data['cart'] = json_decode($data['cart'], 1);
//					$this->success("ok", $data);
//					return;
//				}
//				$this->failure("invalid id ", $_GET['id']);
//				return;
//			}
			$this->failure("Missing id ", $_GET['id']);
			session_write_close();
		}
	}