<?php

class mspc2Manager
{
    /**
     * @var array $config
     */
    public $config = [];
    /**
     * @var array $cache
     */
    protected $cache = [];
    /**
     * @var modX $modx
     */
    protected $modx;
    /**
     * @var msPromoCode2 $mspc2
     */
    protected $mspc2;
    /**
     * @var pdoFetch $pdo
     */
    protected $pdo;
    /**
     * @var miniShop2 $ms2
     */
    protected $ms2;
    /**
     * @var msOptionsPrice $msop
     */
    protected $msop;
    /**
     * @var string $ctx
     */
    protected $ctx = 'web';


    /**
     * @param msPromoCode2 $mspc2
     * @param array $config
     */
    function __construct(msPromoCode2 &$mspc2, array $config = [])
    {
        $this->mspc2 = &$mspc2;
        $this->modx = &$this->mspc2->modx;
        $this->config = $config;
    }


    /**
     * @param string $ctx
     *
     * @return bool
     */
    public function initialize($ctx = 'web')
    {
        $this->ctx = $ctx;

        //
        $this->pdo = $this->mspc2->getPdoFetch();

        //
        $this->ms2 = $this->mspc2->getMiniShop2();
        if (empty($this->ms2->cart)) {
            $this->ms2->loadServices($this->ctx);
        }
        $this->ms2->cart->initialize($this->ctx);

        //
        $this->msop = $this->mspc2->getMsOptionsPrice();

        return true;
    }


    /**
     * Get coupon from db
     * Returns array of coupon or error string
     *
     * @param int|string|array $id - Id (int), or code (string), or [id] (array)
     *
     * @return string|array
     */
    public function getCoupon($id)
    {
        $coupon = null;
        if (is_array($id) && isset($id['id'])) {
            $id = (int)$id['id'];
        }
        if (is_int($id)) {
            $key = 'id';
        } elseif (is_string($id)) {
            $key = 'code';
        }
        if (isset($key)) {
            // TODO: Maybe should move the get from cache here to avoid re-triggering plugins events?

            $response = $this->mspc2->tools->invokeEvent('mspc2OnBeforeGetCoupon', [
                'key' => $key,
                $key => $id,
            ]);
            if ($response['success']) {
                if (!empty($response['data']['coupon']) && is_array($response['data']['coupon'])) {
                    $coupon = $response['data']['coupon'];
                } else {
                    // Get coupon key value from plugin returned data
                    if (!empty($response['data'][$key])) {
                        $id = $response['data'][$key];
                    }

                    // Get from local cache
                    if (empty($coupon)) {
                        $coupon = empty($this->cache[$key][$id]) ? [] : $this->cache[$key][$id];
                    }

                    // Get from db
                    if (empty($coupon)) {
                        if ($couponObj = $this->modx->getObject('mspc2Coupon', [$key => $id])) {
                            $coupon = $couponObj->toArray();

                            // Check coupon on errors
                            if (is_array($coupon) && !empty($coupon)) {
                                $discount_amount = (float)$this->getCartDiscountAmount($coupon);

                                // TODO: Cut this chunk of code into a separate method
                                $message = '';
                                $lexicon_key = null;
                                switch (true) {
                                    case (is_numeric($coupon['count']) && $coupon['count'] <= 0):
                                        $lexicon_key = 'mspc2_front_err_coupon_count';
                                        break;

                                    case ($coupon['active'] === false):
                                        $lexicon_key = 'mspc2_front_err_coupon_active';
                                        break;

                                    case (!empty($coupon['startedon']) && $coupon['startedon'] > time()):
                                        $lexicon_key = 'mspc2_front_err_coupon_startedon';
                                        break;

                                    case (!empty($coupon['stoppedon']) && $coupon['stoppedon'] < time()):
                                        $lexicon_key = 'mspc2_front_err_coupon_stoppedon';
                                        break;

                                    case ($coupon['unsetifnull'] && empty($discount_amount)):
                                        if (!$message = $coupon['unsetifnull_msg']) {
                                            $lexicon_key = 'mspc2_front_err_coupon_cart_is_null';
                                        }
                                        break;
                                }

                                // Set error in coupon variable and unset coupon
                                if (!empty($message) || !empty($lexicon_key)) {
                                    // Too implicit action for this method
                                    // if ($_SESSION['msPromoCode2']['coupon'] === (int)$coupon['id']) {
                                    //     $this->unsetCoupon();
                                    // }
                                    $coupon = $message ?: $this->modx->lexicon($lexicon_key);
                                }
                                unset($lexicon_key);

                                //
                                if (is_array($coupon) && !empty($coupon['showinfo'])) {
                                    if (empty($discount_amount)) {
                                        $this->setMessageSession($this->modx->lexicon('mspc2_front_message_coupon_cart_is_null'), 'info');
                                    }
                                }

                                // Save to local cache
                                if (is_array($coupon)) {
                                    $this->cache['id'][$coupon['id']] = $this->cache['code'][$coupon['code']] = $coupon;
                                }
                            }
                        }
                        unset($couponObj);

                        /**
                         * Not used because PDO::FETCH_ASSOC returns each element as a string:
                         * $q = $this->modx->newQuery('mspc2Coupon')
                         *     ->select($this->modx->getSelectColumns('mspc2Coupon', 'mspc2Coupon'))
                         *     ->where($condition)
                         *     ->limit(1)
                         *     ;
                         * if ($q->prepare() && $q->stmt->execute()) {
                         *     $coupon = $q->stmt->fetch(PDO::FETCH_ASSOC);
                         * }
                         * unset($q);
                         */
                    }
                }
            } else {
                $coupon = (string)($response['message'] ?: $this->modx->lexicon('mspc2_err_unexpected'));
            }
            unset($response);

            //
            if (is_array($coupon) && !empty($coupon)) {
                $response = $this->mspc2->tools->invokeEvent('mspc2OnGetCoupon', [
                    'coupon' => $coupon,
                ]);
                if (!empty($response['data']['coupon']) && is_array($response['data']['coupon'])) {
                    $coupon = $response['data']['coupon'];
                }
                unset($response);
            }
        }

        // Coupon not found
        if (empty($coupon)) {
            $coupon = $this->modx->lexicon('mspc2_front_err_coupon_exist');
        }

        return $coupon;
    }

