<?
	class getProductByIdProcessor extends modUtilRestProcessor
	{
		/**
		 * @var ms2a_util
		 */
		public $ms2a_util;

		public function initialize()
		{
			$this->modx->addPackage("ms2analytics", MODX_CORE_PATH . "components/ms2analytics/model/");
			include_once MODX_CORE_PATH . 'components/ms2analytics/model/ms2a_util.php';
			$this->ms2a_util = new ms2a_util($this->modx);
			return parent::initialize();
		}

		public function process()
		{
			$key = (string)$_REQUEST['key'];
			if (!$key) {
				return $this->failure('empty query', $key);
			}
			if ($miniShop2 = $this->modx->getService('miniShop2')) {
				// Инициализируем класс в текущий контекст
				$miniShop2->initialize('web');

				$cart = $miniShop2->cart->get();
				if (!array_key_exists($key, $cart)) {
					return $this->failure('not found', [$key, $cart]);
				} else {
					foreach ($cart[$key] as $k => $v) {
						$cartItem['cart.' . $k] = $v;
					}
				}
				$id = $cartItem['cart.id'];
				$count = $cartItem['cart.count'];
			} else {
				return $this->failure('miniShop2 error', $key);
			}
			$fields = $this->ms2a_util->getItemsFields();
			if ($fields) {
				$response = $this->ms2a_util->getProductByIds($id, $fields);
				$response['quantity'] = $count ?: 1;
				if ($response) {
					return $this->success('ok', [
						'config' => $this->ms2a_util->config,
						'product' => $response,
					]);
				} else {
					return $this->failure('not found', [$id, $response]);
				}
			}
			return $this->failure('empty fields', [$id]);

		}
	}

	return 'getProductByIdProcessor';