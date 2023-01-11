<?php
if (!class_exists('msPreProductUpdateProcessor')) {
    require_once dirname(__FILE__) . '/update.class.php';
}

/**
 * SetProperty a msProduct
 */
class modmsProductSetPropertyProcessor extends msPreProductUpdateProcessor
{
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
        $processor = new modmsProductSetPropertyProcessor($modx, $properties);
        return $processor;
    }

    /** {@inheritDoc} */
    public function beforeSet()
    {
        $this->SetPropertyField();
        return parent::beforeSet();
    }
}

return 'modmsProductSetPropertyProcessor';
