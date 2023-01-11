<?php


abstract class mspPayAnyWayPlugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var msppayanyway $msppayanyway */
    protected $msppayanyway;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    public function __construct($modx, &$scriptProperties)
    {
        /** @var modX $modx */
        $this->modx = $modx;
        $this->scriptProperties =& $scriptProperties;

        $fqn = $modx->getOption('msppayanyway_class', null, 'msppayanyway.msppayanyway', true);
        $path = $modx->getOption('msppayanyway_class_path', null, MODX_CORE_PATH . 'components/msppayanyway/model/', true);
        $this->msppayanyway = $modx->getService(
            $fqn,
            '',
            $path,
            $this->scriptProperties
        );
        if (!$this->msppayanyway) {
            return false;
        }

        $this->msppayanyway->initialize($this->modx->context->key);
    }

    abstract public function run();
}