<?php

$config = new Lightnote\Config();
$config->setProperty('lightnote.repository.adapter', 'MySql');
$config->setProperty('lightnote.repository.config.host', 'localhost');
$config->setProperty('lightnote.repository.config.username', 'root');
$config->setProperty('lightnote.repository.config.password', 'test1');
$config->setProperty('lightnote.repository.config.database', 'lightnote');
$config->setProperty('lightnote.module.backend.path', 'backend');