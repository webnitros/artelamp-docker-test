<?php

class mspc2ComboGroupGetListProcessor extends modProcessor
{
    /** @var msPromoCode2 $mspc2 */
    protected $mspc2;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->mspc2 = $this->modx->getService('mspromocode2', 'msPromoCode2',
            $this->modx->getOption('mspc2_core_path', null, MODX_CORE_PATH . 'components/mspromocode2/') . 'model/mspromocode2/');
        $this->mspc2->initialize($this->modx->context->key);

        return parent::initialize();
    }

    /**
     * @return string
     */
    public function process()
    {
        $output = [];

        //
        $filter = $this->getProperty('filter', false);
        if (!empty($filter)) {
            $output[] = [
                'display' => '(Все)',
                'value' => '',
            ];
        }

        //
        $notempty = $this->getProperty('notempty', true);
        if (!empty($filter) || empty($notempty)) {
            $output[] = [
                'display' => '(Не указано)',
                'value' => '_',
            ];
        }

        //
        $query = $this->getProperty('query', '');
        if (empty($filter) && !empty($query) && $query !== 'default') {
            $output[] = [
                'display' => $query,
                'value' => $query,
            ];
        }

        //
        $output[] = [
            'display' => 'default',
            'value' => 'default',
        ];

        //
        $q = $this->modx->newQuery('mspc2Coupon')
            ->select(['list'])
            ->where(join(' AND ', [
                'list != ""',
                'list != "default"',
                'list != "' . $query . '"',
            ]))
            ->sortby('list', 'ASC')
            ->groupby('list')
        ;
        if ($q->prepare() && $q->stmt->execute()) {
            if ($rows = $q->stmt->fetchAll(PDO::FETCH_COLUMN)) {
                foreach ($rows as $row) {
                    $output[] = [
                        'display' => $row,
                        'value' => $row,
                    ];
                }
            }
        }

        return $this->outputArray($output);
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('mspromocode2:default');
    }
}

return 'mspc2ComboGroupGetListProcessor';