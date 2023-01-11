<?php
	$suffix = '_art';
	/** @var modX $modx */
	/** @var array $scriptProperties */
	/** @var msOrder $msOrder */
	/** @var object $order */
	switch ($modx->event->name) {
		case 'msOnSaveOrder':
			if ($scriptProperties['mode'] == modSystemEvent::MODE_NEW) {
				$id = $msOrder->get('id');
				$msOrder->set('num', "{$id}{$suffix}");
				$msOrder->save();
			}
			break;
		case 'msOnSubmitOrder':
			if ($cart = $order->ms2->cart->status()) {
				$minCartCost = $modx->getOption('minCartCost');
				if ($cart['total_cost'] < $minCartCost) {
					$modx->event->output("Сумма вашего заказа должна превышать $minCartCost руб!");
				}
			}
			break;
	}
	return NULL;