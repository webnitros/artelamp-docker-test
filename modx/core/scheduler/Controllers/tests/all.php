<?php

/**
 * Запуск тестов для проверки настроек
 */
class CrontabControllerTestsAll extends modCrontabController
{

    public function run()
    {
        // Запустит тест из файла tests/DemoTest.php
        $this->addTest('minishop');
        #$this->addTest('Settings');
        $this->runTest();
    }
}