    /**
     * Creates a coupon with a code in a given format.
     * Returns array of coupon data or error string
     *
     * @param string $format
     * @param array $data
     *
     * @return array|string
     */
    public function generateCoupon($format, array $data)
    {
        $data = array_intersect_key($data, array_flip([
            'code', 'list', 'count', 'discount', 'description',
            'showinfo', 'allcart', 'oneunit', 'onlycart', 'unsetifnull', 'unsetifnull_msg', 'oldprice',
            'lifetime', 'startedon', 'stoppedon',
            'active', 'properties',
        ]));

        // Randomize code for coupon
        while (empty($data['code']) || $this->modx->getCount('mspc2Coupon', ['code' => $data['code']])) {
            $data['code'] = $this->mspc2->getRandexp()->get($format);
        }

        // Generate coupon
        $response = $this->mspc2->tools->runProcessor('mgr/coupons/create', array_merge([
            'active' => true,
        ], $data, [
            //
        ]));
        if (!$result = $this->mspc2->tools->formatProcessorErrors($response)) {
            $response = $response->getObject();
            $result = $this->getCoupon((int)$response['id']);
        }

        return $result;
    }

    /**
     * Get current coupon
     * Returns array of coupon or error string
     *
     * @return null|string|array
     */
    public function getCurrentCoupon()
    {
        $coupon = null;

        // Get coupon from placeholder
        $coupon_code = $this->modx->getPlaceholder('_coupon');
        if ($this->mspc2->tools->isJSON($coupon_code)) {
            $coupon = $this->modx->fromJSON($coupon_code);
        } elseif (is_string($coupon_code)) {
            $coupon = $this->getCoupon((string)$coupon_code);
        }

        // Get coupon from session
        elseif (is_null($coupon_code)) {
            $coupon_id = @$_SESSION['msPromoCode2']['coupon'] ?: null;
            if (is_int($coupon_id)) {
                $coupon = $this->getCoupon($coupon_id);
            }
        }

        return $coupon;
    }

