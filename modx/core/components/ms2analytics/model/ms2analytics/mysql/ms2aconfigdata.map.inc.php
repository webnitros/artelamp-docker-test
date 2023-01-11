<?php
$xpdo_meta_map['Ms2aConfigData']= array (
  'package' => 'ms2analytics',
  'version' => '1.1',
  'table' => 'ms2a_config_data',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' =>
  array (
    'engine' => 'MyISAM',
  ),
  'fields' =>
  array (
    'key' => NULL,
    'value' => NULL,
    'default' => NULL,
    'category' => NULL,
  ),
  'fieldMeta' =>
  array (
    'key' =>
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
    'value' =>
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'default' =>
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'category' =>
    array (
        'dbtype' => 'varchar',
        'precision' => '50',
        'phptype' => 'string',
        'null' => true,
        'index' => 'index',
    ),
  ),
  'indexes' =>
  array (
    'key' =>
    array (
      'alias' => 'key',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' =>
      array (
        'key' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'category' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
);
