<?php

class msExportOrdersExcelFile
{
    /** @var modFile|null $file */
    protected $object = null;
    /** @var modMediaSource|null $mediaSource */
    protected $mediaSource = null;

    public function __construct(modMediaSource $modMediaSource, modFile $object)
    {
        $this->mediaSource = &$modMediaSource;
        $this->object = &$object;
    }

    /**
     * Вернет относительный путь к файлу
     * @return string
     */
    public function path()
    {
        $basePath = $this->mediaSource->getBasePath();
        $basePath = str_ireplace('\\', '/', $basePath);
        $path = $this->object->getPath();
        $objectPath = str_ireplace($basePath, '', $path);
        return $objectPath;
    }

    /**
     * Вернет абсолютный путь к файлу
     * @return string
     */
    public function absolutePath()
    {
        return $this->object->getPath();
    }


    /**
     * Вернет ссылку на контроллер для скачивания файла
     * @return boolean
     */
    public function downloadLink()
    {
        /** @var modFile $file */
        $data = array(
            'src' => $this->path(),
            'remove' => $this->mediaSource->getOption('remove_files_is_download'),
            'source' => $this->mediaSource->get('id'),
            'profile' => $this->mediaSource->getOption('profile'),
        );
        $site_url = $this->mediaSource->xpdo->context->getOption('site_url');
        $baseUrl = $this->mediaSource->getBaseUrl();
        $url = $site_url . $baseUrl . '?' . http_build_query($data);
        $url = str_ireplace($site_url . '/', $site_url, $url);
        return $url;
    }

    /**
     * Проверит наличие файла
     * @return null|string
     */
    public function exists()
    {
        return $this->object->exists();
    }


    /**
     * Проверит наличие файла
     * @return null|string
     */
    public function extension()
    {
        return $this->object->getExtension();
    }

    /**
     * Проверит наличие файла
     * @return null|string
     */
    public function baseName()
    {
        return $this->object->getBaseName();
    }

    /**
     * Вернет вес файла измерив размер контента
     * @return null|string
     */
    public function size()
    {
        $output = $this->object->getContents();
        return strlen($output);
    }

    /**
     * Вернет полученный контент
     * @return null|string
     */
    public function content()
    {
        return $this->object->getContents();
    }

    /**
     * Автоматически скачает контент
     * @param boolean $remove true удалит файл сразу поле получени контента
     * @param array $options дополнительные данные для заголовков
     */
    public function download($remove = false, $options = array())
    {
        /** @var modFile $file
        $this->object->download(array(
        'mimetype' => $this->get_mime_type($this->object->getExtension())
        ));*/

        $options = array_merge(array(
            'mimetype' => $this->get_mime_type($this->object->getExtension()),
            #'mimetype' => 'application/octet-stream',
            'filename' => '"' . $this->object->getBasename() . '"',
        ), $options);

        $output = $this->object->getContents();

        header('Content-type: ' . $options['mimetype']);
        header('Content-Disposition: attachment; filename=' . $options['filename']);
        #header('Content-Length: ' . $this->object->getSize()); // функция не верно расчитываем размер файла
        header('Content-Length: ' . $this->size());

        $this->headers();

        echo $output;
        // Удаление файла после получения контента
        if ($remove) {
            $this->object->remove();
        }
        die();

    }

    /**
     * Вернет полученный контент
     * @return boolean
     */
    public function remove()
    {
        return $this->object->remove();
    }

    /**
     * Добавит имя для закладки
     */
    public function headers()
    {
        switch ($this->extension()) {
            case 'xls':
            case 'xlsx':

                #header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                // If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                break;
            case 'json':
                #header('Content-Type: application/vnd.acme.blog-v1+json');
                break;
            case 'csv':
                #header('Content-Type: text/csv');
                header('Cache-Control: no-cache, no-store, must-revalidate');
                header('Pragma: no-cache');
                header('Expires: 0');
                break;
            default:
                break;
        }
    }


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

}