    /**
     * Set coupon in session
     * Returns array of coupon or error string
     *
     * @param int|string|array $id - Id (int), or code (string), or [id] (array)
     *
     * @return string|array
     */
    public function setCoupon($id)
    {
        $this->unsetCoupon();
        $coupon = $this->getCoupon($id);
        if (is_array($coupon)) {
            $response = $this->mspc2->tools->invokeEvent('mspc2OnBeforeSetCoupon', [
                'order' => null,
                'coupon' => $coupon,
            ]);
            if ($response['success']) {
                // Save coupon id to session
                $_SESSION['msPromoCode2']['coupon'] = (int)$coupon['id'];

                // Set discount to cart
                $this->refreshCartDiscount();

                // Set discount amount to session
                $discount_amount = (float)($this->modx->getPlaceholder('_discount_amount') ?: 0);
                $_SESSION['msPromoCode2']['discount_amount'] = $discount_amount;

                //
                $this->mspc2->tools->invokeEvent('mspc2OnSetCoupon', [
                    'order' => null,
                    'coupon' => $coupon,
                    'discount_amount' => $discount_amount,
                ]);
            } else {
                $coupon = (string)($response['message'] ?: $this->modx->lexicon('mspc2_err_unexpected'));
            }
        }

        return $coupon;
    }

    /**
     * Unset coupon and remove from session
     *
     * @return bool
     */
    public function unsetCoupon()
    {
        if (!empty($_SESSION['msPromoCode2']['coupon']) && is_int($_SESSION['msPromoCode2']['coupon'])) {
            $coupon = $this->getCoupon($_SESSION['msPromoCode2']['coupon']);

            // Unset coupon from session
            unset($_SESSION['msPromoCode2']['coupon']);
            unset($_SESSION['msPromoCode2']['discount_amount']);
            unset($_SESSION['msPromoCode2']['messages']);

            // Unset discount from cart
            $this->refreshCartDiscount();

            // Unset discount_amount from session
            unset($_SESSION['msPromoCode2']['discount_amount']);

            //
            $this->mspc2->tools->invokeEvent('mspc2OnUnsetCoupon', [
                'order' => null,
                'coupon' => $coupon,
            ]);
        }

        return true;
    }

    /**
     * Update discount in the cart
     *
     * @return bool
     */
    public function refreshCartDiscount()
    {
        // Get cart products
        if ($cart = $this->ms2->cart->get()) {
            // Set from key to msPromoCode placeholder
            $this->modx->setPlaceholder('_call_from', 'cart');

            //
            $cart = $this->prepareProductPrices($cart);

            // Unset msPromoCode placeholder with from key
            $this->modx->unsetPlaceholder('_call_from');

            // Get discount amount
            $discount_amount = $this->getProductsDiscountAmount($cart);
            $_SESSION['msPromoCode2']['discount_amount'] = $discount_amount;
            $this->modx->setPlaceholder('_discount_amount', $discount_amount);

            // // Regenerate product keys in cart
            // $this->refreshCartProductKeys();

            //
            $this->ms2->cart->set($cart);
        }

        return true;
    }

    /**
     * Update discount in the order
     *
     * @param int|msOrder $id
     *
     * @return bool
     */
    public function refreshOrderDiscount($id)
    {
        /** @var null|msOrder $order */
        $order = null;
        if (is_object($id) && $id instanceof msOrder) {
            $order = $id;
        } elseif (is_numeric($id)) {
            $order = $this->modx->getObject('msOrder', ['id' => (int)$id]);
        }
        if (empty($order)) {
            return false;
        }

        // Get order products
        if ($orderProducts = $order->getMany('Products')) {
            $products = [];
            /** @var msOrderProduct $orderProduct */
            foreach ($orderProducts as $orderProduct) {
                $products[] = $orderProduct->toArray();
            }
            unset($orderProduct);

            // Set from key to msPromoCode placeholder
            $this->modx->setPlaceholder('_call_from', 'order');

            // Magic: set product context key to global
            $context_key = $this->modx->context->key;
            $this->modx->context->key = $order->get('context');

            //
            $products = $this->prepareProductPrices($products, $order);

            // Magic: reset product context key in global
            $this->modx->context->key = $context_key;
            unset($context_key);

            // Unset msPromoCode placeholder with from key
            $this->modx->unsetPlaceholder('_call_from');

            // Get discount amount
            $discount_amount = $this->getProductsDiscountAmount($products);
            $this->modx->setPlaceholder('_discount_amount', $discount_amount);

            // Set price with discount for order products
            foreach ($products as $product) {
                if ($orderProduct = $this->modx->getObject('msOrderProduct', [
                    'id' => $product['id'],
                ])) {
                    $orderProduct->set('price', $product['price']);
                    $orderProduct->set('cost', $product['price'] * $product['count']);
                    $orderProduct->save();
                }
            }
            unset($orderProduct);

            // Update order object and refresh cost, cart_cost, delivery_cost
            $order = $this->modx->getObject('msOrder', ['id' => $order->get('id')]);
            $order->updateProducts();
        }

        return true;
    }

