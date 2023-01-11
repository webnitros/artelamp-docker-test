<?php

class msExportUsersExcelProfileExportProcessor extends modObjectProcessor
{
    public $objectType = 'msExportUsersExcelProfile';
    public $classKey = 'msExportUsersExcelProfile';
    public $languageTopics = ['msexportusersexcel'];
    //public $permission = 'remove';
    /* @var msExportUsersExcel $msExportUsersExcel */
    public $msExportUsersExcel;

    /**
     * Отчищает директорию от сообщенией которые не успели стереться
     */
    protected function cleraDirCache()
    {
        $path = $this->msExportUsersExcel->config['registry'];
        if (file_exists($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if (preg_match('/.*?\.msg\.php$/i', $file)) {
                    if (file_exists($path . $file)) {
                        unlink($path . $file);
                    }
                }
            }
        }
    }


    /**
     * @return array|string
     */
    public function process()
    {
        
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }
        $this->modx->lexicon->load('msexportusersexcel:console');

        $id = trim($this->getProperty('id'));
        if (empty($id)) {
            return $this->failure($this->modx->lexicon('msexportusersexcel_profile_err_ns'));
        }
        
        $baseParams = $this->getProperty('baseParams');
        if (!empty($baseParams)) {
            $baseParams = $this->modx->fromJSON($baseParams);
            if (isset($baseParams['action'])) {
                unset($baseParams['action']);
            }
        }
     
        /** @var msExportUsersExcelProfile $object */
        if (!$object = $this->modx->getObject($this->classKey, $id)) {
            return $this->failure($this->modx->lexicon('msexportusersexcel_profile_err_nf'));
        }



        $object->set('classExport', $this->getProperty('classExport'));
        $object->set('ajax', 1);


        $this->msExportUsersExcel = $this->modx->getService('msExportUsersExcel', 'msExportUsersExcel', MODX_CORE_PATH . 'components/msexportusersexcel/model/');
        if (!$this->msExportUsersExcel->initialize()) {
            return $this->failure('Load config error');
        }

        // Подклчение пространства имен для экспортируемого класса
        if (!$this->msExportUsersExcel->getNamespace($object->get('namespace'), $object->get('namespace_path'))) {
            return $this->failure($this->modx->lexicon('msexportusersexcel_profile_err_namespace', array('namespace' => $object->get('namespace'))));
        }

        $this->cleraDirCache();

        $this->modx->lexicon('msexportusersexcel_console_ini_get', array(
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
        ));

        $this->modx->log(3, $this->modx->lexicon('msexportusersexcel_console_profile', array('classExport' => $object->get('classExport'), 'name' => $object->get('name'))));


        $data = array();
        $query = $this->msExportUsersExcel->query->newQuery($object->getConfig());
        if ($query->runProcessorData($baseParams)) {
            $data = $query->getData();
        }


        if (!$object->export($this->getProperty('classExport'), $data)) {
            $this->modx->log(1, $this->modx->lexicon('msexportusersexcel_console_error_export'));
            $this->modx->log(3, 'COMPLETED');
            return $this->failure($this->modx->lexicon('msexportusersexcel_console_error_export'));
        }


        if (!$object->fileExists()) {
            $this->modx->log(1, $this->modx->lexicon('msexportusersexcel_console_error_exists_file', array('path' => $object->fileAbsolutePath())));
            $this->modx->log(3, 'COMPLETED');
            return $this->failure();
        }

        $filename = $object->fileBaseName();
        $download_link = $object->fileDownloadLink();
        $total = $query->getTotal();
        $total_export = $query->getTotalExport();
        $this->modx->log(3, $this->modx->lexicon('msexportusersexcel_console_total_export', array(
            'total' => $total,
            'total_export' => $total_export
        )));


        if ($object->get('download')) {
            $this->modx->log(3, $this->modx->lexicon('msexportusersexcel_console_download'));
        } else {
            $this->modx->log(3, $this->modx->lexicon('msexportusersexcel_console_link', array('filename' => $filename, 'download_link' => $download_link)));
        }

        //$exec_time = microtime(true) - $this->msExportUsersExcel->start_time;
        //$this->modx->log(3, $this->modx->lexicon('msexportusersexcel_console_end', array('time' => $exec_time)));
        $this->modx->log(3, 'COMPLETED');
        sleep(1);

        return $this->success('', array(
            'total' => $total,
            'total_export' => $total_export,
            'download_link' => $download_link,
            'download' => $object->get('download')
        ));
    }

}

return 'msExportUsersExcelProfileExportProcessor';