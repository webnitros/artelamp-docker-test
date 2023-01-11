<?php

class ReadLogJsonRequestGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'ReadLogJsonRequest';
    public $classKey = 'ReadLogJsonRequest';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = ['readlogjson:manager'];
    //public $permission = 'list';


    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = trim($this->getProperty('query'));
        if ($query) {
            $c->where([
                'name:LIKE' => "%{$query}%",
                'OR:description:LIKE' => "%{$query}%",
            ]);
        }
        $processed = $this->getProperty('processed');
        if ($processed != '') {
            $c->where("{$this->objectType}.processed={$processed}");
        }

        $method = trim($this->getProperty('method'));
        if (!empty($method)) {
            $c->where("{$this->objectType}.method={$method}");
        }
        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->cleanup();
        $array['actions'] = [];

        $lexicon_key = 'request';
        $lexicon_key_action = 'Request';


        // Edit
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('readlogjson_' . $lexicon_key . '_update'),
            'action' => 'update' . $lexicon_key_action,
            'button' => false,
            'menu' => true,
        ];



        // copy
        $array['actions'][] = array(
            'class' => '',
            'button' => false,
            'menu' => true,
            'action' => 'copyRequest',
            'icon' => 'icon icon-copy',
            'title' => $this->modx->lexicon('readlogjson_action_copy'),
        );


        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('readlogjson_' . $lexicon_key . '_remove'),
            'multiple' => $this->modx->lexicon('readlogjson_' . $lexicon_key . 's_remove'),
            'action' => 'remove' . $lexicon_key_action,
            'button' => true,
            'menu' => true,
        ];
        return $array;
    }
}

return 'ReadLogJsonRequestGetListProcessor';
