<?php

	use Bukashk0zzz\YmlGenerator\Model\Offer\AbstractOffer;
	use Bukashk0zzz\YmlGenerator\Model\Offer\OfferGroupAwareInterface;
	use Bukashk0zzz\YmlGenerator\Model\Offer\OfferGroupTrait;
	use Bukashk0zzz\YmlGenerator\Model\Offer\OfferSimple;

	class YandexOffer extends OfferSimple
	{

		/**
		 * @var string
		 */
		private $name;

		/**
		 * @var string
		 */
		private $vendor;

		/**
		 * @var string
		 */
		private $vendorCode;
		/**
		 * @var string
		 */
		private $collectionUri;

		/**
		 * @return string
		 */
		public function getType()
		{
			return NULL;
		}

		/**
		 * @return string
		 */
		public function getName()
		{
			return $this->name;
		}

		/**
		 * @param string $name
		 *
		 * @return $this
		 */
		public function setName($name)
		{
			$this->name = $name;

			return $this;
		}

		/**
		 * @return string
		 */
		public function getVendor()
		{
			return $this->vendor;
		}

		/**
		 * @param string $vendor
		 *
		 * @return $this
		 */
		public function setVendor($vendor)
		{
			$this->vendor = $vendor;

			return $this;
		}

		/**
		 * @return string
		 */
		public function getVendorCode()
		{
			return $this->vendorCode;
		}

		/**
		 * @param string $vendorCode
		 *
		 * @return $this
		 */
		public function setVendorCode($vendorCode)
		{
			$this->vendorCode = $vendorCode;

			return $this;
		}

		public function setCollectionUri($collectionUri)
		{
			$this->collectionUri = $collectionUri;
			return $this;
		}

		public function getCollectionUri()
		{
			return $this->collectionUri ?: '';
		}

		/**
		 * @return array
		 */
		protected function getOptions()
		{
			return [
				'name'          => $this->getName(),
				'vendor'        => $this->getVendor(),
				'vendorCode'    => $this->getVendorCode(),
//				'collectionUri' => $this->getCollectionUri(),
			];
		}
	}