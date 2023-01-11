<?php

class msExportUsersExcelProfile extends xPDOSimpleObject
{
    /* @var array $fields */
    protected $fields = null;

    /* @var array|null $data */
    protected $data = null;

    /* @var modFileMediaSource $mediasource */
    protected $mediasource = null;

    /* @var msExportUsersExcelFile $file */
    protected $file = null;

    /**
     *  Loads modMediaSource
     *
     * @param bool $false
     * @return modFileMediaSource|null
     */
    public function loadSource($false = false)
    {
        if (!is_object($this->mediasource) || !($this->mediasource instanceof modFileMediaSource)) {
            if (!$this->mediasource = $this->getOne('Source')) {
                $this->mediasource = $false ? false : $this->xpdo->newObject('sources.modFileMediaSource');
            }
        }
        return $this->mediasource;
    }

    /**
     *  Loads modMediaSource and initialize
     * @return modFileMediaSource|boolean
     */
    public function loadSourceInitialize()
    {
        if ($source = $this->loadSource()) {
            if ($source->initialize()) {
                $source->set('profile_id', $this->get('id'));
                $this->xpdo->lexicon->load('core:file');
                return $source;
            }
        }
        $this->xpdo->log(modX::LOG_LEVEL_ERROR, 'Could not initialize source ' . print_r($source->toArray(), 1));
        return false;
    }

    /**
     * @return boolean
     */
    public function isDownload()
    {
        return $this->get('download');
    }

    /**
     * @return boolean
     */
    public function isRemove()
    {
        return $this->get('remove');
    }

    /**
     * Loads Fields
     * @return array|boolean
     */
    public function loadFields($false = false)
    {
        if (is_null($this->fields)) {
            $q = $this->xpdo->newQuery('msExportUsersExcelProfileFields');
            $q->sortby('rank', 'ASC');
            $q->where(array(
                'active' => 1
            ));
            if (!$this->fields = $this->getMany('Fields', $q)) {
                $this->fields = $false ? false : array();
            }
        }
        return $this->fields;
    }


    /**
     * add filed to array fields
     * @param msExportUsersExcelProfileFields $field
     * @return array|boolean
     */
    public function addField(msExportUsersExcelProfileFields $field)
    {
        if (!is_array($this->fields)) {
            $this->loadFields();
        }

        if (is_array($this->fields)) {
            $this->fields[] = $field;
            return $this->fields;
        }
        return false;
    }

    /**
     * Return object field
     * @param string $fieldName
     * @return bool|msExportUsersExcelProfileFields
     */
    public function loadField($fieldName)
    {
        /* @var msExportUsersExcelProfileFields $object */
        if (!$object = $this->xpdo->getObject('msExportUsersExcelProfileFields', array(
            'profile_id' => $this->id,
            'field' => $fieldName
        ))) {
            return false;
        }
        return $object;
    }

    /**
     * Remove fields
     */
    public function removesFields()
    {
        $pid = $this->get('id');
        $table = $this->xpdo->getTableName('msExportUsersExcelProfileFields');
        $this->xpdo->exec("DELETE FROM {$table} WHERE `profile_id` = {$pid};");
    }


    /* @var msExportUsersExcel $msExportUsersExcel */
    protected $msExportUsersExcel;

