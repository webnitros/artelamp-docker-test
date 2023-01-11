<?php

/**
 *
 */
class mspc2MsOnCreateOrder extends mspc2Plugin
{
    /**
     * @var miniShop2 $ms2
     */
    protected $ms2;
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

        //
        $this->ms2 = $this->mspc2->getMiniShop2();
        if (empty($this->ms2->cart)) {
            $this->ms2->loadServices();
        }

        //
        $this->manager = $this->mspc2->getManager();
    }

    /**
     *
     */
    public function run()
    {
        /** @var msOrder $order */
        $order = $this->sp['msOrder'];
        if ($order instanceof msOrder) {
            // Get coupon
            $coupon = $this->manager->getCurrentCoupon();
            $coupon = is_array($coupon) ? $coupon : null;
            if (!empty($coupon)) {
                $discount_amount = (float)($_SESSION['msPromoCode2']['discount_amount'] ?: 0);

                /** @var mspc2CouponOrder $couponOrder */
                if ($couponOrder = $this->modx->newObject('mspc2CouponOrder')) {
                    $couponOrder->fromArray([
                        'order' => (int)$order->get('id'),
                        'coupon' => (int)$coupon['id'],
                        'code' => (string)$coupon['code'],
                        'discount' => (string)$coupon['discount'],
                        'discount_amount' => $discount_amount,
                    ]);
                    $couponOrder->save();
                }

                // Add new record to msOrderLog
                $this->ms2->orderLog($order->get('id'), 'coupon', 'set');

                // Unset coupon after
                $this->manager->unsetCoupon();
            }
        }

        // /** @var msOrder $order */
        // $order = $this->sp['msOrder'];
        // if ($order instanceof msOrder) {
        //     // Get coupon
        //     $coupon = $this->manager->getCurrentCoupon();
        //     $coupon = is_array($coupon) ? $coupon : null;
        //     if (!empty($coupon)) {
        //         $coupon['discount_amount'] = (float)($_SESSION['msPromoCode2']['discount_amount'] ?: 0);
        //     }
        //
        //     //
        //     if (is_array($coupon)) {
        //         $data = [
        //             'coupon' => (int)$coupon['id'],
        //             'code' => (string)$coupon['code'],
        //             'discount' => (string)$coupon['discount'],
        //             'discount_amount' => $coupon['discount_amount'],
        //         ];
        //     } else {
        //         $data = [
        //             'coupon' => 0,
        //             'code' => '',
        //             'discount' => '',
        //             'discount_amount' => 0,
        //         ];
        //     }
        //     $data['order'] = (int)$order->get('id');
        //
        //     /** @var mspc2CouponOrder $couponOrder */
        //     if ($couponOrder = $this->modx->newObject('mspc2CouponOrder')) {
        //         $couponOrder->fromArray($data);
        //         $couponOrder->save();
        //     }
        //
        //     if (is_array($coupon)) {
        //         // Add new record to msOrderLog
        //         $this->ms2->orderLog($order->get('id'), 'coupon', 'set');
        //
        //         // Unset coupon after
        //         $this->manager->unsetCoupon();
        //     }
        // }
    }
}