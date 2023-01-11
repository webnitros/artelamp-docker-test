<?php
$xpdo_meta_map['ulLocation']= array (
  'package' => 'userlocation',
  'version' => '1.1',
  'table' => 'ul_locations',
  'extends' => 'xPDOObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'id' => NULL,
    'name' => NULL,
    'type' => '',
    'postal' => '',
    'gninmb' => '',
    'okato' => '',
    'oktmo' => '',
    'fias' => '',
    'active' => 1,
    'parent' => '',
    'resource' => 0,
    'description' => '',
    'properties' => NULL,
  ),
  'fieldMeta' => 
  array (
    'id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '15',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
      'comment' => 'Код адресного объекта из КЛАДР объекта',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '120',
      'phptype' => 'string',
      'null' => false,
      'comment' => 'Наименование объекта',
    ),
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
      'comment' => 'Тип объекта',
    ),
    'postal' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
      'comment' => 'Почтовый индекс объекта',
    ),
    'gninmb' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
      'comment' => 'Код ИФНС объекта',
    ),
    'okato' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '11',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
      'comment' => 'Код ОКАТО объекта',
    ),
    'oktmo' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '8',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
      'comment' => 'Код ОКТМО объекта',
    ),
    'fias' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '36',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
      'comment' => 'Код ФИАС объекта',
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => true,
      'default' => 1,
    ),
    'parent' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '15',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
      'comment' => 'Код родителя объекта',
    ),
    'resource' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'id' => 
    array (
      'alias' => 'id',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'id' => 
        array (
          'length' => '15',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
        array (
          'length' => '120',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'type' => 
    array (
      'alias' => 'type',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'type' => 
        array (
          'length' => '50',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'postal' => 
    array (
      'alias' => 'postal',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'postal' => 
        array (
          'length' => '10',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'gninmb' => 
    array (
      'alias' => 'gninmb',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'gninmb' => 
        array (
          'length' => '10',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'okato' => 
    array (
      'alias' => 'okato',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'okato' => 
        array (
          'length' => '11',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'oktmo' => 
    array (
      'alias' => 'oktmo',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'oktmo' => 
        array (
          'length' => '8',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'fias' => 
    array (
      'alias' => 'fias',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'fias' => 
        array (
          'length' => '36',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'resource' => 
    array (
      'alias' => 'resource',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'resource' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