    /**
     * Экпорт профиля
     *
     * @param null|string $classExport
     * @param array $config
     * @param array|null $data
     * @return bool|msExportUsersExcelFile
     */
    public function export($classExport = null, $data = null, $config = array())
    {
        if ($data) {
            $this->setData($data);
        }

        /* @var msExportUsersExcel $msExportUsersExcel */
        $this->msExportUsersExcel = $this->xpdo->getService('msExportUsersExcel', 'msExportUsersExcel', MODX_CORE_PATH . 'components/msexportusersexcel/model/');
        $this->msExportUsersExcel->initialize();

        /* @var msExportUsersExcelProfileHandler $Profile */
        if ($Profile = $this->msExportUsersExcel->newExportProfile($this, $config)) {

            if ($export = $Profile->export($classExport)) {
                if ($export->save()) {
                    if ($file = $export->loadFile()) {
                        $this->file = $file;
                        return $file;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Автоматически скачивает файл
     * @param boolean|null $remove
     */
    public function fileDownload($remove = null)
    {
        $remove = is_null($remove) ? $this->get('remove') : (boolean)$remove;
        if ($this->file) {
            $this->file->download($remove);
        }
    }


    /**
     * Автоматически скачивает файл
     * @return bool|string
     */
    public function fileExtension()
    {
        if ($this->file) {
            return $this->file->extension();
        }
        return false;
    }

    /**
     * Автоматически скачивает файл
     * @return bool|string
     */
    public function fileBaseName()
    {
        if ($this->file) {
            return $this->file->baseName();
        }
        return false;
    }

    /**
     * Вернет ссылку на файл
     * @return bool|string
     */
    public function fileDownloadLink()
    {
        if ($this->file) {
            return $this->file->downloadLink();
        }
        return false;
    }

    /**
     * Удалит файл
     * @return bool
     */
    public function fileRemove()
    {
        if ($this->file) {
            return $this->file->remove();
        }
        return false;
    }

    /**
     * Вернет полный путь до файла
     * @return bool|string
     */
    public function fileAbsolutePath()
    {
        if ($this->file) {
            return $this->file->absolutePath();
        }
        return false;
    }

    /**
     * Вернет полный путь до файла
     * @return bool|string
     */
    public function fileRelativePath()
    {
        if ($this->file) {
            return $this->file->path();
        }
        return false;
    }

    /**
     * Вернет полный путь до файла
     * @return bool
     */
    public function fileExists()
    {
        if ($this->file) {
            return $this->file->exists();
        }
        return false;
    }

    /**
     * Вернет размер файла
     * @return bool|int
     */
    public function fileSize()
    {
        if ($this->file) {
            return $this->file->size();
        }
        return false;
    }

    /**
     * Вернет контент из файла
     * @return bool|string
     */
    public function fileContent()
    {
        if ($this->file) {
            return $this->file->content();
        }
        return false;
    }


    /**
     * Вернет ращрешенные форматы для экспорта данных
     * @param bool $json
     * @return array|null|string
     */
    public function classExportList($json = false)
    {

        $formats = $this->get('classExportList');
        if (!empty($formats)) {
            $data = explode(',', $formats);

            if ($json) {
                $data = $this->xpdo->toJSON($data);
            }
            return $data;
        }
        return null;
    }

    /**
     * Вернет массив с контентом по профилю
     * @param array $data
     * @return boolean;
     */
    public function setData($data = array())
    {
        if (is_array($data)) {
            $this->data = $data;
            return false;
        }
        $this->data = false;
        return false;
    }

    /**
     * Вернет массив с контентом по профилю
     * @return array|boolean
     */
    public function getData()
    {
        if (is_null($this->data)) {
            /* @var msExportUsersExcel $msExportUsersExcel */
            $this->msExportUsersExcel = $this->xpdo->getService('msExportUsersExcel', 'msExportUsersExcel', MODX_CORE_PATH . 'components/msexportusersexcel/model/');
            $this->msExportUsersExcel->initialize();
            $query = $this->msExportUsersExcel->query->newQuery($this->getConfig());
            if ($query->runProcessorData()) {
                $this->data = $query->getData();
            }
        }
        if ($this->data) {
            return $this->data;
        }
        return false;
    }

    /**
     * Вернет конфиг для выгрузки в Excel
     * @return array|null
     */
    public function getConfig()
    {
        $this->set('profile', $this->get('id'));
        $configs = array('download','date_format','date_process','source');
        foreach ($configs as $field) {
            $name = $field;
            switch ($field){
                case 'source':
                    $name = 'source_default';
                    break;
                default:
                    break;
            }
            $this->set($field, $this->xpdo->getOption('msexportusersexcel_' . $name));
        }
        return $this->toArray();
    }


    /**
     * Вернет список размеров для каждого поля
     * @return array|null
     */
    public function getWidths()
    {
        $widths = null;
        if ($fields = $this->loadFields(true)) {
            /* @var msExportUsersExcelProfileFields $field */
            foreach ($fields as $field) {
                $widths[$field->get('field')] = $field->getWidth();
            }
        }
        return $widths;
    }


    /**
     * Вернет список размеров для каждого поля
     * @return array|null
     */
    public function getAlignmentHorizontals()
    {
        $alignments = null;
        if ($fields = $this->loadFields(true)) {
            /* @var msExportUsersExcelProfileFields $field */
            foreach ($fields as $field) {
                $alignments[$field->get('field')] = $field->getAlignmentHorizontal();
            }
        }
        return $alignments;
    }


    /**
     * Вернет список размеров для каждого поля
     * @return array|null
     */
    public function getAlignmentVerticals()
    {
        $alignments = null;
        if ($fields = $this->loadFields(true)) {
            /* @var msExportUsersExcelProfileFields $field */
            foreach ($fields as $field) {
                $alignments[$field->get('field')] = $field->getAlignmentVertical();
            }
        }
        return $alignments;
    }

    /**
     * Вернет список полей
     * @return array|null
     */
    public function getFields()
    {
        $fields = null;
        if ($tmp = $this->loadFields(true)) {
            /* @var msExportUsersExcelProfileFields $f */
            foreach ($tmp as $f) {
                $field = $f->get('field');
                $value = $f->get('value');
                $fields[$field] = empty($value) ? $field : $value;
            }
        }
        return $fields;
    }

    /**
     * Вернет список полей
     * @return array|null
     */
    public function getHandlers()
    {
        $handlers = null;
        if ($fields = $this->loadFields(true)) {
            /* @var msExportUsersExcelProfileFields $field */
            foreach ($fields as $field) {
                $handlers[$field->get('field')] = $field->get('handler');
            }
        }
        return $handlers;
    }

    /**
     * Последний запуск/завершение
     * @param string $field
     * @param null|int $time
     */
    public function lastRun($field, $time = null)
    {
        if ($field == 'last_start_run' or $field == 'last_end_run') {
            $id = $this->get('id');
            if (!empty($id)) {
                $time = !$time ? time() : $time;
                $sql = "UPDATE {$this->xpdo->getTableName($this->_class)} SET {$field} = '{$time}' WHERE id = " . $id;
                $this->xpdo->exec($sql);
            }
        }
    }


}