<?php

	/**
	 * Демонстрация контроллера
	 */
	class CrontabControllerClearErrors extends modCrontabController
	{

		public function run()
		{
			//очистка файла логов ошибок если тот слишком большой
			if (file_exists(MODX_CORE_PATH . 'cache/logs/error.log') and filesize(MODX_CORE_PATH . 'cache/logs/error.log') > 1024 * 1024 * 10) {
				unlink(MODX_CORE_PATH . 'cache/logs/error.log');
				echo "АЛЯРМ на арте куча логов" . PHP_EOL;
//				$this->sendMessage(['nefediev@technolight.ru'], "АЛЯРМ на арте куча логов", "АЛЯРМ на арте куча логов");
			}
		}
	}
