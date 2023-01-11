<?php


	class MyControllerConfigSave extends modRestController
	{
		public $protected = TRUE;

		public function get()
		{
			return $this->post();
		}

		public function post()
		{
//			$this->properties = json_decode(file_get_contents('php://input'), 1);
//			$sessionId        = $this->getProperty('sessionId');
//			$data             = $this->getProperty('data');
//			$cart             = $this->getProperty('cart');
//			$name             = $this->getProperty('name');
//			if ($sessionId && $name && !empty($data)) {
//				/** @var smartSessionHandler $session */
//				$session = $this->modx->getService($this->modx->getOption('session_handler_class'));
//				$db      = $session->_getSession($sessionId, FALSE);
//				if ($db) {
//					$user_id = (int)$db->get('user_id');
//					if ($user_id > 0) {
//						$_name     = $this->modx->quote($name);
//						$_user_id = $this->modx->quote($user_id);
//						$data     = json_encode($data, JSON_UNESCAPED_UNICODE);
//						$cart     = json_encode($cart, JSON_UNESCAPED_UNICODE);
//						$id       = $this->modx->query("select id from `modx_configurators` where `userId`={$_user_id} and `name`={$_name}");
//
//						if ($id && $id = $id->fetch(PDO::FETCH_COLUMN)) {
//							$upd = $this->modx->prepare(<<<SQL
//UPDATE `modx_configurators` SET `data`=:dat, `cart`=:cart WHERE id=:id
//SQL
//							);
//							$upd->execute(['dat' => $data, 'cart' => $cart, 'id' => $id]);
//						} else {
//							$ins = $this->modx->prepare(<<<SQL
//INSERT INTO `modx_configurators` (`userId`, `data`, `cart`, `name`) VALUES (:user_id, :dat, :cart, :name)
//SQL
//							);
//							$ins->execute(['user_id' => $user_id, 'dat' => $data, 'cart' => $cart, 'name' => $name,]);
//							$id = $this->modx->lastInsertId();
//						}
//						$this->success('yes', ['id' => $id]);
//						return;
//					}
//					$this->failure('Сохранять может только авторизированный пользователь');
//					return;
//				}
//				$this->failure('Сохранять может только авторизированный пользователь');
//				return;
//			}
			$this->failure('empty request');
			session_write_close();
		}
	}