<?php
include_once dirname(__FILE__) .'/processor.php';

/**
 * Multiple a modResource
 */
class modmsResourceGroupMultipleUpdateParentProcessor extends msPreModProcessor
{
    public function process()
    {
        $data = array();
        $access = $this->setCheckbox('access') ? true : false;
        $resourcegroup = (int)$this->getProperty('resourcegroup', false);
        if (empty($resourcegroup)) {
            return $this->failure($this->modx->lexicon('mspre_error_update_empty'));
        }

        /* @var modResourceGroup $object */
        if (!$object = $this->modx->getObject('modResourceGroup', $resourcegroup)) {
            return $this->failure($this->modx->lexicon('mspre_error_update_empty'));
        }

        $data['resource_groups'] = $this->modx->toJSON(array(array_merge($object->get(array('id', 'name')), array('access' => $access, 'menu' => null))));
        return $this->multiple($data);
    }
}

return 'modmsResourceGroupMultipleUpdateParentProcessor';
