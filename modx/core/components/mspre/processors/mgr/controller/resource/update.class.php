<?php
include_once dirname(dirname(dirname(__FILE__))) . '/common/trait.php';
require_once MODX_CORE_PATH . 'model/modx/modprocessor.class.php';
require_once MODX_CORE_PATH . 'model/modx/processors/resource/update.class.php';

/**
 * Update an modResource
 */
class msPreResourceUpdateMspreProcessor extends modResourceUpdateProcessor
{
    use msPreTrait;
    /* @var modResource $object */
    public $object;
    public $languageTopics = array('mspre');
    public $classKey = 'modResource';

    /** {@inheritDoc} */
    public static function getInstance(modX &$modx, $className, $properties = array())
    {
        /** @var modProcessor $processor */
        $processor = new msPreResourceUpdateMspreProcessor($modx, $properties);
        return $processor;
    }

    /**
     * @return bool|null|string
     */
    public function beforeSet()
    {
        if ($this->object) {
            $old_alias = $this->object->get('alias');
            $alias = $this->getProperty('alias');
            if (!empty($alias)) {
                if ($alias != $old_alias) {
                    $old_alias = $alias;
                }
            }
            $this->setProperty('class_key', $this->object->get('class_key'));
            $this->setProperty('context_key', $this->object->get('context_key'));
            $this->setProperty('alias', $old_alias);
        }
        $this->updategrig();
        return parent::beforeSet();
    }


}

return 'msPreResourceUpdateMspreProcessor';
