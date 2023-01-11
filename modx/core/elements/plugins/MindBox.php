<?php
	/** @var modX $modx */
	/** @var array $scriptProperties */
	/** @var msOrder $msOrder */
	switch ($modx->event->name) {
		case 'msOnSaveOrder':
			if ($scriptProperties['mode'] === modSystemEvent::MODE_NEW && $_COOKIE['mindboxDeviceUUID']) {
				$token = $modx->getOption('mindbox');
				$mindboxDeviceUUID = $_COOKIE['mindboxDeviceUUID'];
				$id                = $msOrder->get('id');
				$user              = $msOrder->getOne('UserProfile');
				$address           = $msOrder->getOne('Address');
				if (!$user || !$address) {
					break;
				}
				$products = [];
				$q        = $modx->newQuery('msOrderProduct');
				$q->setClassAlias('op');
				$q->select('op.*,data.artikul_1c,data.show_artikul,data.article,data.id as PID');
				$q->innerJoin('msProductData', 'data', 'data.id = op.product_id');
				$q->where([
							  'op.order_id' => $id,
						  ]);
				if ($q->prepare() && $q->stmt->execute()) {
					while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
						$products[] = [
							'basePricePerItem' => (float)$row['price'],
							'quantity'         => (int)$row['count'],
							'product'          =>
								[
									'ids' =>
										[
											'websiteArteLampRu' => $row['PID'],
										],
								],
						];
					}
				}
				$request = [
					'customer' =>
						[
							'ids'           =>
								[
									'websiteIDArteLamp' => $user->get('id'),
								],
							'email'         => $user->get('email'),
							'mobilePhone'   => $user->get('phone'),
							'fullName'      => $user->get('fullname'),
							'customFields'  =>
								[
									'artelampClient' => TRUE,
									'artelampCity'   => $address->get('city'),
								],
							'subscriptions' =>
								[
									0 =>
										[
											'brand'          => 'ArteLamp',
											'pointOfContact' => 'Email',
											'topic'          => 'triggersArteLamp',
											'isSubscribed'   => TRUE,
										],
									1 =>
										[
											'brand'          => 'ArteLamp',
											'pointOfContact' => 'Email',
											'topic'          => 'promoArteLamp',
											'isSubscribed'   => TRUE,
										],
								],
						],
					'order'    =>
						[
							'ids'        =>
								[
									'websiteIDArteLamp' => $id,
								],
							'totalPrice' => $msOrder->get('cost'),
							'lines'      => $products,
						],
				];
				$curl    = curl_init();

				curl_setopt_array($curl, [
					CURLOPT_URL            => 'https://api.mindbox.ru/v3/operations/async?endpointId=artelamp-website&operation=Website.CreateUnauthorizedOrder.ArteLamp&deviceUUID='
						. $mindboxDeviceUUID,
					CURLOPT_RETURNTRANSFER => TRUE,
					CURLOPT_ENCODING       => '',
					CURLOPT_MAXREDIRS      => 10,
					CURLOPT_TIMEOUT        => 5,
					CURLOPT_FOLLOWLOCATION => TRUE,
					CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST  => 'POST',
					CURLOPT_POSTFIELDS     => json_encode($request),
					CURLOPT_HTTPHEADER     => [
						'Content-Type: application/json; charset=utf-8',
						'Accept: application/json',
						'Authorization: Mindbox secretKey="'.$token.'"'
					],
				]);

				$response = curl_exec($curl);
				curl_close($curl);
			}
			break;
	}
	return NULL;