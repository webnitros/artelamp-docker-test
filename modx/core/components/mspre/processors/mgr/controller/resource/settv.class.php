<?php
if (!class_exists('msPreResourceUpdateMspreProcessor')) {
    include_once dirname(dirname(dirname(__FILE__))) . '/common/trait.php';
    require_once dirname(__FILE__) . '/update.class.php';
}

/**
 * SetProperty a modResource
 */
class modmodResourceSetTvProcessor extends msPreResourceUpdateMspreProcessor
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
        $processor = new modmodResourceSetTvProcessor($modx, $properties);
        return $processor;
    }

    /** {@inheritDoc} */
    public function beforeSet()
    {
        $response = $this->updateTv();
        if ($response !== true) {
            return !$this->hasErrors();
        }
        return parent::beforeSet();
    }
}

return 'modmodResourceSetTvProcessor';
