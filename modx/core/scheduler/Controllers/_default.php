<?php
/**
 * Демонстрация контроллера
 */
class CrontabControllerProducts extends modCrontabController
{
    public function run()
    {
        $this->modx->log(modX::LOG_LEVEL_ERROR, "Задание завершено", '', __METHOD__, __FILE__, __LINE__);
    }
}