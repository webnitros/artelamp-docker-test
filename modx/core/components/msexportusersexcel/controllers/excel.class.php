<?php

abstract class msExportUsersExcelPhpExcelExcelController extends msExportUsersExcelPHPExcelDefaultController
{
    /* @var PHPExcel_Worksheet $sheet */
    protected $sheet;

    /* @var PHPExcel $xls */
    protected $xls;

    /* @var int $i */
    protected $i = 1;

    /**
     * @return bool
     */
    public function initialize()
    {
        // Добавить возможность продоления наполнения файла частями
        $this->i = 1;
        if (!class_exists('PHPExcel')) {
            require_once($this->config['corePath'] . '/lib/PHPExcel/Classes/PHPExcel.php');
            require_once $this->config['corePath'] . '/lib/PHPExcel/Classes/PHPExcel/IOFactory.php';
        }

        if (!class_exists('PHPExcel')) {
            return "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error load class PHPExcel";
        }

        $response = $this->newExportExcel();
        if ($response !== true) {
            return $response;
        }
        return parent::initialize();
    }


    /**
     * @param array $config
     */
    public function newExport($config = array())
    {
        $this->i = 1;
        $this->xls = null;
        $this->sheet = null;

        $this->newExportExcel();
        parent::newExport($config);

    }


    /**
     * @return string
     */
    public function newExportExcel()
    {
        try {
            $this->xls = new PHPExcel();
            $this->xls->setActiveSheetIndex(0);
            $this->sheet = $this->xls->getActiveSheet();

            if (!$this->sheet instanceof PHPExcel_Worksheet) {
                return "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Not found class instanceof PHPExcel_Worksheet sheet";
            }
        } catch (baseException $e) {
            return '[PHPExcel] ' . $e->getMessage();
        }
        return true;
    }

    /**
     * @return bool
     */
    public function beforeExport()
    {
        $this->setTabTitle();
        $this->setStyle();


        $this->setWidths();
        $this->setAlignmentVerticals();
        $this->setAlignmentHorizontals();


        if (!$this->headAll()) {
            $this->setHeaders();
            $this->freezePane();
        }


        $this->setData();
        return parent::beforeExport();
    }


