<?php
if (!class_exists('msExportUsersExcelPhpExcelXlsController')) {
    include_once dirname(__FILE__) . '/xls.class.php';
}

class msExportUsersExcelPHPExcelCSVController extends msExportUsersExcelPhpExcelXlsController
{
    /* @var string $class */
    protected $class = 'CSV';
    protected $extension = 'csv';

    /**
     * @return bool
     */
    public function beforeExport()
    {
        if (!$this->headAll()) {
            $this->setHeaders();
        }
        $this->setData();
        return true;
    }


    /* @inheritdoc */
    public function objWriter()
    {
        if (parent::objWriter()) {
            if (!empty($this->config['delimiter'])) {
                $this->objWriter->setDelimiter($this->config['delimiter']);  // Define delimiter
            }
        }
        return true;
    }

    /**
     * @param string $absolute_path_file Абсолютный путь после сохранения
     * @return bool
     */
    public function afterSave($absolute_path_file)
    {
        // Пергоняем файл в читаемый формат
        $csv_text = file_get_contents($absolute_path_file);
        $csv_text_converted = mb_convert_encoding($csv_text, "CP1251", "UTF-8");
        if ($csv_text_converted) {
            file_put_contents($absolute_path_file, $csv_text_converted);
        }
        return true;
    }


}

return 'msExportUsersExcelPHPExcelCSVController';
