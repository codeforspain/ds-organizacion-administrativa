<?php

class Config
{


    const MUNCIPIOS_URL = "http://www.ine.es/daco/daco42/codmun/codmun%02d/%02dcodmun.xls";
    const PROVINCIAS_URL = "http://www.ine.es/daco/daco42/codmun/cod_provincia.htm";
    const AUTONOMIAS_URL = "http://www.ine.es/daco/daco42/codmun/cod_ccaa.htm";
    const ISLAS_URL = "http://www.ine.es/daco/daco42/codmun/codmun%02d/%02dcodislas%02d.xls";

    const MUNCIPIOS_YEAR_START = 2004; // Hay datos desde 2001, pero necesitan tratamiento especial. To Do
    const ISLAS_YEAR_START = 2012; // hay datos desde 2008, pero no incluye el codigo de Isla. To Do
    const ISLAS_PROVINCIA_INE_CODES = "a:3:{i:0;i:7;i:1;i:35;i:2;i:38;}"; //PHP <5.6 compatible (no soporta arrays como constantes)

    const DATA_FOLDER = "data";
    const ARCHIVE_FOLDER = "archive";

    const PROVINCIAS_SOURCE_FILE = "cod_provincia.htm";
    const AUTONOMIAS_SOURCE_FILE = "cod_ccaa.htm";

    const MUNICIPIOS_SOURCE_FILE = "%02dcodmun.xls";
    const ISLAS_SOURCE_FILE = "%02dcodislas%02d.xls";

    const PROVINCIAS_DEST_FILE = "provincias";
    const AUTONOMIAS_DEST_FILE = "autonomias";
    const MUNICIPIOS_DEST_FILE = "municipios";
    const MUNICIPIOS_HISTORICAL_DEST_FILE = "municipios_historical";

    const ISLAS_DEST_FILE = "islas";
    const MUNICIPIOS_ISLAS_HISTORICAL_DEST_FILE = "municipios_islas_historical";
    const MUNICIPIOS_ISLAS_DEST_FILE = "municipios_islas";



