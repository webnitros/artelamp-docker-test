<?php

class antiBotStopListCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'antiBotStopList';
    public $classKey = 'antiBotStopList';
    public $languageTopics = ['antibot:manager'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        /*$user_agent = trim($this->getProperty('user_agent'));
        $user_agent = trim($this->getProperty('user_agent'));
        if (empty($user_agent)) {
            $this->modx->error->addField('user_agent', $this->modx->lexicon('antibot_stoplist_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['user_agent' => $user_agent])) {
            $this->modx->error->addField('user_agent', $this->modx->lexicon('antibot_stoplist_err_ae'));
        }*/
        /*$ip_1 = trim($this->getProperty('ip_1'));
        $ip_2 = trim($this->getProperty('ip_2'));
        $ip_3 = trim($this->getProperty('ip_3'));
        $ip_4 = trim($this->getProperty('ip_4'));
        if (empty($user_agent)) {
            $this->modx->error->addField('user_agent', $this->modx->lexicon('antibot_stoplist_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['user_agent' => $user_agent])) {
            $this->modx->error->addField('user_agent', $this->modx->lexicon('antibot_stoplist_err_ae'));
        }*/

        return parent::beforeSet();
    }
    /**
     * Проверка заполнение файлов
     * @param $arFields
     * @return bool
     */
   /* public function CheckFields($arFields)
    {
        $ip = $this->GetIpArray();
        $user_agent = $this->GetUserAgent();
        $context = $this->GetContext();

        if (((strlen($arFields['user_agent']) < 1)
                || strpos(strtoupper($user_agent), strtoupper($arFields['user_agent'])) !== false)
            && (((strlen($arFields['ip_1']) < 1
                    && strlen($arFields['ip_2']) < 1
                    && strlen($arFields['ip_3']) < 1
                    && strlen($arFields['ip_4']) < 1))
                || ((intval($arFields['mask_1']) & intval($ip[0])) == intval($arFields['ip_1'])
                    && (intval($arFields['mask_2']) & intval($ip[1])) == intval($arFields['ip_2'])
                    && (intval($arFields['mask_3']) & intval($ip[2])) == intval($arFields['ip_3'])
                    && (intval($arFields['mask_4']) & intval($ip[3])) == intval($arFields['ip_4'])))
            && ((strlen($arFields['context']) < 1)
                || ($arFields['context'] == $context)))
            return true;

        return false;
    }*/

}

return 'antiBotStopListCreateProcessor';