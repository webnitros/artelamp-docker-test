<?php


class modmspreRoundGetListProcessor extends modObjectProcessor
{
    public $languageTopics = array('mspre:default');

    /** {@inheritDoc} */
    public function process()
    {
        $array = array(
            0 => array(
                'name'  => $this->modx->lexicon('mspre_round_empty'),
                'value' => ''
            ),
            1 => array(
                'name'  => $this->modx->lexicon('mspre_round_round'),
                'value' => 'round'
            ),
            2 => array(
                'name'  => $this->modx->lexicon('mspre_round_ceil'),
                'value' => 'ceil'
            ),
        );
        return $this->outputArray($array);
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        return parent::outputArray($array, $count);
    }

}

return 'modmspreRoundGetListProcessor';