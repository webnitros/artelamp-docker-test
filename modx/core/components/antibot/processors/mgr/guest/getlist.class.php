<?php

class antiBotGuestGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'antiBotGuest';
    public $classKey = 'antiBotGuest';
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

            $id = (int)$query;
            $count = $this->modx->getCount($this->classKey, $id);
            if ($count > 0) {
                $c->where([
                    'id' => $id,
                ]);
            } else {
                $c->where([
                    'OR:user_agent:LIKE' => "%{$query}%",
                    'OR:ip:LIKE' => "%{$query}%",
                    #'OR:user_id' => "%{$query}%",
                ]);
            }
        }

        /*  $query_ip = trim($this->getProperty('query_ip'));
          if ($query_ip) {
              $c->where([
                  'ip:LIKE' => "%{$query_ip}%",
              ]);
          }*/

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

        /*$bots = array(
            'yandex',
            'mail',
            'google',
        );
        // Fake

        foreach ($bots as $bot) {
            $name = ucfirst($bot);
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-dns action-green',
                'title' => $this->modx->lexicon('antibot_guest_fake_' . $bot),
                //'multiple' => $this->modx->lexicon('antibot_guests_fake'),
                'action' => 'fake' . $name,
                'button' => true,
                'menu' => true,
            ];
        }*/


        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-dns action-green',
            'title' => $this->modx->lexicon('antibot_action_check_bot'),
            //'multiple' => $this->modx->lexicon('antibot_guests_fake'),
            'action' => 'checkBot',
            'button' => true,
            'menu' => true,
        ];

        $array['actions'][] = '-';


        if (!$array['happy']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('antibot_action_happy_enable'),
                'action' => 'happyEnableGuest',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('antibot_action_happy_disable'),
                'action' => 'happyDisableGuest',
                'button' => true,
                'menu' => true,
            ];
        }


        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('antibot_guest_remove'),
            'multiple' => $this->modx->lexicon('antibot_guests_remove'),
            'action' => 'removeGuest',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'antiBotGuestGetListProcessor';
