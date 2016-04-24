<?php

use Sunra\PhpSimple\HtmlDomParser;

/**
 * Procesa los archivos fuente y graba a disco los datos en CSV y JSON
 *
 * @arg source fuente a procesar (OPCIONAL). Puede ser "municipios", "provincias", "autonomias" o "islas". Si no se especifica  procesa todo.
 *
 */
class ProcessCommand extends ConsoleKit\Command
{
    public function execute(array $args, array $options = array())
    {

        if (empty($args)) {
            foreach (get_class_methods($this) as $method) {
                if (strpos($method, 'process')!==false) {
                    $this->$method($options);
                }
            }
        }

        foreach ($args as $arg){
            $processMethod = "process". ucfirst($arg);

            if (!method_exists($this,$processMethod)) {
                die("Argumento invalido {$arg}");
            }
            $this->$processMethod($options);
        }


        //$this->writeln('hello world!', ConsoleKit\Colors::GREEN);

    }


    private function processMunicipios($options)
    {
        $municipiosTotal = [];
        for ($year = MUNCIPIOS_YEAR_START % 2000; $year <= date('y'); $year++) {

            $fileName = sprintf(MUNICIPIOS_SOURCE_FILE, $year);
            if ($year >= 16) $fileName .= "x";  //xlsx a partir de 2016.

            // Procesamos documento xlsx
            $sourceFilename = ARCHIVE_FOLDER . DS . $fileName;
            $excelReader = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($sourceFilename));
            $excelReader->setLoadAllSheets();
            $excelObj = $excelReader->load($sourceFilename);
            $municipiosRaw = $excelObj->getActiveSheet()->toArray(null, true, true, true);

            // Eliminamos las dos primeras filas
            array_shift($municipiosRaw); //solo la primera para 2004
            if ($year > 4) array_shift($municipiosRaw); //la segunda también para > 2004

            $municipiosWithYear = array_map(function ($row) use ($year) {

                if ($year <= 10) { //Antes de 2011: CMUN + DC concatenados. Separamos.
                    $output = [
                        'CPRO' => $row['A'], //str_pad($row['A'],2,"0",STR_PAD_LEFT),
                        'CMUN' => (int)($row['B'] / 10),
                        'DC' => $row['B'] % 10,
                        'NOMBRE' => $row['C'],
                        'YEAR' => 2000 + $year
                    ];
                } else {
                    $output = [
                        'CPRO' => $row['A'], //str_pad($row['A'],2,"0",STR_PAD_LEFT),
                        'CMUN' => $row['B'], //str_pad($row['B'],3,"0",STR_PAD_LEFT)
                        'DC' => $row['C'],
                        'NOMBRE' => $row['D'],
                        'YEAR' => 2000 + $year
                    ];
                }
                return $output;

            }, array_values($municipiosRaw));

            $municipiosTotal = array_merge($municipiosTotal, $municipiosWithYear);
        }

        // Generamos array con el ultimo año
        $municipiosLastYear = array_map(function ($row) {
            return [
                'CPRO' => $row['A'], //str_pad($row['A'],2,"0",STR_PAD_LEFT),
                'CMUN' => $row['B'],
                'DC' => $row['C'],
                'NOMBRE' => $row['D'],
            ];

        }, array_values($municipiosRaw));


        // Grabamos a disco - To DO: Refactorizar a función/clase

        // Grabamos Ficheros CSV

        // Último Año
        $file = fopen(DATA_FOLDER . DS . MUNICIPIOS_DEST_FILE . ".csv", 'w+');
        fputcsv($file, array_keys($municipiosLastYear[0]));
        foreach ($municipiosLastYear as $municipio) {
            fputcsv($file, $municipio);
        }

        // Todos los años
        $file = fopen(DATA_FOLDER . DS . MUNICIPIOS_HISTORICAL_DEST_FILE . ".csv", 'w+');
        fputcsv($file, array_keys($municipiosTotal[0]));
        foreach ($municipiosTotal as $municipio) {
            fputcsv($file, $municipio);
        }

        // Grabamos Ficheros JSON

        // Último Año
        $file = fopen(DATA_FOLDER . DS . MUNICIPIOS_DEST_FILE . ".json", 'w+');
        fwrite($file, json_encode($municipiosLastYear, JSON_UNESCAPED_UNICODE));
        fclose($file);

