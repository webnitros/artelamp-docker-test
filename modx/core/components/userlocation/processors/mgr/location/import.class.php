<?php

// DeskJobStageFile

class ulLocationImportProcessor extends modProcessor
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
            'load_truncate'    => '',
        ]);

        return true;
    }

    public function process()
    {
        /** @var ulLocationImport $handler */
        if ($handler = $this->modx->getService('userlocation.ulLocationImport', '', MODX_CORE_PATH.'components/userlocation/'.'model/', $this->getProperties())) {
            $handler->run();
        }

        return $this->success('');
    }

}

return 'ulLocationImportProcessor';
