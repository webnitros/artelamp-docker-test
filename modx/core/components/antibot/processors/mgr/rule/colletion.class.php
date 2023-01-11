<?php

class antiBotMultipleProcessor extends modProcessor
{

    /**
     * @return array|string
     */
    public function process()
    {
        $id = (int)$this->getProperty('id');

        /* @var antiBotRule $Rule */
        if (!$Rule = $this->modx->getObject('antiBotRule', $id)) {
            return $this->failure('Не удалось получить Правило');
        }


        if (!$Rule->get('active')) {
            return 'Правило отключено список IP адресов получить невозможно';
        }


        /* @var antiBot $antiBot */
        $antiBot = $this->modx->getService('antibot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/');

        $response = $antiBot->getCollectionIp($Rule);

        $tr = '';
        $td = '';
        foreach ($response[0] as $k => $v) {
            $td .= '<th class="x-grid3-col x-grid3-cell x-grid3-td-0 x-selectabl"><div class="x-grid3-hd-inner x-grid3-hd-0" unselectable="on" style="">' . $k . '</div></th>';
        }
        $tr .= '<thead><tr>' . $td . '</tr></thead>';

        foreach ($response as $item) {
            $td = '';
            foreach ($item as $k => $v) {
                $td .= '<td class="x-grid3-col x-grid3-cell x-grid3-td-0 x-selectabl"><div class="x-grid3-cell-inner x-grid3-col-0" style="max-width: 250px">' . $v . '</div></td>';
            }

            $tr .= '<tr>' . $td . '</tr>';
        }
        $outer = '<div class="x-grid3-hd-row "><div class="x-grid3 "></div><table class="x-grid3-row-table"><tbody>' . $tr . '</tbody></table></div></div>';
        return $this->success('', [
            'outer' => $outer
        ]);
    }


}

return 'antiBotMultipleProcessor';
