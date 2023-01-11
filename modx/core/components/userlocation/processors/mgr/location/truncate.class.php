<?php

/**
 * Multiple a ulLocation
 */
class ulLocationTruncateProcessor extends modProcessor
{
    public $classKey = 'ulLocation';
    public $permission = '';

    /** {@inheritDoc} */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::initialize();
    }

    public function process()
    {
        $table = $this->modx->getTableName($this->classKey);

        $this->modx->exec("TRUNCATE {$table};ALTER TABLE {$table} AUTO_INCREMENT = 0;");

        return $this->success();
    }
}

return 'ulLocationTruncateProcessor';