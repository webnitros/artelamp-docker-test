<?php
include_once 'default.php';
/**
 * Multiple a modResource
 */
class modmodResourceMultipleUpdateAdditionalProcessor extends modmodResourceMultipleUpdateAdditionalDefaultProcessor
{
    /** {@inheritDoc} */
    public static function getInstance(modX &$modx, $className, $properties = array())
    {
        $processor = new modmodResourceMultipleUpdateAdditionalProcessor($modx, $properties);
        return $processor;
    }
}
return 'modmodResourceMultipleUpdateAdditionalProcessor';
