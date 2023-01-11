<?php
include_once dirname(__FILE__) .'/processor.php';

/**
 * Multiple a modResource
 */
class modmodResourceMultipleUpdateParentProcessor extends msPreModProcessor
{
    public function process()
    {
        $data = array();
        if ($parent = $this->getProperty('parent', false)) {
            $data['parent'] = $parent;
        } else {
            return $this->failure($this->modx->lexicon('mspre_error_update_empty'));
        }
        return $this->multiple($data);
    }
}

return 'modmodResourceMultipleUpdateParentProcessor';
