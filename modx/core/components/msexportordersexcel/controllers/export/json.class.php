<?php
class msExportOrdersExcelPHPExcelJSONController extends msExportOrdersExcelPHPExcelDefaultController
{
    /* @var string $class */
    protected $class = 'JSON';
    protected $extension = 'json';

    /**
     * Запись данных в обработчик для экспорта
     * @return mixed
     */
    public function objWriter()
    {
        $list = $this->fields ? array($this->fields) : array();
        $data = !empty($this->data) ? array_merge($list, $this->data) : array();
        $this->objWriter = $data;
        return true;
    }

    /**
     * Запись данных в обработчик для экспорта
     * @return string
     */
    public function toJson()
    {
        if ($this->config['json_process']) {
            return json_encode($this->objWriter, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);;
        }
        return $this->modx->toJSON($this->objWriter);
    }


}

return 'msExportOrdersExcelPHPExcelJSONController';
