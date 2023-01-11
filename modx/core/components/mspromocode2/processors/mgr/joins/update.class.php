<?php

class mspc2JoinUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'mspc2Join';
    public $classKey = 'mspc2Join';
    public $languageTopics = array('mspromocode2:default');
    public $permission = 'save';
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

        // Prepare data for updating from grid
        $data = $this->modx->fromJSON($this->getProperty('data'));
        if (is_array($data)) {
            foreach ([
                'pagetitle',
                'actions',
                'menu',
            ] as $v) {
                unset($data[$v]);
            }
        }
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $this->setProperties($data);
        $this->unsetProperty('data');

        return parent::initialize();
    }

    /**
     * @return bool|string
     */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function beforeSet()
    {
        if (!$id = (int)$this->getProperty('id')) {
            return $this->modx->lexicon('mspc2_err_ns');
        }
        if (($tmp = $this->prepareProperties()) !== true) {
            return $tmp;
        }
        unset($tmp);

        // Проверяем на заполненность
        $required = array(
            'resource',
        );
        $this->mspc2->tools->checkProcessorRequired($this, $required, 'mspc2_err_required');

        return parent::beforeSet();
    }

    /**
     * @return string|bool
     */
    public function prepareProperties()
    {
        $properties = $this->getProperties();
        // return print_r($properties, 1);

        // // Count
        // $properties['count'] = is_numeric($properties['count']) ? $properties['count'] : '';
        //
        // // Discount
        // $properties['discount'] = empty($properties['discount']) ? 0 : $properties['discount'];
        //
        // // Создано
        // unset($properties['createdon']);
        // $this->unsetProperty('createdon');
        // $properties['updatedon'] = time();

        $this->setProperties($properties);

        // return print_r($properties, 1);

        return true;
    }
}

return 'mspc2JoinUpdateProcessor';