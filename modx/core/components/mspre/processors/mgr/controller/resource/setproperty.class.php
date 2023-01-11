<?php
if (!class_exists('msPreTrait')) {
    include_once dirname(dirname(dirname(__FILE__))) . '/common/trait.php';
}
if (!class_exists('msPreResourceUpdateMspreProcessor')) {
    require_once dirname(__FILE__) . '/update.class.php';
}

/**
 * SetProperty a modResource
 */
class mspreResourceSetPropertyProcessor extends msPreResourceUpdateMspreProcessor
{
    public $classKey = 'modResource';
    use msPreTrait;

    /**
     * @param modX $modx
     * @param string $className
     * @param array $properties
     *
     * @return modProcessor
     */
    public static function getInstance(modX &$modx, $className, $properties = array())
    {
        /** @var modProcessor $processor */
        $processor = new mspreResourceSetPropertyProcessor($modx, $properties);

        return $processor;
    }

    /** {@inheritDoc} */
    public function beforeSet()
    {
        $this->SetPropertyField();
        return parent::beforeSet();
    }
}

return 'mspreResourceSetPropertyProcessor';
