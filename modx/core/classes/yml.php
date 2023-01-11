<?php
	/**
	 * Created by Andrey Stepanenko.
	 * User: webnitros
	 * Date: 10.08.2020
	 * Time: 22:04
	 */
	include_once realpath(__DIR__ . '/../../../vendor/autoload.php');
	include_once realpath(__DIR__ . '/YandexOffer.php');

	use Bukashk0zzz\YmlGenerator\Generator;
	use Bukashk0zzz\YmlGenerator\Model\Category;
	use Bukashk0zzz\YmlGenerator\Model\Currency;
	use Bukashk0zzz\YmlGenerator\Model\Offer\OfferParam;
	use Bukashk0zzz\YmlGenerator\Model\ShopInfo;
	use Bukashk0zzz\YmlGenerator\Settings;
	use fandeco\category\CategoryExtension;

	abstract class fdc_yml
	{
		/* @var modX modx */
		public $modx;

		public function __construct(modX &$modx)
		{
			$this->modx              = $modx;
			$this->categoryValidator = new \fandeco\category\Category();
		}

		/* @var Settings $settings */
		protected $settings;
		/* @var shopInfo $shopInfo */
		protected $shopInfo;

		/* @var array $currencies */
		protected $currencies = [];
		/* @var array $categories */
		protected $categories = [];
		/* @var array $offers */
		protected $offers = [];
		protected $added  = [];

		/**
		 * @return string|null
		 */
		public function getOutputFile()
		{
			return $this->settings->getOutputFile();
		}

		/**
		 * @param        $file
		 * @param string $encoding
		 * @return $this
		 */
		public function newYml($file, $encoding = 'UTF-8')
		{
			$this->settings = (new Settings())
				->setOutputFile($file)
				->setEncoding('UTF-8')
			;

			$this->shopInfo   = NULL;
			$this->currencies = [];
			$this->offers     = [];
			$this->categories = [];
			return $this;
		}

		public function setShop($name, $company, $url)
		{
			$this->shopInfo = (new ShopInfo())
				->setName($name)
				->setCompany($company)
				->setUrl($url)
			;
			return $this;
		}

		/**
		 * @param string $currencie
		 * @return $this
		 */
		public function setCurrency(string $currencie)
		{
			$rate = count($this->currencies);
			if ($rate == 0) {
				$rate = 1;
			}
			$this->currencies[] = (new Currency())
				->setId($currencie)
				->setRate($rate)
			;

			return $this;
		}

		public function getIDByName($name)
		{
			$hash = hash('sha256', $name);
			$id   = preg_replace("@\D+@", "", $hash);
			$id   = (int)substr($id, 0, 6);
			if (isset($this->ids[$id]) && $this->ids[$id] !== $name) {
				echo '<pre>';
				echo __FILE__ . ':' . __LINE__ . "\n";
				var_dump($name);
				die;
			}
			$this->ids[$id] = $name;
			return $id;
		}

		/**
		 * @param array $arrays
		 * @return $this
		 */
		public function setCategory($arrays = [])
		{
			foreach ($arrays as $key => $name) {
				if (isset($this->added[$name])) {
					continue;
				}
				$this->added[$name] = $name;
				$this->categories[] = (new Category())
					->setId($this->getIDByName($name))
					->setName($name)
				;
			}
			return $this;
		}

		public static function string2float(string $value)
		{
			return (float)preg_replace('/\s+/', '', str_replace(',', ' . ', $value));
		}

		/**
		 * Выполняется перед записью offer
		 * @param null $callback
		 */
		public function setProducts($products, $callback = NULL, $currencie = 'RUB')
		{
			foreach ($products as $product) {
				$product_id  = $product['id'];
				$url         = $this->modx->makeUrl($product_id, '', '', 'full');
				$price       = $product['price'];
				$old_price   = $product['old_price'];
				$parent      = $product['parent'];
				$pagetitle   = $product['pagetitle'];
				$pictures    = $product['pictures'];
				$description = $product['description'];
				$VendorCode  = $product['article'];
				$vendor      = $product['vendor_name'] ?: $product['vendor_code'];
				$weight      = self::string2float($product['weight']);
				$barcode     = $product['barcode'];
				$in_stock    = $product['in_stock'];

				// Offers
				$offer = (new YandexOffer())
					->setId($product_id)
					->setAvailable(TRUE)
					->setUrl($url)
				;

				if (!empty($pictures) and is_array($pictures)) {
					foreach ($pictures as $picture) {
						$offer->addPicture($picture);
					}
				}

				$offer->setPrice($price);
				if (!empty($old_price)) {
					$offer->setOldPrice($old_price);
				}


				if (!empty($old_price)) {
					$offer->setOldPrice($old_price);
				}

				if (!empty($weight)) {
					$offer->setWeight($weight);
				}

				if (!empty($barcode)) {
					$offer->setBarcodes([$barcode]);
				}
				if ($in_stock) {
					$offer->setAvailable(TRUE);
				} else {
					$offer->setAvailable(FALSE);
				}
				try {
					$offer
						->setVendor($vendor)
						->setVendorCode($VendorCode)
						->setCurrencyId($currencie)
						->setCategoryId($this->getIDByName($product['sub_category'] ?: $product['good_type_web']))
						->setDelivery(FALSE)
						->setName($this->nameGenerator($product))
						->setDescription(strip_tags($description))
					;
				} catch (CategoryExtension $e) {
					echo $e->getMessage() . PHP_EOL;
				}


				if (!is_null($callback) && gettype($callback) == 'object') {
					$offer = call_user_func($callback, $offer, $product);
				}
				$this->offers[] = $offer;
			}
			return $this;
		}

		/**
		 * @throws CategoryExtension
		 */
		public function nameGenerator(array $product)
		: string
		{
			$result = '';
			try {
				$data       = $this->categoryValidator->getDataByCategory($product['good_type_web'], $product['sub_category']);
				$article    = $product['article'];
				$collection = $product['collection'];
				$o          = json_decode($product['osobennost']);
				if (!empty($o)) {
					$osobennost = $o[0];
				} else {
					$osobennost = "Аксессуар";
				}
				$sub_category = $product['sub_category'];
				$vendor       = $product['vendor_name'] ?: $product['vendor_code'];
				$vendor       = mb_convert_case($vendor, MB_CASE_TITLE, "UTF-8");
				$collection   = mb_convert_case($collection, MB_CASE_TITLE, "UTF-8");
				$osobennost   = mb_convert_case($osobennost, MB_CASE_TITLE, "UTF-8");
				$sub_category = mb_convert_case($sub_category, MB_CASE_TITLE, "UTF-8");
				$result       = eval("return \"" . $data['template'] . '";');
			} catch (CategoryExtension $e) {
				echo $e->getMessage() . PHP_EOL;
				echo $e->getCategory() . PHP_EOL;
				echo $e->getSubCategory() . PHP_EOL;
				$result = mb_convert_case($product['pagetitle'], MB_CASE_TITLE, "UTF-8");
			} catch (Exception $e) {
				echo $e->getMessage() . PHP_EOL;
				$result = mb_convert_case($product['pagetitle'], MB_CASE_TITLE, "UTF-8");
			}
			if (empty($result)) {
				$result = $product['pagetitle'];
			}
			return $result;
		}

		/**
		 * @return bool|string
		 */
		public function generate()
		{
			return (new Generator($this->settings))->generate(
				$this->shopInfo,
				$this->currencies,
				$this->categories,
				$this->offers
			);
		}

		/**
		 * @param string $name
		 * @param string $value
		 * @param string $unit
		 * @return OfferParam
		 */
		public function addParam($name = '', $value = '', $unit = '')
		{
			return (new OfferParam())
				->setName($name)
				->setValue($value)
				->setUnit($unit)
			;
		}
	}