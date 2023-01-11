<?php

require_once MODX_CORE_PATH . 'model/modx/processors/resource/getnodes.class.php';

class msOptionCategoryGetNodesProcessor extends modResourceGetNodesProcessor
{
    protected $pid;
    protected $parent_id;
    protected $categories;
    public $classKey = 'msCategory';

    /** @var null|msOption */
    public $option = null;
    public $optionCategories = null;

    protected $map = array();
    /* @var modContext $objectContext */
    public $objectContext = null;

    /** {@inheritDoc} */
    public function initialize()
    {
        $initialize = parent::initialize();
        $this->pid = $this->getProperty('currentResource');
        if ($res = $this->modx->getObject('msProduct', $this->pid)) {
            $this->parent_id = $res->get('parent');
        }

        $mspre = $this->modx->getService('mspre', 'mspre', MODX_CORE_PATH . 'components/mspre/model/');
        $root_id = $this->getProperty('root_parent', $mspre->getOption('mspre_root_parent', array(), 0));
        if (!empty($root_id)) {
            $this->categories = explode(',', $root_id);
        }

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
        $this->prepareMap();

        $this->setDefaultProperties(array(
            'sortBy' => $this->modx->getOption('mspre_tree_default_sort', null, 'menuindex'),
            'nodeField' => $this->modx->getOption('mspre_resource_tree_node_name', null, 'pagetitle'),
            'nodeFieldFallback' => $this->modx->getOption('mspre_resource_tree_node_name_fallback', null, 'pagetitle'),
            'qtipField' => $this->modx->getOption('mspre_resource_tree_node_tooltip', null, ''),
        ));

        return $initialize;
    }

    public function prepare()
    {
        $this->contextKey = $this->getProperty('contextKey');
        parent::prepare();
    }


    /**
     * Get the query object for grabbing Contexts in the tree
     * @return xPDOQuery
     */
    public function getContextQuery()
    {
        $this->itemClass = 'modContext';
        $c = $this->modx->newQuery($this->itemClass, array('key:!=' => 'mgr'));
        if (!empty($this->defaultRootId)) {
            $c->where(array(
                "(SELECT COUNT(*) FROM {$this->modx->getTableName('modResource')} WHERE context_key = modContext.{$this->modx->escape('key')} AND id IN ({$this->defaultRootId})) > 0",
            ));
        }
        if ($this->modx->getOption('context_tree_sort', null, false)) {
            $ctxSortBy = $this->modx->getOption('context_tree_sortby', null, 'key');
            $ctxSortDir = $this->modx->getOption('context_tree_sortdir', null, 'ASC');
            $c->sortby($this->modx->getSelectColumns('modContext', 'modContext', '', array($ctxSortBy)), $ctxSortDir);
        }
        return $c;
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function process()
    {
        $this->getRootNode();
        $this->prepare();

        if (empty($this->contextKey) || $this->contextKey == 'root') {
            $c = $this->getContextQuery();
        } else {
            $c = $this->getResourceQuery();
        }


        $collection = $this->modx->getCollection($this->itemClass, $c);
        $search = $this->getProperty('search', '');
        if (!empty($search) && empty($node) && (empty($this->contextKey) || $this->contextKey == 'root')) {
            $this->search($search);
        }

        $this->iterate($collection);

        if ($this->getProperty('stringLiterals', false)) {
            return $this->modx->toJSON($this->items);
        } else {
            return $this->toJSON($this->items);
        }
    }


    /**
     * Gets all of the child resource ids for a given resource.
     *
     * @see getTree for hierarchical node results
     * @param integer $id The resource id for the starting node.
     * @param integer $depth How many levels max to search for children (default 10).
     * @param array $options An array of filtering options, such as 'context' to specify the context to grab from
     * @return array An array of all the child resource ids for the specified resource.
     */
    public function getChildIds($id = null, $depth = 10, $resourceMap)
    {
        $children = array();
        if ($id !== null && intval($depth) >= 1) {
            $id = is_int($id) ? $id : intval($id);
            if (isset ($resourceMap["{$id}"])) {
                if ($children = $resourceMap["{$id}"]) {
                    foreach ($children as $child) {
                        $processDepth = $depth - 1;
                        if ($c = $this->getChildIds($child, $processDepth, $resourceMap)) {
                            $children = array_merge($children, $c);
                        }
                    }
                }
            }
        }
        return $children;
    }

    /**
     * Gets all of the parent resource ids for a given resource.
     *
     * @param integer $id The resource id for the starting node.
     * @param integer $height How many levels max to search for parents (default 10).
     * @param array $options An array of filtering options, such as 'context' to specify the context to grab from
     * @return array An array of all the parent resource ids for the specified resource.
     */
    public function getParentIds($id = null, $height = 10, $context = 'web', $resourceMap = array())
    {
        $parentId = 0;
        $parents = array();
        if ($id && $height > 0) {
            foreach ($resourceMap as $parentId => $mapNode) {
                if (array_search($id, $mapNode) !== false) {
                    $parents[] = $parentId;
                    break;
                }
            }
            if ($parentId && !empty($parents)) {
                $height--;
                $parents = array_merge($parents, $this->getParentIds($parentId, $height, $context, $resourceMap));
            }
        }
        return $parents;
    }


    /**
     * Gets all of the child resource ids for a given resource.
     *
     * @see getTree for hierarchical node results
     * @param integer $id The resource id for the starting node.
     * @param integer $depth How many levels max to search for children (default 10).
     * @param array $options An array of filtering options, such as 'context' to specify the context to grab from
     * @return array An array of all the child resource ids for the specified resource.
     */
    public function getResourceCategory($context = null, $root_id = null)
    {
        $resourceMap = $parents = $ids = array();
        $q = $this->modx->newQuery('modResource');
        $q->select('id,parent');
        $q->where(array(
            'context_key' => $context,
            'class_key' => $this->classKey,
        ));
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $parents[] = $row['parent'];
                $ids[$row['id']] = $row['parent'];
            }
        }

