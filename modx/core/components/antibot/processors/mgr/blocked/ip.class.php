<?php

class antiBotBlockedIpProcessor extends modObjectProcessor
{
    public $objectType = 'antiBotHits';
    public $classKey = 'antiBotHits';
    public $languageTopics = ['antibot:manager'];
    public $nameList = 'hits';

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
            return $this->failure($this->modx->lexicon('antibot_hit_err_ns'));
        }

        /* @var antiBot $antiBot */
        $antiBot = $this->modx->getService('antibot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/');


        $msg = array();
        foreach ($ids as $id) {
            /** @var antiBotHits $hit */
            if (!$hit = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('antibot_hit_err_nf'));
            }

            $ip = $hit->get('ip');

            $addMsg = $ip;

            $ips = explode('.', $ip);
            $criteria = array(
                'ip_1' => $ips[0],
                'ip_2' => $ips[1],
                'ip_3' => $ips[2],
                'ip_4' => $ips[3],
                'context' => $hit->get('context'),
            );

            if (!$count = (boolean)$this->modx->getCount('antiBotStopList', $criteria)) {
                $array = array_merge($criteria, array(
                    'comment' => $this->modx->lexicon('antibot_comment_blocked', array('name' => $this->nameList)),
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

            if (!in_array($ip, $msg)) {
                $msg[] = $addMsg;
            }


        }

        $ip = count($msg) > 0 ? implode('<br>', $msg) : '';
        $message = $this->modx->lexicon('antibot_blocked_success_message', array('list' => $ip));
        return $this->success($message);
    }
}

return 'antiBotBlockedIpProcessor';