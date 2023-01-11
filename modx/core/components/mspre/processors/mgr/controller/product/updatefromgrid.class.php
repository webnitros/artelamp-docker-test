<?php
include_once dirname(dirname(dirname(__FILE__))) . '/common/trait.php';
if (!class_exists('msPreProductUpdateProcessor')) {
    require_once(dirname(__FILE__) . '/update.class.php');
}

/**
 * Update FromGrid a msProduct
 */
class modmsProductFromGridProcessor extends msPreProductUpdateProcessor
{
    use msPreTrait;

    public static function getInstance(modX &$modx, $className, $properties = array())
    {
        /** @var modProcessor $processor */
        $processor = new modmsProductFromGridProcessor($modx, $properties);

        return $processor;
    }

    /** {@inheritDoc} */
    public function initialize()
    {
        $response = $this->updateFromGrid();
        if ($response !== true) {
            return $response;
        }
        return parent::initialize();
    }
}

return 'modmsProductFromGridProcessor';