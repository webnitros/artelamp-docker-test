<?php

class mspc2JoinCreateProcessor extends modObjectProcessor
{
    public $objectType = 'mspc2Join';
    public $classKey = 'mspc2Join';
    public $languageTopics = array('mspromocode2:default');
    public $permission = 'create';
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
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        //
        $type = (string)$this->getProperty('type', '');
        if (!in_array($type, ['category', 'product'], true)) {
            return $this->failure($this->modx->lexicon('mspc2_err_unexpected'));
        }

        //
        $coupon = (int)$this->getProperty('coupon', 0);
        $resource = (int)$this->getProperty('resource', 0);

        //
        $object = $this->modx->newObject($this->classKey);
        $object->fromArray([
            'type' => $type,
            'coupon' => $coupon,
            'resource' => $resource,
            // 'discount' => '',
        ]);
        $object->save();

        return $this->success();
    }
}

return 'mspc2JoinCreateProcessor';