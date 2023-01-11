<?php

	class MyController1cResendOrder extends ApiInterface
	{

		public function post()
		{
			$this->get();
		}

		public function get()
		{
			$modx = $this->modx;

			$orders = [];

			$day = (int)$_REQUEST['day'];
			if (empty($day)) {
				$day = 10;
			}
			$today = date('Y-m-d H:i:s', strtotime('-' . $day . ' days', time()));
			/* @var msOrder $order */
			$q = $modx->newQuery('msOrder');
			$q->where([
						  'order_in_1c' => 0,
						  'is_send_admin' => 0,
						  'createdon:>' => $today,
					  ]);
			if ($objectList = $modx->getCollection('msOrder', $q)) {
				foreach ($objectList as $order) {
					$send = $this->order($order);
					echo '<pre>';
					$c = $this->checkOrder($send);
					if (!$c['is_exist']) {
						$a = $this->send('order_send', $send);
						if($a['errCode'] == 0) {
							echo 'Отправил: ' . $send['order_info']['number'] . PHP_EOL;
							$order->set('order_in_1c', 1);
							$order->set('order_1c_id', $a['uuid']);
						}
					} else {
						echo 'Уже существует: ' . $send['order_info']['number'] . PHP_EOL;
						$order->set('order_in_1c', 1);
						$order->set('order_1c_id', $c['uuid']);
					}
					$order->save();
				}
			}
			$this->success('', $orders);
		}

		function checkOrder($order)
		{
			$response = $this->send('order_exist?number=' . $order['order_info']['number']);
			return $response;
		}

		public function send($method, $data = [])
		{
			$curl = curl_init();
			curl_setopt_array($curl, [
				CURLOPT_URL            => 'https://rest.massive.ru/' . $method,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_ENCODING       => '',
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 0,
				CURLOPT_FOLLOWLOCATION => TRUE,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => 'POST',
				CURLOPT_POSTFIELDS     => json_encode($data, 256),
				CURLOPT_HTTPHEADER     => [
					'Authorization: Basic PEJhc2ljIEF1dGggVXNlcm5hbWU+OjxCYXNpYyBBdXRoIFBhc3N3b3JkPg==',
					'Content-Type: application/json',
				],
			]);
			$response = curl_exec($curl);
			curl_close($curl);
			return json_decode($response, 1);
		}

		public function order($order)
		{

			$Address  = $order->getOne('Address');
			$order_id = $order->get('id');
			$q        = $this->modx->newQuery('msOrderProduct');
			$q->setClassAlias('op');
			$q->select('op.*,data.artikul_1c,data.show_artikul,data.article');
			$q->innerJoin('msProductData', 'data', 'data.id = op.product_id');
			$q->where([
						  'op.order_id' => $order_id,
					  ]);
			if ($q->prepare() && $q->stmt->execute()) {
				while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
					$this->prepareData($row);
				}
			}
			$phone         = $this->formatPhone($this->modx->util->rawText($Address->get('phone')));
			$payment       = (int)$order->get('payment');
			$phoneFeatures = FALSE;
			if ($Profile = $Address->getOne('UserProfile')) {
				$mobilephone = $Profile->get('mobilephone');
				if (!empty($mobilephone)) {
					if (strripos($phone, $mobilephone) === FALSE) {
						$phoneFeatures = TRUE;
					}
				}
			}

			$note = ($tmp = $Address->get('comment')) ? $Address->get('comment') : '';
			if ($phoneFeatures) {
				if (!empty(trim($note))) {
					$note .= PHP_EOL;
				}
				$note .= ' Телефон указанный в заказе отличает от пользовательского. Указанный в заказе ' . $phone . '; У пользователя: ' . $mobilephone;
			}
			switch ($payment) {
				case 10:
					$payment = 2;
					break;
				default:
					$payment = 1;
					break;
			}
			$params = [
				'products_list' => $this->products,
				'order_info'    => [
					"status"        => 1,
					'number'        => (string)$order->get('num'),
					"price_type"    => 2,
					"delivery_type" => 1,
					"pay_type"      => $payment,
					'shop'          => "000000023",
					'note'          => (string)$note,
					#'note' => ($tmp = $Address->get('comment')) ? $Address->get('comment') : 'ТЕСТОВЫЙ ЗАКАЗ С НОВОГО САЙТА. ОТГРУЖАТЬ НЕ НУЖНО',
					'login'         => '',
					'createdon'     => date('c', strtotime($order->get('createdon'))),
					'type'          => '', // Не используется
					'source'        => "artelamp.it", // Не используется
				],
				'client_info'   => [
					'id'     => (string)str_replace('+7', '', $phone),
					'name'   => (string)$Address->get('receiver'),
					'phone'  => (string)$phone,
					'email'  => (string)$Address->get('email'),
					'city'   => (string)$Address->get('city'),
					'street' => (string)$Address->get('street'),
					'house'  => (string)$Address->get('building'),
					'room'   => (string)$Address->get('room'),
				],
			];
			return $params;
		}

		public function formatPhone($phone)
		{
			#хорошо, спасибо. Такие просто шлем, "как есть", если с 7/8 или +7, или 10 цифр, то приводим к +7

			$len   = strlen($phone);
			$first = substr($phone, 0, 1);
			switch ($len) {
				case 11:
					if ($first == 7) {
						$phone = '+' . $phone;
					} elseif ($first == 8) {
						$phone = substr($phone, 1);
						$phone = '+7' . $phone;
					}
					break;
				case 10:
					if ($first != 7) {
						$phone = '+7' . $phone;
					}
					break;
				default:
					break;
			}
			return $phone;
		}

		public function prepareData($product)
		{
			$defective = FALSE;


			$artikul = $product['artikul_1c'] ?: $product['article'] ?: $product['show_artikul'];
			$price   = $product['price'];
			$count   = $product['count'];


			$data             = [
				'count'          => (int)$count,
				'price'          => (float)$price,
				'artikul'        => (string)$artikul,
				'price_discount' => (float)$price,
			];
			$this->products[] = $data;
		}
	}