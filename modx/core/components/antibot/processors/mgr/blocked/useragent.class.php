<?php
class antiBotBlockedUserAgentProcessor extends modObjectProcessor
{
    public $objectType = 'antiBotHits';
    public $classKey = 'antiBotHits';
    public $languageTopics = ['antibot:manager'];

    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }


        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('antibot_guest_err_ns'));
        }

        /* @var antiBot $antiBot */
        $antiBot = $this->modx->getService('antibot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/');


        $msg = array();
        foreach ($ids as $id) {
            /** @var antiBotHits $hit */
            if (!$hit = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('antibot_guest_err_nf'));
            }

            $user_agent = $hit->get('user_agent');
            $addMsg = $user_agent;

            $criteria = array(
                'user_agent' => $hit->get('user_agent'),
                'context' => 'web',
            );

            if (!$count = (boolean)$this->modx->getCount('antiBotStopList', $criteria)) {
                $array = array_merge($criteria, array(
                    'comment' => $this->modx->lexicon('antibot_comment_blocked', array('name' => 'guest')),
                    'message' => $this->modx->lexicon('antibot_message_blocked'),
                    'active' => 1,
                ));
                $response = $antiBot->runProcessor('mgr/stoplist/create', $array);
                if ($response->isError()) {
                    return $this->failure($response->getMessage());
                }

                $this->modx->error->reset();
            } else {
                $addMsg .= $this->modx->lexicon('antibot_blocked_is_have');
            }

            if (!in_array($user_agent, $msg)) {
                $msg[] = $addMsg;
            }
        }

        $ip = count($msg) > 0 ? implode('<br>', $msg) : '';
        $message = $this->modx->lexicon('antibot_blocked_success_message', array('list' => $ip));
        return $this->success($message);
    }
}
return 'antiBotBlockedUserAgentProcessor';