<?php

	class CrontabControllerAnalogs extends modCrontabController
	{
		public $table;
		public $linksTable;
		public $products = [];
		public $pool     = [];

		public function initialize()
		{
			echo "initialize" . PHP_EOL;
			$this->table      = $this->modx->getTableName('msProductData');
			$this->linksTable = $this->modx->getTableName('msProductLink');

			$this->addLinkQ = $this->modx->prepare(
				<<<SQL
INSERT IGNORE INTO {$this->linksTable} (`link`,`master`,`slave`,`rank`) values (1,:master,:slave,:rank)
SQL
			);
			$this->delete   = $this->modx->prepare(
				<<<SQL
DELETE FROM {$this->linksTable} where `master`=:master
SQL
			);
			$q              = $this->modx->query("SELECT article,id FROM {$this->table}");
			if ($q) {
				$this->products = $q->fetchAll(PDO::FETCH_KEY_PAIR);
			}
			return !empty($this->products);
		}

		public function process()
		{
			echo "process" . PHP_EOL;
			$arts = array_keys($this->products);
			foreach ($arts as $art) {
				try {
					$data   = $this->getAnalogByArt($art);
					$master = $this->products[$art] ?: 0;
					if ($master) {
						$this->delete($master);
						foreach ($data as $analog) {
							echo "Обрабатываю {$art} => {$analog['artikul_1c']}: ";
							$slave = $this->products[$analog['artikul_1c']] ?: 0;
							$score = $analog['score'];
							if ($slave) {
								$this->addLink($master, $slave, $score);
								echo "Ok" . PHP_EOL;
							} else {
								echo "Не найдено" . PHP_EOL;
							}
						}
					}
				} catch (Exception $e) {
					echo $e->getMessage() . PHP_EOL;
				}
			}
			$this->addLink(0, 0, 0, TRUE);
		}

		public function getAnalogByArt($art, $scope = 100, $size = 1000)
		{
			if ($scope < 2) {
				throw new Exception('can`t get analogs');
			}
			$url   = "https://fandeco.ru/rest/analogs/analogs";
			$param = http_build_query(
				[
					'returnArtikle1c' => 1,
					'size'            => $size,
					'list'            => 'analogs',
					'min_score'       => $scope,
					'artikul_1c'      => $art,
				]
			);
			$url   .= "?" . $param;
			$out   = json_decode(file_get_contents($url), 1);
			if (empty($out)) {
				return $this->getAnalogByArt($art, $scope / 2, $size);
			}
			if (!$out['success']) {
				throw new Exception('can`t get analogs');
			}
			return $out['data']['results'];
		}

		/**
		 * Добавляет связи товаров пачкой по 1000 для ускорения
		 *
		 * @param       $master
		 * @param       $slave
		 * @param int   $score
		 * @param false $UPDATE
		 */
		public function delete($master)
		{
			$this->pool[] .= <<<SQL
DELETE FROM {$this->linksTable} where `master`= {$master};
SQL;
		}

		public function addLink($master, $slave, $score = 0, $UPDATE = FALSE)
		{
			if ($master and $slave) {
				$score = str_replace(',', '.', $score);
				//Добавление запроса в пул
				$this->pool[] .= <<<SQL
INSERT IGNORE INTO {$this->linksTable} (`link`,`master`,`slave`,`rank`) values (1,{$master},{$slave},'{$score}');
SQL;
			}
			//проверка стоит ли отправлять запрос
			if (count($this->pool) >= 1000 or (count($this->pool) > 0 and $UPDATE)) {
				$sql = implode("\n", $this->pool);
				$this->modx->query($sql);
				echo 'Добавлено ' . count($this->pool) . PHP_EOL;
				$this->pool = [];
			}
		}

	}