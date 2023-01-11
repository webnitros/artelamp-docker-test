<?php
class msOneClickFormController extends msoneclickDefaultController
{

    /* @var int $count */
    protected $count = 0;
    /* @var int|null $product_id */
    protected $product_id = null;
    /* @var string|null $hash */
    protected $hash = null;
    /* @var array $data */
    protected $data = array();

    /**
     * Default config
     *
     * @param array $config
     */
    public function setDefault($config = array())
    {
        $required_fields = $this->modx->getOption('msoneclick_required_fields', null, '');
        $this->config = array_merge(array(
            'delivery' => $this->modx->getOption('msoneclick_deliverys'),
            'payment' => $this->modx->getOption('msoneclick_payments'),
            'tplForm' => 'tpl.msOneClick.form',
            'default_images' => '/assets/components/minishop2/img/web/ms2_small.png',
            'tplSendSuccess' => $this->modx->getOption('tplSendSuccess', null, 'tpl.msoneclick.send'),
            'field_required_class' => 'msoc_field__required',
            'siteUrl' => $this->modx->getOption('site_url'),
            'method' => 'MS',
            'required_fields' => $required_fields,
            'json_response' => false,
            'captchaPath' => $this->ms->config['captchaPath'],
        ), $config);

        if ($res = $this->getSession()) {
            $this->config = $res;
        }

    }

    /**
     * Вернет конфиг
     * @return array|boolean
     */
    public function getSession()
    {
        $hash = isset($_REQUEST['hash']) ? $_REQUEST['hash'] : null;;
        if ($hash) {
            if (isset($_SESSION['msOneClickConfig'][$hash])) {
                $sessionConfig = $_SESSION['msOneClickConfig'][$hash];
                return array_merge($this->config, $sessionConfig);
            }
        }
        return false;
    }


    /**
     * Получения формы для оформления заказа
     *
     * @param array $arr параметры для наполенения формы
     *
     * @return array
     */
    public function get($arr = array())
    {
        /* @var modProcessorResponse $response */
        $response = $this->ms->runProcessor('web/form/get', $arr);
        $data = $response->response;

        $model = $data['object']['model'];

        if ($this->ms->config['base64_encode']) {
            $data['object']['model'] = base64_encode($model);
        }

        return $data;
    }


    /**
     * Добавление полей пользователя в заказ
     *
     * @param array $data поле
     *
     * @return array
     */
    public function add($data)
    {
        $field = $data['field'];
        $value = $data['value'];

        $out_errors = array();
        $response = $this->check_field($field, $value);
        $this->config['json_response'] = 1;
        if (!$response['success']) {
            $out_errors[] = array(
                'name' => $field,
                'message' => $response['message']
            );

            return $this->error($this->modx->lexicon('msoc_errors'),
                array('errors' => $out_errors, 'field' => $field)
            );
        }

        if (!empty($value)) {
            $_SESSION['minishop2']['order'][$field] = $value;
        }
        return $this->success('', array('errors' => array(), 'field' => $field));
    }


    /**
     * Отправка формы
     *
     * @param array $data данные для отправки заказа
     *
     * @return array
     */
    public function sendForm($data = array())
    {
        // Автоматическая подстановка префикса для телефонного номера
        if (!empty($data['phone']) and !empty($data['phone_prefix'])) {
            $phone = preg_replace("/[^0-9]/", '', $data['phone']);
            $data['phone'] = $data['phone_prefix'] . $phone;
        }

        $this->data = $data;

        // Проверка формы
        if ($response = $this->getErros()) {
            return $response;
        }

        // Создание заказа
        $response = $this->createOrder($data);
        if (!$response['success']) {
            $message = !empty($this->config['errorMessage']) ? $this->config['errorMessage'] :$response['message'];
            return $this->error($message,$response['data']);
        }

        // Уничтожение кода проверки чтобы невозможно было отправить повтороно
        if (isset($_SESSION['msOneClickRandomnr'])) {
            unset($_SESSION['msOneClickRandomnr']);
        }

        $message = !empty($this->config['positiveMessage']) ? $this->config['positiveMessage'] :$this->modx->lexicon('msoc_success_order_send');
        $response = array(
            'body' => $this->ms->pdoTools->getChunk($this->config['tplSendSuccess'], $response['object'])
        );
        $response = $this->beforeResponseSuccess($response);
        return $this->success($message, $response);
    }

    /**
     * @param array $response
     * @return array
     */
    public function beforeResponseSuccess(array $response)
    {
        if (!empty($this->config['redirectToPage'])) {

            $response['redirectToPage'] = $this->config['redirectToPage'];
        }
        return $response;
    }

    /**
     * Содание заказ или отправка на email
     *
     * @return array
     */
    private function createOrder($data)
    {
        if (empty($data) or !is_array($data)) {
            $message = $this->modx->lexicon('msoc_err_count');
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": " . $message);
            return $this->error($message);
        }

