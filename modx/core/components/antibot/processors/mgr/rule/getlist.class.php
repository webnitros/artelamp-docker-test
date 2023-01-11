<?php

class antiBotRuleGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'antiBotRule';
    public $classKey = 'antiBotRule';
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
        /* @var antiBotRule $object */
        $array = $object->toArray();
        $array['actions'] = [];


        if ($array['active']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('antibot_rule_collection'),
                'action' => 'colletionRule',
                'button' => true,
                'menu' => true,
            ];
            $array['actions'][] = '-';
        }


        if (!$array['active']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('antibot_rule_enable'),
                'multiple' => $this->modx->lexicon('antibot_rule_enable'),
                'action' => 'enableRule',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('antibot_rule_disable'),
                'multiple' => $this->modx->lexicon('antibot_rule_disable'),
                'action' => 'disableRule',
                'button' => true,
                'menu' => true,
            ];
        }


        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('antibot_rule_update'),
            'action' => 'updateRule',
            'button' => true,
            'menu' => true,
        ];

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('antibot_rule_remove'),
            'multiple' => $this->modx->lexicon('antibot_rule_remove'),
            'action' => 'removeRule',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'antiBotRuleGetListProcessor';
