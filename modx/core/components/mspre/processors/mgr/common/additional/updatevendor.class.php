<?php
include_once dirname(__FILE__) .'/processor.php';

/**
 * Multiple a modResource
 */
class modmodResourceMultipleUpdateSourceProcessor extends msPreModProcessor
{
    public function process()
    {
        if (!isset($this->properties['vendor'])) {
            return $this->failure($this->modx->lexicon('mspre_error_update_empty'));
        }
        $value = (int)$this->getProperty('vendor', 0);
        $data = array(
            'vendor' => empty($value) ? 0 : $value
        );
        return $this->multiple($data);
    }
}

return 'modmodResourceMultipleUpdateSourceProcessor';
