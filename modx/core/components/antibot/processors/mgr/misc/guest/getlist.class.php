<?php


class antiBotCodeResponseGetListProcessor extends modObjectProcessor
{
    public $languageTopics = ['antibot:manager'];

    /** {@inheritDoc} */
    public function process()
    {
        $array = array();
        $q = $this->modx->newQuery('antiBotGuest');
        $q->select('id as value,ip as name');


        $query = $this->getProperty('query');
        if (!empty($query)) {

            $id = (int)$query;
            $count = $this->modx->getCount('antiBotGuest', $id);
            if ($count > 0) {
                $q->where([
                    'id' => $id,
                ]);
            } else {
                $q->where(array(
                    'ip:LIKE' => "%{$query}%",
                    'OR:user_agent:LIKE' => "%{$query}%",
                ));
            }

        }

        $count = $this->modx->getCount('antiBotHits', $q);
        $q->limit($this->getProperty('limit'), $this->getProperty('start'));
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
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
                    'name' => $this->modx->lexicon('antibot_all_guest'),
                    'value' => '',
                )
            ), $array);
        }
        return parent::outputArray($array, $count);
    }

}

return 'antiBotCodeResponseGetListProcessor';
