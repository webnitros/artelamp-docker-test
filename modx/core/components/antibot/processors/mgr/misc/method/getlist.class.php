<?php


class antiBotMethodsGetListProcessor extends modObjectProcessor
{
    public $languageTopics = ['antibot:manager'];

    /** {@inheritDoc} */
    public function process()
    {
        $array = array();
        $q = $this->modx->newQuery('antiBotHits');
        $q->select('method as value');



        $query = $this->getProperty('query');
        if (!empty($query)) {
            $q->where(array(
                'method:LIKE' => "%{$query}%",
            ));
        }
        $q->groupby('method');

        $count = $this->modx->getCount('antiBotHits', $q);
        $q->limit($this->getProperty('limit'), $this->getProperty('start'));
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $row['name'] = $row['value'];
                $array[] = $row;
            }
        }

        return $this->outputArray($array, $count);
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        if ($this->getProperty('addall')) {
            $array = array_merge_recursive(array(
                array(
                    'name' => $this->modx->lexicon('antibot_all_methods'),
                    'value' => '',
                )
            ), $array);
        }
        return parent::outputArray($array, $count);
    }

}

return 'antiBotMethodsGetListProcessor';
