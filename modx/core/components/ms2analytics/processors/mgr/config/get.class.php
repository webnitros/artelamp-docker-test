<?php

	class ms2AnalyticsGetConfigProcessor extends modObjectGetListProcessor
	{
		public $classKey = 'Ms2aConfigData';
		public $defaultSortField = 'id';
		public $defaultSortDirection = 'asc';

		public function beforeQuery()
		{
			$search = $this->getProperty('query');
			$category = $this->getProperty('category','basic');
			$this->where = [
				'category'=>$category
			];
			if (!empty($search)) {
				if (is_numeric($search)) {
					$this->where = [
						'id' => (int)$search,
					];
				} else {
					$var = $this->modx->newObject($this->classKey);
					$arr = $var->_fields;
					foreach ($arr as $field => $n) {
						$this->where["OR:{$field}:LIKE"] = "%" . $search . "%";
					}
				}
			}
			return parent::beforeQuery();
		}
		public function getData()
		{
			$data = [];
			$limit = (int)$this->getProperty('limit');
			$start = (int)$this->getProperty('start');

			/* query for chunks */
			$c = $this->modx->newQuery($this->classKey);
			if (!empty($this->where)) {
				$c->where($this->where);
			}
			$c = $this->prepareQueryBeforeCount($c);
			$data['total'] = $this->modx->getCount($this->classKey, $c);
			$c = $this->prepareQueryAfterCount($c);

			$sortClassKey = $this->getSortClassKey();
			$sortKey = $this->modx->getSelectColumns($sortClassKey, $this->getProperty('sortAlias', $sortClassKey), '', [$this->getProperty('sort')]);
			if (empty($sortKey)) $sortKey = $this->getProperty('sort');
			$c->sortby($sortKey, $this->getProperty('dir'));
			if ($limit > 0) {
				$c->limit($limit, $start);
			}
			$c->prepare();
			$data['results'] = $this->modx->getCollection($this->classKey, $c);
			return $data;
		}
	}
	return "ms2AnalyticsGetConfigProcessor";