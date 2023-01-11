<?php
include_once dirname(__FILE__) .'/processor.php';

/**
 * Multiple a modResource
 */
class modmodResourceUpdateSourceProcessor extends msPreModProcessor
{
    public function process()
    {
        $data = array();
        if ($content_type = $this->getProperty('source', false)) {
            $data['source'] = $content_type;
        } else {
            return $this->failure($this->modx->lexicon('mspre_error_update_empty'));
        }
        return $this->multiple($data);
    }
}

return 'modmodResourceUpdateSourceProcessor';
