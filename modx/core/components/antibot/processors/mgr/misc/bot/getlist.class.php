<?php


class antiBotCodeResponseGetListProcessor extends modObjectProcessor
{
    public $languageTopics = ['antibot:manager'];

    /** {@inheritDoc} */
    public function process()
    {
        /* @var antiBot $antiBot*/
        $antiBot = $this->modx->getService('antibot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/');
        $array = array();
        foreach ($antiBot->rules as $bot => $rule) {
            $array[] = [
                'value' => $bot,
                'name' => $this->modx->lexicon('antibot_bot_' . $bot),
            ];
        }

        return $this->outputArray($array, count($array));
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        if ($this->getProperty('addall')) {
            $array = array_merge_recursive(array(
                array(
                    'name' => $this->modx->lexicon('antibot_all_boot'),
                    'value' => '',
                )
            ), $array);
        }
        return parent::outputArray($array, $count);
    }

}

return 'antiBotCodeResponseGetListProcessor';