    /**
     * Prepare product prices in array
     * Returns array of products
     *
     * @param array $rows
     * @param null|msOrder $order
     *
     * @return array
     */
    public function prepareProductPrices(array $rows, $order = null)
    {
        $ids = [];
        foreach ($rows as $key => $row) {
            $ids[@$row['product_id'] ?: $row['id']] = $key;
        }
        $ids = array_keys($ids);

        /** @var msProductData $productTmp */
        $productTmp = $this->modx->newObject('msProductData');

        //
        $coupon = $this->getCurrentCoupon();
        $coupon = is_array($coupon) ? $coupon : null;

        //
        $products = [];
        $this->pdo->setConfig([
            'parents' => 0,
            'resources' => join(',', $ids),
            'class' => 'msProduct',
            'leftJoin' => [
                'Data' => [
                    'class' => 'msProductData',
                ],
            ],
            'select' => [
                'msProduct' => $this->modx->getSelectColumns('msProduct', 'msProduct', '', array('content'), true),
                'Data' => $this->modx->getSelectColumns('msProductData', 'Data', '', array('id'), true),
            ],
            'where' => [
                'class_key' => 'msProduct',
            ],
            'groupby' => 'msProduct.id',
            'limit' => 0,
            'offset' => 0,
            'depth' => 100,
            'return' => 'data',
        ], false);
        foreach (($this->pdo->run() ?: []) as $v) {
            $products[$v['id']] = $v;
        }

        //
        $ids = array_flip($ids);
        foreach ($rows as $key => &$row) {
            $row['product_id'] = @$row['product_id'] ?: $row['id'];
            if (empty($products[$row['product_id']])) {
                continue;
            }
            $product = $products[$row['product_id']];

            /** @var string|array $options */
            $options = $row['options'] ?: [];
            $options = is_array($options) ? $options : $this->modx->fromJSON($options);
            if (!empty($options)) {
                unset($options['modifications'], $options['modification']);
            }
            $product['msoptionsprice_options'] = $options;
            // $this->modx->log(1, 'prepareProductPrices $options ' . print_r($options, 1));

            // Magic: set product context key to global
            $context_key = $this->modx->context->key;
            $this->modx->context->key = $product['context_key'];

            // Get product price
            $productTmp->fromArray($product, '', true, true);
            $price = $productTmp->getPrice($product);

            // Magic: reset product context key in global
            $this->modx->context->key = $context_key;
            unset($context_key);

            // Get msOptionsPrice placeholder
            $returned = $this->modx->getPlaceholder('_returned_price') ?: [];

            // Get price without discount
            if (!empty($returned['price_without_discount'])) {
                $product['price'] = $returned['price_without_discount'];
            }

            // Get discount of product
            $row['discount_price'] = ($product['price'] - $price);
            $row['discount_cost'] = $row['discount_amount'] = $row['discount_price'] * ($row['count'] ?: 1);
            $row['price'] = $price;

            // Get modification id
            $modification_id = 0;
            if (!empty($returned['msoptionsprice_options']['modification'])) {
                $modification_id = $returned['msoptionsprice_options']['modification'];
            }

            // Get product old price with msOptionsPrice method
            $row['old_price'] = 0;
            if (!empty($modification_id) && !empty($this->msop)) {
                // $this->modx->log(1, '$product[pagetitle] ' . print_r($product['pagetitle'], 1));
                // $this->modx->log(1, '$modification_id ' . print_r($modification_id, 1));

                if ($modification = $this->msop->getModificationById($modification_id, $product['id'])) {
                    if (!empty($row['price'])) {
                        $modification['cost'] = $row['price'];
                    }
                    $row['old_price'] = $this->msop->getOldCostByModification($modification);

                    // $this->modx->log(1, '$row[old_price] ' . print_r($row['old_price'], 1));
                }
            }

            // Get product old price
            if (empty($row['old_price'])) {
                if (!empty($product['old_price']) && $row['price'] < $product['old_price']) {
                    $row['old_price'] = $product['old_price'];
                } elseif (!empty($product['price']) && $row['price'] < $product['price']) {
                    $row['old_price'] = $product['price'];
                }
            }

            // Unset msOptionsPrice placeholder
            $this->modx->setPlaceholder('_returned_price', null);

            //
            if (is_array($coupon)) {
                $response = $this->mspc2->tools->invokeEvent('mspc2OnSetProductDiscountPrice', [
                    'coupon' => $coupon,
                    'order' => $order ?: null,
                    'product' => $row,
                    'key' => $key,
                    'price' => $row['price'],
                    'old_price' => $product['price'],
                    'discount' => $returned['discount_amount'],
                    'discount_price' => $row['discount_price'],
                    'discount_cost' => $row['discount_cost'],
                ]);
                if ($response['success']) {
                    if (!empty($response['data']['product']) && is_array($response['data']['product'])) {
                        $row = $response['data']['product'];
                    }
                }
            }
        }
        unset(
            $productTmp,
            $products,
            $product,
            $product_id,
            $ids,
            $key,
            $price,
            $options,
            $returned,
            $row
        );

        return $rows;
    }

