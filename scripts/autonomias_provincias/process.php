<?php

require_once "vendor/autoload.php";
use Sunra\PhpSimple\HtmlDomParser;

$forceDownload = false; // por defect no se descargan las fuentes si ya existen

// Parametros
$urlProvincias = "http://www.ine.es/daco/daco42/codmun/cod_provincia.htm";
$urlAutonomias = "http://www.ine.es/daco/daco42/codmun/cod_ccaa.htm";
$dataFolder = "../../data";
$archiveFolder = "../../archive";
$provinciasSourceFile = "cod_provincia.htm";
$autonomiasSourceFile = "cod_ccaa.htm";
$provinciasDestFile = "provincias";
$autonomiasDestFile = "autonomias";

// Creamos la carpeta si no existe
is_dir($dataFolder) || mkdir($dataFolder);


// Procesamos opciones de entrada

$shortopts = "p";  // pretty_print
$shortopts .= "e"; // unescaped_unicode
$shortopts .= "h"; // unescaped_unicode
$shortopts .= "d"; // unescaped_unicode

$longopts  = array(
    "pretty_print",
    "escaped_unicode",
    "help",
    "download",
);

$options = getopt($shortopts,$longopts);

$jsonOptions = JSON_UNESCAPED_UNICODE; //default

if (array_key_exists('pretty_print',$options) || array_key_exists('p',$options)) {
    $jsonOptions+=JSON_PRETTY_PRINT;
}

if (array_key_exists('escaped_unicode',$options) || array_key_exists('e',$options)) {
    $jsonOptions-=JSON_UNESCAPED_UNICODE;
}

if (array_key_exists('help',$options) || array_key_exists('h',$options)) {
    showHelp();
    exit;
}
if (array_key_exists('download',$options) || array_key_exists('d',$options)) {
    $forceDownload = true;
}


// Descargamos fuentes si no existen

if (!file_exists("$archiveFolder/$provinciasSourceFile") || $forceDownload){
    file_put_contents("$archiveFolder/$provinciasSourceFile", fopen($urlProvincias, 'r'));
}

if (!file_exists("$archiveFolder/$autonomiasSourceFile") || $forceDownload){
    file_put_contents("$archiveFolder/$autonomiasSourceFile", fopen($urlAutonomias, 'r'));
}

// PROCESAMOS PROVINCIAS

$html=iconv("ISO-8859-15", "UTF-8", file_get_contents($urlProvincias));

$dom = HtmlDomParser::str_get_html( $html );

$provincias=[];

foreach($dom->find('table[summary$=quetacion]') as $table) {
    foreach($table->find('tr[!valign]') as $row){
        $provincias[] = [
            'codigo' => trim($row->children(0)->innertext),
            'nombre' => html_entity_decode(trim($row->children(1)->innertext)),
        ];


    };
};

// Grabamos JSON
$file = fopen("$dataFolder/$provinciasDestFile.json",'w+');
fwrite($file, json_encode($provincias,$jsonOptions) );
fclose($file);

// Grabamos csv

$file = fopen("$dataFolder/$provinciasDestFile.csv",'w+');
fputcsv($file, array_keys($provincias[0]));

foreach ($provincias as $provincia) {
    fputcsv($file, $provincia);
}
fclose($file);



// PROCESAMOS AUTONOMIAS

$html=iconv("ISO-8859-15", "UTF-8", file_get_contents($urlAutonomias));

$dom = HtmlDomParser::str_get_html( $html );

$autonomias=[];

$table=$dom->find('table',0);

$rows =  array_slice($table->find('tr'),1);
foreach($rows as $row){
    $autonomias[] = [
        'codigo' => trim($row->children(0)->innertext),
        'nombre' => html_entity_decode(trim($row->children(1)->innertext)),
    ];
};

// Grabamos JSON
$file = fopen("$dataFolder/$autonomiasDestFile.json",'w+');
fwrite($file, json_encode($autonomias,$jsonOptions) );
fclose($file);

// Grabamos csv

$file = fopen("$dataFolder/$autonomiasDestFile.csv",'w+');
fputcsv($file, array_keys($autonomias[0]));

foreach ($autonomias as $autonomia) {
    fputcsv($file, $autonomia);
}
fclose($file);




function showHelp() {
    echo "Uso: $ php " . basename(__FILE__) . " [-hvd]\n\n";
    echo "Procesa los archivos fuente de autonomias y provincias que publica el INE alojados en ../../archive.\n";
    echo "Si no los encuentra, los descarga.\n\n";
    echo "Opciones:

    -h                        muestra esta ayuda y termina
    -d                        fuerza la descarga de los ficheros fuente aunque ya existan
    --pretty_print, -p        Formatea la salida JSON, añadiendo indentación y CR/LF al final de las lineas.
    --escaped_unicode, -u     Codifica caracteres Unicode multibyte escapado como \\uXXXX.

    ";

}
