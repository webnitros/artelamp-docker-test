<?php
if (!class_exists('msManagerController')) {
    require_once dirname(dirname(__FILE__)) . '/index.class.php';
}

/**
 * The home manager controller for mspre.
 *
 */
class mspreResourceManagerController extends mspreMainController
{
    public $nameController = 'resource';

    /* @var string $functions */
    public $class_key = 'msProduct';

    /**
     * @param null $exclude
     * @return array|null
     */
    public function loadActions($exclude = null)
    {
        return parent::loadActions(array('vendor', 'source'));
    }

}