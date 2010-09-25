<?php
define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
define('APPLICATION_ENV', 'local');


include_once LIBRARY_PATH . '/Lightnote/Loader.php';
spl_autoload_register('Lightnote\Loader::load');

$application = new Lightnote\Application();
$application->run();
