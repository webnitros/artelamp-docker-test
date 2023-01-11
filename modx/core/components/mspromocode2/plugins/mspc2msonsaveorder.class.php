<?php

/**
 *
 */
class mspc2MsOnSaveOrder extends mspc2Plugin
{
    /**
     * @var mspc2Manager $manager
     */
    protected $manager;

    /**
     * @param msPromoCode2 $mspc2
     * @param array        $sp
     */
    public function __construct(msPromoCode2 &$mspc2, array &$sp)
    {
        parent::__construct($mspc2, $sp);

        $this->manager = $this->mspc2->getManager();
    }

    /**
     *
     */
    public function run()
    {
        /** @var msOrder $msOrder */
        $msOrder = &$this->sp['msOrder'];

        //
        if ($msOrder->get('update_products')) {
            $cart_cost = $cost = 0;
            $delivery_cost = $msOrder->get('delivery_cost');
            $products = $msOrder->getMany('Products');

            /** @var msOrderProduct $product */
            foreach ($products as $product) {
                $cart_cost += $product->get('cost');
            }
            $msOrder->fromArray(array(
                'cost' => $cart_cost + $delivery_cost,
                'cart_cost' => $cart_cost,
                'update_products' => false,
            ));

            // Save
            $msOrder->save();
            $msOrder->set('update_products', true);
        }
    }
}