    /**
     * Checks if the product is joined to the coupon
     *
     * @param int|string|array $coupon - Id (int), or code (string), or [id] (array)
     * @param int $product_id
     *
     * @return bool
     */
    public function isProductJoinedToCoupon($coupon, $product_id)
    {
        $joined = null;
        if (!is_array($coupon) || empty($coupon['id']) || empty($coupon['code'])) {
            $coupon = $this->getCoupon($coupon);
        }
        if (!is_array($coupon) || empty($product_id)) {
            return $joined;
        }

        // Checking exists joins of coupon
        if ($this->modx->getCount('mspc2Join', [
            'coupon' => $coupon['id'],
        ])) {
            $join_product_exists = false;
            $join_category_exists = false;

            // First checking product join
            $q = $this->modx->newQuery('mspc2Join')
                ->select('discount')
                ->where([
                    'type' => 'product',
                    'coupon' => $coupon['id'],
                    'resource' => $product_id,
                ])
                ->limit(1)
            ;
            if ($q->prepare()->execute()) {
                if ($join = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    // $this->modx->log(1, 'getProductDiscount product $product_id ' . print_r($product_id, 1));
                    // $this->modx->log(1, 'getProductDiscount product $join ' . print_r($join, 1));

                    $join_product_exists = true;

                    //
                    $joined = (string)$join['discount'] !== '0';
                }
            }
            unset($q);

            // Second checking category join
            if (is_null($joined)) {
                // Get categories tree of product
                $parent_ids = $this->modx->getParentIds($product_id, 10, [
                    'context' => $this->modx->context->get('key'),
                ]);
                array_pop($parent_ids);
                // $this->modx->log(1, 'getProductDiscount category $parent_ids 1 ' . print_r($parent_ids, 1));

                // Get additional categories
                $additional_categories = [];
                $q = $this->modx->newQuery('msCategoryMember')
                    ->select('category_id')
                    ->where([
                        'product_id' => $product_id,
                    ])
                ;
                if ($q->prepare()->execute()) {
                    $additional_categories = $q->stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
                }
                if (!empty($additional_categories)) {
                    $parent_ids = array_merge(
                        $parent_ids,
                        $additional_categories
                    );
                }
                // $this->modx->log(1, 'getProductDiscount category $parent_ids 2 ' . print_r($parent_ids, 1));

                //
                $q = $this->modx->newQuery('mspc2Join')
                    ->select([
                        'resource',
                        'discount',
                    ])
                    ->where([
                        'type' => 'category',
                        'coupon' => $coupon['id'],
                        'resource:IN' => $parent_ids,
                    ])
                    ->sortby('discount', 'DESC')
                ;
                if ($q->prepare()->execute()) {
                    if ($joins = $q->stmt->fetchAll(PDO::FETCH_ASSOC)) {
                        // $this->modx->log(1, 'getProductDiscount category $product_id ' . print_r($product_id, 1));
                        // $this->modx->log(1, 'getProductDiscount category $joins ' . print_r($joins, 1));

                        $join_category_exists = true;

                        // If without additional categories
                        if (empty($additional_categories)) {
                            // Restructure joins array
                            $tmp = [];
                            foreach ($joins as $join) {
                                $tmp[$join['resource']] = $join;
                            }
                            $joins = $tmp;
                            unset($tmp);

                            // Get join item for category with maximum depth
                            foreach ($parent_ids as $parent_id) {
                                if (!empty($joins[$parent_id])) {
                                    $join = $joins[$parent_id];
                                    $joined = (string)$join['discount'] !== '0';
                                    break;
                                }
                            }
                        } else {
                            // Get first item of join object
                            foreach ($joins as $join) {
                                $joined = (string)$join['discount'] !== '0';
                                break;
                            }
                        }
                        unset($join);
                    }
                }
                unset($q);
            }
        } else {
            $joined = true;
        }

        //
        if (is_null($joined)) {
            $joined = false;
        }

        return $joined;
    }

