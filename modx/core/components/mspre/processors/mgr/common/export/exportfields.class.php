<?php

/**
 * Multiple a msProduct
 */
class modmsProductExportFieldsProcessor extends modProcessor
{
    public function process()
    {
        $this->unsetProperty('action');
        $fields = $this->getProperties();
        $fieldSave = array();
        foreach ($fields as $field => $checked) {
            $fieldSave[] = $field . ':' . $checked;
        }

        /* @var modSystemSetting $object*/
        if ($object = $this->modx->getObject('modSystemSetting', 'mspre_export_fields_product')) {
            $object->set('value', implode(',', $fieldSave));
            $object->save();
            $this->modx->reloadConfig();
        }


        $export_fields = array();
        foreach ($fieldSave as $k => $export_field) {
            list($field, $checked) = explode(':', $export_field);
            $export_fields[$field] = (boolean)$checked;
        }

        return $this->success('', array('fields_export' => $export_fields));
    }
}

return 'modmsProductExportFieldsProcessor';