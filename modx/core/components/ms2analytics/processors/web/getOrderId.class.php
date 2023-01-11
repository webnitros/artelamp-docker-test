<?
	class getProductByIdProcessor extends modUtilRestProcessor
	{

		public function initialize()
		{
			$this->modx->addPackage("ms2analytics", MODX_CORE_PATH . "components/ms2analytics/model/");
			include_once MODX_CORE_PATH . 'components/ms2analytics/model/ms2a_util.php';
			$this->ms2a_util = new ms2a_util($this->modx);
			return parent::initialize();
		}

		public function process()
		{
			if (!$id = (string)$_REQUEST['key']) {
				return $this->failure('empty query', $id);
			}
			$select = [
				"GROUP_CONCAT(concat(product.product_id,':',product.count)) as items",
			];

			$Ms2aCdata = [];
			foreach ($this->ms2a_util->config['order'] as $key => $value) {
				if (!is_array($value)) {
					$Ms2aCdata[$key] = $value;
					if (!empty($value) and !empty($this->ms2a_util->config['order']['default'][$key])) {
						$select[] = "{$value} as '{$key}'";
					}
				}
			}

			$q = $this->modx->newQuery('msOrder');
			$q->setClassAlias('order');
			$q->select($select);
			$q->leftJoin('msOrderProduct', 'product', 'product.order_id = order.id');
			$q->groupby('order.id');
			$q->where([
				'order.id' => $id,
			]);
			if ($q->prepare() && $q->stmt->execute()) {
				$order = $q->stmt->fetch(PDO::FETCH_ASSOC);
			}
			if ($order) {
				$arr = explode(',', $order['items']);
				$items = [];
				foreach ($arr as $k => $v) {
					$v = explode(':', $v);
					$id = $v[0];
					$count = $v[1];
					$items[$k] = $this->ms2a_util->getProductByIds($id);
					$items[$k]['quantity'] = $count;
				}
				$response = [];
				$response['products'] = $items;
				$response['config'] = $this->ms2a_util->config;
				$response['order'] = $order;

				return $this->success('ok', $response);
			}else{
				return $this->failure('not found', $id);
			}
		}
	}

	return 'getProductByIdProcessor';