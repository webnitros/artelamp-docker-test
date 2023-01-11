<?php
include_once dirname(__FILE__) .'/processor.php';

/**
 * Multiple a modResource
 */
class modmodResourceMultipleUpdateTemplateProcessor extends msPreModProcessor
{
    public function process()
    {
        $data = array();
        if ($createdon = $this->getProperty('createdon', false)) {
            $data['createdon'] = $createdon;
        }
        if ($editedon = $this->getProperty('editedon', false)) {
            $data['editedon'] = $editedon;
        }
        if ($pub_date = $this->getProperty('pub_date', false)) {
            $data['pub_date'] = $pub_date;
        }
        if ($publishedon = $this->getProperty('publishedon', false)) {
            $data['publishedon'] = $pub_date;
        }
        if ($unpub_date = $this->getProperty('unpub_date', false)) {
            $data['unpub_date'] = $unpub_date;
        }


        return $this->multiple($data);
    }
}

return 'modmodResourceMultipleUpdateTemplateProcessor';
