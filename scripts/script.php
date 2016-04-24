<?php

require_once "vendor/autoload.php";

define('DS','/');

define ("MUNCIPIOS_URL","http://www.ine.es/daco/daco42/codmun/codmun%02d/%02dcodmun.xls");
define ("PROVINCIAS_URL","http://www.ine.es/daco/daco42/codmun/cod_provincia.htm");
define ("AUTONOMIAS_URL","http://www.ine.es/daco/daco42/codmun/cod_ccaa.htm");
define ("ISLAS_URL","http://www.ine.es/daco/daco42/codmun/codmun%02d/%02dcodislas%02d.xls");

define ("MUNCIPIOS_YEAR_START",2004); // Hay datos desde 2001, pero necesitan tratamiento especial. To Do
define ("ISLAS_YEAR_START",2012); // hay datos desde 2008, pero no incluye el codigo de Isla. To Do
define ("ISLAS_PROVINCIA_INE_CODES",serialize([07,35,38])); //PHP <5.6 compatible (no soporta arrays como constantes)

define ("DATA_FOLDER","../data");
define ("ARCHIVE_FOLDER","../archive");

define ("PROVINCIAS_SOURCE_FILE","cod_provincia.htm");
define ("AUTONOMIAS_SOURCE_FILE","cod_ccaa.htm");

define ("MUNICIPIOS_SOURCE_FILE","%02dcodmun.xls");
define ("ISLAS_SOURCE_FILE","%02dcodislas%02d.xls");

define ("PROVINCIAS_DEST_FILE","provincias");
define ("AUTONOMIAS_DEST_FILE","autonomias");
define ("MUNICIPIOS_DEST_FILE","municipios");
define ("MUNICIPIOS_HISTORICAL_DEST_FILE","municipios_historical");

define ("ISLAS_DEST_FILE","islas");
define ("MUNICIPIOS_ISLAS_HISTORICAL_DEST_FILE","municipios_islas_historical");
define ("MUNICIPIOS_ISLAS_DEST_FILE","municipios_islas");


// Main

$console = new ConsoleKit\Console();
$console->addCommandsFromDir("lib",null,true);

if (sizeof($argv)==1) {
    $console->run(['download']);
    $console->run(['process']);
} else {
    $console->run();
}

