<?php

include_once dirname(dirname(__FILE__)) . '/default.php';

class msOneClickFormProcessor extends msOneClickFormDefaultProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {

        $this->getUserData();

        $method = $this->getProperty('method');
        switch ($method) {
            case 'CALLBACK':
                break;
            default:
                $this->getProductData();
                break;
        }


        $this->getOrderData();
        $this->getRequiredFields();
        $this->setOptionsPrefix();
        $this->getValueProductOptions();

        $formid = $this->getHashForm();
        $data = $this->getProperties();


        $tplForm = $this->modx->getOption('tplForm', $data, 'tpl.msoneclick.form');
        $tplModal = $this->modx->getOption('tplModal', $data, 'tpl.msoneclick.modal');

        $dataForm = $this->getProperties();
        $dataForm['captcha_path'] = $this->ms->config['captchaPath'];
        return $this->success($this->modx->lexicon('msoc_form_success'), array(
            'model' => $this->ms->pdoTools->getChunk($tplModal, array(
                'selector' => $this->getProperty('selector', 'oneClick'),
                'form' => $this->ms->pdoTools->getChunk($tplForm, $dataForm),
            ), 1),
            'formid' => $formid,
        ));
    }


    /**
     * Добавляем плейсхолдеры с обязательными полями
     */
    protected function setOptionsPrefix()
    {
        $options = null;
        if ($prefixs = $this->ms->getPrefixPhone()) {
            $phone_prefix = '';
            $order = $this->getProperty('order');
            if (isset($order['phone_prefix'])) {
                $phone_prefix = $order['phone_prefix'];
            }
            foreach ($prefixs as $prefix) {
                $pr = $prefix['prefix'];
                $selected = $phone_prefix == $pr ? 'selected' : '';
                $options [] = '<option ' . $selected . ' value="' . $pr . '">+' . $pr . '</option>';
            }
        }

        $this->setProperty('prefix_options', !empty($options) ? implode('', $options) : '');
    }

    /**
     * Добавляем плейсхолдеры с обязательными полями
     */
    protected function getRequiredFields()
    {
        $required_fields = $this->modx->getOption('msoneclick_required_fields', null, null);
        if ($required_fields) {
            $cls = $this->modx->getOption('field_required_class', $this->getProperties(), 'msoc_field__required');
            $fields = explode(',', $required_fields);
            foreach ($fields as $field) {
                $this->setProperty($field . '_required', $cls);
            }
        }
        return true;
    }


    /**
     * Хешь индификатора формы
     * @return string
     */
    protected function getHashForm()
    {
        $formid = 'msoneclickForm-' . $this->getProperty('hash');
        $this->setProperty('formid', $formid);;
        return $formid;
    }


    /**
     * Добавляем пользовательские поля к заказу
     */
    protected function getUserData()
    {
        if ($this->modx->user->isAuthenticated($this->getProperty('ctx'))) {

            /* @var modUserProfile $Profile */
            if ($Profile = $this->modx->user->getOne('Profile')) {

                $Profile->addFieldAlias('fullname', 'receiver');
                $Profile->addFieldAlias('country', 'addr_country');

                $this->setOrder($Profile->get(array(
                    'fullname',
                    'phone',
                    'mobilephone',
                    'address',
                    'country',
                    'city',
                    'fax',
                    'zip',
                    'website',
                    'state',
                    'dob',
                    'gender',
                )));

                $this->setOrder(array('username' => $this->modx->user->username));

            }
        }
    }

    /**
     * Вернет отформатированный телефон если цифры совпадут
     * @param $phone
     * @return array|bool
     */
    protected function handlerPhone($phone)
    {

        $phone = trim($phone);
        if (!empty($phone) and $prefixs = $this->ms->getPrefixPhone()) {
            $phone = preg_replace("/[^0-9]/", '', $phone);
            foreach ($prefixs as $row) {
                $prefix = $row['prefix'];
                $prefix_len = $row['prefix_len'];
                $phone_len = $row['phone_len'];

                // Длина телефона должна совпадать с  маской
                // Если совпадает то получаем первую цифру
                if ($phone_len == strlen($phone)) {

                    // Если первая цифра совпадает то длину префикса из телефона
                    if (substr($phone, 0, $prefix_len) == $prefix) {
                        $data = array(
                            'phone' => substr($phone, $prefix_len),
                            'phone_prefix' => $prefix
                        );
                        return $data;
                    }
                }
            }

        }
        return false;
    }


    /**
     * Запишит данные из начатого заказа
     */
    protected function getOrderData()
    {
        $order = $this->ms2->order->get();
        $order['phone_prefix'] = (int)isset($order['phone_prefix']) ? $order['phone_prefix'] : '';
        if ($data = $this->handlerPhone($order['phone'])) {
            $phone = $data['phone'];
            $phone_prefix = $data['phone_prefix'];
            $order['phone'] = $phone;
            $order['phone_prefix'] = $phone_prefix;
        }
        $order['phone_prefix'] = 7;
        if (is_array($order)) {
            $this->setOrder($order);
        }
    }

    /**
     * @return bool
     */
    protected function getProductData()
    {
        $price = $this->product->getPrice();
        $data = $this->product->toArray();

        $old_price = $data['old_price'];
        /** @var miniShop2 $miniShop2 */
        if ($miniShop2 = $this->modx->getService('miniShop2')) {
            $price = $miniShop2->formatPrice($price);
            $old_price = $miniShop2->formatPrice($old_price);
        }

        $data['old_price'] = $old_price;
        $data['price'] = $price;
        $data['count'] = 1;
        $data['thumb'] = empty($data['thumb']) ? $this->modx->getOption('default_images', $this->getProperties(), '/assets/components/minishop2/img/web/ms2_small.png') : $data['thumb'];


        $this->setProperty('product', $data);
        return false;
    }

    protected function getValueProductOptions($data = array())
    {

        $options = array();
        if (isset($_REQUEST['options']) and is_array($_REQUEST['options'])) {
            foreach ($_REQUEST['options'] as $item) {
                $name = $item['name'];
                $value = $item['value'];
                switch ($item['name']) {
                    case 'id':
                        break;
                    case 'count':
                        $options[$name] = $value;
                        break;
                    default:

                        $optionsq = explode('[', $name);
                        if (count($optionsq) == 2) {
                            preg_match_all('/\[.*?]/', $name, $arr);
                            $name_options = $arr[0][0];
                            $name_options = substr($name_options, 1);
                            $name_options = substr($name_options, 0, -1);
                        } else {
                            $name_options = $name;
                        }

                        $this->setProperty('options_' . $name_options, $value);
                        break;
                }
            }
        }

        return empty($options) ? $data : $options;
    }
}

return 'msOneClickFormProcessor';