    /**
     * @return boolean
     */
    public function objWriter()
    {
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->xls, $this->class);
        return true;
    }

    /**
     * Запись данных в обработчик для экспорта
     * @return string
     */
    public function toExcel()
    {
        ob_start();
        $this->objWriter->save('php://output');
        $content = ob_get_contents();

        ob_end_clean();
        return $content;
    }


    /**
     * Закрепить первую строку с заголовком
     */
    protected function freezePane()
    {
        if ($this->config['head_process'] and $this->config['head_freezepane']) {
            $i = $this->i;
            $this->sheet->freezePane('A' . $i);
        }
    }


    /**
     * Добавляем заголовки полей для первой строки
     *
     * @return boolean
     */
    protected function setHeaders()
    {
        if ($this->config['head_process'] and $this->fields) {
            $headers = $this->fields;
            $i = $this->i++;

            $this->setLevel($i);

            // Установка высоты колонки
            $this->setHeight($this->sheet, $i, $this->height());

            $col = 0;
            foreach ($headers as $field => $value) {
                if ($this->offsetColump($field)) {
                    $colump = $col++;
                    $this->setValue($colump, $i, $value);
                }
            }

            // add color first line
            if (!empty($this->config['head_color'])) {
                if (strlen($this->config['head_color']) == 6 or strlen($this->config['head_color']) == 3) {
                    $color = $this->config['head_color'];
                    $count = count($headers) - 1;
                    if ($count > 0) {
                        $lastColump = PHPExcel_Cell::stringFromColumnIndex($count);
                        $this->sheet->getStyle('A' . $i . ':' . $lastColump . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
                    }
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error Color head len 3 or 6");
                }
            }

        }
        return true;
    }


    /**
     * @param PHPExcel_Worksheet $sheet
     * @param $height
     */
    static private function setHeight($sheet, $i, $height)
    {
        $height = (int)$height;
        if ((is_int($height) and $height > 0)) {
            $sheet->getRowDimension($i)->setRowHeight($height);
        }
    }

    /**
     * Записывае массив для экспорта
     * @return bool
     */
    protected function setData()
    {
        if ($this->data) {
            foreach ($this->data as $row) {
                if (!$this->dependent) {
                    if ($this->headAll()) {
                        $this->setHeaders();
                    }
                }
                $this->setRecord($row);
                $this->setDependent($row);
            }
        }
        return true;
    }


    /**
     * Групировка для зависимых объектов
     * @param int $i
     */
    protected function setLevel($i)
    {
        if ($this->config['line_grouping']) {
            $level = 0;
            if ($this->dependent) {
                $level = 1;
            }
            $this->sheet->setShowSummaryBelow(false);
            $this->sheet->getRowDimension($i)->setOutlineLevel($level);
            $show = !$this->config['line_grouping_show'] ? $level == 0 : true;
            $this->sheet->getRowDimension($i)->setVisible($show);
        }
    }

    /**
     * Добавляет в xls зависимые профили
     * @param array|null $owner
     */
    protected function setDependent($owner = null)
    {
        // Делам запрос в зависимый профиль
        if (!$this->dependent and !empty($this->config['dependent_profile'])) {

            // записываем старый конфиг
            $fields = $this->fields;
            $config = $this->config;

            $profile = $this->config['dependent_profile'];

            /* @var msExportUsersExcelProfile $msExportUsersExcelProfile */
            if ($msExportUsersExcelProfile = $this->modx->getObject('msExportUsersExcelProfile', array('name' => $profile))) {
                $msExportUsersExcelProfile->set('owner', $owner);
                if ($Profile = $this->msExportUsersExcel->newExportProfile($msExportUsersExcelProfile)) {
                    if ($export = $Profile->export($this->config['classExport'], true)) {
                        /* @var msExportUsersExcelPhpExcelExcelController $export */
                        $export->dependentRows($export, $msExportUsersExcelProfile, $this->sheet, $this->xls, $this->i);
                    }
                }
            }


            // Возвращаем параметры выгрузки по умолчанию для основного профиля
            $this->fields = $fields;
            $this->setDefault($config);
            $this->dependent = false;
        }
    }

    /**
     * Заполнение данных на основе зависимой таблицы
     * @param msExportUsersExcelPhpExcelExcelController $export
     * @param msExportUsersExcelProfile $msExportUsersExcelProfile
     * @param PHPExcel_Worksheet $sheet
     * @param PHPExcel $xls
     * @param int $i
     * @return array
     */
    static private function dependentRows($export, $msExportUsersExcelProfile, $sheet, $xls, $i)
    {
        $config = $msExportUsersExcelProfile->getConfig();
        $export->sheet = $sheet;
        $export->xls = $xls;
        $export->i = $i;
        $export->config = $config;

        // Записываем новые значения
        $export->setDefault($config);
        $export->setHeaders();
        $export->setData();

    }

    protected function height()
    {
        return $this->config['height'];
    }

    /**
     * Записывает значения xls
     */
    protected function setRecord($row)
    {

        $col = 0;
        $i = $this->i++;
        $this->setLevel($i);
        $this->setHeight($this->sheet, $i, $this->height());
        foreach ($row as $field => $value) {
            if ($this->offsetColump($field)) {
                $colump = $col++;
                $this->setValue($colump, $i, $value);
            }
        }


    }

    /**
     * Добавит имя для закладки
     */
    protected function setTabTitle()
    {
        $this->sheet->setTitle($this->config['tab']);
    }

    /**
     * Добавить стиль для
     */
    protected function setStyle()
    {
        if (!empty($this->config['style'])) {
            $data = $this->modx->fromJSON('[' . $this->config['style'] . ']');
            if (!is_array($data)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error style not is array");
            }
            if (count($data) > 0) {
                foreach ($data as $css) {
                    foreach ($css as $letter => $data) {
                        $this->sheet->getStyle($letter)->applyFromArray($data);
                    }
                }
            }
        }
    }


    /**
     * Устанавливает ширину колонок для форматов Excel
     * @param null|int $colump
     * @param null|string $field
     */
    protected function setWidth($colump = null, $field = null)
    {
        $wi = (int)$this->config['width'];
        if (isset($this->widths[$field]) and is_int($this->widths[$field])) {
            $tmp = (int)trim($this->widths[$field]);
            if (is_int($tmp)) {
                $wi = $tmp;
            }
        }

        $colump = $this->colump($colump);
        $this->sheet->getColumnDimension($colump)->setWidth($wi);

    }


    /**
     * Устанавливает ширину колонок для форматов Excel
     * @param null|int $colump
     * @param null|string $field
     */
    protected function setAlignmentHorizontal($colump = null, $field = null)
    {
        $alignment = (string)$this->config['alignmentHorizontal'];
        if (isset($this->alignmentHorizontals[$field]) and is_string($this->alignmentHorizontals[$field])) {
            $tmp = (string)trim($this->alignmentHorizontals[$field]);
            if (is_string($tmp)) {
                $alignment = $tmp;
            }
        }

        $colump = $this->colump($colump);
        switch ($alignment) {
            case 'left':
            case 'right':
            case 'center':
            case 'justify':
                $this->sheet->getStyle($colump)
                    ->getAlignment()
                    ->setHorizontal($alignment);
                break;
            case '':
                break;
            default:
                $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error invalid value :" . $alignment);
                break;
        }

    }

    /**
     * Устанавливает ширину колонок для форматов Excel
     * @param null|int $colump
     * @param null|string $field
     */
    protected function setAlignmentVertical($colump = null, $field = null)
    {
        $alignment = (string)$this->config['alignmentVertical'];
        if (isset($this->alignmentVerticals[$field]) and is_string($this->alignmentVerticals[$field])) {
            $tmp = (string)trim($this->alignmentVerticals[$field]);
            if (is_string($tmp)) {
                $alignment = $tmp;
            }
        }


        $colump = $this->colump($colump);
        switch ($alignment) {
            case 'top':
            case 'bottom':
            case 'center':
            case 'distributed':
            case 'justify':

                $this->sheet->getStyle($colump)->getAlignment()->setVertical($alignment);
                break;
            case '':
                break;
            default:
                $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error invalid value :" . $alignment);
                break;
        }

    }

    protected function colump($colump)
    {
        return PHPExcel_Cell::stringFromColumnIndex($colump);
    }


    /**
     * Пропускаемы колонки
     * @param string $field
     * @return bool
     */
    protected function offsetColump($field)
    {
        $colump = $this->config['hide_colump'];
        if (!empty($colump)) {
            if (!is_array($colump)) {
                $fields = explode(',', $colump);
                $this->config['hide_colump'] = $fields;
            } else {
                $fields = $colump;
            }

            if (in_array($field, $fields)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Устанавливает ширину колонок для форматов Excel
     * @return boolean
     */
    protected function setWidths()
    {
        if ($this->widths) {
            $colump = 0;
            foreach ($this->fields as $field => $head) {
                if ($this->offsetColump($field)) {
                    $this->setWidth($colump, $field);
                    $colump++;
                }
            }
        }
        return true;
    }


    /**
     * Устанавливает ширину колонок для форматов Excel
     * @return boolean
     */
    protected function setAlignmentVerticals()
    {
        if ($this->alignmentVerticals) {
            $colump = 0;
            foreach ($this->fields as $field => $head) {
                if ($this->offsetColump($field)) {
                    $this->setAlignmentVertical($colump, $field);
                    $colump++;
                }
            }
        }
        return true;
    }


    /**
     * Устанавливает ширину колонок для форматов Excel
     * @return boolean
     */
    protected function setAlignmentHorizontals()
    {
        if ($this->alignmentHorizontals) {
            $colump = 0;
            foreach ($this->fields as $field => $head) {
                if ($this->offsetColump($field)) {
                    $this->setAlignmentHorizontal($colump, $field);
                    $colump++;
                }
            }
        }
        return true;
    }

    /**
     * Set value
     * @param int $colump column letter
     * @param string $row_id line colump
     * @param string $value value
     */
    protected function setValue($colump, $row_id, $value)
    {
        if (is_int($colump) and is_int($row_id)) {
            $colump = PHPExcel_Cell::stringFromColumnIndex($colump);
            $this->sheet->setCellValue($colump . $row_id, $value);
        }
    }
}