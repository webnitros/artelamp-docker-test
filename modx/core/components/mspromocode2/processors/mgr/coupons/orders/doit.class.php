<?php

class mspc2CouponOrderDoItProcessor extends modProcessor
{
    /**
     * @var msPromoCode2 $mspc2
     */
    protected $mspc2;
    /**
     * @var miniShop2 $ms2
     */
    protected $ms2;
    /**
     * @var mspc2Manager $manager
     */
    protected $manager;

    /**
     * @return bool
     */
    public function initialize()
    {
        //
        $this->mspc2 = $this->modx->getService('mspromocode2', 'msPromoCode2',
            $this->modx->getOption('mspc2_core_path', null, MODX_CORE_PATH . 'components/mspromocode2/') . 'model/mspromocode2/');
        $this->mspc2->initialize($this->modx->context->key);

        //
        $this->ms2 = $this->mspc2->getMiniShop2();
        if (empty($this->ms2->cart)) {
            $this->ms2->loadServices();
        }

        //
        $this->manager = $this->mspc2->getManager();

        return parent::initialize();
    }

    /**
     * @return string
     */
    public function process()
    {
        $action = preg_replace('/^.+\/([a-z]+)$/', '$1', $this->getProperty('action', ''));
        if (empty($action)) {
            return $this->mspc2->tools->failure('mspc2_err_unexpected');
        }

        /** @var msOrder $order */
        $order_id = (int)$this->getProperty('order', 0);
        if (!$order = $this->modx->getObject('msOrder', ['id' => $order_id])) {
            return $this->mspc2->tools->failure('mspc2_ms2_err_required_order');
        }

        /** @var string $code */
        $code = (string)$this->getProperty('code', '');
        if ($action === 'set' && empty($code)) {
            return $this->mspc2->tools->failure('mspc2_err_code_required');
        }

        //
        $data = [
            'order' => null,
            'coupon' => [
                'code' => '',
                'discount' => 0,
                'discount_amount' => 0,
            ],
            'status' => ($action === 'set'),
        ];

        /** @var mspc2CouponOrder $couponOrder */
        /** @var string|array|mspc2Coupon $coupon */
        switch ($action) {
            /**
             * Get coupon order
             */
            case 'get':
                // Get coupon data
                if ($couponOrder = $this->modx->getObject('mspc2CouponOrder', [
                    'order' => $order_id,
                ])) {
                    // Get coupon data
                    $coupon = $couponOrder->getOne('Coupon');
                    $data['coupon'] = array_merge(
                        is_object($coupon) ? $coupon->toArray() : [],
                        $couponOrder->toArray()
                    );

                    // Set status
                    $data['status'] = true;
                }
                break;

            /**
             * Set coupon to order
             */
            case 'set':
                // Check order status
                if ($this->manager->isOrderStatus($order, 'paid') ||
                    $this->manager->isOrderStatus($order, 'cancel')) {
                    return $this->mspc2->tools->failure('mspc2_ms2_err_order_paid');
                }

                // Check coupon in order
                if ($couponOrder = $this->modx->getObject('mspc2CouponOrder', [
                    'order' => $order_id,
                ])) {
                    // Get coupon data
                    $coupon = $couponOrder->getOne('Coupon');
                    $data['coupon'] = array_merge(
                        is_object($coupon) ? $coupon->toArray() : [],
                        $couponOrder->toArray()
                    );
                } else {
                    // Get order products
                    $products = [];
                    if ($orderProducts = $order->getMany('Products')) {
                        /** @var msOrderProduct $orderProduct */
                        foreach ($orderProducts as $orderProduct) {
                            $product = $orderProduct->toArray();
                            $product['id'] = !empty($product['product_id'])
                                ? $product['product_id'] : $product['id'];
                            $products[] = $product;
                        }
                    }
                    unset($orderProducts, $orderProduct);

                    // Set order products to msPromoCode placeholder
                    $this->modx->setPlaceholder('_order_products', $this->modx->toJSON($products));

                    // Check and get coupon
                    $coupon = $this->manager->getCoupon($code);
                    if (!is_array($coupon)) {
                        return $this->mspc2->tools->failure($coupon);
                    }

                    //
                    $response = $this->mspc2->tools->invokeEvent('mspc2OnBeforeSetCoupon', [
                        'order' => $order,
                        'coupon' => $coupon,
                    ]);
                    if (!$response['success']) {
                        return $this->mspc2->tools->failure($response['message']);
                    }

                    // Set coupon to msPromoCode placeholder
                    $this->modx->setPlaceholder('_coupon', $this->modx->toJSON($coupon));

                    // Set coupon to order products
                    $this->manager->refreshOrderDiscount($order);

                    // Get discount amount
                    $discount_amount = (float)($this->modx->getPlaceholder('_discount_amount') ?: 0);

                    // Unset msPromoCode placeholder with coupon
                    $this->modx->unsetPlaceholder('_coupon');

                    // Unset msPromoCode placeholder with order products
                    $this->modx->unsetPlaceholder('_order_products');

                    // Set coupon to order
                    $couponOrder = $this->modx->newObject('mspc2CouponOrder');
                    $couponOrder->fromArray([
                        'order' => (int)$order->get('id'),
                        'coupon' => (int)$coupon['id'],
                        'code' => (string)$coupon['code'],
                        'discount' => (string)$coupon['discount'],
                        'discount_amount' => $discount_amount,
                    ]);
                    $couponOrder->save();

                    $this->mspc2->tools->invokeEvent('mspc2OnSetCoupon', [
                        'order' => $order,
                        'coupon' => $coupon,
                        'discount_amount' => $discount_amount,
                    ]);

                    // Get coupon data
                    $data['coupon'] = array_merge(
                        $coupon ?: [],
                        $couponOrder->toArray()
                    );
                }
                break;

            /**
             * Unset coupon from order
             */
            case 'unset':
                // Check order status
                if ($this->manager->isOrderStatus($order, 'paid') ||
                    $this->manager->isOrderStatus($order, 'cancel')) {
                    return $this->mspc2->tools->failure('mspc2_ms2_err_order_paid');
                }

                // Check coupon in order
                if ($couponOrder = $this->modx->getObject('mspc2CouponOrder', [
                    'order' => $order_id,
                ])) {
                    // Set coupon to msPromoCode placeholder
                    $this->modx->setPlaceholder('_coupon', '');

                    // Set coupon to order products
                    $this->manager->refreshOrderDiscount($order);

                    // Unset msPromoCode placeholder with coupon
                    $this->modx->unsetPlaceholder('_coupon');

                    // Get coupon code
                    $coupon = $couponOrder->getOne('Coupon');
                    $data['coupon']['code'] = $couponOrder->get('code');
                    $coupon = $coupon->toArray();

                    // Remove coupon from order
                    $couponOrder->remove();

                    //
                    $this->mspc2->tools->invokeEvent('mspc2OnUnsetCoupon', [
                        'order' => $order,
                        'coupon' => $coupon,
                    ]);
                }
                break;
        }

        // Add new record to msOrderLog
        if (in_array($action, ['set', 'unset'], true)) {
            $this->ms2->orderLog($order_id, 'coupon', $action);
        }

        // Get order data
        if ($order = $this->modx->getObject('msOrder', $order_id)) {
            $data['order'] = $order->toArray();
        }

        //
        return $this->modx->toJSON([
            'success' => true,
            'object' => $data,
        ]);
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('mspromocode2:default');
    }
}

return 'mspc2CouponOrderDoItProcessor';