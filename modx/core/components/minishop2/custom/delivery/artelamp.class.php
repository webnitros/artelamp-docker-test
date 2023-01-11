<?php
	if (!class_exists('msDeliveryInterface')) {
		require_once dirname(dirname(dirname(__FILE__))) . '/model/minishop2/msdeliveryhandler.class.php';
	}

	class ArtelampDeliveryHandler extends msDeliveryHandler implements msDeliveryInterface
	{

		public function getCost(msOrderInterface $order, msDelivery $delivery, $cost = 0)
		{
			$cart      = $order->ms2->cart->status();
			$cart_cost = (int)$cart['total_cost'];
			$limits    = $delivery->get('description');
			if (empty($limits)) {
				return $cost;
			}
			$limits = explode("\n", $limits);
			if (empty($limits)) {
				return $cost;
			}
			$add_price = 0;
			foreach ($limits as $limit) {
				$limit = explode("|", $limit);
				$l     = (int)$limit[0];
				$c     = (int)$limit[1];
				if ($cart_cost > $l) {
					$add_price = $c;
				}
			}
			return $cost + $add_price;
		}
	}