<?php
include_once dirname(dirname(dirname(__FILE__))) . '/common/trait.php';
class msPreTvUpdateProcessor extends modProcessor
{
    use msPreTrait;
    /* @var modTemplateVar|null $tv */
    public $tv = null;
    /* @var modResource|null $object */
    public $object = null;

    public $classKey = 'modResource';
    public $languageTopics = array('mspre:default');

    public function initialize()
    {
        $field_name = $this->getProperty('field_name');
        if (empty($field_name)) {
            return $this->modx->lexicon('mspre_err_field_name');
        }

        $id = (int)$this->getProperty('id');
        if (empty($id)) {
            return $this->modx->lexicon('mspre_err_resource');
        }

        $field_value = $this->getProperty('field_value');
        if (empty($field_value)) {
            return $this->modx->lexicon('mspre_err_values');
        }

        $tvname = prefixTv($field_name);
        $this->setProperty('field_name', $tvname);

        /* @var modTemplateVar $object */
        if (!$this->tv = $this->modx->getObject('modTemplateVar', array('name' => $tvname))) {
            return $this->modx->lexicon('mspre_err_tvname_could_not', array('tvname' => $tvname));
        }

        /* @var modTemplateVar $object */
        if (!$this->object = $this->modx->getObject($this->classKey, $id)) {
            return $this->modx->lexicon('mspre_err_resource_could_not');
        }

        $template = $this->object->get('template');
        if (!$this->tv->hasTemplate($this->object->get('template'))) {
            if (!$modTemplate = $this->modx->getObject('modTemplate', $template)) {
                return $this->modx->lexicon('mspre_err_resource_template');
            }
            return $this->modx->lexicon('mspre_tv_error_has_template', array('tv_name' => $tvname, 'tv_id' => $this->tv->get('id'), 'template_id' => $modTemplate->get('id'), 'template' => $modTemplate->get('templatename')));
        }
        return true;
    }


    public function process()
    {
        $field_name = $this->getProperty('field_name');
        $values = $this->getProperty('field_value');
        if (empty($values)) {
            return $this->modx->lexicon('mspre_err_values');
        }

        $this->unsetProperty('field_name');
        $this->unsetProperty('field_value');

        $values = $this->modx->fromJSON($values);
        foreach ($values as $field => $value) {
            $field = str_ireplace('[]', '', $field);
            $this->setProperty($field, $value);
        }


        $response = $this->saveTemplateVariables($this->tv);
        if ($response !== true) {
            $msg = "Не удалось сохранить ТВ параметр {$field_name}";
            $this->modx->log(modX::LOG_LEVEL_ERROR, $msg, '', __METHOD__, __FILE__, __LINE__);
            return $this->failure($msg);
        }
        return $this->success();
    }

    /**
     * Set any Template Variables passed to the Resource. You must pass "tvs" as 1 or true to initiate these checks.
     * @return array|mixed
     */
    public function saveTemplateVariables($tv)
    {
        /** @var modTemplateVar $tv */

        if ($tv->checkResourceGroupAccess()) {
            $tvKey = 'tv' . $tv->get('id');
            $value = $this->getProperty($tvKey, null);

            /* set value of TV */
            if ($tv->get('type') != 'checkbox') {
                $value = $value !== null ? $value : $tv->get('default_text');
            } else {
                $value = $value ? $value : '';
            }

            /* validation for different types */
            switch ($tv->get('type')) {
                case 'url':
                    $prefix = $this->getProperty($tvKey . '_prefix', '');
                    if ($prefix != '--') {
                        $value = str_replace(array('ftp://', 'http://'), '', $value);
                        $value = $prefix . $value;
                    }
                    break;
                case 'date':
                    $value = empty($value) ? '' : strftime('%Y-%m-%d %H:%M:%S', strtotime($value));
                    break;
                /* ensure tag types trim whitespace from tags */
                case 'tag':
                case 'autotag':
                    $tags = explode(',', $value);
                    $newTags = array();
                    foreach ($tags as $tag) {
                        $newTags[] = trim($tag);
                    }
                    $value = implode(',', $newTags);
                    break;
                default:
                    /* handles checkboxes & multiple selects elements */
                    if (is_array($value)) {
                        $featureInsert = array();
                        while (list($featureValue, $featureItem) = each($value)) {
                            if (isset($featureItem) && $featureItem === '') {
                                continue;
                            }
                            $featureInsert[count($featureInsert)] = $featureItem;
                        }
                        $value = implode('||', $featureInsert);
                    }
                    break;
            }

            /* if different than default and set, set TVR record */
            $default = $tv->processBindings($tv->get('default_text'), $this->object->get('id'));
            if (strcmp($value, $default) != 0) {

                /* update the existing record */
                $tvc = $this->modx->getObject('modTemplateVarResource', array(
                    'tmplvarid' => $tv->get('id'),
                    'contentid' => $this->object->get('id'),
                ));

                if ($tvc == null) {
                    /** @var modTemplateVarResource $tvc add a new record */
                    $tvc = $this->modx->newObject('modTemplateVarResource');
                    $tvc->set('tmplvarid', $tv->get('id'));
                    $tvc->set('contentid', $this->object->get('id'));
                }
                $tvc->set('value', $value);
                return $tvc->save();

                /* if equal to default value, erase TVR record */
            } else {
                $tvc = $this->modx->getObject('modTemplateVarResource', array(
                    'tmplvarid' => $tv->get('id'),
                    'contentid' => $this->object->get('id'),
                ));
                if (!empty($tvc)) {
                    return $tvc->remove();
                }
            }
        }
        return true;
    }
}

return 'msPreTvUpdateProcessor';