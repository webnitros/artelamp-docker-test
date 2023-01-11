<?php
include_once dirname(__FILE__) .'/processor.php';

/**
 * Multiple a modResource
 */
class modmodResourceMultipleUpdateUserProcessor extends msPreModProcessor
{
    public function process()
    {
        $data = array();
        if ($createdby = $this->getProperty('createdby', false)) {
            $data['createdby'] = $createdby;
        }
        if ($editedby = $this->getProperty('editedby', false)) {
            $data['editedby'] = $editedby;
        }
        if ($publishedby = $this->getProperty('publishedby', false)) {
            $data['publishedby'] = $publishedby;
        }
        return $this->multiple($data);
    }
}

return 'modmodResourceMultipleUpdateUserProcessor';
