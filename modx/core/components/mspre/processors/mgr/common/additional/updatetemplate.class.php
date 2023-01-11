<?php
include_once dirname(__FILE__) .'/processor.php';

/**
 * Multiple a modResource
 */
class modmodResourceUpdateTemplateProcessor extends msPreModProcessor
{
    public function process()
    {
        $data = array();
        if ($template = $this->getProperty('template', false)) {
            $data['template'] = $template;
        } else {
            return $this->failure($this->modx->lexicon('mspre_error_update_empty'));
        }

        return $this->multiple($data);
    }
}

return 'modmodResourceUpdateTemplateProcessor';
