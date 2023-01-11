<?php
	/**
	 * Created by Andrey Stepanenko.
	 * User: webnitros
	 * Date: 17.12.2019
	 * Time: 12:07
	 */

	class fdkEventsHandlerCreateOrder extends fdkEventsHandler
	{
		/**
		 * Перед созданием заказа
		 * @param modSystemEvent $event
		 * @param array          $scriptProperties
		 * @return bool
		 */
		public function msOnBeforeCreateOrder(modSystemEvent $event, $scriptProperties = [])
		{
			/* @var array $scriptProperties */
			/* @var msOrder $msOrder */
			/* @var msOrderHandler $order */
			$msOrder = $scriptProperties['msOrder'];
			$order = $scriptProperties['order'];
			$data = $order->get();


			$email = !empty($data['email']) ? $data['email'] : '';
			$phone = !empty($data['phone']) ? $data['phone'] : '';
			$receiver = !empty($data['receiver']) ? $data['receiver'] : '';

			if (!empty($email)) {
				$msOrder->Address->set('email', $email);
			}


			if ($city = $this->getCityName()) {
				$msOrder->Address->set('city', $city); // Записываем город в котором пользователь оформил заказ
			}


			// Проверка что заказ отправил администратор
			if ($this->isSendAdmin($data) || ($this->blockedUserOrders($email) or $this->blockedUserOrders($phone) or $this->blockedUserOrders($receiver))) {
				$msOrder->set('is_send_admin', TRUE);
				$msOrder->set('order_in_1c', TRUE); // чтобы заказы в 1с не отправлялись
			}

			return TRUE;
		}

		private function getCityName()
		{
			/* @var UserLocation $UserLocation */
			if ($UserLocation = $this->modx->getService('userlocation.UserLocation', '', MODX_CORE_PATH . 'components/userlocation/model/')) {
				$Location = $UserLocation->getLocation();
				if ($Location instanceof ulLocation) {
					return $Location->get('name');
				}
			}
			return NULL;
		}

		/**
		 * Проверка что отправил администратор
		 * @param array $data
		 * @return bool
		 */
		private function isSendAdmin($data = [])
		{
			if ($this->modx->user->isAuthenticated('mgr')) {
				return $is_send_admin = TRUE;
			} else {
				$is_send_admin = FALSE;
				$receiver = $this->modx->util->rawText($data['receiver']);
				if (strripos($receiver, 'тестовый') !== FALSE) {
					return $is_send_admin = TRUE;
				} else {
					switch ($receiver) {
						case 'тест':
						case 'test':
							return $is_send_admin = TRUE;
							break;
						default:
							break;
					}

					$email = trim((string)$data['email']);
					switch ($email) {
						case 'bustep.ru@yandex.ru':
						case 'info@bustep.ru':
						case 'technolighttest@gmail.com':
						case 'msoneclick4@artelamp.it':
						case 'msoneclick4@artelamp.ru':
							return $is_send_admin = TRUE;
							break;
						default:
							break;
					}

					$phone = (string)$data['phone'];
					if (strripos($phone, '00000') !== FALSE or strripos($phone, '999999') !== FALSE or strripos($phone, '1111111') !== FALSE) {
						return $is_send_admin = TRUE;
					} elseif (strripos($phone, '222222') !== FALSE) {
						return $is_send_admin = TRUE;
					} elseif ($this->modx->user->id == 1) {
						return $is_send_admin = TRUE;
					}
				}
			}
			return $is_send_admin;
		}


		/**
		 * Блокирует пользователя по наименованию
		 * @param $url
		 * @return bool
		 */
		private function blockedUserOrders($q)
		{
			$q = mb_strtolower($q);
			$blockedWords = explode(',', $this->modx->getOption('fdk_blocked_user_orders'));
			if (!empty($blockedWords)) {
				foreach ($blockedWords as $word) {
					if (!empty($word)) {
						$word = mb_strtolower($word);
						if (strripos($q, $word) !== FALSE) {
							return TRUE;
						}
					}
				}
			}

			return FALSE;
		}
	}

	return [
		'class' => 'fdkEventsHandlerCreateOrder',
		'events' => [
			'msOnBeforeCreateOrder',
		],
	];