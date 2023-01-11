<?php
include_once MODX_CORE_PATH . 'model/modx/modmanagercontroller.class.php';

/**
 * Class mspreMainController
 */
class msPreTvRenderController extends modExtraManagerController
{
}

class msPreTvRenderProcessor extends modProcessor
{
    /* @var modTemplateVar|null $tv */
    public $tv = null;
    /* @var modResource|null $resource */
    public $resource = null;

    public $classKey = 'modResource';
    public $languageTopics = array('mspre:default');

    public function initialize()
    {
        $tvname = $this->getProperty('tvname');
        if (empty($tvname)) {
            return $this->modx->lexicon('mspre_err_tvname');
        }

        $resource = (int)$this->getProperty('resource');
        if (empty($resource)) {
            return $this->modx->lexicon('mspre_err_resource');
        }


        $tvname = prefixTv($tvname);

        /* @var modTemplateVar $object */
        if (!$this->tv = $this->modx->getObject('modTemplateVar', array('name' => $tvname))) {
            return $this->modx->lexicon('mspre_err_tvname_could_not', array('tvname' => $tvname));
        }


        /* @var modTemplateVar $object */
        if (!$this->resource = $this->modx->getObject($this->classKey, $resource)) {
            return $this->modx->lexicon('mspre_err_resource_could_not');
        }

        $template = $this->resource->get('template');
        if (!$this->tv->hasTemplate($this->resource->get('template'))) {
            if (!$modTemplate = $this->modx->getObject('modTemplate', $template)) {
                return $this->modx->lexicon('mspre_err_resource_template');
            }
            return $this->modx->lexicon('mspre_tv_error_has_template', array('tv_name' => $tvname, 'tv_id' => $this->tv->get('id'), 'template_id' => $modTemplate->get('id'), 'template' => $modTemplate->get('templatename')));
        }
        return true;
    }


    public function process()
    {
        $this->modx->controller = new msPreTvRenderController($this->modx);
        // Инициализируем smarty иначе коннектор установится не верный
        if (!isset($this->modx->smarty)) {
            $this->modx->getService('smarty', 'smarty.modSmarty', '', array(
                'template_dir' => $this->modx->getOption('manager_path') . 'templates/' . $this->modx->getOption('manager_theme', null, 'default') . '/',
            ));
        }
        $this->modx->controller->setPlaceholders(array('_config' => $this->modx->config));

        // Меняем обработчик для типа поля richtext так как визуальный редактор у нас отключен по умолчанию
        if ($this->tv->get('type') == 'richtext') {
            $this->tv->set('type', 'textarea');
        }

        $this->input = $this->tv->renderInput($this->resource->get('id'));


        $id = prefixTvAdd($this->tv->get('name') . '-' . $this->tv->get('id'));

        $data = array(
            'tv_id' => $id,
            'html' => $this->renderHtml(),
            'js' => $this->renderJs(),
        );

        return $this->success('', $data);
    }


    public $input = null;

    /**
     * @return bool|string
     */
    public function renderHtml()
    {
        if ($input = $this->input) {
            $out = explode('<script', $input);

            $tv_id = $this->tv->get('id');

            $html = '<input type="hidden" id="tvdef' . $tv_id . '" value="">';
            $html .= $out[0];


            return $html;
        }
        return false;
    }

    /**
     * @return bool|string
     */
    public function renderJs()
    {
        $id = $this->tv->get('id');
        if ($input = $this->input) {
            $output = explode('// <![CDATA[', $input);
            #$output = explode('Ext.onReady(function() {', $output);
            $output = explode('// ]]>', $output[1]);

            $out = $output[0];


            if (strripos($out, 'MODx.fireResourceFormChange') !== false) {
                #$out = str_ireplace('MODx.fireResourceFormChange', 'mspre.grid.product.fireResourceFormChange', $out);
                #$out = str_ireplace('MODx.fireResourceFormChange', 'mspre.grid.product.fireResourceFormChange', $out);
            }


            #$tv_id = $this->tv->get('id');


            #$out = str_ireplace('modx-panel-resource', 'mspre-window-tv-image-html', $out);
            #$out = str_ireplace('modx-panel-resource', 'mspre-window-tv-image-html-formpanel' , $out);

#echo '<pre>';  print_r($out); die;

            /*$cutCode = array(
                "Ext.getCmp('modx-panel-resource').getForm().add(fld);",
                "Ext.getCmp('modx-panel-resource').getForm().add(fld{$tv_id});"
            );


            foreach ($cutCode as $rep) {
                if (strripos($out, $rep) !== false) {
                    $out = str_ireplace($rep, "", $out);
                }
            }*/


            //mspre.grid.product.fireResourceFormChange

            #if (strripos($out, 'MODx.makeDroppable') !== false) {
            #    $out = explode('MODx.makeDroppable', $out);
            #    $out = $out[0].'});';
            # }

            return $out;
        }
        return false;
    }

}

return 'msPreTvRenderProcessor';