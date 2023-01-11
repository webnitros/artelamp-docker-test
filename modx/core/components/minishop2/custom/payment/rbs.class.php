<?php

	include (dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/config/config.rbs.php';

	include 'rbs-lib/rbs-discount.php';

	if (!class_exists('msPaymentInterface')) {
		require_once dirname(dirname(dirname(__FILE__))) . '/model/minishop2/mspaymenthandler.class.php';
	}

	class RBS extends msPaymentHandler implements msPaymentInterface
	{

		/**
		 * Версия модуля
		 *
		 * @var string
		 */
		const module_version = "1.3.0";
		/**
		 * CMS
		 *
		 * @var string
		 */
		const cms_name = 'Modx Revolution';
		/**
		 * ЛОГИН МЕРЧАНТА
		 *
		 * @var string
		 */
		const merchant_login = RBS_MERCHANT_LOGIN;
		/**
		 * ПАРОЛЬ МЕРЧАНТА
		 *
		 * @var string
		 */
		const merchant_password = RBS_MERCHANT_PASSWORD;
		/**
		 * СТРАНИЦА ВОЗВРАТА ПОСЛЕ УСПЕШНОЙ ОПЛАТЫ
		 *
		 * @var string
		 */
		const success_url = RBS_SUCCESS_URL;
		/**
		 * СТРАНИЦА ВОЗВРАТА ПОСЛЕ УСПЕШНОЙ ОПЛАТЫ
		 *
		 * @var string
		 */
		const error_url = RBS_ERROR_URL;
		/**
		 * URL шлюза для тестового режима
		 *
		 * @var string
		 */
		const test_url = RBS_TEST_URL;
		/**
		 * URL шлюза для боевого режима
		 *
		 * @var string
		 */
		const prod_url = RBS_PROD_URL;
		/**
		 * НДС
		 *
		 *
		 * @var integer
		 */
		const vat_rate = RBS_VAT_RATE;

		/**
		 * Скидки
		 *
		 *
		 * @var boolean
		 */
		const discount_enable = RBS_DISCOUNT_ENABLED;
		/**
		 * Логирование
		 *
		 * @var boolean
		 */
		const logging = RBS_LOGGING;

		const ffd_version = RBS_FFD_VERSION;
		const ffd_payment_method = RBS_FFD_PAYMENT_METHOD;
		const ffd_payment_object = RBS_FFD_PAYMENT_OBJECT;

		public $config;
		public $modx;

		function __construct(xPDOObject $object, $config = [])
		{
			$this->modx = &$object->xpdo;

			$siteUrl = $this->modx->getOption('site_url');
			$assetsUrl = $this->modx->getOption('minishop2.assets_url', $config, $this->modx->getOption('assets_url') . 'components/minishop2/');

			$this->config = array_merge([
				'gatewayUrl' => RBS_TEST_MODE ? self::test_url : self::prod_url,
				'returnUrl' => trim($siteUrl) . '/' . trim($assetsUrl, '/') . '/payment/rbs.php',
				'userName' => self::merchant_login,
				'password' => self::merchant_password,
//            'currency' => self::currency,
				'jsonParams' => [
					'CMS' => self::cms_name,
					'Module-Version' => self::module_version,
				],
				'logging' => self::logging,
				'ffd_version' => self::ffd_version,
				'paymentMethod' => self::ffd_payment_method,
				'paymentObject' => self::ffd_payment_object,
				'tax_type' => self::vat_rate,
			], $config);

		}


		/**
		 * ФОРМИРОВАНИЕ ЗАКАЗА
		 *
		 * Метод register.do
		 *
		 * @param mixed[] Заказ
		 * @return mixed[]
		 */
		public function send(msOrder $order)
		{

			$id = $order->get('num');

			if (RBS_TWO_STAGE === TRUE) {
				$method = 'registerPreAuth.do';
			} else {
				$method = 'register.do';
			}

			$data = [
				'orderNumber' => $id,
				'amount' => round($order->get('cost') * 100),
				'description' => ("Оплата заказа - " . $id),
				'userName' => $this->config['userName'],
				'password' => $this->config['password'],
				'returnUrl' => $this->config['returnUrl'],
				'sessionTimeoutSecs' => RBS_SESSION_TIMEOUT_SECS,
//            'currency' => $this->config['currency'],
			];


			if (RBS_SEND_ORDER === TRUE) {


				$order_billing_phone = preg_replace('/\D+/', '', $_POST['phone']);
				$order_billing_email = $_POST['email'];
				$items = [];
				$itemsCnt = 1;

				$products = $order->getMany('Products');
				$i = 0;

				foreach ($products as $val) {
					// here is minishop2
					$tax_type = 0;

					/** @var msProduct $product */
					$name = $val->get('name');
					if (empty($name) && $product = $val->getOne('Product')) {
						$name = $product->get('pagetitle');
					}
					$price = $val->get('price') * 100;
					$count = $val->get('count');

					$item['positionId'] = $itemsCnt++;
					$item['name'] = $name;
					$item['quantity'] = [
						'value' => $val->get('count'),
						'measure' => 'шт',
					];
					$item['itemAmount'] = $price * $count;
					$item['itemCode'] = $val->get('id');
					$item['tax'] = [
						'taxType' => $this->config['tax_type'],
					];
					$item['itemPrice'] = str_replace(',', '.', $price);
					$i++;

					if ($this->config['ffd_version'] == "v105") {
						$item['itemAttributes'] = [
							'attributes' => [
								[
									'name' => 'paymentMethod',
									'value' => $this->config['paymentMethod'],
								],
								[
									'name' => 'paymentObject',
									'value' => $this->config['paymentObject'],
								],
							],
						];
					}
					$items[] = $item;
				}

				$delivery_cost = $order->get('delivery_cost');
				if ($delivery_cost > 0) {
					$delivery_info = $order->getOne('Delivery')->toArray();
					$item_delivery = [
						'positionId' => $itemsCnt++,
						'name' => $delivery_info['name'],
						'quantity' => [
							'value' => 1,
							'measure' => 'шт',
						],
						'itemAmount' => round($delivery_cost * 100),
						'itemCode' => $delivery_info['id'] . "_DELIVERY",
						'itemPrice' => round($delivery_cost * 100),
						'tax' => [
							'taxType' => $this->config['tax_type'],
						],
					];
					if ($this->config['ffd_version'] == "v105") {
						$item_delivery['itemAttributes'] = [
							'attributes' => [
								[
									'name' => 'paymentMethod',
									'value' => 4,
								],
								[
									'name' => 'paymentObject',
									'value' => 4,
								],
							],
						];
					}
					array_push($items, $item_delivery);
				}

				// DISCOUNT CALCULATE
				if (self::discount_enable) {
					$DiscountHelper = new rbsDiscount();
					$discount = $DiscountHelper->discoverDiscount($data['amount'], $items);
					if ($discount > 0) {
						$DiscountHelper->setOrderDiscount($discount);
						$recalculatedPositions = $DiscountHelper->normalizeItems($items);
						$recalculatedAmount = $DiscountHelper->getResultAmount();
						$items = $recalculatedPositions;
					}
				}

				/* Создание и заполнение массива данных заказа для фискализации */
				$order_bundle = [
					'orderCreationDate' => time(),
					'customerDetails' => [
						'email' => $order_billing_email,
						'phone' => $order_billing_phone,
					],
					'cartItems' => ['items' => $items],
				];
				$this->config['jsonParams']['email'] = $_POST['email'];
				/* Заполнение массива данных для запроса c фискализацией */
				$data['orderBundle'] = json_encode($order_bundle);
				$data['jsonParams'] = json_encode($this->config['jsonParams']);
				$data['taxSystem'] = RBS_TAX_SYSTEM;


			}

			$response = $this->gateway($method, $data);

			if ($response['errorCode'] > 0) {
				return $this->success('', ['redirect' => $this->config['returnUrl'] . '?error=1&code=' . $response['errorCode'] . '&message=' . $response['errorMessage']]);
			}
			$orderId = $order->get('id');
			$this->modx->cacheManager->set((int)$orderId, $response['formUrl'], 0, [xPDO::OPT_CACHE_KEY => 'rbs']);

			return $this->success('', ['redirect' => '/basket?msorder=' . $orderId]);
		}

		/**
		 * ПЕРЕДАЧА ДАННЫХ В ШЛЮЗ
		 *
		 *
		 * @param string - Название метода
		 * @param array [] - Данные
		 * @return mixed[]
		 */
		public function gateway($method, $data)
		{

			$curl = curl_init();
			curl_setopt_array($curl, [
				CURLOPT_URL => $this->config['gatewayUrl'] . $method,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_POST => TRUE,
				CURLOPT_POSTFIELDS => http_build_query($data),
				CURLOPT_HTTPHEADER => ['CMS:' . self::cms_name, 'Module-Version: ' . self::module_version],
				CURLOPT_SSLVERSION => 6,
			]);

			$response = curl_exec($curl);
			$response = json_decode($response, TRUE);

			curl_close($curl);

			if ($this->config['logging']) {
				$this->modx->log(modX::LOG_LEVEL_ERROR, '[ms2:rbs] Метод ' . $method . '  gateway request: ' . print_r($data, 1) . ' gateway response:' . print_r($response, 1));
			}
			return $response;
		}

		/**
		 * ПОЛУЧЕНИЕ ДАННЫХ О СТАТУСЕ ЗАКАЗА
		 *
		 * @param string id заказа в шлюзе
		 */
		public function receiver($orderId)
		{

			$data = [
				'orderId' => $orderId,
				'userName' => $this->config['userName'],
				'password' => $this->config['password'],
			];


			$response = $this->gateway('getOrderStatusExtended.do', $data);

			if (($response['errorCode'] == 0) && (($response['orderStatus'] == 1) || ($response['orderStatus'] == 2))) {
				/** @var miniShop2 $ms2 */
				$ms2 = $this->modx->getService('miniShop2');
				$ms2->changeOrderStatus($response['orderNumber'], 2);
				$redirectUrl = self::success_url . "?msorder={$response['orderNumber']}&pay=confirm";
			} else {
				$ms2 = $this->modx->getService('miniShop2');
				$ms2->changeOrderStatus($response['orderNumber'], 5);
				$error = $response['actionCodeDescription'];
				$redirectUrl = self::error_url . "?msorder={$response['orderNumber']}&pay=error&msg=$error";
			}
			$this->modx->cacheManager->delete((int)$response['orderNumber'], [xPDO::OPT_CACHE_KEY => 'rbs']);
			$this->modx->sendRedirect($redirectUrl);
		}

		public function returnMain()
		{
			$siteUrl = $this->modx->getOption('site_url');
			$this->modx->sendRedirect($siteUrl);
		}

		/**
		 * BASE METHOD, DONT USE
		 */
		public function receive(msOrder $order)
		{
		}
	}

