<?php

interface msExportOrdersExcelProfileHandlerInterface
{
    /**
     * Метож для добавления в процессоры
     * @param array $config
     */
    public function newHandler($config = array(), $fields = array(), $widths = array(), $handlers = array(), $alignments = array());

    /**
     * Общая функция для экспорта выбранного профиля без модификаций данных
     * @param  string|null $classExport
     * @return boolean|msExportOrdersExcelPHPExcelDefaultController
     */
    public function export($classExport = null, $dependent = false);


    /**
     * Записываем заголовки полей
     * @return array|null;
     */
    public function getHeaders();

    /**
     * Устанавливает выравнивание полей по верикали
     * @return array|null;
     */
    public function getAlignmentVerticals();

    /**
     * Устанавливает выравнивание полей по горизонтили
     * @return array|null;
     */
    public function getAlignmentHorizontals();

    /**
     * Записываем заголовки полей
     * @return array|null;
     */
    public function getWidths();

    /**
     * Записываем заголовки полей
     * @return array|null;
     */
    public function getFields();

    /**
     * Вернет ссылку на скачивание файла
     * @return array|null|boolean;
     */
    public function getData();


}

class msExportOrdersExcelProfileHandler implements msExportOrdersExcelProfileHandlerInterface
{

    /** @var modX $modx */
    public $modx;

    /** @var msExportOrdersExcel $msExportOrdersExcel */
    public $msExportOrdersExcel;


    /* @var array|null $config */
    public $config = null;


    /* @var array|null $fields */
    protected $fields = null;
    /* @var array|null $headers */
    protected $headers = null;
    /* @var array|null $widths */
    protected $widths = null;
    /* @var array|null $alignmentVerticals */
    protected $alignmentVerticals = null;
    /* @var array|null $alignmentHorizontals */
    protected $alignmentHorizontals = null;
    /* @var array|null $handlers */
    protected $handlers = null;
    /* @var array|null $data */
    protected $data = null;


    /**
     * @param msExportOrdersExcel $msExportOrdersExcel
     * @param msExportOrdersExcelProfile $msExportOrdersExcelProfile
     */
    function __construct(msExportOrdersExcel $msExportOrdersExcel)
    {
        $this->modx = &$msExportOrdersExcel->modx;
        $this->msExportOrdersExcel = &$msExportOrdersExcel;
        $this->setDefault();
    }

    /**
     * @param array $config
     */
    protected function setDefault($config = array())
    {
        $this->config = array(
            'processor' => $this->modx->getOption('msexportordersexcel_processor', null, 'core/components/msexportordersexcel/processors/mgr/export/default'),
            'limit' => $this->modx->getOption('msexportordersexcel_limit', null, 5000),
            'start' => $this->modx->getOption('msexportordersexcel_start', null, 0),
            'sort' => $this->modx->getOption('msexportordersexcel_sort', null, 'id'),
            'dir' => $this->modx->getOption('msexportordersexcel_dir', null, 'ASC'),
            'select' => $this->modx->getOption('msexportordersexcel_select', null, ''),
            'leftjoin' => $this->modx->getOption('msexportordersexcel_leftjoin', null, ''),
            'innerjoin' => $this->modx->getOption('msexportordersexcel_innerjoin', null, ''),
            'groupby' => $this->modx->getOption('msexportordersexcel_groupby', null, ''),
            'having' => $this->modx->getOption('msexportordersexcel_having', null, ''),
            'filename' => $this->modx->getOption('msexportordersexcel_filename', null, 'export-%d.%m.%Y %T'),
            'tab' => $this->modx->getOption('msexportordersexcel_tab', null, 'export'),
            'source' => $this->modx->getOption('msexportordersexcel_source_default', null, 0),
            'path' => $this->modx->getOption('msexportordersexcel_path_default', null, ''),
            'classExport' => $this->modx->getOption('msexportordersexcel_classExport', null, 'xls'),
            'download' => $this->modx->getOption('msexportordersexcel_download', null, true),
            'remove' => $this->modx->getOption('msexportordersexcel_remove', null, true),

            // view content
            'width' => $this->modx->getOption('msexportordersexcel_width', null, 20),
            'json_process' => $this->modx->getOption('msexportordersexcel_json_process', null, false),
            'head_process' => $this->modx->getOption('msexportordersexcel_head_process', null, true),
            'head_color' => $this->modx->getOption('msexportordersexcel_head_color', null, 'EEEEEE'),
            'height' => $this->modx->getOption('msexportordersexcel_height', null, 20),
            'style' => $this->modx->getOption('msexportordersexcel_style', null, ''),
            'delimiter' => $this->modx->getOption('msexportordersexcel_delimiter', null, ';'),
            'date_format' => $this->modx->getOption('msexportordersexcel_date_format', null, 'd.m.Y H:i:s'),
            'date_process' => $this->modx->getOption('msexportordersexcel_date_process', null, true),
            'profile' => null,

        );
        if (is_array($config) and count($config) > 0) {
            $this->config = array_merge($this->config, $config);
        }
    }

