<?php
include_once dirname(dirname(__FILE__)) . '/default.php';

class msOneClickFormMsProcessor extends msOneClickFormDefaultProcessor
{
    protected $method = "MS";

    /**
     * @return array|string
     */
    public function process()
    {

        $product_id = $this->getProperty('product_id');
        $count = $this->getProperty('count');
        $options = $this->getProperty('options', array());

        $this->unsetProperty('product_id');
        $this->unsetProperty('count');
        $this->unsetProperty('options');
        $this->unsetProperty('hash');
        $this->unsetProperty('price');
        $this->unsetProperty('ctx');
        $this->unsetProperty('msc_action');
        $this->unsetProperty('fast_order');
        $this->unsetProperty('pageId');

        $data = $this->getProperties();


        // Сохраняем состояние корины и отчищаем её
        $old_cart = $this->ms2->cart->get();
        $old_order = $this->ms2->order->get();
        $this->ms2->cart->clean();
        $this->ms2->order->clean();

        // add product cart
        $response = $this->ms2->cart->add($product_id, $count, $options);
        if (!is_array($response)) {
            $response = $this->modx->fromJSON($response);
        }
        if (!$response['success']) {
            return $response;
        }

        $this->enabledDeliveryPayment();


        // Внесение корректировки в отправку заказа по причине того что в minishop добавли обязательность включения способа доставки при отправки
        // так же в minishop добавли обязательность заполнения email адреса
        $email_own_name = $this->modx->getOption('msoneclick_email_own_name', null, '');
        if (!empty($email_own_name)) {
            $email = trim($this->getProperty('email', ''));
            if (empty($email)) {
                $data['email'] = $email_own_name;
                $this->setProperty('email', $email_own_name);
            }
        } else {
            if ($email_generate = $this->modx->getOption('msoneclick_email_generate', null, true)) {
                $email = trim($this->getProperty('email', ''));
                if (empty($email)) {

                    $site = trim($this->modx->getOption('msoneclick_email_site', null, null));
                    $prefix = trim($this->modx->getOption('msoneclick_email_prefix', null, 'msoneclick'));
                    $site = empty($site) ? $_SERVER['HTTP_HOST'] : $site;
                    $site = '@' . $site;


                    $next = 1;
                    $username = null;
                    $q = $this->modx->newQuery('modUser');
                    $q->select('username');
                    $q->sortby('username', 'ASC');
                    $q->where(array(
                        'username:LIKE' => '%' . $prefix . '%',
                    ));
                    if ($q->prepare() && $q->stmt->execute()) {
                        while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                            $value = str_ireplace($site, '', $row['username']);
                            $int = (int)str_ireplace($prefix, '', $value);
                            if ($int > $next) {
                                $next = $int;
                            }
                        }
                    }
                    $next++;
                    $newemail = $prefix . $next . $site;

                    $data['email'] = $newemail;
                    $this->setProperty('email', $newemail);
                }
            }
        }


        // set order value
        $this->ms2->order->set($data);

        // send order miniShop2
        $this->ms2->config['json_response'] = 1;
        $response = $this->ms2->order->submit($data);

        $this->disabledDeliveryPayment();


        if (!is_array($response)) {
            $response = $this->modx->fromJSON($response);
        }


        if (!$response['success']) {
            return $response;
        }


        /*
         * true получать заказа из сессий
         * false получать заказ по емаил адресу
         */
        $ifSession = $this->modx->getOption('msoneclick_get_order_session', null, true);
        if (!$ifSession) {
            $email = $this->getProperty('email');
            if ($Profile = $this->modx->getObject('modUserProfile', array('email' => $email))) {
                $internalKey = $Profile->get('internalKey');
                $q = $this->modx->newQuery('msOrder');
                $q->sortby('id', 'DESC');
                $q->limit(1);
                $q->where(array(
                    'user_id' => $internalKey,
                    'payment' => $this->payment->get('id'),
                    'delivery' => $this->delivery->get('id'),
                ));
                $msOrder = $this->modx->getObject('msOrder', $q);
            }
        } else {
            // Получаем созданные заказ из сессии
            $orderId = isset($_SESSION['minishop2']['orders']) ? (int)array_pop($_SESSION['minishop2']['orders']) : null;
            if (!$orderId) {
                $message = $this->modx->lexicon('msoc_err_order_empty_orderId');
                $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": " . $message);
                return $this->failure($message);
            }
            if (!$msOrder = $this->modx->getObject('msOrder', $orderId)) {
                $message = $this->modx->lexicon('msoc_err_order_empty');
                $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": " . $message);
                return $this->failure($message);
            }
        }


        // Возвращает товары в корзину по заказу
        if (!empty($old_cart)) {
            $this->ms2->cart->set($old_cart);
        }
        if (!empty($old_order)) {
            $this->ms2->order->set($old_order);
        }

        $data = $msOrder->toArray();


        $data['method'] = $this->method;


        $data['payment_link'] = '';
        if ($payment = $msOrder->getOne('Payment')) {
            if ($class = $payment->get('class')) {
                $this->ms2->loadCustomClasses('payment');

                if (class_exists($class)) {
                    /** @var msPaymentHandler|PayPal $handler */
                    $handler = new $class($msOrder);
                    if (method_exists($handler, 'getPaymentLink')) {
                        $link = $handler->getPaymentLink($msOrder);
                        $data['payment_link'] = $link;
                    }
                }
            }
        }

        return $this->success($this->modx->lexicon('msoc_success_order_send'), $data);
    }
}

return 'msOneClickFormMsProcessor';