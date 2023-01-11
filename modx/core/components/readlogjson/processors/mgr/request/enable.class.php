<?php
include_once dirname(__FILE__) . '/update.class.php';
class ReadLogJsonRequestEnableProcessor extends ReadLogJsonRequestUpdateProcessor
{
    public function beforeSet()
    {
        $this->setProperty('active', true);
        return true;
    }
}
return 'ReadLogJsonRequestEnableProcessor';
