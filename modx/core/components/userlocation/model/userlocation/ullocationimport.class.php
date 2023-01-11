<?php


class ulLocationImport
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


    public function getImportFile()
    {
        $path = $this->getProp('tempnam');
        $file = $this->getProp('file');
        if (!empty($file) AND is_array($file) AND is_uploaded_file($file['tmp_name'])) {
            move_uploaded_file($file['tmp_name'], $path);
        } elseif (!empty($file) AND is_string($file) AND (strpos($file, '://') !== false OR file_exists($file))) {
            if ($stream = fopen($file, 'r')) {
                if ($res = fopen($path, 'w')) {
                    while (!feof($stream)) {
                        fwrite($res, fread($stream, 8192));
                    }
                    fclose($res);
                }
                fclose($stream);
            }
        }

        clearstatcache(true, $path);
        if (file_exists($path) AND filesize($path)) {
            return $path;
        }

        return false;
    }

    public function clearImportFile()
    {
        if ($path = $this->getProp('tempnam')) {
            @unlink($path);
        }
    }

    public function getImportClasses()
    {
        return ['ulLocation', /*'ulLocationActive', 'ulLocationParent'*/];
    }

    public function getImportFields()
    {
        $fields = [];
        $classes = $this->getImportClasses();
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


    public function getImportRow()
    {
        $rows = [];
        if ($stream = fopen($this->getProp('tempnam'), 'r')) {
            while ($rows = fgetcsv($stream, 0, $this->getProp('csv_terminated', ','), $this->getProp('csv_enclosed', ','), $this->getProp('csv_escaped', "'"))) {
                break;
            }
            fclose($stream);
        }

        return $rows;
    }


    public function getImportVars($fields = [], $row = [])
    {
        $vars = [];
        $classes = $this->getImportClasses();
        foreach ($classes as $class) {
            $vars[$class] = ['id'];
            foreach ($fields as $alias => $field) {
                if ($class === $alias) {
                    $field = @array_diff($field, ['id']);
                } else {
                    $field = array_pad([], count($field) - 1, '');
                }

                array_walk($field, function (&$v, $k) {
                    if ($v === '') {
                        $v = '@skip'.$k;
                    }
                });

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
        if ($file = $this->getImportFile()) {
            $fields = $this->getImportFields();
            $row = $this->getImportRow();
            $vars = $this->getImportVars($fields, $row);
            foreach ($vars as $class => $var) {
                $this->importFile($file, $class, $var);
            }
        }

        $this->clearImportFile();
    }

    public function getXPDO()
    {
        $database_user = $this->modx->config['username'];
        $database_password = $this->modx->config['password'];
        $table_prefix = $this->modx->config['table_prefix'];
        $database_dsn = $this->modx->config['dsn'];
        $config_options = [
            xPDO::OPT_CACHE_PATH   => MODX_CORE_PATH.'cache/',
            xPDO::OPT_TABLE_PREFIX => $table_prefix,
        ];
        $driver_options = [
            PDO::MYSQL_ATTR_LOCAL_INFILE => true,
            //PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            /** @var xPDO $xpdo */
            $xpdo = new xPDO($database_dsn, $database_user, $database_password, $config_options, $driver_options);
            if ($xpdo AND is_object($xpdo)) {
                $xpdo->setLogLevel(xPDO::LOG_LEVEL_ERROR);
                $xpdo->setLogTarget('HTML');
                if ($xpdo->connect()) {
                    return $xpdo;
                }
            }
        } catch (PDOException $e) {
            die('database connection failed: '.$e->getMessage());
        }


        return false;
    }

    protected function importFile($file, $class, array $vars)
    {
        if ($table = $this->modx->getTableName($class) AND $xpdo = $this->getXPDO()) {

            if ($this->getProp('load_truncate')) {
                $this->modx->exec("TRUNCATE {$table};ALTER TABLE {$table} AUTO_INCREMENT = 0;");
            }

            $method = strtolower($this->getProp('load_method', ''));
            switch (true) {
                case $method === 'replace':
                    $method = 'REPLACE ';
                    break;
                case $method === 'ignore':
                    $method = 'IGNORE ';
                    break;
                default:
                    $method = '';
                    break;
            }

            $ignore = '';
            if ($lines = (int)$this->getProp('csv_ignore_lines', '')) {
                $ignore = 'IGNORE '.$lines.' LINES ';
            }

            if ($stmt = $xpdo->prepare("".
                "LOAD DATA LOCAL INFILE '".$file."' ".$method." INTO TABLE ".$table." CHARACTER SET utf8mb4 
                FIELDS TERMINATED BY ".$xpdo->quote($this->getProp('csv_terminated', '"'))." 
                ENCLOSED BY ".$xpdo->quote($this->getProp('csv_enclosed', '"'))." 
                ESCAPED BY ".$xpdo->quote($this->getProp('csv_escaped', "'"))." 
                LINES TERMINATED BY ".$xpdo->quote(PHP_EOL)." ".$ignore."(".implode(',', $vars).")
                ;")
            ) {

                //$this->modx->log(1, print_r($stmt->debugDumpParams(), true));
                if (!$stmt->execute()) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[UserLocationImport]\n".print_r($stmt->errorInfo(), true));
                }
            }

        }
    }


}

return 'ulLocationImport';