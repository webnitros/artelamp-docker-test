<?php
include_once dirname(__FILE__) . '/update.class.php';
class ReadLogJsonRequestDisableProcessor extends ReadLogJsonRequestUpdateProcessor
{
    public function beforeSet()
    {
        $this->setProperty('active', false);
        return true;
    }
}
return 'ReadLogJsonRequestDisableProcessor';
