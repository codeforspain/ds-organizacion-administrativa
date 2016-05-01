<?php

require_once "vendor/autoload.php";
require_once('lib/Config.php');

define('DS','/');

define ('BASE_PATH', dirname(__DIR__));
define ('DATA_FOLDER',BASE_PATH . DS . Config::DATA_FOLDER );
define ('ARCHIVE_FOLDER',BASE_PATH . DS . Config::ARCHIVE_FOLDER );

// Comprobamos si los directorios están creados. Si no lo están, los creamos
is_dir(DATA_FOLDER) || mkdir(DATA_FOLDER);
is_dir(ARCHIVE_FOLDER) || mkdir(ARCHIVE_FOLDER);


// Inicializamos consola
$console = new ConsoleKit\Console();
$console->addCommandsFromDir("lib",null,true);

if (sizeof($argv)==1) {
    $console->run(['download']);
    $console->run(['process']);
    $console->run(['update']);
    $console->run(['convert-to-json']);
} else {
    $console->run();
}

