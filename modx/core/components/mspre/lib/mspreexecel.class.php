<?php
class msPreExecel
{
    protected $config = array();


    /**
     * @param array $config
     */
    function __construct(array $config = [])
    {
        $this->config = array_merge(array(
            'path_save' => MODX_CORE_PATH.'export/',
            'path_tmp' => MODX_CORE_PATH.'export/tmp/',
            'format' => '',
            'columns' => '',
        ), $config);
        $this->tstart = time();
    }


    /**
     * Название колонок в файле
     * @param $value
     */
    public function setColumns($value)
    {
        $this->config['columns'] = $value;
    }

    /**
     * Имя вкладки
     * @param $value
     */
    public function setTabName($value)
    {
        $this->config['tab'] = $value;
    }
    /**
     * Имя вкладки
     * @param $value
     */
    public function setFormat($value)
    {
        $this->config['format'] = $value;
    }

    /**
     * Имя вкладки
     * @param $value
     */
    public function setFilename($value)
    {
        $this->config['filename'] = $value;
    }

    private function reset()
    {
        $path_tmp = $this->config['path_tmp'];
        if (!file_exists($path_tmp)) {
            mkdir($path_tmp,0775);
        }
    }

    /**
     * @return bool
     */
    private function loadClassXLSXWriter()
    {
        if (!class_exists('XLSXWriter')) {
            require_once dirname(dirname(__FILE__)) . '/lib/xlsxwriter.class.php';
            return class_exists('XLSXWriter');
        }
        return false;
    }

    /**
     * @return bool
     */
    private function loadClassExportData()
    {
        if (!class_exists('ExportDataCSV')) {
            require_once dirname(dirname(__FILE__)) . '/lib/php-export-data.class.php';
            return class_exists('ExportDataCSV');
        }
        return false;
    }

    /**
     * Экспорт данные в XLS
     * @param array $items массив экспортируемых данных
     * @return boolean
     */
    public function export($items)
    {
        $this->reset();

        $filename = $this->config['filename'];
        $format = mb_strtoupper($this->config['format']);
        $tab = $this->config['tab'];
        $columns = $this->config['columns'];

        switch ($format){
            case 'XLSX':
                if (!$this->loadClassXLSXWriter()) {
                    return false;
                }

                header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
                header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                header('Content-Transfer-Encoding: binary');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');

                $writer = new XLSXWriter();
                $writer->setTempDir($this->config['path_tmp']);
                $writer->setAuthor('Andrey Stepanenko');

                // Добавление заголовков для колонок в первую строку
                $addItem = array();
                foreach ($columns as $column) {
                    $addItem[$column] = $column;
                }
                $items = array_merge(array($addItem), $items);

                foreach($items as $row) {
                    $writer->writeSheetRow($tab, $row);
                }

                @session_write_close();
                $writer->writeToStdOut();
                exit();


                break;
            case 'CSV':
            case 'XLS':
                    $this->loadClassExportData();
                    $exporter = null;
                    $className = $format == 'CSV' ?'ExportDataCSV' : 'ExportDataExcel';

                    /* @var ExportDataCSV|ExportDataExcel $exporter */
                    $exporter = new $className('browser', $filename);
                    function trim_value(&$value)
                    {
                        $value = trim($value);
                    }

                    $headers = array_keys($items[0]);
                    $exporter->initialize(); // starts streaming data to web browser
                    $exporter->addRow($headers);

                    foreach ($items as $item) {
                        $exporter->addRow($item);
                    }

                    @session_write_close();
                    $exporter->finalize(); // writes the footer, flushes remaining data to browser.
                    exit();
                break;
            default:
                break;
        }

        return true;
    }
}



