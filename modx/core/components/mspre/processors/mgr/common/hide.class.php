<?php

class modResoruceHideInTreeProcessor extends modObjectUpdateProcessor
{
    public $classKey = 'modResource';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = array(
            'show_in_tree' => false,
        );
        return true;
    }
}

return 'modResoruceHideInTreeProcessor';