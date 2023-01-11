<?php


class modmspreStatusGetListProcessor extends modObjectProcessor
{
    public $languageTopics = array('mspre:filters');

    /** {@inheritDoc} */
    public function process()
    {
        $array = array(
            'published' => 1,
            'unpublished' => 2,
            'deleted' => 3,
            'undeleted' => 4,
            'new' => 5,
            'unnew' => 6,
            'popular' => 7,
            'unpopular' => 8,
            'favorite' => 9,
            'unfavorite' => 10,
            'image' => 11,
            'unimage' => 12,
            'duplicate' => 13,
            'show_in_tree' => 14,
            'unshow_in_tree' => 15,
            'duplicate_article' => 16,
            'more_category' => 17,
            'not_more_category' => 18
        );

        $controller = $this->getProperty('controller', null);
        if (empty($controller)) {
            return $this->failure('Не удалось получить контролер');
        }


        /* @var mspre $mspre */
        $mspre = $this->modx->getService('mspre', 'mspre', MODX_CORE_PATH . 'components/mspre/model/');


        if (!$ManagerController = $mspre->loadManagerController($controller)) {
            return $this->failure("Не удалось загрузить контроллер {$controller} не загружен");
        }


        $filter_status = $ManagerController->loadAllowedFiltersStatus();
        $filter_status = array_map('trim', explode(',', $filter_status));


        $filters = array();
        foreach ($array as $per => $action) {
            if (in_array($per, $filter_status)) {
                $filters[] = array(
                    'value' => $per,
                    'name' => $this->modx->lexicon('mspre_filter_' . $per),
                    'action' => $action
                );
            }
        }

        $query = $this->getProperty('query');
        if (!empty($query)) {
            foreach ($filters as $k => $value) {
                // Исключаем фильтры которые не нашли
                if (stripos($value['name'], $query) === false and stripos($value['value'], $query) === false) {
                    unset($filters[$k]);
                }
            }
            sort($filters);
        }

        return $this->outputArray($filters);
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        /*if ($this->getProperty('addall')) {
            $array = array_merge_recursive(array(
                array(
                    'name'  => $this->modx->lexicon('mspre_status_all'),
                    'value' => ''
                )
            ), $array);
        }*/

        return parent::outputArray($array, $count);
    }

}

return 'modmspreStatusGetListProcessor';