    /**
     * Get discount of product
     * Returns discount string of product
     *
     * @param int|string|array $coupon - Id (int), or code (string), or [id] (array)
     * @param int $product_id
     *
     * @return string
     */
    public function getProductDiscount($coupon, $product_id)
    {
        $discount = null;
        if (!is_array($coupon) || empty($coupon['id']) || empty($coupon['code'])) {
            $coupon = $this->getCoupon($coupon);
        }
        if (!is_array($coupon) || empty($product_id)) {
            return $discount;
        }

        // Checking exists joins of coupon
        if ($this->modx->getCount('mspc2Join', [
            'coupon' => $coupon['id'],
        ])) {
            $join_product_exists = false;
            $join_category_exists = false;

            // First checking product join
            $q = $this->modx->newQuery('mspc2Join')
                ->select('discount')
                ->where([
                    'type' => 'product',
                    'coupon' => $coupon['id'],
                    'resource' => $product_id,
                ])
                ->limit(1)
                ;
            if ($q->prepare()->execute()) {
                if ($join = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    // $this->modx->log(1, 'getProductDiscount product $product_id ' . print_r($product_id, 1));
                    // $this->modx->log(1, 'getProductDiscount product $join ' . print_r($join, 1));

                    $join_product_exists = true;

                    //
                    if ($join['discount'] !== '') {
                        $discount = $join['discount'];
                    }
                }
            }
            unset($q);

            // Second checking category join
            if (is_null($discount)) {
                // Get categories tree of product
                $parent_ids = $this->modx->getParentIds($product_id, 10, [
                    'context' => $this->modx->context->get('key'),
                ]);
                array_pop($parent_ids);
                // $this->modx->log(1, 'getProductDiscount category $parent_ids 1 ' . print_r($parent_ids, 1));

                // Get additional categories
                $additional_categories = [];
                $q = $this->modx->newQuery('msCategoryMember')
                    ->select('category_id')
                    ->where([
                        'product_id' => $product_id,
                    ])
                ;
                if ($q->prepare()->execute()) {
                    $additional_categories = $q->stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
                }
                if (!empty($additional_categories)) {
                    $parent_ids = array_merge(
                        $parent_ids,
                        $additional_categories
                    );
                }
                // $this->modx->log(1, 'getProductDiscount category $parent_ids 2 ' . print_r($parent_ids, 1));

                //
                $q = $this->modx->newQuery('mspc2Join')
                    ->select([
                        'resource',
                        'discount',
                    ])
                    ->where([
                        'type' => 'category',
                        'coupon' => $coupon['id'],
                        'resource:IN' => $parent_ids,
                    ])
                    ->sortby('discount', 'DESC')
                ;
                if ($q->prepare()->execute()) {
                    if ($joins = $q->stmt->fetchAll(PDO::FETCH_ASSOC)) {
                        // $this->modx->log(1, 'getProductDiscount category $product_id ' . print_r($product_id, 1));
                        // $this->modx->log(1, 'getProductDiscount category $joins ' . print_r($joins, 1));

                        $join_category_exists = true;

                        // If without additional categories
                        if (empty($additional_categories)) {
                            // Restructure joins array
                            $tmp = [];
                            foreach ($joins as $join) {
                                $tmp[$join['resource']] = $join;
                            }
                            $joins = $tmp;
                            unset($tmp);

                            // Get discount for category with maximum depth
                            foreach ($parent_ids as $parent_id) {
                                if (!empty($joins[$parent_id])) {
                                    $join = $joins[$parent_id];
                                    if ($join['discount'] !== '') {
                                        $discount = $join['discount'];
                                        break;
                                    }
                                }
                            }
                        } else {
                            // Get first discount of join object
                            foreach ($joins as $join) {
                                if ($join['discount'] !== '') {
                                    $discount = $join['discount'];
                                    break;
                                }
                            }
                        }
                        unset($join);
                    }
                }
                unset($q);
            }

            //
            if (is_null($discount)) {
                $discount = '';
            }

            //
            if (($join_product_exists === true || $join_category_exists === true) &&
                (is_null($discount) || $discount === '')
            ) {
                $discount = $coupon['discount'];
            }
        } elseif (is_null($discount)) {
            // Get discount from coupon
            $discount = $coupon['discount'];
        }

        return $discount;
    }

