<?php

require_once MODX_CORE_PATH . 'model/modx/processors/resource/getnodes.class.php';

class msOptionCategoryGetNodesProcessor extends modResourceGetNodesProcessor
{
    /* @var modContext $objectContext */
    public $objectContext = null;
    public $optionCategories = null;

    /** {@inheritDoc} */
    public function initialize()
    {
        $initialize = parent::initialize();
        if ($contextKey = $this->getProperty('contextKey', false)) {
            $this->contextKey = $contextKey;
        } else {
            $this->contextKey = $this->getProperty('context', false);
            $this->setProperty('contextKey', $this->contextKey);
            if (!$this->contextKey) {
                return false;
            }
        }

        $this->objectContext = $this->modx->getObject('modContext', $this->contextKey);
        $this->setDefaultProperties(array(
            'sortBy' => $this->modx->getOption('mspre_tree_default_sort',null,'menuindex'),
            'nodeField' => $this->modx->getOption('mspre_resource_tree_node_name',null,'pagetitle'),
            'nodeFieldFallback' => $this->modx->getOption('mspre_resource_tree_node_name_fallback',null,'pagetitle'),
            'qtipField' => $this->modx->getOption('mspre_resource_tree_node_tooltip',null,''),
        ));
        return $initialize;
    }

    /** {@inheritDoc} */
    public function prepareResourceNode(modResource $resource)
    {

        $qtipField = $this->getProperty('qtipField');
        $nodeField = $this->getProperty('nodeField');
        $nodeFieldFallback = $this->getProperty('nodeFieldFallback');
        $hasChildren = (int)$resource->get('childrenCount') > 0;
        //show only categories or not empty folders
        # if (!$hasChildren && !($resource instanceof msCategory)) {
        #    return false;
        #}


        // Assign an icon class based on the class_key
        $class = $iconCls = array();
        $classKey = strtolower($resource->get('class_key'));
        if (substr($classKey, 0, 3) == 'mod') {
            $classKey = substr($classKey, 3);
        }
        $classKeyIcon = $this->modx->getOption('mgr_tree_icon_' . $classKey, null, 'tree-resource');
        $iconCls[] = $classKeyIcon;

        $class[] = 'icon-' . strtolower(str_replace('mod', '', $resource->get('class_key')));
        if (!$resource->isfolder) {
            $class[] = 'x-tree-node-leaf icon-resource';
        }
        if (!$resource->get('published')) $class[] = 'unpublished';
        if ($resource->get('deleted')) $class[] = 'deleted';
        if ($resource->get('hidemenu')) $class[] = 'hidemenu';
        if ($hasChildren) {
            $class[] = 'haschildren';
            $iconCls[] = $this->modx->getOption('mgr_tree_icon_folder', null, 'tree-folder');
            $iconCls[] = 'parent-resource';
        }


        // Дополнительные пункты контекстного меню
        $class[] = 'pview';
        $class[] = 'pedit';
        #$class[] = 'unpublished';
        $class[] = 'punpublish';
        $class[] = 'pundelete';
        $class[] = 'ppublish';
        $class[] = 'pqcreate';
        $class[] = 'pqupdate';
        $class[] = 'pduplicate';
        $class[] = 'pnewdoc';
        $class[] = 'pnew';
        $class[] = 'psave';
        $class[] = 'pdelete';


        if ($classKey == 'document') {
            $class[] = 'pnewdoc pnew_modWebLink';
            $class[] = 'pnewdoc pnew_modSymLink';
            $class[] = 'pnewdoc pnew_modDocument';
            $class[] = 'pnewdoc pnew_modStaticResource';
            $class[] = 'pnew pnew_modStaticResource';
            $class[] = 'pnew pnew_modSymLink';
            $class[] = 'pnew pnew_modWebLink';
            $class[] = 'pnew pnew_modDocument';
        }

        # $class[] = 'x-tree-node-leaf';

        $qtip = '';
        if (!empty($qtipField) and !empty($resource->$qtipField)) {
            $qtip = '<b>' . strip_tags($resource->$qtipField) . '</b>';
        } else {

            if ($resource->get('longtitle') != '') {
                $qtip = '<b>' . strip_tags($resource->get('longtitle')) . '</b><br />';
            } else if ($resource->get('pagetitle') != '') {
                $qtip = '<b>' . strip_tags($resource->get('pagetitle')) . '</b><br />';
            }
            if ($resource->get('description') != '') {
                $qtip = '<i>' . strip_tags($resource->get('description')) . '</i>';
            }
        }


        $idNote = $this->modx->hasPermission('tree_show_resource_ids') ? ' <span dir="ltr">(' . $resource->id . ')</span>' : '';
        #$count = $this->modx->getCount('modResource', array('parent' => $resource->id));
        $disabled = false;
        #$disabled = !empty($count) ? true : false;


        if (!empty($qtip)) {
            if (!empty($nodeField) and !empty($resource->$nodeField)) {
                $qtip = '<b>' . strip_tags($resource->$nodeField) . '</b>';
            } else {
                if ($resource->get('longtitle') != '') {
                    $qtip = '<b>' . strip_tags($resource->get('longtitle')) . '</b><br />';
                }
                if ($resource->get('description') != '') {
                    $qtip = '<i>' . strip_tags($resource->get('description')) . '</i>';
                }
            }
        }

        $text = strip_tags($resource->get($nodeField));
        if (empty($text)) {
            $text = $resource->get($nodeFieldFallback);
            $text = strip_tags($text);
        }
        $itemArray = array(
            'text' => $text . $idNote,
            'id' => $resource->context_key . '_' . $resource->id,
            'pk' => $resource->id,
            'cls' => implode(' ', $class),
            'iconCls' => implode(' ', $iconCls),
            'type' => 'modResource',
            'classKey' => $resource->class_key,
            'ctx' => $resource->context_key,
            'hide_children_in_tree' => $resource->hide_children_in_tree,
            'qtip' => $qtip,
            'checked' => $this->getChecked($resource),//!empty($resource->member) || $resource->id == $this->parent_id ? true : false,
            'disabled' => $disabled,
            //'disabled' => $this->modx->getChildIds($resource->id) ? true : false,
            //'disabled' =>  $resource->id == $this->parent_id ? true : false
        );

        $itemArray['preview_url'] = '';
        if (!$resource->get('deleted')) {
            $itemArray['preview_url'] = $this->modx->makeUrl($resource->get('id'), $resource->get('context_key'), '', 'full');
        }

        if (!$hasChildren) {
            $itemArray['hasChildren'] = false;
            $itemArray['children'] = array();
            $itemArray['expanded'] = true;
        } else {
            $itemArray['hasChildren'] = true;
        }


        if (!$hasChildren) {
            unset($itemArray['checked']);
        }


        if ($this->objectContext) {
            $itemArray['settings'] = array(
                'default_template' => $this->objectContext->getOption('default_template'),
                'richtext_default' => $this->objectContext->getOption('richtext_default'),
                'hidemenu_default' => $this->objectContext->getOption('hidemenu_default'),
                'search_default' => $this->objectContext->getOption('search_default'),
                'cache_default' => $this->objectContext->getOption('cache_default'),
                'publish_default' => $this->objectContext->getOption('publish_default'),
                'default_content_type' => $this->objectContext->getOption('default_content_type'),
            );
        }
        return $itemArray;
    }


    /**
     * @param modResource $resource
     * @return bool
     */
    public function getChecked($resource)
    {

        // TODO не возвращает выбранную категорию
        $data = $this->getProperty('categories');
        if (!empty($data)) {
            $data = $this->modx->fromJSON($data);
            if (!empty($data)) {
                $id = $resource->get('id');

                if (in_array($id, $data)) {
                    return true;
                }
            }
        }

        /** @var modResource $cat */
        if ($this->optionCategories) {
            if (count($this->optionCategories) > 0) {
                foreach ($this->optionCategories as $key => $cat) {
                    if ($resource->get('id') == $cat->get('category_id')) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

}

return 'msOptionCategoryGetNodesProcessor';