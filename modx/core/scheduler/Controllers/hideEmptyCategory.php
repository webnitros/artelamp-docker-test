<?php

	/**
	 * Демонстрация контроллера
	 */
	class CrontabControllerHideEmptyCategory extends modCrontabController
	{

		public function run()
		{
			$table1 = trim($this->modx->getTableName('modResource'), '`');
			$table2 = trim($this->modx->getTableName('msProductData'), '`');
			$sql = <<<SQL
SELECT id,pagetitle FROM `$table1` AS `msc` WHERE class_key = 'msCategory' 
AND (
SELECT COUNT(*) FROM `$table1` AS `msp`
left join `$table2` as `p` on `p`.id = `msp`.id
 WHERE msp.parent = msc.id AND (
 ( msp.class_key = 'msCategory' AND  published = 1)
  OR
 ( msp.class_key = 'msProduct' AND  `p`.stock > 0))
) = 0
and published = 1
and template <> 14
SQL;
			$emptyCat = $this->modx->query($sql);
			if ($emptyCat) {
				$emptyCat = $emptyCat->fetchAll(PDO::FETCH_COLUMN);
				$in = $this->modx->util->arrayToSqlIn($emptyCat);
				$this->modx->query("UPDATE `$table1` set published=0,hidemenu=1 where id IN($in)");
				echo '<pre>';
				print_r($emptyCat);

			}
			$sql = <<<SQL
SELECT id,pagetitle FROM `$table1` AS `msc` WHERE class_key = 'msCategory' 
AND (
SELECT COUNT(*) FROM `$table1` AS `msp`
left join `$table2` as `p` on `p`.id = `msp`.id
 WHERE msp.parent = msc.id AND (
 ( msp.class_key = 'msCategory' AND  published = 1)
  OR
 ( msp.class_key = 'msProduct' AND  `p`.stock > 0))
) > 0
and published = 0
and template <> 14
SQL;
			$fullCat = $this->modx->query($sql);
			if ($fullCat) {
				$fullCat = $fullCat->fetchAll(PDO::FETCH_COLUMN);
				$in = $this->modx->util->arrayToSqlIn($fullCat);
				$this->modx->query("UPDATE `$table1` set published=1,hidemenu=0 where id IN($in)");
				echo '<pre>';
				print_r($fullCat);
			}


			$sql       = "SELECT parent.id,SUM(p.published) AS pub FROM `$table1` AS p
INNER JOIN `$table1` AS parent ON parent.id = p.parent AND parent.published = 1
WHERE p.class_key = 'msProduct'
GROUP BY p.parent";
			$emptyCat2 = $this->modx->query($sql);
			if ($emptyCat2) {
				$emptyCat2 = $emptyCat2->fetchAll(PDO::FETCH_ASSOC);
				$hide      = [];
				foreach ($emptyCat2 as $row) {
					if ((int)$row['pub'] === 0) {
						$hide[] = $row['id'];
					}
				}
				$in = $this->modx->util->arrayToSqlIn($hide);
				$this->modx->query("UPDATE `$table1` set published=0,hidemenu=1 where id IN($in)");
				echo '<pre>';
				print_r($hide);
			}
		}
	}
