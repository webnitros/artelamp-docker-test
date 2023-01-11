<?php

/**
 *
 */
class mspc2MsOnUpdateOrderProduct extends mspc2Plugin
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
        /** @var msOrder $order */
        /** @var msOrderProduct $orderProduct */
        /** @var mspc2CouponOrder $couponOrder */
        /** @var string|array|mspc2Coupon $coupon */
        $orderProduct = $this->sp['object'];
        if (!is_object($orderProduct)) {
            return;
        }
        $order = $this->modx->getObject('msOrder', $orderProduct->get('order_id'));

        // Get order coupon
        if ($couponOrder = $this->modx->getObject('mspc2CouponOrder', [
            'order' => $order->get('id'),
        ])) {
            // Get coupon data
            $coupon = $couponOrder->getOne('Coupon');

            // Set coupon to msPromoCode placeholder
            $this->modx->setPlaceholder('_coupon', $this->modx->toJSON($coupon));

            // Set coupon to order products
            $this->manager->refreshOrderDiscount($order);

            // Get discount amount
            $discount_amount = (float)($this->modx->getPlaceholder('_discount_amount') ?: 0);

            // Unset msPromoCode placeholder with coupon
            $this->modx->unsetPlaceholder('_coupon');

            // Update discount amount in order
            if (!empty($couponOrder)) {
                $couponOrder->set('discount_amount', $discount_amount);
                $couponOrder->save();
            }
        }
    }
}