<?php

class antiBotStopListGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'antiBotStopList';
    public $classKey = 'antiBotStopList';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $languageTopics = ['antibot:manager'];
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
                'user_agent:LIKE' => "%{$query}%",
                'OR:redirect_url:LIKE' => "%{$query}%",
                'OR:context:LIKE' => "%{$query}%",
                'OR:ip:LIKE' => "%{$query}%",
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
        /* @var antiBotStopList $object */
        $array = $object->toArray();
        $array['actions'] = [];


        $array['context'] = empty($array['context']) ? $this->modx->lexicon('antibot_stoplist_context_all') : $array['context'];
        $array['ip'] = $object->getIp();


        if (!$array['active']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('antibot_stoplist_enable'),
                'multiple' => $this->modx->lexicon('antibot_stoplists_enable'),
                'action' => 'enableStopList',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('antibot_stoplist_disable'),
                'multiple' => $this->modx->lexicon('antibot_stoplists_disable'),
                'action' => 'disableStopList',
                'button' => true,
                'menu' => true,
            ];
        }

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('antibot_stoplist_remove'),
            'multiple' => $this->modx->lexicon('antibot_stoplists_remove'),
            'action' => 'removeStopList',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'antiBotStopListGetListProcessor';
