<?php
define('APPLICATION_PATH', dirname(dirname(__FILE__)) . '/src/application');
define('LIBRARY_PATH', dirname(dirname(__FILE__)) . '/src/library');
define('DATA_PATH', dirname(__FILE__) . '/data');
define('APPLICATION_ENV', 'local');

include_once LIBRARY_PATH . '/Lightnote/Loader.php';
spl_autoload_register('Lightnote\Loader::load');
