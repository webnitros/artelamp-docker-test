<?php
include_once dirname(__FILE__) .'/processor.php';

/**
 * Multiple a modResource
 */
class modmodResourceMultipleUpdatePagetitleProcessor extends msPreModProcessor
{
    public function process()
    {
        if (!$class_key = $this->getProperty('class_key', false)) {
            return $this->failure($this->modx->lexicon('mspre_error_class_key'));
        }

        $mode = 'insert';
        if (isset($this->properties['replace'])) {
            $mode = 'replace';
            if (!$replace = $this->getProperty('replace', false)) {
                return $this->failure($this->modx->lexicon('mspre_error_replace'));
            }
        }


        if (!$field = $this->getProperty('field', false)) {
            return $this->failure($this->modx->lexicon('mspre_error_field'));
        }


        if (!$new_value = $this->getProperty('new_value', false)) {
            $empty = $this->getProperty('empty', false);
            if (!$empty) {
                return $this->failure($this->modx->lexicon('mspre_error_field'));
            }
        }

        $resources = array();
        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        foreach ($ids as $id) {
            switch ($mode){
                case 'insert':
                    $resources[$id] = array(
                        $field => $new_value,
                    );
                    break;
                case 'replace':
                    if ($object = $this->modx->getObject($class_key, $id)) {
                        $value = $object->get($field);
                        // Замена производится только там где нашли
                        if (strripos($value, $replace) !== false) {
                            $value = str_ireplace($replace, $new_value, $value);
                            $resources[$id] = array(
                                $field => $value,
                            );
                        }
                    }
                    break;
                default:
                    break;
            }

        }


        $response = $this->getProcossor();
        if ($response !== true) {
            return $response;
        }


        if (!empty($resources)) {
            $i = 0;
            foreach ($resources as $id => $data) {
                $i++;
                if (!empty($id)) {
                    if ($response = $this->modx->runProcessor('update',
                        array_merge(array(
                            'id' => $id
                        ), $data),
                        array('processors_path' => $this->processor)
                    )
                    ) {
                        if ($response->isError()) {
                            return $response->getResponse();
                        }
                    }

                }
            }
        }

        return $this->success();
    }
}

return 'modmodResourceMultipleUpdatePagetitleProcessor';
