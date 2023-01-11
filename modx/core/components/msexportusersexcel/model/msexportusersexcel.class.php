<?php
if (!class_exists('baseException')) {
    class baseException extends Exception
    {
    }
}

class msExportUsersExcel
{
    /** @var modX $modx */
    public $modx;
    /* @var array|null $controllers */
    public $controllers = null;

    /* @var array|null $config */
    public $config = null;

    /* @var int $memory */
    private $memory;
    /* @var int $start_time */
    public $start_time;

    /* @var array|null $errors */
    protected $errors = null;

    /* @var msExportUsersExcelProfileHandler|null $profile */
    public $profile = null;

    /* @var msExportUsersExcelQueryHandler|null $query */
    public $query = null;

    /* @var array|null $handlerField */
    public $handlerField = null;
    /* @var modFileMediaSource $mediasource */
    protected $mediasource = null;

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $cachePath = MODX_CORE_PATH . 'cache/default/msexportusersexcel/';
        $corePath = MODX_CORE_PATH . 'components/msexportusersexcel/';
        $assetsUrl = MODX_ASSETS_URL . 'components/msexportusersexcel/';

        $this->config = array_merge([
            'cachePath' => $cachePath,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'controllersPath' => $corePath . 'controllers/export/',
            'customPath' => $corePath . 'custom/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'action' => $assetsUrl . 'download.php',
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'registry' => MODX_CORE_PATH . 'cache/registry/mgr/msexportusersexcel/',
        ], $config);

