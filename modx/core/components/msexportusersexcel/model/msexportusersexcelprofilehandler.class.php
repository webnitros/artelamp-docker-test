<?php

interface msExportUsersExcelProfileHandlerInterface
{
    /**
     * Метож для добавления в процессоры
     * @param array $config
     */
    public function newHandler($config = array(), $fields = array(), $widths = array(), $handlers = array(), $alignments = array());

    /**
     * Общая функция для экспорта выбранного профиля без модификаций данных
     * @param  string|null $classExport
     * @return boolean|msExportUsersExcelPHPExcelDefaultController
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

class msExportUsersExcelProfileHandler implements msExportUsersExcelProfileHandlerInterface
{

    /** @var modX $modx */
    public $modx;

    /** @var msExportUsersExcel $msExportUsersExcel */
    public $msExportUsersExcel;


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
     * @param msExportUsersExcel $msExportUsersExcel
     * @param msExportUsersExcelProfile $msExportUsersExcelProfile
     */
    function __construct(msExportUsersExcel $msExportUsersExcel)
    {
        $this->modx = &$msExportUsersExcel->modx;
        $this->msExportUsersExcel = &$msExportUsersExcel;
        $this->setDefault();
    }

    /**
     * @param array $config
     */
    protected function setDefault($config = array())
    {
        $this->config = array(
            'processor' => $this->modx->getOption('msexportusersexcel_processor', null, 'core/components/msexportusersexcel/processors/mgr/export/default'),
            'limit' => $this->modx->getOption('msexportusersexcel_limit', null, 5000),
            'start' => $this->modx->getOption('msexportusersexcel_start', null, 0),
            'sort' => $this->modx->getOption('msexportusersexcel_sort', null, 'id'),
            'dir' => $this->modx->getOption('msexportusersexcel_dir', null, 'ASC'),
            'select' => $this->modx->getOption('msexportusersexcel_select', null, ''),
            'leftjoin' => $this->modx->getOption('msexportusersexcel_leftjoin', null, ''),
            'innerjoin' => $this->modx->getOption('msexportusersexcel_innerjoin', null, ''),
            'groupby' => $this->modx->getOption('msexportusersexcel_groupby', null, ''),
            'having' => $this->modx->getOption('msexportusersexcel_having', null, ''),
            'filename' => $this->modx->getOption('msexportusersexcel_filename', null, 'export-%d.%m.%Y %T'),
            'tab' => $this->modx->getOption('msexportusersexcel_tab', null, 'export'),
            'source' => $this->modx->getOption('msexportusersexcel_source_default', null, 0),
            'path' => $this->modx->getOption('msexportusersexcel_path_default', null, ''),
            'classExport' => $this->modx->getOption('msexportusersexcel_classExport', null, 'xls'),
            'download' => $this->modx->getOption('msexportusersexcel_download', null, true),
            'remove' => $this->modx->getOption('msexportusersexcel_remove', null, true),

            // view content
            'width' => $this->modx->getOption('msexportusersexcel_width', null, 20),
            'json_process' => $this->modx->getOption('msexportusersexcel_json_process', null, false),
            'head_process' => $this->modx->getOption('msexportusersexcel_head_process', null, true),
            'head_color' => $this->modx->getOption('msexportusersexcel_head_color', null, 'EEEEEE'),
            'height' => $this->modx->getOption('msexportusersexcel_height', null, 20),
            'style' => $this->modx->getOption('msexportusersexcel_style', null, ''),
            'delimiter' => $this->modx->getOption('msexportusersexcel_delimiter', null, ';'),
            'date_format' => $this->modx->getOption('msexportusersexcel_date_format', null, 'd.m.Y H:i:s'),
            'date_process' => $this->modx->getOption('msexportusersexcel_date_process', null, true),
            'profile' => null,

        );
        if (is_array($config) and count($config) > 0) {
            $this->config = array_merge($this->config, $config);
        }
    }

    /**
     * Метож для добавления в процессоры
     * @param array $config
     * @return null|msExportUsersExcelProfileHandler;
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
     * @return boolean|msExportUsersExcelPHPExcelDefaultController
     */
    public function export($classExport = null, $dependent = false)
    {
        if ($export = $this->msExportUsersExcel->newExport($classExport, $this->config, $dependent)) {
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
        return $this->msExportUsersExcel->parserContent($this->data, $this->fields, $this->config, $this->handlers);
    }

}