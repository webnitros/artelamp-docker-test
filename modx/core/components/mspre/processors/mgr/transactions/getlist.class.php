<?php

class mspreTransactionsGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'mspreTransactions';
    public $classKey = 'mspreTransactions';
    public $defaultSortField = 'updatedon';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = array('mspre:default');
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
                'product_id:LIKE' => $query,
                'or:field:LIKE' => $query,
            ]);
        }


        $date_start = trim($this->getProperty('date_start'));
        if (!empty($date_start)) {

            $date_start = date('Y-m-d H:i:s', strtotime($date_start));
            $c->where([
                'updatedon:>' => $date_start,
            ]);
        }

        $date_end = trim($this->getProperty('date_end'));
        if (!empty($date_end)) {
            $date_end = date('Y-m-d H:i:s', strtotime($date_end));
            $c->where([
                'updatedon:<' => $date_end,
            ]);
        }

        if (isset($this->properties['round'])) {
            $round = trim($this->getProperty('round'));
            $c->where([
                'round' => $round,
            ]);
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
        $array = $object->toArray();
        $array['actions'] = [];


        $array['round'] = $this->modx->lexicon('mspre_round_'.$array['round']);
        $array['increase'] = $this->modx->lexicon('mspre_increase_'.$array['increase']);



        // Remove
        /*$array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('exportusers_profile_actions_remove'),
            'multiple' => $this->modx->lexicon('exportusers_profiles_actions_remove'),
            'action' => 'removeProfile',
            'button' => true,
            'menu' => true,
        ];*/

        return $array;
    }

}

return 'mspreTransactionsGetListProcessor';