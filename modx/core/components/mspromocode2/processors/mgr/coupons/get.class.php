<?php

class mspc2CouponGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'mspc2Coupon';
    public $classKey = 'mspc2Coupon';
    public $languageTopics = array('mspromocode2:default');
    public $permission = 'view';

    /**
     * @return mixed
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::process();
    }
}

return 'mspc2CouponGetProcessor';