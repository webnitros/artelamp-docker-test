<?php


class modmspreIncreaseGetListProcessor extends modObjectProcessor
{
    public $languageTopics = array('mspre');

    /** {@inheritDoc} */
    public function process()
    {
        $array = array(
            0 => array(
                'name'  => $this->modx->lexicon('mspre_increase_new'),
                'value' => 'new'
            ),
            1 => array(
                'name'  => $this->modx->lexicon('mspre_increase_percent_up'),
                'value' => 'percent_up'
            ),
            2 => array(
                'name'  => $this->modx->lexicon('mspre_increase_percent_down'),
                'value' => 'percent_down'
            ),
            3 => array(
                'name'  => $this->modx->lexicon('mspre_increase_side_up'),
                'value' => 'side_up'
            ),
            4 => array(
                'name'  => $this->modx->lexicon('mspre_increase_side_down'),
                'value' => 'side_down'
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

return 'modmspreIncreaseGetListProcessor';