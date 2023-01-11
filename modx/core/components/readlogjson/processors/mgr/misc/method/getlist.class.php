<?php


class ReadLogJsonActiveGetListProcessor extends modObjectProcessor
{
    public $languageTopics = array('readlogjson:manager');

    /** {@inheritDoc} */
    public function process()
    {
        $array = array(
            0 => array(
                'name' => 'GET',
                'value' => 'get'
            ),
            1 => array(
                'name' => 'POST',
                'value' => 'post'
            ),
            2 => array(
                'name' => 'DELETE',
                'value' => 'delete'
            ),
            3 => array(
                'name' => 'PATCH',
                'value' => 'patch'
            ),
        );

        $query = $this->getProperty('query');
        if (!empty($query)) {
            foreach ($array as $k => $format) {
                if (stripos($format['name'], $query) === false) {
                    unset($array[$k]);
                }
            }
            sort($array);
        }

        return $this->outputArray($array);
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        if ($this->getProperty('addall')) {
            $array = array_merge_recursive(array(
                array(
                    'name' => $this->modx->lexicon('readlogjson_all_method'),
                    'value' => ''
                )
            ), $array);
        }

        return parent::outputArray($array, $count);
    }

}

return 'ReadLogJsonActiveGetListProcessor';
