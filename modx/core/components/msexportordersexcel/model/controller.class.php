<?php
if (!class_exists('BinaryFileResponse')) {
    require_once(dirname(dirname(__FILE__)) . '/lib/vendor/autoload.php');
}

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

interface msExportOrdersExcelPHPExcelInterface
{
    /**
     * Initializes cart to context
     * Here you can load custom javascript or styles
     *
     * @return boolean
     */
    public function initialize();


    /**
     * Сохранит файл
     * @return boolean
     */
    public function save();


    /**
     * Записывае массив c полями
     * @param array|null $fields массив с полями
     * @return boolean
     */
    public function fromFields($fields = null);

    /**
     * Записывае массив для экспорта
     * @param array|null $data массив для выгрузки данных
     * @return boolean
     */
    public function fromData($data = null);

    /**
     * Устанавливает ширину колонок для форматов Excel
     * @param array|null|string $widths
     * @return boolean
     */
    public function fromWidths($widths = null);

    /**
     * Устанавливает ширину колонок для форматов Excel
     * @param array|null|string $alignmentHorizontals
     * @return boolean
     */
    public function fromAlignmentHorizontals($alignmentHorizontals = null);

    /**
     * Устанавливает ширину колонок для форматов Excel
     * @param array|null|string $alignmentVerticals
     * @return boolean
     */
    public function fromAlignmentVerticals($alignmentVerticals = null);

}

abstract class msExportOrdersExcelPHPExcelDefaultController implements msExportOrdersExcelPHPExcelInterface
{
    /* @var \modX $modx */
    protected $modx;

    /* @var \msExportOrdersExcel $msExportOrdersExcel */
    protected $msExportOrdersExcel;

    /* @var array|null $config */
    protected $config = null;

    /* @var string $class класс для экспорта */
    protected $class = null;

    /* @var string|null $extension расширение файла для экспорта */
    protected $extension = null;

    /* @var null|Response $response */
    protected $response = null;

    /* @var array|PHPExcel_Writer_IWriter $objWriter */
    protected $objWriter = null;

    /* @var array|null $data */
    protected $data = null;

    /* @var array|null $alignmentVerticals */
    protected $alignmentVerticals = null;

    /* @var array|null $alignmentHorizontals */
    protected $alignmentHorizontals = null;

    /* @var array|null $widths */
    protected $widths = null;

    /* @var array|null $fields */
    protected $fields = null;

    /* @var msExportOrdersExcelFile|null $file */
    protected $file = null;

    /* @var boolean $dependent */
    public $dependent = false;

    /**
     * msExportOrdersExcelPHPExcelDefaultController constructor.
     * @param msExportOrdersExcel $msExportOrdersExcel
     * @param array $config
     */
    public function __construct(msExportOrdersExcel $msExportOrdersExcel, array $config = array())
    {
        $this->msExportOrdersExcel = &$msExportOrdersExcel;
        $this->modx = &$msExportOrdersExcel->modx;
        $this->setDefault($config);
        $topics = $this->getLanguageTopics();
        foreach ($topics as $topic) {
            $this->modx->lexicon->load($topic);
        }
    }