        /**
         * Дополнительный под запрос для получения родительских категорий
         */
        $par = array();
        $parents = array_unique($parents);
        foreach ($parents as $parent) {
            if (!isset($ids[$parent]) and $parent != 0) {
                $par[] = $parent;
            }
        }


        if (count($par) > 0) {
            $ids = $this->getParentRoot($par, $ids);
        }

        if (count($ids) > 0) {
            foreach ($ids as $id => $parent) {
                $resourceMap[$parent][] = $id;
            }
        }
        return $resourceMap;
    }

    public function getParentRoot($parents, $ids)
    {

        $q = $this->modx->newQuery('modResource');
        $q->select('id,parent');
        $q->where(array(
            'id:IN' => $parents
        ));
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $parent = $row['parent'];
                $ids[$row['id']] = $parent;
                if ($parent != 0) {
                    $ids = $this->getParentRoot(array(0 => $parent), $ids);
                }
            }
        }
        return $ids;
    }

    public $RootId = 0;

    public function prepareMap()
    {
        $context = null;
        $ids = $resourceMap = array();
        if (!empty($this->categories)) {
            foreach ($this->categories as $val) {
                list($context, $root_id) = explode(':', $val);
                if ($context == $this->getProperty('contextKey')) {
                    $resourceMap = $this->getResourceCategory($context, $root_id);
                    $this->RootId = $root_id;
                    $ids = $this->getChildIds($root_id, 10, $resourceMap);
                }
            }
        } else {
            $context = $this->getProperty('contextKey');
            $resourceMap = $this->getResourceCategory($context);
            $ids = $this->getChildIds(0, 10, $resourceMap);

        }


        if (count($ids) > 0 and $context) {
            foreach ($ids as $cat) {
                $this->map[] = $cat;
                $parent = $this->getParentIds($cat, 1, $context, $resourceMap);
                if (in_array($parent, $this->map)) {
                    break;
                }
                $this->map[] = $parent[0];
            }
            $this->map = array_unique($this->map);
        }


    }

    /**
     * Determine the context and root and start nodes for the tree
     *
     * @return void
     */
    public function getRootNode()
    {
        $this->defaultRootId = $this->modx->getOption('tree_root_id', null, $this->RootId);
        $id = $this->getProperty('id');
        if (empty($id) || $id == 'root') {
            $this->startNode = $this->defaultRootId;
        } else {
            $parts = explode('_', $id);
            $this->contextKey = isset($parts[0]) ? $parts[0] : false;
            $this->startNode = !empty($parts[1]) ? intval($parts[1]) : 0;
        }
        if ($this->getProperty('debug')) {
            echo '<p style="width: 800px; font-family: \'Lucida Console\'; font-size: 11px">';
        }

        $this->defaultRootId = $this->RootId;
    }


    /** {@inheritDoc} */
    public function getResourceQuery()
    {
        $resourceColumns = array(
            'id'
        , 'pagetitle'
        , 'longtitle'
        , 'alias'
        , 'description'
        , 'parent'
        , 'published'
        , 'deleted'
        , 'isfolder'
        , 'menuindex'
        , 'menutitle'
        , 'hidemenu'
        , 'class_key'
        , 'context_key'
        , 'isfolder'
        );


        $this->itemClass = 'modResource';
        $c = $this->modx->newQuery($this->itemClass);
        $c->leftJoin('modResource', 'Child', array('modResource.id = Child.parent'));
        $c->select($this->modx->getSelectColumns('modResource', 'modResource', '', $resourceColumns));
        $c->select(array(
            'childrenCount' => 'COUNT(Child.id)',
        ));
        $c->where(array(
            'context_key' => $this->contextKey,
            #'class_key' => $this->classKey
        ));


        if (empty($this->startNode) && !empty($this->defaultRootId)) {
            $c->where(array(
                'id:IN' => explode(',', $this->defaultRootId),
                'parent:NOT IN' => explode(',', $this->defaultRootId),
            ));
        } else {
            $c->where(array(
                'parent' => $this->startNode,
            ));
        }
        $c->groupby($this->modx->getSelectColumns('modResource', 'modResource', '', $resourceColumns), '');
        $c->sortby('modResource.' . $this->getProperty('sortBy'), $this->getProperty('sortDir'));
        #$c->prepare();


        return $c;
    }


    /** {@inheritDoc} */
    public function prepareContextNode(modContext $context)
    {
        $context->prepare();
        return array(
            'text' => $context->get('key')
        , 'id' => $context->get('key') . '_0'
        , 'pk' => $context->get('key')
        , 'ctx' => $context->get('key')
        , 'leaf' => false
        , 'cls' => 'icon-context'
        , 'iconCls' => $this->modx->getOption('mgr_tree_icon_context', null, 'tree-context')
        , 'qtip' => $context->get('description') != '' ? strip_tags($context->get('description')) : ''
        , 'type' => 'modContext'
        );
    }


    /** {@inheritDoc} */
    public function prepareResourceNode(modResource $resource)
    {

        // show only categories and their parents
        if (!in_array($resource->get('id'), $this->map)) {
            return false;
        }

        $qtipField = $this->getProperty('qtipField');
        $nodeField = $this->getProperty('nodeField');
        $nodeFieldFallback = $this->getProperty('nodeFieldFallback');

        $hasChildren = (int)$resource->get('childrenCount') > 0;
        //show only categories or not empty folders
        if ($this->classKey == 'msCategory') {
            if (!$hasChildren && !($resource instanceof msCategory)) {
                return false;
            }
        }


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


        if ($itemArray['classKey'] != $this->classKey) {
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
        $data = $this->getProperty('categories');
        if (!empty($data)) {
            $data = is_array($data) ? $data : $this->modx->fromJSON($data);
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