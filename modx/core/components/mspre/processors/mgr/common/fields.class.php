<?php

/**
 * Multiple a msProduct
 */
class modmsFieldsProcessor extends modProcessor
{
    public $languageTopics = array('mspre');
    public $processors_path = null;

    public function process()
    {
        if (!$controller = $this->getProperty('controller', false)) {
            return $this->failure($this->modx->lexicon('mspre_error_controller'));
        }

        if (!$mode = $this->getProperty('mode', false)) {
            return $this->failure($this->modx->lexicon('mspre_error_mode'));
        }

        if (!$fields = $this->getProperty('fields', false)) {
            return $this->failure($this->modx->lexicon('mspre_error_fields'));
        }

        $fields = is_array($fields) ? $fields : $this->modx->fromJSON($fields);
        if (!isset($fields['selected_fields'])) {
            return $this->failure($this->modx->lexicon('mspre_error_selected_fields'));
        }


        if (empty($fields['selected_fields'])) {
            return $this->failure($this->modx->lexicon('mspre_error_selected_fields'));
        }


        $selected = '';
        $selected_fields = false;
        if (!empty($fields['selected_fields'])) {
            $selected_fields = is_array($fields['selected_fields']) ? $fields['selected_fields'] : $this->modx->fromJSON($fields['selected_fields']);
        }

        
        if (empty($selected_fields)) {
            return $this->failure($this->modx->lexicon('mspre_error_selected_fields'));
        }
        if ($selected_fields) {
            $selected = array();
            $add = array();
            foreach ($selected_fields as $row) {
                $field = trim($row['field']);
                if (!in_array($field, $add)) {
                    $size = isset($row['size']) ? trim($row['size']) : false;
                    $add[] = $field;
                    $selected[] = $size ? $field . ':' . $size : $field;
                }
            }

            if (empty($selected)) {
                return $this->failure($this->modx->lexicon('mspre_error_selected'));
            }
            $selected = implode(',', $selected);
        }



        $optionKey = 'mspre_' . $controller . '_' . $mode . '_selected_fields';



        $saveSettingUser = (boolean)$this->modx->getOption('mspre_enable_save_setting_user');

        if ($saveSettingUser) {
            if (!$this->modx->user->isAuthenticated('mgr')) {
                return $this->failure($this->modx->lexicon('mspre_error_auth_mgr'));
            }

            /* @var modUserSetting $object*/
            if(!$object = $this->modx->getObject('modUserSetting', array('user' => $this->modx->user->get('id'), 'key' => $optionKey))){
               /* @var modUserSetting $object*/
               $object = $this->modx->newObject('modUserSetting');
               $object->set('user',$this->modx->user->get('id'));
            }

            $object->set('key',$optionKey);
            $object->set('area','mspre_fields');
            $object->set('xtype','textfield');
            $object->set('namespace','mspre');
            $object->set('editedon',time());
            
        } else {
            if (!$object = $this->modx->getObject('modSystemSetting', array('key' => $optionKey))) {
                return $this->failure($this->modx->lexicon('mspre_error_systemsetting'));
            }
        }
        $object->set('value', $selected);

      

        if (!$object->save()) {
            return $this->failure($this->modx->lexicon('mspre_error_systemsetting'));
        }

        #$this->modx->reloadConfig();

        $this->modx->getCacheManager();
        $this->modx->cacheManager->refresh(array(
            'system_settings' => array()
        ));

        return $this->success();
    }
}

return 'modmsFieldsProcessor';