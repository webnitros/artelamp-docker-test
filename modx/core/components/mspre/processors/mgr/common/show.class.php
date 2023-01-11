<?php

class modResoruceShowInTreeProcessor extends modObjectUpdateProcessor
{
    public $classKey = 'modResource';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = array(
            'show_in_tree' => true,
        );
        return true;
    }
}

return 'modResoruceShowInTreeProcessor';