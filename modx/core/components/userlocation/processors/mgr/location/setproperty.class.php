<?php

require_once(dirname(__FILE__) . '/update.class.php');

/**
 * SetProperty a ulLocation
 */
class ulLocationSetPropertyProcessor extends ulLocationUpdateProcessor
{
    public $classKey = 'ulLocation';

    /** {@inheritDoc} */
    public static function getInstance(modX &$modx, $className, $properties = array())
    {
        /** @var modProcessor $processor */
        $processor = new ulLocationSetPropertyProcessor($modx, $properties);

        return $processor;
    }

    /** {@inheritDoc} */
    public function initialize()
    {
        $fieldName = $this->getProperty('field_name', null);
        $fieldValue = $this->getProperty('field_value', null);

        if ($fieldName != null AND $fieldValue != null) {
            $this->setProperty($fieldName, $fieldValue);
        }

        return parent::initialize();
    }

}

return 'ulLocationSetPropertyProcessor';