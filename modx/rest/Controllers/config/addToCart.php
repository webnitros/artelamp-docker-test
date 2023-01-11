<?php

	class MyControllerConfigAddToCart extends modRestController
	{
		public $protected = TRUE;

		public function get()
		{
			return $this->post();
		}

		public function post()
		{
			$cart = $this->getProperties();
			$arts = [];
			$q    = $this->modx->newQuery('msProductData');
			$q->select("article,id,stock");
			if ($q->prepare() && $q->stmt->execute()) {
				while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
					$arts[$row['article']] = $row;
				}
			}
			/** @var miniShop2 $miniShop2 */
			if ($miniShop2 = $this->modx->getService('miniShop2')) {
				// Инициализируем класс в текущий контекст
				$scriptProperties = [
					'json_response'     => TRUE, // возвращать ответы в JSON
					'allow_deleted'     => FALSE, // не добавлять в корзину товары с deleted = 1
					'allow_unpublished' => FALSE, // не добавлять в корзину товары с published = 0
				];
				$miniShop2->initialize("web", $scriptProperties);
				$errors  = [];
				$success = [];
				foreach ($cart as $art => $count) {
					$r = json_decode($miniShop2->cart->add((int)$arts[$art]['id'], $count), 1);
					if ($r['success']) {
						$success[$art] = $r;
					} else {
						$errors[$art] = $r;
					}
				}
			}
			if (empty(!$errors)) {
				$this->failure('errors', ['errors' => $errors, 'success' => $success, 'session' => session_id()], empty($success) ? 400 : 200);
			} else {
				$this->success("ok", ['success' => $success, 'errors' => $errors,'session'=>session_id()]);
			}
			session_write_close();
		}
	}