<?php

/**
 * The base class for Nsi.
 */
class mspreActionsExport extends mspreActions
{
    /* @inheritdoc */
    public function getMenus($actions = array())
    {
        $actions[] = array(
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-download',
            'title' => $this->lexicon('mspre_export_csv'),
            'action' => 'exportCSV',
        );
        $actions[] = array(
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-download',
            'title' => $this->lexicon('mspre_export_xls'),
            'action' => 'exportXLS',
        );
        $actions[] = array(
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-download',
            'title' => $this->lexicon('mspre_export_xlsx'),
            'action' => 'exportXLSX',
        );
        $actions[] = '-';


        $actions[] = array(
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-sort red',
            'title' => $this->lexicon('mspre_export_fields'),
            'action' => 'windowFields',
            'combo_id' => 'export',
        );
        return $actions;
    }
}
return 'mspreActionsExport';