<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->initDatabaseMapFromDumps(array (
  'default' => 
  array (
    'tablesByName' => 
    array (
      'config' => '\\Propel\\Map\\ConfigTableMap',
      'files' => '\\Propel\\Map\\FilesTableMap',
      'import_history' => '\\Propel\\Map\\ImportHistoryTableMap',
    ),
    'tablesByPhpName' => 
    array (
      '\\Config' => '\\Propel\\Map\\ConfigTableMap',
      '\\Files' => '\\Propel\\Map\\FilesTableMap',
      '\\ImportHistory' => '\\Propel\\Map\\ImportHistoryTableMap',
    ),
  ),
));
