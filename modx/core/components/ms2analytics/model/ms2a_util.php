<?php
	/**
	 * Created by Andrey Stepanenko.
	 * User: webnitros
	 * Date: 30.11.2020
	 * Time: 11:44
	 */

	class ms2a_util
	{
		/**
		 * @var modX
		 */
		public $modx;

		public function __construct(modX &$modx)
		{
			$this->modx = &$modx;
			$Ms2aC = $this->modx->newQuery('Ms2aConfigData');
			$Ms2aC->select(['key', 'value', 'default', 'category']);
			$this->config = [];
			if ($Ms2aC->prepare() && $Ms2aC->stmt->execute()) {
				while ($row = $Ms2aC->stmt->fetch(PDO::FETCH_ASSOC)) {
					$this->config[$row['key']] = $row['value'];
					$this->config[$row['category']][$row['key']] = $row['value'];
					$this->config['default'][$row['key']] = $row['default'];
					$this->config[$row['category']]['default'][$row['key']] = $row['default'];
				}
			}

		}

		public function getProductByIds($id, $select = [])
		{
			if (empty($select)) {
				$select = $this->getItemsFields();
			}
			if (is_array($id)) {
				$res = [];
				foreach ($id as $i) {
					$res[$i] = $this->_getProductByIds($id, $select);
				}
				return $res;
			} else {
				return $this->_getProductByIds($id, $select);
			}
		}

		public function getItemsFields()
		{
			$select = [];
			foreach ($this->config['product'] as $key => $value) {
				if (!empty($value) and !is_array($value)) {
					$select[] = "{$value} as '{$key}'";
				}
			}
			return $select;
		}

		public function _getProductByIds($id, $select = [])
		{
			if (!is_numeric($id)) {
				return FALSE;
			}
			$id = (int)$id;
			$q = $this->modx->newQuery('msProduct');
			$q->setClassAlias('product');
			$q->select($select);
			$q->leftJoin('msProductData', 'data', 'data.id = product.id');
			$q->leftJoin('modResource', 'parent', 'parent.id = product.parent');
			$q->leftJoin('msVendor', 'vendor', 'vendor.id = data.vendor');
			$q->where([
				'product.id' => $id,
			]);
			$q->limit(1);
			if ($q->prepare() && $q->stmt->execute()) {
				return $q->stmt->fetch(PDO::FETCH_ASSOC);
			}
			return FALSE;

		}

		public function bu()
		{
			$otherProps = [
				'processors_path' => $this->modx->getOption('core_path') . 'components/ms2analytics/processors/',
			];
			$this->modx->runProcessor('mgr/config/reset', ['do' => 'bu'], $otherProps);
			return TRUE;
		}

		public function reset()
		{
			$otherProps = [
				'processors_path' => $this->modx->getOption('core_path') . 'components/ms2analytics/processors/',
			];
			$this->modx->runProcessor('mgr/config/reset', ['do' => 'reset'], $otherProps);
			return TRUE;
		}

		public function insert()
		{
			$otherProps = [
				'processors_path' => $this->modx->getOption('core_path') . 'components/ms2analytics/processors/',
			];
			$this->modx->runProcessor('mgr/config/reset', ['do' => 'insert'], $otherProps);
			return TRUE;
		}
	}