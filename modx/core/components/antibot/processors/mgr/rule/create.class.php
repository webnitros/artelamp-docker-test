<?php

class antiBotRuleCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'antiBotRule';
    public $classKey = 'antiBotRule';
    public $languageTopics = ['antibot:manager'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $hour = (int)$this->getProperty('hour');
        $hits_per_minute = (int)$this->getProperty('hits_per_minute');
        $method = trim($this->getProperty('hit_method'));
        $name = trim($this->getProperty('name'));
        $core_response = trim($this->getProperty('core_response'));

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('antibot_rule_err_name'));
        }

        if (empty($method)) {
            $this->modx->error->addField('hit_method', $this->modx->lexicon('antibot_rule_err_hit_method'));
        }

        if (empty($core_response)) {
            $this->modx->error->addField('core_response', $this->modx->lexicon('antibot_rule_err_core_response'));
        }

        if ($hour == 0) {
            $this->modx->error->addField('hour', $this->modx->lexicon('antibot_rule_err_hour'));
        }

        if ($hits_per_minute == 0) {
            $this->modx->error->addField('hits_per_minute', $this->modx->lexicon('antibot_rule_err_hits_per_minute'));
        }

        return parent::beforeSet();
    }


}

return 'antiBotRuleCreateProcessor';
