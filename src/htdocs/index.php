<?php
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/cms'));
define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/cms/library'));

include_once LIBRARY_PATH . '/Lightnote/Loader.php';
spl_autoload_register('Lightnote\Loader::load');

include_once APPLICATION_PATH . '/config/config.php';

$application = new Lightnote\Application($config);
$application->run();
