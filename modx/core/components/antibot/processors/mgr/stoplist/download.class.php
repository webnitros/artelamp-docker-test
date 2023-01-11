<?php

class antiBotStopListDownloadProcessor extends modProcessor
{
    public $languageTopics = ['antibot:manager'];
    //public $permission = 'save';


    /**
     * @return array|string
     */
    public function process()
    {
        $service = $this->modx->getService('antibot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/');
        if ($service instanceof antiBot) {
            if ($curl = $service->loadRequest()) {
                $response = $curl->request('stoplist');
                if (!$response['success']) {
                    return $this->failure($response['message']);
                }
                if (isset($response['object']['stoplist'])) {
                    /* @var array $stoplist */
                    $stoplist = $response['object']['stoplist'];
                    if (count($stoplist)) {
                        foreach ($stoplist as $row) {
                            $useragent = $row['user_agent'];

                            /* @var antiBotStopList $object */
                            if (!$object = $this->modx->getObject('antiBotStopList', array('user_agent' => $useragent))) {
                                $object = $this->modx->newObject('antiBotStopList');
                                $object->fromArray($row);
                                $object->save();
                            }
                        }
                    }
                }
            }
        }
        return $this->success();
    }

}

return 'antiBotStopListDownloadProcessor';
