<?php

	/**
	 * Демонстрация контроллера
	 */
	class CrontabControllerClearLogs extends modCrontabController
	{

		public function run()
		{
			//Очистка логов цен
			echo "Очистка логов цен" . PHP_EOL;
			$dt   = new DateTime();
			$time = (int)$dt->modify('- 1 months')->format('U');
			$this->modx->query(<<<SQL
delete from ara3_fdk_prepare_prices where `updatedon` < $time
SQL
			);
			$this->modx->query(<<<SQL
delete from ara3_fdk_prepare_stocks where `updatedon` < $time
SQL
			);
		}
	}