        // Todos los años
        $file = fopen(DATA_FOLDER . DS . MUNICIPIOS_HISTORICAL_DEST_FILE . ".json", 'w+');
        fwrite($file, json_encode($municipiosTotal, JSON_UNESCAPED_UNICODE));
        fclose($file);
    }


    private function processProvincias($options)
    {
        $html=iconv("ISO-8859-15", "UTF-8", file_get_contents(ARCHIVE_FOLDER . DS . PROVINCIAS_SOURCE_FILE));
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

        // Grabamos a disco - To DO: Refactorizar a función/clase

        // Grabamos JSON
        $file = fopen(DATA_FOLDER . DS . PROVINCIAS_DEST_FILE .".json",'w+');
        fwrite($file, json_encode($provincias,JSON_UNESCAPED_UNICODE) );
        fclose($file);

        // Grabamos CSV
        $file = fopen(DATA_FOLDER . DS . PROVINCIAS_DEST_FILE . ".csv",'w+');
        fputcsv($file, array_keys($provincias[0]));
        foreach ($provincias as $provincia) {
            fputcsv($file, $provincia);
        }
        fclose($file);
    }


    private function processAutonomias($options)
    {
        $html=iconv("ISO-8859-15", "UTF-8", file_get_contents(ARCHIVE_FOLDER . DS . AUTONOMIAS_SOURCE_FILE));

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

        // Grabamos a disco - To DO: Refactorizar a función/clase

        // Grabamos JSON
        $file = fopen(DATA_FOLDER . DS . AUTONOMIAS_DEST_FILE .".json",'w+');
        fwrite($file, json_encode($autonomias,JSON_UNESCAPED_UNICODE) );
        fclose($file);


        // Grabamos CSV
        $file = fopen(DATA_FOLDER . DS . AUTONOMIAS_DEST_FILE .".csv",'w+');
        fputcsv($file, array_keys($autonomias[0]));

        foreach ($autonomias as $autonomia) {
            fputcsv($file, $autonomia);
        }
        fclose($file);
    }

    /*
     * Procesa los archivos fuente de islas y genera:
     *
     *    - islas(.csv/.json)
     *    - municipios_islas(.csv/.json)
     *    - municipios_islas_historical(.csv/.json)
     *
     * @param $options opciones CLI
     *
     */
    private function processIslas($options){

        $islasTotal = [];

        for ($year = ISLAS_YEAR_START % 2000; $year <= date('y'); $year++) {

            // Agrupamos los datos de los tres archivos en un solo array
            $islasRaw = [];
            foreach (unserialize(ISLAS_PROVINCIA_INE_CODES) as $isla) {
                $fileName = sprintf(ISLAS_SOURCE_FILE, $year, $isla);
                $sourceFilename = ARCHIVE_FOLDER . DS . $fileName;
                $excelReader = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($sourceFilename));

                $excelReader->setLoadAllSheets();
                $excelObj = $excelReader->load($sourceFilename);
                $islaRaw = $excelObj->getActiveSheet()->toArray(null, true, true, true);

                //quitamos las tres primeras files
                for ($i=0;$i==3;$i++) {
                    if (!is_numeric($islaRaw[$i]['A'])) {
                        unset($islaRaw);
                    }
                }
                $islaRaw=array_slice($islaRaw,3);
                $islasRaw = array_merge($islasRaw,$islaRaw);

            }

            // quitamos lineas en blanco (p.e datos para 2012)
            $islasRaw = array_filter($islasRaw,function($row) {
                return !empty($row['A']);
            });
            // Generamos hash con los atributos correctos para el histórico
            $islasWithYear = array_map(function ($row) use ($year) {
                $output = [
                    'CPRO' => $row['A'],
                    'CISLA' => $row['B'],
                    'CMUN' => $row['D'],
                    'DC' => $row['E'],
                    'NOMBRE' => $row['F'],
                    'YEAR' => 2000 + $year
                ];
                return $output;

            }, array_values($islasRaw));

            $islasTotal = array_merge($islasTotal, $islasWithYear);

        }

        // Generamos hash con los atributos correctos para el último año.
        $islasLastYear = array_map(function ($row) {
            return [
                'CPRO' => $row['A'],
                'CISLA' => $row['B'],
                'CMUN' => $row['D'],
                'DC' => $row['E'],
                'NOMBRE' => $row['F'],
            ];

        }, array_values($islasRaw));

        // Agrupamos islas y generamos hash con atributos correctos
        $islas = array_reduce($islasRaw, function ($result, $row) {
            $result[$row['B']] = [
                'CPRO' => $row['A'],
                'CISLA' => $row['B'],
                'NOMBRE' => $row['C']];
            return $result;
        }, array());

        // Grabamos a disco - To DO: Refactorizar a función/clase

        // Grabamos Ficheros CSV

        // Último Año
        $file = fopen(DATA_FOLDER . DS . MUNICIPIOS_ISLAS_DEST_FILE . ".csv", 'w+');
        fputcsv($file, array_keys(reset($islasLastYear)));
        foreach ($islasLastYear as $municipio) {
            fputcsv($file, $municipio);
        }

        // Todos los años
        $file = fopen(DATA_FOLDER . DS . MUNICIPIOS_ISLAS_HISTORICAL_DEST_FILE . ".csv", 'w+');
        fputcsv($file, array_keys(reset($islasTotal)));
        foreach ($islasTotal as $municipio) {
            fputcsv($file, $municipio);
        }

        // Solo Islas
        $file = fopen(DATA_FOLDER . DS . ISLAS_DEST_FILE . ".csv", 'w+');
        fputcsv($file, array_keys(reset($islas)));
        foreach ($islas as $isla) {
            fputcsv($file, $isla);
        }

        // Grabamos Ficheros JSON

        // Último Año
        $file = fopen(DATA_FOLDER . DS . MUNICIPIOS_ISLAS_DEST_FILE . ".json", 'w+');
        fwrite($file, json_encode($islasLastYear, JSON_UNESCAPED_UNICODE));
        fclose($file);

        // Todos los años
        $file = fopen(DATA_FOLDER . DS . MUNICIPIOS_ISLAS_HISTORICAL_DEST_FILE . ".json", 'w+');
        fwrite($file, json_encode($islasTotal, JSON_UNESCAPED_UNICODE));
        fclose($file);

        // Solo islas
        $file = fopen(DATA_FOLDER . DS . ISLAS_DEST_FILE . ".json", 'w+');
        fwrite($file, json_encode($islas, JSON_UNESCAPED_UNICODE));
        fclose($file);
    }

}