    static $datapackage = [
        "name" => "ds-organizacion-administrativa",
        "title" => "Organización Administrativa de España",
        "descriptions" => "Listado de comunidades, provincias, municipios, islas con su correspondiente código INE",
        "licenses" => [
            [
                "type" => "odc-pddl",
                "url" => "http://opendatacommons.org/licenses/pddl/"
            ]
        ],
        "author" => [
            "name" => "Code for Spain",
            "web" => "http://www.codeforspain.org"
        ],
        "keywords" => [ "Provincias", "Municipios", "Comunidades Autonomas", "Islas"],

        "sources" => [
             [
                "name" => "Instituto Nacional de Estadistica",
                "web" => "http://www.ine.es/jaxi/menu.do?type=pcaxis&path=/t20/e245/codmun&file=inebase"
             ]
        ],
        "resources" =>[
            array(
                'name' => 'ds_oa_municipios',
                'title' => 'Municipios de España',
                'description' => 'Relacion de municipios de España por provincia, según el INE.',
                'format' => 'csv',
                'path' => 'data/municipios.csv',
                'schema' =>
                    array (
                        'fields' =>
                            array (
                                array (
                                    'name' => 'municipio_id',
                                    'type' => 'number',
                                    'description' => 'Código INE del municipio',
                                    'pattern' => '[0-9]{5}',
                                ),
                                array (
                                    'name' => 'provincia_id',
                                    'type' => 'number',
                                    'description' => 'Código INE de la provincia',
                                    'pattern' => '[0-9]{2}',
                                ),
                                array (
                                    'name' => 'cmun',
                                    'type' => 'number',
                                    'description' => 'Código INE del municipio en relación a la provincia. provincia_id concatenado con cmun resulta en el codigo INE del municipio',
                                    'pattern' => '[0-9]{3}',
                                ),
                                array (
                                    'name' => 'dc',
                                    'type' => 'number',
                                    'description' => 'Dígito de control que, asignado mediante una regla de cálculo, permite la detección de errores de grabación y codificación',
                                    'pattern' => '[0-9]{1}',
                                ),
                                array (
                                    'name' => 'nombre',
                                    'type' => 'string',
                                    'description' => 'Denominación oficial del municipio',
                                ),
                            ),
                    ),
                ),
            array (
                'name' => 'ds_oa_municipios_historical',
                'title' => 'Municipios de España (Histórico)',
                'description' => 'Relacion de municipios de España, por provincia y año, según el INE. Recoge modificaciones anuales.',
                'format' => 'csv',
                'path' => 'data/municipios_historical.csv',
                'schema' =>
                    array (
                        'fields' =>
                            array (
                                array (
                                    'name' => 'municipio_id',
                                    'type' => 'number',
                                    'description' => 'Código INE del municipio',
                                    'pattern' => '[0-9]{5}',
                                ),
                                array (
                                    'name' => 'provincia_id',
                                    'type' => 'number',
                                    'description' => 'Código INE de la provincia',
                                    'pattern' => '[0-9]{2}',
                                ),
                                array (
                                    'name' => 'cmun',
                                    'type' => 'number',
                                    'description' => 'Código INE del municipio en relación a la provincia. provincia_id concatenado con cmun resulta en el codigo INE del municipio',
                                    'pattern' => '[0-9]{3}',
                                ),
                                array (
                                    'name' => 'dc',
                                    'type' => 'number',
                                    'description' => 'Dígito de control que, asignado mediante una regla de cálculo, permite la detección de errores de grabación y codificación',
                                    'pattern' => '[0-9]{1}',
                                ),
                                array (
                                    'name' => 'nombre',
                                    'type' => 'string',
                                    'description' => 'Denominación oficial del municipio',
                                ),
                                array (
                                    'name' => 'year',
                                    'type' => 'number',
                                    'pattern' => '[0-9]{4}',
                                    'description' => 'Año del dato',
                                ),
                            ),
                    ),
                ),
            array (
                'name' => 'ds_oa_provincias',
                'title' => 'Provincias de España',
                'description' => 'Relacion de provincias de España según el INE.',
                'format' => 'csv',
                'path' => 'data/provincias.csv',
                'schema' =>
                    array (
                        'fields' =>
                            array (
                                array (
                                    'name' => 'provincia_id',
                                    'type' => 'number',
                                    'description' => 'Código INE de la provincia',
                                    'pattern' => '[0-9]{2}',
                                ),
                                array (
                                    'name' => 'nombre',
                                    'type' => 'string',
                                    'description' => 'Denominación oficial de la provincia',
                                ),
                            ),
                    ),
            ),
            array (
                'name' => 'ds_oa_autonomias',
                'title' => 'Comunidades Autonomas de España ',
                'description' => 'Relacion de comunidades autonomas de España, según el INE.',
                'format' => 'csv',
                'path' => 'data/autonomias.csv',
                'schema' =>
                    array (
                        'fields' =>
                            array (
                                array (
                                    'name' => 'autonomia_id',
                                    'type' => 'number',
                                    'description' => 'Código INE de la comunidad autónoma',
                                    'pattern' => '[0-9]{2}',
                                ),
                                array (
                                    'name' => 'nombre',
                                    'type' => 'string',
                                    'description' => 'Denominación oficial de la comunidad autónoma',
                                ),
                            ),
                    ),
            ),
            array (
                'name' => 'ds_oa_municipios_islas',
                'title' => 'Municipios de España por Isla',
                'description' => 'Relacion de municipios que contiene cada Isla en al último año, según el INE.',
                'format' => 'csv',
                'path' => 'data/municipios_islas.csv',
                'schema' =>
                    array (
                        'fields' =>
                            array (
                                array (
                                    'name' => 'municipio_id',
                                    'type' => 'number',
                                    'description' => 'Código INE del municipio',
                                    'pattern' => '[0-9]{5}',
                                ),
                                array (
                                    'name' => 'provincia_id',
                                    'type' => 'number',
                                    'description' => 'Código INE de la provincia',
                                    'pattern' => '[0-9]{2}',
                                ),
                                array (
                                    'name' => 'isla_id',
                                    'type' => 'number',
                                    'description' => 'Código INE de la isla',
                                    'pattern' => '[0-9]{3}',
                                ),
                                array (
                                    'name' => 'cmun',
                                    'type' => 'number',
                                    'description' => 'Código INE del municipio en relación a la provincia. provincia_id concatenado con cmun resulta en el codigo INE del municipio',
                                    'pattern' => '[0-9]{3}',
                                ),
                                array (
                                    'name' => 'dc',
                                    'type' => 'number',
                                    'description' => 'Dígito de control que, asignado mediante una regla de cálculo, permite la detección de errores de grabación y codificación',
                                    'pattern' => '[0-9]{1}',
                                ),
                                array (
                                    'name' => 'nombre',
                                    'type' => 'string',
                                    'description' => 'Denominación oficial del municipio',
                                ),
                            ),
                    ),
            ),
            array (
                'name' => 'ds_oa_municipios_islas_historical',
                'title' => 'Municipios de España por Isla (Histórico)',
                'description' => 'Relacion de municipios por isla y por año desde 2008, según el INE. Recoge modificaciones anuales.',
                'format' => 'csv',
                'path' => 'data/municipios_islas_historical.csv',
                'schema' =>
                    array (
                        'fields' =>
                            array (
                                array (
                                    'name' => 'municipio_id',
                                    'type' => 'number',
                                    'description' => 'Código INE del municipio',
                                    'pattern' => '[0-9]{5}',
                                ),
                                array (
                                    'name' => 'year',
                                    'type' => 'number',
                                    'pattern' => '[0-9]{4}',
                                    'description' => 'Año del dato',
                                ),
                                array (
                                    'name' => 'provincia_id',
                                    'type' => 'number',
                                    'description' => 'Código INE de la provincia',
                                    'pattern' => '[0-9]{2}',
                                ),
                                array (
                                    'name' => 'isla_id',
                                    'type' => 'number',
                                    'description' => 'Código INE de la isla',
                                    'pattern' => '[0-9]{3}',
                                ),
                                array (
                                    'name' => 'cmun',
                                    'type' => 'number',
                                    'description' => 'Código INE del municipio en relación a la provincia. provincia_id concatenado con cmun resulta en el codigo INE del municipio',
                                    'pattern' => '[0-9]{3}',
                                ),
                                array (
                                    'name' => 'dc',
                                    'type' => 'number',
                                    'description' => 'Dígito de control que, asignado mediante una regla de cálculo, permite la detección de errores de grabación y codificación',
                                    'pattern' => '[0-9]{1}',
                                ),
                                array (
                                    'name' => 'nombre',
                                    'type' => 'string',
                                    'description' => 'Denominación oficial del municipio',
                                ),

                            ),
                    ),
            ),
            array (
                'name' => 'ds_oa_islas',
                'title' => 'Islas de España',
                'description' => 'Relacion de islas por provincia según el INE.',
                'format' => 'csv',
                'path' => 'data/islas.csv',
                'schema' =>
                    array (
                        'fields' =>
                            array (
                                array (
                                    'name' => 'isla_id',
                                    'type' => 'number',
                                    'description' => 'Código INE de la isla',
                                    'pattern' => '[0-9]{3}',
                                ),
                                array (
                                    'name' => 'provincia_id',
                                    'type' => 'number',
                                    'description' => 'Código INE de la provincia',
                                    'pattern' => '[0-9]{2}',
                                ),

                                array (
                                    'name' => 'nombre',
                                    'type' => 'string',
                                    'description' => 'Denominación oficial de la isla',
                                ),
                            ),
                    ),
            ),
       ]
    ];


}