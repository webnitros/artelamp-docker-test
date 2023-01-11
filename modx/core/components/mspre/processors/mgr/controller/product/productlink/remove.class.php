<?php

class modmsProductProductLinkRemoveProcessor extends modObjectGetProcessor
{
    public $classKey = 'msLink';

    public function process()
    {
        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->modx->lexicon('mspre_err_ids');
        }
        $type = $this->object->get('type');
        $q = $this->modx->newQuery('msProductLink');
        $q->command('DELETE');
        $q->where(array('link' => $this->object->get('id')));
        switch ($type) {
            case 'many_to_many':
            case 'one_to_one':
                $q->where(array('master:IN' => $ids, 'OR:slave:IN' => $ids));
                break;

            case 'many_to_one':
            case 'one_to_many':
                $q->where(array('master:IN' => $ids, 'OR:slave:IN' => $ids));
                break;
        }
        $q->prepare();
        $q->stmt->execute();
        return $this->success();
    }

}

return 'modmsProductProductLinkRemoveProcessor';
