<?php

/**
 *
 */
class mspc2MsOnGetProductPrice extends mspc2Plugin
{
    /**
     * @var mspc2Manager $manager
     */
    protected $manager;
    /**
     * @var msOptionsPrice $msop
     */
    protected $msop;

    /**
     * @param msPromoCode2 $mspc2
     * @param array        $sp
     */
    public function __construct(msPromoCode2 &$mspc2, array &$sp)
    {
        parent::__construct($mspc2, $sp);

        //
        $this->manager = $this->mspc2->getManager();

        //
        $this->msop = $this->mspc2->getMsOptionsPrice();
    }

    /**
     *
     */
    public function run()
    {
        /** @var msProductData $product */
        $data = $this->sp['data'];
        $product = $this->sp['product'];
        $price = $price_without_discount = $this->sp['price'];
        $oldprice = (float)(!empty($data['old_price'])
            ? $data['old_price']
            : (is_object($product)
                ? $product->get('old_price')
                : 0
            ));
        $returned = $this->modx->getPlaceholder('_returned_price') ?: [];
        $call_from = $this->modx->getPlaceholder('_call_from') ?: 'product';
        // $this->modx->log(1, '$data ' . print_r($data, 1));
        // $this->modx->log(1, '$product ' . print_r($product->toArray(), 1));
        // $this->modx->log(1, '$_REQUEST ' . print_r($_REQUEST, 1));
        // $this->modx->log(1, '$call_from ' . print_r($call_from, 1));
        // $this->modx->log(1, '$oldprice ' . print_r($oldprice, 1));

        // Get price from msOptionsPrice placeholder
        if (!empty($returned['price'])) {
            if (empty($data['id']) || (int)$returned['id'] === (int)$data['id']) {
                $price = $price_without_discount = $returned['price'];
            }
        }
        $returned['price'] = $price;

        // Set product id to msOptionsPrice placeholder
        if (!empty($data['id'])) {
            $returned['id'] = $data['id'];
        }
        // if (empty($returned['id']) && !empty($data['id'])) {
        //     $returned['id'] = $data['id'];
        // }

        // Set discount amount element of array
        $returned['discount_amount'] = 0;

        // Set price without discount to msOptionsPrice placeholder
        $returned['price_without_discount'] = $price_without_discount;
        // $this->modx->log(1, '$returned ' . print_r($returned, 1));

        // Get product id
        $product_id = $returned['id'] ?: $data['id'];

        // Get options of cart product
        $options = $this->modx->getOption('msoptionsprice_options', $data);
        $options = is_null($options)
            ? $this->modx->getOption('msoptionsprice_options', $returned)
            : $options;
        $options = is_null($options)
            ? $this->modx->getOption('options', $_REQUEST)
            : $options;
        $options = is_array($options) ? $options : [];
        if (isset($options[0]) && $options[0] === '[]') {
            unset($options[0]);
        }
        // $this->modx->log(1, '$data ' . print_r($data, 1));
        // $this->modx->log(1, '$returned ' . print_r($returned, 1));
        // $this->modx->log(1, '$options ' . print_r($options, 1));

        //
        if (!empty($this->msop)) {
            // Get modification id
            $modification_id = @$options['modification'] ?: 0;
            // $this->modx->log(1, '$modification_id ' . print_r($modification_id, 1));

            // Get modification data
            $modification = [];
            if (!empty($modification_id)) {
                $modification = $this->msop->getModificationById($modification_id, $product_id);
            }
            elseif (empty($modification_id) && !empty($options)) {
                $modification = $this->msop->getModificationByOptions($product_id, $options);
            }
            // $this->modx->log(1, '$modification ' . print_r($modification, 1));

            // Get product modification old price with msOptionsPrice method
            if (!empty($modification)) {
                $oldprice = $this->msop->getOldCostByModification($modification);
                // $this->modx->log(1, '$oldprice ' . print_r($oldprice, 1));
            }
        }

        if (is_array($options)) {
            // Get actual coupon
            $coupon = $this->manager->getCurrentCoupon();
            $coupon = is_array($coupon) ? $coupon : null;
            // $this->modx->log(1, '$coupon ' . print_r($coupon, 1));

            //
            $is_discount = is_array($coupon) && !empty($coupon); // is coupon valid
            if ($is_discount === true) { // AND
                $is_discount = $coupon['allcart'] && in_array($call_from, ['cart', 'order']); // is all cart discount
                if ($is_discount === false) { // OR
                    $is_discount = !$coupon['allcart']; // is not all cart discount
                    if ($is_discount === true) { // AND
                        $is_discount = !$coupon['oldprice'] || empty($oldprice); // is old price allowed
                        if ($is_discount === true) { // AND
                            $is_discount = !$coupon['onlycart'] || in_array($call_from, ['cart', 'order']); // is print discount only in cart page
                        }
                    }
                }
            }

            //
            // if (is_array($coupon) && !empty($coupon) // is coupon valid
            //     && (
            //         ($coupon['allcart'] && in_array($call_from, ['cart', 'order'])) // is all cart discount
            //         || (
            //             (!$coupon['oldprice'] || empty($oldprice)) // is old price allowed
            //             &&
            //             (!$coupon['onlycart'] || in_array($call_from, ['cart', 'order'])) // is print discount only in cart page
            //         )
            //     )
            // ) {
            if ($is_discount === true) {
                $discount = 0;

                if ($coupon['allcart']) {
                    if ($this->manager->isCouponAllowedOnCart($coupon)) {
                        $discount = $coupon['discount'];
                        if (!empty(floatval($discount)) && strstr($discount, '%') === false) {
                            $cart_cost = $this->manager->getCartCost();
                            $price_percent_of_cost = $price * 100 / $cart_cost;
                            $discount = (($discount / 100) * floatval($price_percent_of_cost));
                        }
                    }
                } else {
                    // Get product discount
                    $discount = $this->manager->getProductDiscount($coupon, $product_id);

                    // Get discount for one unit of product
                    if ($coupon['oneunit'] && in_array($call_from, ['cart', 'order']) && !empty(floatval($discount))) {
                        if (strstr($discount, '%') !== false) {
                            $discount = (($price / 100) * floatval($discount));
                        }
                        $count = $this->manager->getCartProductCount($product_id, $options);
                        $count = empty($count) ? 1 : $count;
                        $discount = $discount / $count;
                    }
                }

                //
                $returned['discount_amount'] = $discount;

                //
                if (!empty($data) && !empty(floatval($discount))) {
                    // Get sale price
                    if (strstr($discount, '%') !== false) {
                        $price = $price - (($price / 100) * floatval($discount));
                    } else {
                        $price = $price - floatval($discount);
                    }
                }

                // Prepare min price
                $min_price = $this->modx->getOption('mspc2_min_price', null, 0);
                if (is_numeric($min_price)) {
                    if ($price_without_discount > $min_price) {
                        if ($price < $min_price) {
                            $price = $min_price;
                        }
                    } else {
                        $price = $price_without_discount;
                    }
                }
            }
        }

        // Set product price
        $returned['price'] = $price;
        $this->setPrice($returned);
    }

    /**
     * @param $returned
     *
     * @return bool
     */
    protected function setPrice($returned)
    {
        if (is_numeric($returned['price'])) {
            $this->modx->setPlaceholder('_returned_price', $returned);
            $this->modx->event->returnedValues['price'] = $returned['price'];
        }

        return true;
    }
}