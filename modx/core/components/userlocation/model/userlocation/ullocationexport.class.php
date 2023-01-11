<?php


class ulLocationExport
{

    /* @var modX $modx */
    public $modx;
    /** @var UserLocation $UserLocation */
    public $UserLocation;
    /** @var array $props */
    public $props = [];


    /**
     * @param  modX   $modx
     * @param  array  $config
     */
    function __construct(modX &$modx, array $props = [])
    {
        $this->modx =& $modx;
        $this->props = $props;
        if (!$this->UserLocation = $modx->getService('userlocation.UserLocation', '', MODX_CORE_PATH.'components/userlocation/model/')) {
            return;
        }
        if (empty($this->props['tempnam'])) {
            $this->props['tempnam'] = tempnam(MODX_BASE_PATH, 'ul_');
        }
    }

    function __call($n, array $p)
    {
        echo __METHOD__.' says: '.$n;
    }

    public function getProps()
    {
        return $this->props;
    }

    public function getProp($key, $default = null)
    {
        if (isset($this->props[$key])) {
            return $this->props[$key];
        }

        return $default;
    }


    public function getExportFile()
    {
        $path = $this->getProp('tempnam');
        clearstatcache(true, $path);
        if (file_exists($path)) {
            return $path;
        }

        return false;
    }

    public function clearExportFile()
    {
        if ($path = $this->getProp('tempnam')) {
            @unlink($path);
        }
    }

    public function getExportClasses()
    {
        return ['ulLocation', /*'ulLocationActive', 'ulLocationParent'*/];
    }

    public function getExportFields()
    {
        $fields = [];
        $classes = $this->getExportClasses();
        foreach ($classes as $class) {
            $fields[$class] = [];
            if ($this->modx->loadClass($class)) {
                if (isset($this->modx->map[$class]['fieldMeta'])) {
                    $fields[$class] = array_keys($this->modx->map[$class]['fieldMeta']);
                }
            }
        }

        return $fields;
    }


    public function getExportVars($fields = [])
    {
        $vars = [];
        $classes = $this->getExportClasses();
        foreach ($classes as $class) {
            $vars[$class] = ['id'];
            foreach ($fields as $alias => $field) {
                if ($class === $alias) {
                    //$field = @array_diff($field, ['id']);
                } else {
                    $field = array_pad([], count($field) - 1, '');
                }

                /*array_walk($field, function (&$v, $k) {
                    if ($v === '') {
                        $v = '@skip'.$k;
                    }
                });*/

                $vars[$class] = array_merge($vars[$class], $field);

                /* $row = array_slice($row, count($vars[$class]));

                 if (empty($row)) {
                     break;
                 }*/
            }
        }

        return $vars;
    }

    public function run()
    {
        if ($file = $this->getExportFile()) {
            $fields = $this->getExportFields();
            $vars = $this->getExportVars($fields);
            foreach ($vars as $class => $var) {
                $this->exportFile($file, $class, $var);
            }
        }
        //$this->clearExportFile();
    }

    protected function exportFile($file, $class, array $vars)
    {
        if ($table = $this->modx->getTableName($class)) {

            $c = $this->modx->newQuery($class);
            $c->select($this->modx->getSelectColumns($class, $class, ''));
            $fp = fopen($file, 'w');
            fputcsv($fp, $vars, $this->getProp('csv_terminated', '"'), $this->getProp('csv_enclosed', '"'), $this->getProp('csv_escaped', "'"));
            if ($c->prepare() && $c->stmt->execute()) {
                while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
                    fputcsv($fp, $row, $this->getProp('csv_terminated', '"'), $this->getProp('csv_enclosed', '"'), $this->getProp('csv_escaped', "'"));
                }
            }
            fclose($fp);
            $this->download($file);
        }
    }

    public function download($filePath, $fileName = 'locations.csv')
    {
        if (!file_exists($filePath)) {
            return false;
        }

        /*
         * from https://github.com/goldsky/FileDownload-R/blob/6124c82db015fa86883be598ff75a0d57816e8a8/core/components/filedownloadr/models/filedownload/filedownload.class.php#L1177
         *
         */
        // required for IE
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }
        @set_time_limit(300);
        @ini_set('magic_quotes_runtime', 0);
        ob_end_clean(); //added to fix ZIP file corruption
        ob_start(); //added to fix ZIP file corruption
        header('Pragma: public');  // required
        header('Expires: 0');  // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT');
        header('Content-Description: File Transfer');
        header('Content-Type:'); //added to fix ZIP file corruption
        header('Content-Type: "application/force-download"');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . (string)(filesize($filePath))); // provide file size
        header('Connection: close');
        sleep(1);
        //Close the session to allow for header() to be sent
        session_write_close();
        ob_flush();
        flush();
        $chunksize = 1 * (1024 * 1024); // how many bytes per chunk
        $buffer = '';
        $handle = @fopen($filePath, 'rb');
        if ($handle === false) {
            return false;
        }
        while (!feof($handle) && connection_status() == 0) {
            $buffer = @fread($handle, $chunksize);
            if (!$buffer) {
                die();
            }
            echo $buffer;
            ob_flush();
            flush();
        }
        fclose($handle);
        die();
    }

}

return 'ulLocationExport';