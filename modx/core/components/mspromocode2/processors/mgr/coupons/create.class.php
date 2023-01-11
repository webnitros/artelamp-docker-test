<?php

class mspc2CouponCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'mspc2Coupon';
    public $classKey = 'mspc2Coupon';
    public $languageTopics = array('mspromocode2:default');
    // public $permission = 'create';
    /**
     * @var msPromoCode2 $mspc2
     */
    protected $mspc2;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->mspc2 = $this->modx->getService('mspromocode2', 'msPromoCode2',
            $this->modx->getOption('mspc2_core_path', null, MODX_CORE_PATH . 'components/mspromocode2/') . 'model/mspromocode2/');
        $this->mspc2->initialize($this->modx->context->key);

        return parent::initialize();
    }

    /**
     * @return bool|string
     */
    public function beforeSave()
    {
        // if (!$this->checkPermissions()) {
        //     return $this->modx->lexicon('access_denied');
        // }

        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function beforeSet()
    {
        if (($tmp = $this->prepareProperties()) !== true) {
            return $tmp;
        }
        unset($tmp);

        // Проверяем на заполненность
        $required = array(
            // 'discount',
            'code:mspc2_err_code_required',
        );
        $this->mspc2->tools->checkProcessorRequired($this, $required, 'mspc2_err_required');

        // Проверяем на уникальность
        $unique = array(
            'code:mspc2_err_code_unique',
        );
        $this->mspc2->tools->checkProcessorUnique('', 0, $this, $unique, 'mspc2_err_unique');

        // Check on characters
        $code = (string)$this->getProperty('code', '');
        if (preg_match('/[^0-9a-zа-яйё \-+=_#@*!?()\[\]]/ui', $code)) {
            $this->addFieldError('code', $this->modx->lexicon('mspc2_err_code_characters'));
        }

        return parent::beforeSet();
    }

    /**
     * @return bool
     */
    public function fireBeforeSaveEvent()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        /** @var mspc2Join $coupon_id */
        $coupon_id = $this->object->get('id');
        $joins = $this->modx->getIterator('mspc2Join', ['coupon' => 0]);
        foreach ($joins as $join) {
            $join->set('coupon', $coupon_id);
            $join->save();
        }

        return parent::afterSave();
    }

    /**
     * @return string|bool
     */
    public function prepareProperties()
    {
        $properties = $this->getProperties();
        // return print_r($properties, 1);

        // // Вычисляем позицию
        // // $properties['idx'] = $this->modx->getCount($this->classKey, array(
        // //     'object_id' => (int)$this->getProperty('object_id')
        // // )); // Для группировки по родителю
        // $properties['idx'] = $this->modx->getCount($this->classKey, array('id:!=' => 0));
        // ++$properties['idx'];

        // Count
        $properties['count'] = is_numeric($properties['count']) ? $properties['count'] : '';

        // Discount
        $properties['discount'] = empty($properties['discount']) ? 0 : $properties['discount'];

        // Message for unset if null
        if ($properties['unsetifnull_msg'] === $this->modx->lexicon('mspc2_field_unsetifnull_msg_desc')) {
            $properties['unsetifnull_msg'] = '';
        }

        // Создано
        $properties['createdon'] = empty($properties['createdon']) ? time() : $properties['createdon'];
        $properties['updatedon'] = time();

        // Lifetime
        if (!empty($properties['lifetime']) && is_numeric($properties['lifetime'])) {
            $properties['startedon'] = time();
            $properties['stoppedon'] = time() + $properties['lifetime'];
            unset($properties['lifetime']);
        }

        $this->setProperties($properties);

        // return print_r($properties, 1);

        return true;
    }
}

return 'mspc2CouponCreateProcessor';