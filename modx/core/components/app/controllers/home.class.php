<?php

/**
 * The home manager controller for Fandeco1C.
 *
 */
class AppHomeManagerController extends modExtraManagerController
{
    /**
     *
     */
    public function initialize()
    {
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['fandeco1c:manager','fandeco1c:default'];
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
        return $this->modx->lexicon('fandeco1c');
    }




    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="fandeco1c-panel-home-div"></div>';
        return '';
    }
}