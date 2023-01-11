<?php

require_once MODX_CORE_PATH . 'model/modx/modprocessor.class.php';
require_once MODX_CORE_PATH . 'components/minishop2/processors/mgr/orders/getlog.class.php';

class mspc2MsOrderLogGetListProcessor extends msOrderLogGetListProcessor
{
    // public $classKey = 'msOrderLog';
    // public $languageTopics = array('default', 'minishop2:manager');
    public $defaultSortField = 'timestamp';
    public $defaultSortDirection = 'DESC';
    // public $permission = 'msorder_view';

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);

        $c->sortby('`msOrderLog`.`timestamp`', 'DESC');
        $c->groupby('`msOrderLog`.`id`');

        return $c;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function prepareArray(array $data)
    {
        if ($data['action'] === 'status') {
            if ($status = $this->modx->getObject('msOrderStatus', array('id' => $data['entry']))) {
                $data['entry'] = $status->name;
                $data['color'] = $status->color;
                $data['entry'] = '<span style="color:#' . $data['color'] . ';">' . $data['entry'] . '</span>';
            }
        }

        return $data;
    }
}

return 'mspc2MsOrderLogGetListProcessor';