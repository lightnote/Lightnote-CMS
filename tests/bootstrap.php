<?php
define('APPLICATION_PATH', dirname(dirname(__FILE__)) . '/src/htdocs/cms');
define('LIBRARY_PATH', dirname(dirname(__FILE__)) . '/src/htdocs/cms/library');
define('TEST_PATH', dirname(__FILE__));
define('DATA_PATH', dirname(__FILE__) . '/data');
define('APPLICATION_ENV', 'local');

error_reporting(E_ALL);
ini_set('display_errors', 'on');

include_once LIBRARY_PATH . '/Lightnote/Loader.php';
spl_autoload_register('Lightnote\Loader::load');
\Lightnote\Session::$isUnitTest = true;