    /**
     * Get discount amount from products array
     * Returns amount of discount
     *
     * @param array $rows
     *
     * @return float
     */
    public function getProductsDiscountAmount(array $rows)
    {
        $discount_amount = 0;
        foreach ($rows as $row) {
            $discount_amount += $row['discount_amount'] ?: 0;
        }

        return (float)$discount_amount;
    }

    /**
     * Get discount amount from products of cart
     * Returns amount of discount
     *
     * @param null|int|string|array $coupon - Id (int), or code (string), or [id] (array)
     * @param bool $format
     *
     * @return float|string
     */
    public function getCartDiscountAmount($coupon = null, $format = false)
    {
        $discount_amount = (float)0;
        if (is_null($coupon)) {
            if (!empty($_SESSION['msPromoCode2']['coupon']) && is_int($_SESSION['msPromoCode2']['coupon'])) {
                $coupon = $_SESSION['msPromoCode2']['coupon'];
            }
        }
        if (!is_array($coupon) || empty($coupon['code'])) {
            $coupon = $this->getCoupon($coupon);
        }
        if (is_array($coupon)) {
            if (isset($_SESSION['msPromoCode2']['discount_amount'])) {
                $discount_amount = (float)($_SESSION['msPromoCode2']['discount_amount'] ?: 0);
            } else {
                $products = $this->modx->getPlaceholder('_order_products');
                if ($this->mspc2->tools->isJSON($products)) {
                    $products = $this->modx->fromJSON($products);
                }
                if (empty($products)) {
                    $products = $this->ms2->cart->get();
                }
                if (!empty($products)) {
                    // Set coupon to msPromoCode placeholder
                    $tmp = $this->modx->getPlaceholder('_coupon');
                    $this->modx->setPlaceholder('_coupon', $this->modx->toJSON($coupon));

                    // Set from key to msPromoCode placeholder
                    $this->modx->setPlaceholder('_call_from', 'cart');

                    //
                    $products = $this->prepareProductPrices($products);

                    // Unset msPromoCode placeholder with coupon
                    if (is_null($tmp)) {
                        $this->modx->unsetPlaceholder('_coupon');
                    } else {
                        $this->modx->setPlaceholder('_coupon', $tmp);
                    }
                    unset($tmp);

                    // Unset msPromoCode placeholder with from key
                    $this->modx->unsetPlaceholder('_call_from');

                    // Get discount amount
                    $discount_amount = $this->getProductsDiscountAmount($products);
                }
            }
        }

        //
        if ($format) {
            $this->ms2->formatPrice($discount_amount);
        }

        return $discount_amount;
    }