        $this->modx->addPackage('msexportusersexcel', $this->config['modelPath']);

    }

    /**
     * Initializes component into different contexts.
     *
     * @param array $scriptProperties Properties for initialization.
     * @return boolean;
     */
    public function initialize($scriptProperties = array())
    {
        $this->start_time = microtime(true);
        if (is_array($scriptProperties) > 0 and count($scriptProperties) > 0) {
            $this->config = array_merge($this->config, $scriptProperties);
        }


        // Create temp dir
        $cacheDir = $this->config['cachePath'];
        $cacheDirDefault = dirname($cacheDir);
        if (!file_exists($cacheDirDefault)) {
            if (!mkdir($cacheDirDefault, 0777)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not create directory ' . $cacheDirDefault);
                return false;
            }
        }

        if (!file_exists($cacheDir)) {
            if (!mkdir($cacheDir, 0777)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not create directory ' . $cacheDir);
                return false;
            }
        }


        $cacheDirExport = $cacheDir . 'export/';
        if (!file_exists($cacheDirExport)) {
            if (!mkdir($cacheDirExport, 0777)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not create directory ' . $cacheDirExport);
                return false;
            }
        }

        $this->modx->lexicon->load('msexportusersexcel:manager');


        return $this->loadService();
    }


    /**
     * Загрузка обработчика для
     * @return bool
     */
    public function loadService()
    {
        if (!class_exists('msExportUsersExcelProfileHandler')) {
            require_once $this->config['modelPath'] . 'msexportusersexcelprofilehandler.class.php';
        }

        if (!class_exists('msExportUsersExcelQueryHandler')) {
            require_once dirname(__FILE__) . '/msexportusersexcelqueryhandler.class.php';
        }


        // Custom profile class
        $profile_class = $this->modx->getOption('msexportusersexcel_profile_handler_class', null, 'msExportUsersExcelProfileHandler');
        if ($profile_class != 'msExportUsersExcelProfileHandler') {
            $this->loadCustomClasses('profile');
        }

        if (!class_exists($profile_class)) {
            $profile_class = 'msExportUsersExcelProfileHandler';
        }

        $this->profile = new $profile_class($this, $this->config);
        if (!($this->profile instanceof msExportUsersExcelProfileHandlerInterface)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not initialize msExportUsersExcel profile handler class: "' . $profile_class . '"');
            return false;
        }


        // Custom query class
        $query_class = $this->modx->getOption('msexportusersexcel_query_handler_class', null, 'msExportUsersExcelQueryHandler');
        if ($query_class != 'msExportUsersExcelQueryHandler') {
            $this->loadCustomClasses('query');
        }
        if (!class_exists($query_class)) {
            $query_class = 'msExportUsersExcelQueryHandler';
        }

        $this->query = new $query_class($this);
        if (!($this->query instanceof msExportUsersExcelQueryInterface)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not initialize msExportUsersExcel query handler class: "' . $query_class . '"');
            return false;
        }
        $this->initErrorHandler();
        return true;
    }


    /**
     * Импорт конфигов
     */
    public function importConfigs()
    {

        $lang = $this->modx->getOption('manager_language');
        $dir = $this->config['corePath'] . 'profiles/' . $lang . '/';
        if (file_exists($dir)) {

            $source = 1;
            if ($object = $this->modx->getObject('modSystemSetting', array('key' => 'msexportusersexcel_source_default'))) {
                $source = $object->get('value');
            }

            $files = scandir($dir);
            foreach ($files as $file) {
                if (preg_match('/.*?\.json$/i', $file)) {
                    $response = null;
                    $file = $dir . $file;
                    if (file_exists($file)) {
                        $json = file_get_contents($file);
                        $data = $this->modx->fromJSON($json);
                        $response = $data[0];
                        if (is_array($response)) {
                            $profile = str_ireplace('.json', '', basename($file));
                            $res = $this->importConfig($profile, $response, $source);
                            if ($res !== true) {
                                $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error import profile " . print_r($res, 1));
                            }
                        }

                    } else {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": File {$file} not found");
                    }
                }
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Import config Dir {$dir} not found");
        }
    }

    /**
     * Метод для ручной конфигурации конфига
     * @param string $profile
     * @param array $data
     * @param int $source
     * @return array|bool
     */
    private function importConfig($profile, $data, $source)
    {
        $fields = $data['fields'];
        unset($data['fields']);

        /* @var msExportUsersExcelProfile $object */
        if ($object = $this->modx->getObject('msExportUsersExcelProfile', array('name' => $profile))) {
            $object->remove();
        }

        // Создаем новый конфиг
        $object = $this->modx->newObject('msExportUsersExcelProfile');
        $object->fromArray(array_merge($data, array(
            'name' => $profile,
            'source' => $source
        )));

        foreach ($fields as $f) {
            /* @var msExportUsersExcelProfileFields $field */
            $field = $this->modx->newObject('msExportUsersExcelProfileFields');
            $field->fromArray($f, '', true);
            $object->addMany($field, 'Fields');
        }
        if (!$object->save()) {
            /* @var xPDOValidator $validator */
            $validator = $object->getValidator();
            if ($validator->validate() == false) {
                return $validator->getMessages();
            }
            return false;
        }
        return true;
    }


    /**
     * @return boolean
     */
    private function initErrorHandler()
    {
        // This storage is freed on error (case of allowed memory exhausted)
        $this->memory = str_repeat('*', 1024 * 1024);
        register_shutdown_function(function () {
            $this->memory = null;
            if ((!is_null($err = error_get_last())) && (!in_array($err['type'], array(E_NOTICE, E_WARNING)))) {
                $this->modx->log(1, $this->modx->lexicon('msexportusersexcel_console_error_handler', array_merge($err, array(
                    'exec_time' => microtime(true) - $this->start_time
                ))));
                return false;
            }
        });
        return true;
    }


    /**
     * Method loads custom controllers
     *
     * @var string $name Directory for load controllers
     * @param array $scriptProperties
     *
     * @return void
     */
    public function loadController($name, $scriptProperties = array())
    {
        if (!class_exists('msExportUsersExcelPHPExcelDefaultController')) {
            require_once dirname(__FILE__) . '/controller.class.php';
        }


        $name = strtolower(trim($name));
        $file = $this->config['controllersPath'] . $name . '/' . $name . '.class.php';

        if (!file_exists($file)) {
            $file = $this->config['controllersPath'] . $name . '.class.php';
        }

        if (file_exists($file)) {

            /** @noinspection PhpIncludeInspection */
            $class = include_once($file);
            if (!class_exists($class)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[msExportUsersExcel] Wrong controller at class "' . $class . '" ' . $file);
            } /* @var msExportUsersExcelPHPExcelDefaultController $controller */
            else if ($controller = new $class($this, array_merge($this->config, $scriptProperties))) {
                if ($controller instanceof msExportUsersExcelPHPExcelDefaultController && $controller->initialize()) {
                    $this->controllers[strtolower($name)] = $controller;
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[msExportUsersExcel] Could not load controller ' . $file);
                }
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[msExportUsersExcel] Could not find controller ' . $file);
        }
    }

    /**
     * Method loads custom classes from specified directory
     *
     * @var string $dir Directory for load classes
     *
     * @return void
     */
    public function loadCustomClasses($dir)
    {
        $files = scandir($this->config['customPath'] . $dir);
        foreach ($files as $file) {
            if (preg_match('/.*?\.class\.php$/i', $file)) {
                include_once($this->config['customPath'] . $dir . '/' . $file);
            }
        }
    }

    /**
     * Запись ошибки
     * @param string $error
     */
    public function setError($error, $setLog = true)
    {
        if ($setLog) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $error);
        }
        if (empty($error)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Set value error is empty $error');
        }
        $this->errors[] = is_array($error) ? implode(',', $error) : $error;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return $this->errors ? true : false;
    }

    /**
     * @param boolean $json
     * @return array|null|string
     */
    public function getErrors($json = false)
    {
        if ($json) {
            return $this->errors ? $this->errors : null;
        }
        return $this->errors;
    }

    /**
     * Вернет список классов для экспорта данных
     * @return array|null
     */
    public function getClassExport()
    {
        $list = null;
        $dir = $this->config['controllersPath'];
        $files = scandir($dir);
        foreach ($files as $file) {
            if (preg_match('/.*?\.class\.php$/i', $file)) {
                $name = str_ireplace('.class.php', '', $file);
                $list[] = $name;
            }
        }
        return $list;
    }

    /**
     * Вегрузка даных
     * @param null|string $name класс для экспорта данных xlsx|xls|CSV|JSON
     * @param array $scriptProperties optional можно выгружать конфиг профиля
     * @return bool|msExportUsersExcelPHPExcelDefaultController|msExportUsersExcelPHPExcelJSONController
     */
    public function newExport($name = null, $scriptProperties = array(), $dependent = false)
    {
        $name = strtolower(trim($name));
        if (!isset($this->controllers[$name])) {
            $this->loadController($name, $scriptProperties);
        }

        if (isset($this->controllers[$name])) {
            /* @var msExportUsersExcelPHPExcelDefaultController $handler */
            $handler = $this->controllers[$name];
            if (!$dependent) {
                $handler->newExport($scriptProperties);
            }
            return $handler;
        }
        return false;
    }


    /**
     * Вегрузка даных по профилю
     * @param null|string|int|msExportUsersExcelProfile $profile id профиля для экпорта или можно объект профиля
     * @param array $scriptProperties optional
     * @return bool|msExportUsersExcelProfileHandler
     */
    public function newExportProfile($profile = null, $scriptProperties = array())
    {
        if (!$profile) {
            return false;
        }

        if (!is_object($profile)) {
            $criteria = array(
                'name' => $profile,
            );
            if (is_int($profile)) {
                $criteria = $profile;
            }

            /* @var msExportUsersExcelProfile $msExportUsersExcelProfile */
            $profile = $this->modx->getObject('msExportUsersExcelProfile', $criteria);
        }

        // Если объект получен то делаем
        if ($profile instanceof msExportUsersExcelProfile) {
            $config = $profile->getConfig();
            if (is_array($scriptProperties) and count($scriptProperties) > 0) {
                $config = array_merge($config, $scriptProperties);
            }
            return $this->profile->newHandler($profile->getData(), $config, $profile->getFields(), $profile->getWidths(), $profile->getHandlers(), $profile->getAlignmentVerticals(), $profile->getAlignmentHorizontals());
        }
        return false;
    }


    /**
     * @param string $action
     * @param array $data
     * @return modProcessorResponse;
     */
    public function runProcessor($action, $data = array())
    {
        /* @var modProcessorResponse $response */
        $response = $this->modx->runProcessor($action, $data, array(
            'processors_path' => MODX_CORE_PATH . 'components/msexportusersexcel/processors/'
        ));
        return $response;
    }


    /**
     * @param msExportUsersExcelProfile $profile
     * @param string $classKey
     * @param string $prefix
     * @param boolean $addHandlerField - добавлять обработчики для полей
     * @return boolean
     */
    static public function importFields(msExportUsersExcelProfile $profile, $classKey, $prefix = '', $addHandlerField = false)
    {
        $meta = $profile->xpdo->getFieldMeta($classKey);
        if (count($meta) == 0) {
            return false;
        }

        $rank = $profile->xpdo->getCount('msExportUsersExcelProfileFields', array('profile_id' => $profile->id));
        foreach ($meta as $field => $item) {
            /* @var msExportUsersExcelProfileFields $object */
            $object = $profile->xpdo->newObject('msExportUsersExcelProfileFields');

            if (!$profile->isNew()) {
                $object->set('profile_id', $profile->id);
            }

            if (!empty($prefix)) {
                $field = $prefix . '.' . $field;
            }

            $object->set('field', $field);
            $object->set('active', 1);
            $object->set('rank', $rank++);

            // handler
            if ($addHandlerField) {
                $handler = '';
                switch ($item['phptype']) {
                    case 'boolean':
                        $handler = 'boolean';
                        break;
                    case 'timestamp':
                        $handler = 'date';
                        break;
                }

                $object->set('handler', $handler);
            }

            // offset
            switch ($item['phptype']) {
                case 'array':
                case 'json':
                    break;
                default:
                    if (!$profile->isNew()) {
                        if (!$count = (boolean)$profile->xpdo->getCount($classKey, array(
                            'profile_id' => $object->get('profile_id'),
                            'field' => $object->get('field'),
                        ))) {
                            $object->save();
                        }
                    } else {
                        $profile->addMany($object, 'Fields');
                    }
                    break;
            }
        }
        return true;
    }


    /**
     * Вернет конфиг для выгрузки в Excel
     * @param string $path
     * @param modFileMediaSource $source
     * @return msExportUsersExcelFile|boolean
     */
    public function getFile($path, modFileMediaSource $source)
    {
        $path = trim($path);
        if (!class_exists('msExportUsersExcelFile')) {
            require_once(dirname(dirname(__FILE__)) . '/lib/classes/File.php');
        }

        if (empty($path)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Path empty");
            return false;
        }

        $modFile = $source->fileHandler->make($path);
        if (!$modFile instanceof modFile) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not found file path: "' . $path . '" source: ' . $source->get('name'));
            return false;
        }
        return new msExportUsersExcelFile($source, $modFile);
    }

    /**
     * Ensures that the passed path has a / at the end
     *
     * @param string $path
     * @return string The postfixed path
     */
    public function postfixSlash($path)
    {
        $len = strlen($path);
        if (substr($path, $len - 1, $len) != '/') {
            $path .= '/';
        }
        return $path;
    }


    /**
     *  Loads modMediaSource and initialize
     * @param int $source_id id истчника файлов
     * @return modFileMediaSource|boolean
     */
    public function loadSourceInitialize($source_id = null)
    {
        if ($source = $this->loadSource($source_id)) {
            if ($source->initialize()) {
                // Устанавливаем паметра по умолчанию
                $properties = $source->getPropertyList();
                $source->setOption('allowedFileTypes', $properties['allowedFileTypes']);
                $source->setOption('upload_files', $properties['upload_files']);
                $this->modx->lexicon->load('core:file');
                return $source;
            }
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not initialize source ' . print_r($source->toArray(), 1));
        }
        return false;
    }

    /**
     *  Loads modMediaSource
     *
     * @param int $source id истчника файлов
     * @return modFileMediaSource|null
     */
    public function loadSource($source = null)
    {
        if ($source) {
            $source = trim($source);
            $source = (int)$source;
            if (!is_object($this->mediasource) || !($this->mediasource instanceof modFileMediaSource)) {
                if (!$this->mediasource = $this->modx->getObject('sources.modFileMediaSource', $source)) {
                    $this->mediasource = false;
                }
            }
        }
        return $this->mediasource;
    }


    /**
     * Sanitize the specified path
     *
     * @param string $path The path to clean
     * @return string The sanitized path
     */
    public function sanitizePath($path)
    {
        return preg_replace(array("/\.*[\/|\\\]/i", "/[\/|\\\]+/i"), array('/', '/'), $path);
    }


    /**
     * Парсер контента для преобразование в читабельный вид
     * @param array $data массив с контентом
     * @param array $fields список файлов с заголовками
     * @param array $config конфигурация профиля
     * @param array $handlers обработчики полей
     * @return bool
     */
    public function parserContent($data = null, $fields = array(), $config = array(), $handlers = array())
    {
        if (is_array($data) and !empty($data)) {
            if ($data = $this->ProcedureWritingFields($data, $fields)) {
                if ($data = $this->FieldProcessingProcedure($this, $data, $config, $handlers)) {
                    return $data;
                }
            }
        }
        return null;
    }


    /**
     * Записываем новые поля
     * @param array $data
     * @param array|null $fields
     * @return boolean|array
     */
    static private function ProcedureWritingFields($data = array(), $fields = null)
    {
        if (is_array($data) and count($data) > 0) {
            if ($fields) {
                $newData = array();
                foreach ($data as $row) {
                    $tmp = array();
                    foreach ($fields as $name => $head) {
                        if (!empty($name) and is_string($name)) {
                            $tmp[$name] = isset($row[$name]) ? trim($row[$name]) : '';
                        } else {
                            $tmp[$name] = '';
                        }
                    }
                    $newData[] = $tmp;
                }

                return $newData;
            } else {
                return $data;
            }
        }
        return null;
    }

    /**
     * Обработка полей назначенными обработчиками
     * @param msExportUsersExcel $export
     * @param array $data ;
     * @param array $config ;
     * @param array $handlers ;
     * @return boolean|array
     */
    static private function FieldProcessingProcedure(msExportUsersExcel $export, $data = array(), $config = array(), $handlers = null)
    {
        if (!is_array($handlers) or empty($handlers)) {
            return $data;
        }

        if (!is_array($data)) {
            $export->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error could data empty");
            return false;
        }
        foreach ($data as $k => $row) {
            if (is_int($k)) {
                foreach ($row as $field => $value) {
                    if (!empty($handlers[$field])) {
                        $name = $handlers[$field];
                        if ($classHandler = $export->loadHandlerField($name, $config)) {
                            $data[$k][$field] = $classHandler->processValue($field, $value);
                        } else {
                            $export->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error could not found handler Field {$name}");
                            return false;
                        }
                    }
                }
            }
        }

        return $data;
    }


    /**
     * Вернет обработчик
     * @param string $name имя обработчика
     * @param array $config
     * @return msExportUsersExcelHandlerFieldsHandler|boolean
     */
    public function loadHandlerField($name, $config = array())
    {
        if (!class_exists('msExportUsersExcelHandlerFieldsHandler')) {
            include_once dirname(__FILE__) . '/msexportusersexcelfieldshandler.class.php';
        }
        if (!isset($this->handlerField[$name])) {
            $handler = ucfirst($name);
            $handler_class = 'msExportUsersExcelHandlerFields' . $handler . 'Handler';

            // Custom order class
            $this->loadCustomClasses('handlerfields');

            if (!class_exists($handler_class)) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Handler could not found. Name class {$handler_class}");
                return false;
            }

            $this->handlerField[$name] = new $handler_class($this, $config);
            return $this->handlerField[$name];
        } else {
            return $this->handlerField[$name];
        }
    }


    /**
     * Регистрация кнопок на элементах
     * @param string $area
     */
    public function ButtonRegistration($area, $namespace = '', $controller = '')
    {
        /* @var msExportUsersExcelProfile $object */
        if ($object = $this->modx->getObject('msExportUsersExcelProfile', array('area' => $area))) {
            $this->modx->controller->addLastJavascript($this->config['jsUrl'] . 'mgr/misc/export.js');
            $this->modx->controller->addHtml('<script type="text/javascript">
                    msExportUsersExcel = {};
                    msExportUsersExcel.config = [];
                    msExportUsersExcel.config.list = ' . $object->classExportList(true) . ';
                    msExportUsersExcel.config.namespace = "' . $namespace . '";
                    msExportUsersExcel.config.controller = "' . $controller . '";
                    msExportUsersExcel.config.area = "' . addslashes($area) . '";
                    msExportUsersExcel.config.profile = "' . addslashes($object->get('id')) . '";
                    msExportUsersExcel.config.connector_url = "' . MODX_ASSETS_URL . 'components/msexportusersexcel/connector.php";
                </script>
            ');
        }
    }


    /**
     * Проверка существования пространства имен
     * @param string $namespace
     * @param string $path
     * @return bool
     */
    public function getNamespace($namespace, $path)
    {
        if (empty($namespace)) {
            return true;
        }
        $path = MODX_BASE_PATH . $this->postfixSlash($path);
        if (!file_exists($path)) {
            $this->modx->error->addField('namespace_path', $this->modx->lexicon('msexportusersexcel_profile_err_namespace_path', array('namespace_path' => $path)));
            return false;
        }
        $namespaceClass = $this->modx->getService($namespace, $namespace, $path);
        if (!$namespaceClass instanceof mSearch2) {
            $this->modx->error->addField('namespace', $this->modx->lexicon('msexportusersexcel_profile_err_namespace', array('namespace' => $namespace)));
            return false;
        }

        return true;
    }

}