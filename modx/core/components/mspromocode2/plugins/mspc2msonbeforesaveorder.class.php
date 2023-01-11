<?php

/**
 * Remember old costs
 */
class mspc2MsOnBeforeSaveOrder extends mspc2Plugin
{
    public function run()
    {
        /** @var msOrder $msOrder */
        $msOrder = &$this->sp['msOrder'];
        $msOrder->set('cart_cost_old', $msOrder->get('cart_cost'));
        $msOrder->set('cost_old', $msOrder->get('cost'));
    }
}