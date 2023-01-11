<?php

class mspc2CouponRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'mspc2Coupon';
    public $classKey = 'mspc2Coupon';
    public $languageTopics = array('mspromocode2:default');
    public $permission = 'remove';

    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        if ($ids = $this->getProperty('id')) {
            $ids = array($ids);
        } else {
            $ids = $this->modx->fromJSON($this->getProperty('ids'));
            if (empty($ids)) {
                return $this->failure($this->modx->lexicon('mspc2_err_ns'));
            }
        }

        foreach ($ids as $id) {
            /** @var mspc2Coupon $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('mspc2_err_nf'));
            }
            $object->remove();
        }

        return $this->success();
    }
}

return 'mspc2CouponRemoveProcessor';