    /**
     * Get count of product of cart
     * Returns count of product
     *
     * @param int $id
     * @param array $options
     *
     * @return int
     */
    public function getCartProductCount($id, array $options)
    {
        $count = 0;
        $products = $this->modx->getPlaceholder('_order_products');
        if ($this->mspc2->tools->isJSON($products)) {
            $products = $this->modx->fromJSON($products);
        }
        if (empty($products)) {
            $products = $this->ms2->cart->get();
        }
        if (!empty($products)) {
            //
            unset($options['modifications'], $options['modification']);
            ksort($options);

            // $this->modx->log(1, '$id ' . print_r($id, 1));
            // $this->modx->log(1, '$options ' . print_r($options, 1));
            // $this->modx->log(1, '$products ' . print_r($products, 1));

            //
            foreach ($products as $product) {
                if ((int)$product['id'] === (int)$id) {
                    //
                    unset(
                        $product['options']['modifications'],
                        $product['options']['modification']
                    );
                    ksort($product['options']);
                    if ($product['options'] === $options) {
                        $count += $product['count'];
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Get total cost of cart products
     * Returns total cost of cart products
     *
     * @return int|float
     */
    public function getCartCost()
    {
        $cost = 0;
        $products = $this->modx->getPlaceholder('_order_products');
        if ($this->mspc2->tools->isJSON($products)) {
            $products = $this->modx->fromJSON($products);
        }
        if (empty($products)) {
            $products = $this->ms2->cart->get();
        }
        if (!empty($products)) {
            foreach ($products as $product) {
                $cost += ($product['price'] * $product['count']) + (@$product['discount_amount'] ?: 0);
                // $cost += ($product['price'] + (@$product['discount_price'] ?: 0)) * $product['count'];
            }
        }

        return $cost;
    }

    /**
     * Checks if a coupon is allowed for goods in the current cart
     *
     * @param null|int|string|array $coupon - Id (int), or code (string), or [id] (array)
     *
     * @return bool
     */
    public function isCouponAllowedOnCart($coupon = null)
    {
        $allowed = false;
        $products = $this->modx->getPlaceholder('_order_products');
        if ($this->mspc2->tools->isJSON($products)) {
            $products = $this->modx->fromJSON($products);
        }
        if (empty($products)) {
            $products = $this->ms2->cart->get();
        }
        if (!empty($products)) {
            foreach ($products as $product) {
                $allowed = $this->isProductJoinedToCoupon($coupon, $product['id']);
            }
        }

        return $allowed;
    }

    /**
     * Update keys of cart products
     *
     * @return bool
     */
    public function refreshCartProductKeys()
    {
        // Get cart products
        if ($cart = $this->ms2->cart->get()) {
            // Regenerate product keys in cart
            $tmp = [];
            foreach ($cart as $k => $v) {
                $k = md5($v['id'] . $v['price'] . $v['weight'] . (json_encode($v['options'])));
                if (isset($tmp[$k])) {
                    $v['count'] += $tmp[$k]['count'];
                }
                $tmp[$k] = $v;
            }
            $cart = $tmp;
            unset($tmp);

            //
            $this->ms2->cart->set($cart);
        }

        return true;
    }

    /**
     * @param msOrder|int $order
     *
     * @return msOrder
     */
    public function getOrder($order)
    {
        if (is_numeric($order)) {
            $order = $this->modx->getObject('msOrder', ['id' => $order]);
        }
        if (!is_object($order) || !$order instanceof msOrder) {
            $order = null;
        }

        return $order;
    }

    /**
     * Checking order status, variable $status_type is equal order status
     *
     * @param msOrder|int $order
     * @param string $status_type
     *
     * @return bool
     */
    public function isOrderStatus($order, $status_type)
    {
        $flag = false;
        if ($order = $this->getOrder($order)) {
            if ($status = $this->modx->getOption('mspc2_order_status_' . $status_type)) {
                $flag = (int)$order->get('status') === (int)$status;
            }
        }

        return $flag;
    }

    /**
     *
     *
     * @param string $type
     *
     * @return string
     */
    public function getMessageSession($type = 'error')
    {
        $msg = (@$_SESSION['msPromoCode2']['messages'][$type] ?: '');
        unset($_SESSION['msPromoCode2']['messages'][$type]);

        return $msg;
    }

    /**
     *
     *
     * @param string $msg
     * @param string $type
     *
     * @return string
     */
    public function setMessageSession($msg, $type = 'error')
    {
        if (!is_string($msg)) {
            $msg = '';
        }
        if (!empty($msg)) {
            if (empty($_SESSION['msPromoCode2']['messages'])) {
                $_SESSION['msPromoCode2']['messages'] = [];
            }
            $_SESSION['msPromoCode2']['messages'][$type] = $msg;
        }

        return $msg;
    }

    /**
     *
     *
     * @param string $type
     *
     * @return mixed
     */
    public function unsetMessageSession($type = 'error')
    {
        if (empty($_SESSION['msPromoCode2']['messages'])) {
            $_SESSION['msPromoCode2']['messages'] = [];
        }
        unset($_SESSION['msPromoCode2']['messages'][$type]);
    }
}