    /**
     * Метож для добавления в процессоры
     * @param array $config
     * @return null|msExportOrdersExcelProfileHandler;
     */
    public function newHandler($data = array(), $config = array(), $fields = array(), $widths = array(), $handlers = array(), $alignmentVerticals = array(), $alignmentVerticalHorizontals = array())
    {
        $this->data = $data;
        $this->fields = $fields;
        $this->widths = $widths;
        $this->alignmentVerticals = $alignmentVerticals;
        $this->alignmentHorizontals = $alignmentVerticalHorizontals;
        $this->handlers = $handlers;
        $this->setDefault($config);
        return $this;
    }

    /**
     * Общая функция для экспорта выбранного профиля без модификаций данных
     * @param  string|null $classExport
     * @return boolean|msExportOrdersExcelPHPExcelDefaultController
     */
    public function export($classExport = null, $dependent = false)
    {
        if ($export = $this->msExportOrdersExcel->newExport($classExport, $this->config, $dependent)) {
            if (!$dependent) {
                $export->fromAlignmentVerticals($this->getAlignmentVerticals());
                $export->fromAlignmentHorizontals($this->getAlignmentHorizontals());
                $export->fromWidths($this->getWidths());
            }
            $export->dependent = $dependent;
            $export->fromFields($this->getFields());
            $export->fromData($this->getData());
            return $export;
        }
        return false;
    }

    public function toArray()
    {
        return array(
            'fields' => $this->getFields(),
            'headers' => $this->getHeaders(),
            'handlers' => $this->handlers,
            'widths' => $this->getWidths(),
            'alignmentVerticals' => $this->getAlignmentVerticals(),
            'alignmentHorizontals' => $this->getAlignmentHorizontals(),
            'data' => $this->getData(),
        );
    }

    /**
     * Вернет конфиг
     * @return array|null;
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Записываем заголовки полей
     * @return array|null;
     */
    public function getHeaders()
    {
        $headers = null;
        if ($this->fields) {
            foreach ($this->fields as $field => $name) {
                if (!is_string($name) || empty($name)) {
                    $name = '';
                }
                $headers[$field] = $name;
            }
        }
        return $headers;
    }


    /**
     * Записываем заголовки полей
     * @return array|null;
     */
    public function getWidths()
    {
        $widths = null;
        if ($this->widths) {
            foreach ($this->widths as $field => $w) {
                if (!is_int($w) || empty($w)) {
                    $w = 20;
                }
                $widths[$field] = $w;
            }
        }
        return $widths;
    }

    /**
     * Записываем заголовки полей
     * @return array|null;
     */
    public function getAlignmentVerticals()
    {
        $alignments = null;
        if ($this->alignmentVerticals) {
            foreach ($this->alignmentVerticals as $field => $w) {
                if (!is_string($w) || empty($w)) {
                    $w = '';
                }
                $alignments[$field] = $w;
            }
        }
        return $alignments;
    }

    /**
     * Записываем заголовки полей
     * @return array|null;
     */
    public function getAlignmentHorizontals()
    {
        $alignments = null;
        if ($this->alignmentHorizontals) {
            foreach ($this->alignmentHorizontals as $field => $w) {
                if (!is_string($w) || empty($w)) {
                    $w = '';
                }
                $alignments[$field] = $w;
            }
        }
        return $alignments;
    }

    /**
     * Записываем заголовки полей
     * @return array|null;
     */
    public function getFields()
    {
        $fields = null;
        if ($this->fields) {
            foreach ($this->fields as $field => $name) {
                if (!is_string($name) || empty($name)) {
                    $name = '';
                }
                $fields[$field] = $name;
            }
        }
        return $fields;
    }

    /**
     * Вернет ссылку на скачивание файла
     * @return array|null|boolean;
     */
    public function getData()
    {
        return $this->msExportOrdersExcel->parserContent($this->data, $this->fields, $this->config, $this->handlers);
    }

}