    /**
     * @param array $config
     */
    protected function setDefault($config = array())
    {
        $this->config = array(
            'filename' => $this->modx->getOption('msexportordersexcel_filename', null, 'export-%d.%m.%Y %T'),
            'tab' => $this->modx->getOption('msexportordersexcel_tab', null, 'export'),
            'source' => $this->modx->getOption('msexportordersexcel_source_default', null, 0),
            'path' => $this->modx->getOption('msexportordersexcel_path_default', null, ''),
            'classExport' => $this->modx->getOption('msexportordersexcel_classExport', null, 'xls'),
            'download' => $this->modx->getOption('msexportordersexcel_download', null, true),
            'remove' => $this->modx->getOption('msexportordersexcel_remove', null, true),

            // view content
            'width' => $this->modx->getOption('msexportordersexcel_width', null, 20),
            'alignmentHorizontal' => $this->modx->getOption('msexportordersexcel_alignment_horizontal', null, ''),
            'alignmentVertical' => $this->modx->getOption('msexportordersexcel_alignment_vertical', null, ''),
            'json_process' => $this->modx->getOption('msexportordersexcel_json_process', null, false),
            'head_process' => $this->modx->getOption('msexportordersexcel_head_process', null, true),
            'head_all' => $this->modx->getOption('msexportordersexcel_head_all', null, false),
            'head_freezepane' => $this->modx->getOption('msexportordersexcel_head_freezepane', null, false),
            'line_grouping' => $this->modx->getOption('msexportordersexcel_line_grouping', null, true),
            'line_grouping_show' => $this->modx->getOption('msexportordersexcel_line_grouping_show', null, false),
            'head_color' => $this->modx->getOption('msexportordersexcel_head_color', null, 'EEEEEE'),
            'height' => $this->modx->getOption('msexportordersexcel_height', null, 20),
            'style' => $this->modx->getOption('msexportordersexcel_style', null, ''),
            'delimiter' => $this->modx->getOption('msexportordersexcel_delimiter', null, ';'),
            'date_format' => $this->modx->getOption('msexportordersexcel_date_format', null, 'd.m.Y H:i:s'),
            'date_process' => $this->modx->getOption('msexportordersexcel_date_process', null, true),
            'profile' => null,
            'dependent_profile' => null,
            'owner' => null,
            'hide_colump' => '',
        );

        if (is_array($config) and count($config) > 0) {
            $this->config = array_merge($this->config, $config);
        }
    }

    /**
     * @param array $config
     */
    public function newExport($config = array())
    {
        $this->file = null;
        $this->data = null;
        $this->widths = null;
        $this->alignmentVerticals = null;
        $this->alignmentHorizontals = null;
        $this->objWriter = null;
        $this->fields = null;
        $this->setDefault($config);
    }

