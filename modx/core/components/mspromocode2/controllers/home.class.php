<?php

class msPromoCode2HomeManagerController extends modExtraManagerController
{
    /** @var msPromoCode2 $mspc2 */
    public $mspc2;

    /**
     *
     */
    public function initialize()
    {
        $this->mspc2 = $this->modx->getService('mspromocode2', 'msPromoCode2',
            $this->modx->getOption('mspc2_core_path', null, MODX_CORE_PATH . 'components/mspromocode2/') . 'model/mspromocode2/');

        parent::initialize();
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('mspromocode2:default');
    }

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }

    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('mspromocode2');
    }

    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->mspc2->loadManagerScripts();
    }

    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->mspc2->config['templatesPath'] . 'home.tpl';
    }
}