<?php

class msProductFileDownloadProcessor extends modObjectProcessor
{
    public $classKey = 'msProductData';
    public $languageTopics = array('minishop2:default', 'minishop2:product');
    public $permission = 'msproductfile_save';
    /** @var modMediaSource $mediaSource */
    public $mediaSource;
    /** @var miniShop2 $miniShop2 */
    protected $miniShop2;
    /** @var msProductData $product */
    private $product = 0;

    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }

        /** @var msProduct $product */
        $id = (int)$this->getProperty('product_id');
        if (!$this->product = $this->modx->getObject('msProductData', $id)) {
            return $this->modx->lexicon('ms2_gallery_err_no_product');
        }

        $this->miniShop2 = $this->modx->getService('miniShop2');

        return true;
    }

    /**
     * @return array|string
     */
    public function process()
    {
        if (!class_exists('fdkNewDownloadImages')) {
            include_once MODX_CORE_PATH . 'classes/fdkNewDownloadImages.php';
        }
        $this->fdkNewDownloadImages = new fdkNewDownloadImages($this->modx);
        $status = $this->fdkNewDownloadImages->getImages($this->product, $this->product->get('artikul_1c'));
        if ($status !== 200) {
            return $this->failure('Произошла ошибка: STATUS: '.$status.'. MSG:' . $this->fdkNewDownloadImages->errorMsg);
        }
        return $this->success('');
    }


}

return 'msProductFileDownloadProcessor';
