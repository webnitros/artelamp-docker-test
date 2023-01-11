<?php

class msPreCheckTemplateAccessProcessor extends modProcessor
{
    /* @var modResource|null $resource */
    public $resource = null;

    /* @var modTemplateVar|null $tv */
    public $tv = null;
    /* @var modTemplate|null $template */
    public $template = null;
    public $languageTopics = array('mspre:default');

    public function initialize()
    {

        $template = (int)$this->getProperty('template');
        $resource = (int)$this->getProperty('resource');
        if (empty($resource) and empty($template)) {
            return $this->modx->lexicon('mspre_err_access_template');
        }


        $tvname = $this->getProperty('tvname');
        if (empty($tvname)) {
            return $this->modx->lexicon('mspre_err_field');
        }


        $tvname = prefixTv($tvname);
        if (!$this->tv = $this->modx->getObject('modTemplateVar', array('name' => $tvname))) {
            return $this->modx->lexicon('mspre_err_tvname_could_not', array('tvname' => $tvname));
        }


        if ($resource) {
            if (!$this->resource = $this->modx->getObject('modResource', $resource)) {
                return $this->modx->lexicon('mspre_err_tvname_could_not_resource');
            } else {
                $template = $this->resource->get('template');
                if (empty($template)) {
                    return $this->modx->lexicon('mspre_err_resource_template', array('resource' => $this->resource->get('id')));
                }
            }
        }

        if (!empty($template)) {
            if (!$this->template = $this->modx->getObject('modTemplate', $template)) {
                return $this->failure($this->modx->lexicon('mspre_err_resource_template'));
            }
        } else {
            return $this->modx->lexicon('mspre_err_access_template');
        }

        return true;
    }

    public function process()
    {
        $id = $this->template->get('id');

        $access = false;
        if ($this->tv->hasTemplate($id)) {
            $access = true;
        }

        $templates = array(
            $id => array(
                'id' => $id,
                'access' => true,
            )
        );

        $templates = $this->modx->toJSON($templates);
        return $this->success('', array(
            'access' => $access,
            'template_id' => $this->template->get('id'),
            'template_name' => $this->template->get('templatename'),
            'tv_id' => $this->tv->get('id'),
            'tv_name' => $this->tv->get('name'),
            'tv_caption' => $this->tv->get('caption'),
            'templates' => $templates,
        ));
    }
}

return 'msPreCheckTemplateAccessProcessor';