<?php

class antiBotHitsGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'antiBotHits';
    public $classKey = 'antiBotHits';
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

        $query_ip = trim($this->getProperty('query_ip'));
        if (!empty($query_ip)) {
            $c->where([
                'ip:LIKE' => "%{$query_ip}%",
            ]);
        }

        $query_url = trim($this->getProperty('query_url'));
        if (!empty($query_url)) {
            $c->where([
                'url:LIKE' => "%{$query_url}%",
            ]);
        }


        $query_url_from = trim($this->getProperty('query_url_from'));
        if (!empty($query_url_from)) {
            $c->where([
                'url_from:LIKE' => "%{$query_url_from}%",
            ]);
        }


        $query_user_agent = trim($this->getProperty('query_user_agent'));
        if (!empty($query_user_agent)) {
            $c->where([
                'user_agent:LIKE' => "%{$query_user_agent}%",
            ]);
        }



        $query_code = trim($this->getProperty('code_response'));
        if ($query_code) {
            $c->where([
                'antiBotHits.code_response' => $query_code,
            ]);
        }

        $query_method = trim($this->getProperty('method'));
        if ($query_method) {
            $c->where([
                'antiBotHits.method' => $query_method,
            ]);
        }


        $guest = (int)$this->getProperty('guest');
        if ($guest != 0) {
            $c->where([
                'antiBotHits.guest_id' => $guest,
            ]);
        }


        $c->leftJoin('modUser', 'User', "User.id = {$this->classKey}.user_id");
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
        $c->select('User.username as username');


        if ($date_start = $this->getProperty('date_start')) {
            $c->andCondition(array(
                'createdon:>=' => date('Y-m-d 00:00:00', strtotime($date_start)),
            ), null, 1);
        }
        if ($date_end = $this->getProperty('date_end')) {
            $c->andCondition(array(
                'createdon:<=' => date('Y-m-d 23:59:59', strtotime($date_end)),
            ), null, 1);
        }



        $c->leftJoin('antiBotStopList', 'List', "List.ip = {$this->classKey}.ip");
        $c->select("COUNT(List.id) as blocked");
        $c->groupby("{$this->classKey}.id");
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
        $array['username'] = empty($array['username']) ? $this->modx->lexicon('antibot_stoplist_username_guest') : $array['username'];
        $array['blocked'] = !empty($array['blocked']) ? true : false;



        if (!$object->get('blocked')) {
            // Add blocked
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-close action-red',
                'title' => $this->modx->lexicon('antibot_action_blocked'),
                'multiple' => $this->modx->lexicon('antibot_action_blockeds'),
                'action' => 'blockedHitIp',
                'button' => true,
                'menu' => true,
            ];
            // Add blocked
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-close action-red',
                'title' => $this->modx->lexicon('antibot_action_blocked_user_agent'),
                'multiple' => $this->modx->lexicon('antibot_action_blockeds_user_agent'),
                'action' => 'blockedHitUserAgent',
                'button' => true,
                'menu' => true,
            ];
        }


        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('antibot_hit_remove'),
            'multiple' => $this->modx->lexicon('antibot_hits_remove'),
            'action' => 'removeHit',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'antiBotHitsGetListProcessor';