        $method = isset($data['method']) ? $data['method'] : 'MS';
        switch ($method) {
            case 'MAIL':
            case 'MS':
            case 'CALLBACK':
                /* @var modProcessorResponse $response */
                $response = $this->ms->runProcessor('web/order/' . strtolower($method), $data);
                return $response->response;
                break;
            default:
                $message = $this->modx->lexicon('msoc_err_not_method', array('method' => $method));
                $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": " . $message);
                return $this->error($message);
                break;
        }
    }

    /** @var boolean|null|array $required_fields */
    public $required_fields = null;

    /**
     * @return array|bool
     */
    protected function getRequiredFields()
    {
        if (is_null($this->required_fields)) {
            $this->required_field = false;
            if (!empty($this->config['required_fields'])) {
                $this->required_fields = is_array($this->config['required_fields']) ? $this->config['required_fields'] : explode(',', $this->config['required_fields']);
            }

            // Add to form field norobot if the captcha is on
            if (!empty($this->config['enable_captcha'])) {
                if (!in_array('norobot', $this->required_fields)) {
                    $this->required_fields[]= 'norobot';
                }
            }
        }
        return $this->required_fields;
    }


    /**
     * Проверка обяательных полей
     *
     * @return array|boolean
     */
    private function getErros()
    {
        $errors = array();
        if ($required_fields = $this->getRequiredFields()) {

            foreach ($required_fields as $field) {
                $response = $this->check_field($field, $this->data[$field]);
                if (!$response['success']) {
                    $errors[$field] = $response['message'];
                }
            }
        }

        if (!empty($errors)) {
            $out_errors = array();
            foreach ($errors as $field => $message) {
                $out_errors[] = array(
                    'name' => $field,
                    'message' => $message
                );
            }

            $message = !empty($this->config['errorMessage']) ? $this->config['errorMessage'] :$this->modx->lexicon('msoc_errors');
            return $this->error(
                $message,
                array('errors' => $out_errors)
            );
        }

        return false;
    }


    /**
     * Проверка валидации поля
     *
     * @param string $field поле
     * @param string $value значение
     *
     * @return array
     */
    protected function check_field($field, $value)
    {
        $response = $this->check_validate($field, $value);
        if (!$response['success']) {
            return $this->error($response['message']);
        }
        return $this->success();
    }


    /**
     * Проверка валидации полей
     *
     * @param string $field поле
     * @param string $value значение
     *
     * @return array
     */
    protected function check_validate($field, $value)
    {
        $required_fields = $this->getRequiredFields();
        if (in_array($field, $required_fields)) {
            switch ($field) {
                case 'norobot':
                    if (empty($value)) {
                        return $this->error($this->modx->lexicon('msoc_err_norobot_empty'));
                    } else if (empty($_SESSION['msOneClickRandomnr'])) {
                        return $this->error($this->modx->lexicon('msoc_err_norobot_session'));
                    } else if (md5($value) != $_SESSION['msOneClickRandomnr']) {
                        return $this->error($this->modx->lexicon('msoc_err_norobot'));
                    }
                    break;
                case 'receiver':

                    $first = (int)substr($value, 0, 1);
                    if (empty($value)) {
                        return $this->error($this->modx->lexicon('msoc_err_name'));
                    } else if (strlen($value) < 2) {
                        return $this->error($this->modx->lexicon('msoc_err_name_strlen'));
                    } else if (!empty($first)) {
                        return $this->error($this->modx->lexicon('msoc_err_name_numeric'));
                    }

                    break;
                case 'email':
                    if (empty($value)) {
                        return $this->error($this->modx->lexicon('msoc_err_email'));
                    } else if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return $this->error($this->modx->lexicon('msoc_err_email_invalid'));
                    }

                    break;
                case 'phone':

                    $value = preg_replace("/[^0-9]/", '', $value);
                    if (empty($value)) {
                        return $this->error($this->modx->lexicon('msoc_err_phone'));
                    }

                    $phone_strlen = strlen($value);

                    // Если включена маска для вырезания телефонного кода страны
                    if ($prefixs = $this->ms->getPrefixPhone()) {
                        $phone_lens = array_column($prefixs, 'phone_len');
                        if (!in_array($phone_strlen, $phone_lens)) {
                            // Не нашли не одного совпадения с номером телефона
                            return $this->error($this->modx->lexicon('msoc_err_phone_valide'));
                        }
                    } else if ($phone_strlen != 11) {
                        return $this->error($this->modx->lexicon('msoc_err_phone_valide'));
                    }


                    break;
                default:
                    if (empty($value)) {
                        return $this->error($this->modx->lexicon('msoc_err_all_field'));
                    }
                    break;
            }
        }
        return $this->success();
    }

}

return 'msOneClickFormController';