    /**
     * @return boolean
     */
    public function headAll()
    {
        return $this->config['head_all'];
    }
    /**
     * @return array $config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return array $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    protected function getLanguageTopics()
    {
        return array('msexportordersexcel:handler');
    }

    /**
     * @return bool
     */
    public function initialize()
    {
        if (!$this->response instanceof Response) {
            if (!class_exists('Response')) {
                require_once(dirname(dirname(__FILE__)) . '/lib/classes/ResponseTrait.php');
                require_once(dirname(dirname(__FILE__)) . '/lib/classes/Response.php');
            }
            $this->response = new Response();
            if (!($this->response instanceof Response)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not initialize msExportOrdersExcel controller handler class: "Response"');
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $field
     * @param mixed $default
     * @return mixed|null
     */
    public function getOption($field, $default = null)
    {
        return isset($this->config[$field]) ? $this->config[$field] : $default;
    }


    /**
     * @param array $content массив с контентом
     * @return bool
     */
    public function save()
    {
        $response = $this->validateFields();
        if ($response !== true) {
            return $response;
        }

        $response = $this->beforeExport();
        if ($response !== true) {
            return $response;
        }


        $response = $this->objWriter();
        if ($response !== true) {
            return $response;
        }

        if (!$this->process()) {
            return false;
        }
        return true;
    }

    /**
     * @param string|null $varable
     * @param null|array $array
     * @return boolean
     */
    private function setArray($varable = null, $array = null)
    {
        if ((is_array($array) and count($array) > 0) and is_string($varable)) {
            $this->{$varable} = $array;
            return true;
        }
        return null;
    }

    /**
     * Устанавливает выравнивание колонки для форматов Excel
     * @param array|null|string $alignmentHorizontals
     * @return boolean
     */
    public function fromAlignmentHorizontals($alignmentHorizontals = null)
    {
    
        $data = null;
        if (!is_array($alignmentHorizontals)) {
            if (is_string($alignmentHorizontals) and !empty($alignmentHorizontals)) {
                $tmp = explode(',', $alignmentHorizontals);
                if (count($tmp) > 0) {
                    foreach ($tmp as $d) {
                        $wi = explode(':', $d);
                        if (isset($wi[0]) and isset($wi[1])) {
                            $data[$wi[0]] = (int)$wi[1];
                        } else {
                            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error delimiter field AlignmentHorizontals value: " . print_r($d, 1));
                        }
                    }
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error set AlignmentHorizontals data " . print_r($data, 1));
                }
            }
        } else {
            $data = $alignmentHorizontals;
        }
        return $this->setArray('alignmentHorizontals', $data);
    }


    /**
     * Устанавливает выравнивание колонки для форматов Excel
     * @param array|null|string $alignmentVerticals
     * @return boolean
     */
    public function fromAlignmentVerticals($alignmentVerticals = null)
    {
        $data = null;
        if (!is_array($alignmentVerticals)) {
            if (is_string($alignmentVerticals) and !empty($alignmentVerticals)) {
                $tmp = explode(',', $alignmentVerticals);
                if (count($tmp) > 0) {
                    foreach ($tmp as $d) {
                        $wi = explode(':', $d);
                        if (isset($wi[0]) and isset($wi[1])) {
                            $data[$wi[0]] = (int)$wi[1];
                        } else {
                            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error delimiter field alignmentVerticals value: " . print_r($d, 1));
                        }
                    }
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error set alignmentVerticals data " . print_r($data, 1));
                }
            }
        } else {
            $data = $alignmentVerticals;
        }
        return $this->setArray('alignmentVerticals', $data);
    }


    /**
     * Устанавливает ширину колонок для форматов Excel
     * @param array|null|string $widths
     * @return boolean
     */
    public function fromWidths($widths = null)
    {
        $data = null;
        if (!is_array($widths)) {
            if (is_string($widths) and !empty($widths)) {
                $tmp = explode(',', $widths);
                if (count($tmp) > 0) {
                    foreach ($tmp as $d) {
                        $wi = explode(':', $d);
                        if (isset($wi[0]) and isset($wi[1])) {
                            $data[$wi[0]] = (int)$wi[1];
                        } else {
                            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error delimiter field Widths value: " . print_r($d, 1));
                        }
                    }
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error set Widths data " . print_r($data, 1));
                }
            }
        } else {
            $data = $widths;
        }
        return $this->setArray('widths', $data);
    }

    /**
     * Записываем список полей
     *
     * @param array|null $fields массив с полями и наименования полей
     * @return boolean
     */
    public function fromFields($fields = null)
    {
        return $this->setArray('fields', $fields);
    }

    /**
     * Записывае массив для экспорта
     * @param array|null $data
     * @return bool
     */
    public function fromData($data = null)
    {
        $this->data = $data;
        return true;
    }


    /**
     * Валидация данных
     * - если указаны fields и data то сравнивается количество полей.
     * @return bool
     */
    protected function validateFields()
    {
        $content = $this->data;

        if (empty($content)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error content empty");
            return false;
        }

        // Возможно как вариант количество поле должно автоматически заполнять из первого массива с присвоеним заголовков из fields
        if ((is_array($content) and count($content) > 0) and $this->fields) {
            #$fields = array_keys($this->fields);
            if (count(array_diff_assoc($this->fields, $content[0])) == 0) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": The number of fields varable 'Data' in the first record does not match the array passed to fields ");
                return false;
            }
        }
        return true;
    }

    /**
     * Включит добавление заголовков для колонок в первую строку
     * @param string|null $field название переенной в конфиге
     * @param mixed $value ново значение
     * @return bool
     */
    public function setOption($field = null, $value = null)
    {
        if (isset($this->config[$field])) {
            $this->config[$field] = $value;
            return true;
        }
        return false;
    }

    /**
     * Дополнительны параметр для запросов перед экспортом данных
     * @return bool
     */
    protected function beforeExport()
    {
        return true;
    }


    /**
     * Вернет контент
     * @return string
     */
    protected function getExtension()
    {
        return strtolower($this->extension);
    }

    /**
     * Вернет контент
     * @return string
     */
    protected function getContent()
    {
        return $this->response->getContent();
    }

    /**
     * Сохранит данные в объект msExportOrdersExcelFile
     * @return boolean
     */
    protected function process()
    {
        $this->response->setContent($this);

        $content = (string)$this->getContent();

        if (empty($content)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Empty content");
            return false;
        }

        /* @var modMediaSource $Source */
        if (!$source = $this->loadSourceInitialize()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Could not found source id: {$this->config['source']}");
            return false;
        }


        if (!$filename = $this->getFilename()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not get filename ' . print_r($this->msExportOrdersExcel->profile->object->toArray(), 1));
            return false;
        }


        // Относительный путь
        $relative_path = (string)!empty($this->config['path']) ? $this->msExportOrdersExcel->postfixSlash($this->config['path']) : '';
        $relative_path_file = $relative_path . $filename;


        // Абсолютынй путь
        $absolute_path = $this->msExportOrdersExcel->postfixSlash($source->getBasePath()) . $relative_path;
        $absolute_path_file = $absolute_path . $filename;


        // Папка с временными данными
        $cachePath = $this->msExportOrdersExcel->config['cachePath'] . $filename;


        // Создание директории из относительного пути
        $source->createContainer($relative_path, '/');
        $source->errors = array();
        if ($source instanceof modFileMediaSource) {
            /** @var modFile $file */
            $file = $source->fileHandler->make($absolute_path_file);
            // Удаляем файл если он есть и создаем новый
            if (!$file->exists()) {

                $source->createObject($relative_path, $filename, $content); // Создаем файл

                #$source->removeObject($relative_path_file);
            } else {
                $source->updateObject($relative_path_file, $content); // Обновляем файл
            }

        } else {
            // Сохраняем файл через удаленные источники файлов
            if (!file_exists($cachePath)) {
                // Добоавляем файл во временную директорию
                file_put_contents($cachePath, $content);
            }
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $ext = strtolower($ext);
            $data = array(
                'type' => $this->get_mime_type($ext),
                'name' => $filename,
                'tmp_name' => $cachePath,
                'error' => '',
                'size' => filesize($cachePath),
            );

            // Отправляем файл на загрузку
            $source->uploadObjectsToContainer($relative_path, array($data));
            unlink($cachePath);
        }

        if ($source->hasErrors()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error class mediaSource " . print_r($source->getErrors(), 1));
            return false;
        }


        $file = $this->msExportOrdersExcel->getFile($absolute_path_file, $source);
        if (!$file instanceof msExportOrdersExcelFile) {
            return false;
        }
        $this->file = $file;
        $this->afterSave($absolute_path_file);
        return true;

    }

    /**
     * @param string $absolute_path_file Абсолютный путь после сохранения
     * @return bool
     */
    public function afterSave($absolute_path_file)
    {
        return true;
    }

    /**
     * Запись данных в обработчик для экспорта
     * @return mixed|msExportOrdersExcelFile
     */
    public function loadFile()
    {
        if ($this->file instanceof msExportOrdersExcelFile) {
            return $this->file;
        }
        return true;
    }


    /**
     * Запись данных в обработчик для экспорта
     * @return mixed
     */
    protected function objWriter()
    {
        return true;
    }


    /**
     *  Loads modMediaSource and initialize
     * @return modFileMediaSource|boolean
     */
    protected function loadSourceInitialize()
    {
        $source_id = $this->config['source'];

        if ($source = $this->msExportOrdersExcel->loadSourceInitialize($source_id)) {

            // Устанавливаем паметра по умолчанию
            $properties = $source->getPropertyList();
            $source->setOption('allowedFileTypes', $properties['allowedFileTypes']);
            $source->setOption('upload_files', $properties['upload_files']);
            $source->setOption('remove_files_is_download', $this->config['remove']);
            $source->setOption('profile', $this->config['profile']);
            $this->modx->lexicon->load('core:file');
            return $source;
        }
        return false;
    }


    /**
     * @param string $ext
     * @return mixed|string
     */
    private function get_mime_type($ext)
    {
        // Массив с MIME-типами
        $mimetypes = Array(
            'json' => 'application/vnd.acme.blog-v1+json',
            "csv" => "text/x-comma-separated-values",
            "xls" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        );
        // Расширение в нижний регистр
        $ext = trim(strtolower($ext));
        if ($ext != '' && isset($mimetypes[$ext])) {
            // Если есть такой MIME-тип, то вернуть его
            return $mimetypes[$ext];
        } else {
            // Иначе вернуть дефолтный MIME-тип
            return "application/force-download";
        }
    }


    /**
     * Вернет название файла
     * @return bool|mixed|string
     */
    public function getFilename()
    {
        $filename = $this->config['filename'];
        if (!$this->extension) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Could not found extension for class");
            return false;
        }

        // Получаем расширение по классу для экспорта
        // Наименование файла с расширением
        $filename = str_replace(',', '', strftime($filename)) . '.' . $this->extension;
        //$filename = rawurlencode($filename);
        return $filename;
    }


}