<?php

// DeskJobStageFile

class ulLocationExportProcessor extends modProcessor
{

    public function getLanguageTopics()
    {
        return ['file', 'userlocation'];
    }

    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_upload');
    }

    public function initialize()
    {
        $this->setDefaultProperties([
            'csv_terminated'   => ',',
            'csv_enclosed'     => '"',
            'csv_escaped'      => "'",
            'csv_ignore_lines' => "",
            'load_method'      => "",
        ]);

        return true;
    }

    public function process()
    {
        //$this->modx->log(1, print_r($this->getProperties(), 1));

        /** @var ulLocationExport $handler */
        if ($handler = $this->modx->getService('userlocation.ulLocationExport', '', MODX_CORE_PATH.'components/userlocation/'.'model/', $this->getProperties())) {
            $handler->run();
        }

        return $this->success('');
    }

}

return 'ulLocationExportProcessor';
