<?php

use Sunra\PhpSimple\HtmlDomParser;

use ConsoleKit\Widgets\ProgressBar;
use ConsoleKit\Widgets\Box;

/**
 * Procesa los archivos fuente y graba a disco los datos en CSV
 *
 */
class ProcessCommand extends ConsoleKit\Command
{

    /**
     * Overriding para que invoque all por defecto.
     */
    public function execute(array $args, array $options = array())
    {
        //si se invoca sin sucomando, ejecuta todo
        if (!count($args)) {
            $args=['all'];
        }

        return parent::execute($args, $options);
    }


    /**
     * Procesa todos los archivos fuente y genera los .csv
     *
     */
    public function executeAll(array $args, array $options = array())
    {

        foreach (get_class_methods($this) as $method) {
            if (strpos($method, 'execute') !== false && $method != 'executeAll' && $method != 'execute') {
                $this->$method($options);
            }
        }
    }


    /**
     * Procesa el último año de de los archivos fuente de municipios y genera municipios.csv
     *
     */
    public function executeMunicipiosLast(array $args, array $options = array())
    {
        $columns = ['municipio_id', 'provincia_id', 'cmun', 'dc', 'nombre'];

        $box = new ConsoleKit\Widgets\Box($this->getConsole(), 'Municipios Last Year');
        $box->write();
        $this->getConsole()->writeln("");

        $file = fopen(DATA_FOLDER . DS . Config::MUNICIPIOS_DEST_FILE . ".csv", 'w+');
        fputcsv($file, $columns);
        $this->parseMunicipioYearToFile(date('y'), $file, false);

    }

    /**
     * Procesa el último año de de los archivos fuente de municipios y genera municipios-historical.csv
     *
     */
    public function executeMunicipios(array $args, array $options = array())
    {

        $columns = ['municipio_id', 'year', 'provincia_id', 'cmun', 'dc', 'nombre'];

        $box = new ConsoleKit\Widgets\Box($this->getConsole(), 'Municipios Historical');
        $box->write();
        $this->getConsole()->writeln("");

        $file = fopen(DATA_FOLDER . DS . Config::MUNICIPIOS_HISTORICAL_DEST_FILE . ".csv", 'w+');
        fputcsv($file, $columns);

        for ($year = Config::MUNCIPIOS_YEAR_START % 2000; $year <= date('y'); $year++) {
            $this->parseMunicipioYearToFile($year, $file);
        }



    }


    /**
     * Procesa el archivo fuente de provincias y genera provincias.csv
     *
     */
    public function executeProvincias(array $args, array $options = array())
    {

        $box = new ConsoleKit\Widgets\Box($this->getConsole(), 'Provincias');
        $box->write();
        $this->getConsole()->writeln("");

        $html = iconv("ISO-8859-15", "UTF-8",
            file_get_contents(ARCHIVE_FOLDER . DS . Config::PROVINCIAS_SOURCE_FILE));
        $dom = HtmlDomParser::str_get_html($html);

        $provincias = [];

        foreach ($dom->find('table[summary$=quetacion]') as $table) {
            foreach ($table->find('tr[!valign]') as $row) {
                $provincias[] = [
                    'provincia_id' => trim($row->children(0)->innertext),
                    'nombre' => html_entity_decode(trim($row->children(1)->innertext)),
                ];
            };
        };


        // Grabamos CSV
        $file = fopen(DATA_FOLDER . DS . Config::PROVINCIAS_DEST_FILE . ".csv", 'w+');
        fputcsv($file, array_keys($provincias[0]));
        foreach ($provincias as $provincia) {
            fputcsv($file, $provincia);
        }
        fclose($file);
    }



    /**
     * Procesa el archivo fuente de autonomias y genera autonomias.csv
     *
     */
    public function executeAutonomias(array $args, array $options = array())
    {

        $box = new ConsoleKit\Widgets\Box($this->getConsole(), 'Autonomias');
        $box->write();
        $this->getConsole()->writeln("");

        $html = iconv("ISO-8859-15", "UTF-8", file_get_contents(ARCHIVE_FOLDER . DS . Config::AUTONOMIAS_SOURCE_FILE));

        $dom = HtmlDomParser::str_get_html($html);

        $autonomias = [];

        $table = $dom->find('table', 0);
        $rows = array_slice($table->find('tr'), 1);

        foreach ($rows as $row) {
            $autonomias[] = [
                'autonomia_id' => trim($row->children(0)->innertext),
                'nombre' => html_entity_decode(trim($row->children(1)->innertext)),
            ];
        };

        // Grabamos CSV
        $file = fopen(DATA_FOLDER . DS . Config::AUTONOMIAS_DEST_FILE . ".csv", 'w+');
        fputcsv($file, array_keys($autonomias[0]));

        foreach ($autonomias as $autonomia) {
            fputcsv($file, $autonomia);
        }
        fclose($file);
    }


    /**
     * Procesa los archivos fuente de islas y genera municipios_islas_historical.csv
     *
     */
    public function executeMunicipiosIslas(array $args, array $options = array())
    {

        $columns = ['municipio_id', 'year', 'isla_id', 'provincia_id', 'cmun', 'dc', 'nombre'];

        $box = new ConsoleKit\Widgets\Box($this->getConsole(), 'Municipios Islas Historical');
        $box->write();
        $this->getConsole()->writeln("");

        $file = fopen(DATA_FOLDER . DS . Config::MUNICIPIOS_ISLAS_HISTORICAL_DEST_FILE . ".csv", 'w+');
        fputcsv($file, $columns);


        for ($year = Config::ISLAS_YEAR_START % 2000; $year <= date('y'); $year++) {
            $this->parseIslaYearToFile($year, $file);
        }
    }


    /**
     * Procesa el último año de los archivos fuente de islas y genera municipios_islas.csv
     *
     * @param $options opciones CLI
     *
     */
    public function executeMunicipiosIslasLast(array $args, array $options = array())
    {

        $columns = ['municipio_id', 'isla_id', 'provincia_id', 'cmun', 'dc', 'nombre'];

        $box = new ConsoleKit\Widgets\Box($this->getConsole(), 'Municipios Islas');
        $box->write(); $this->getConsole()->writeln("");

        $file = fopen(DATA_FOLDER . DS . Config::MUNICIPIOS_ISLAS_DEST_FILE . ".csv", 'w+');
        fputcsv($file, $columns);

        $this->parseIslaYearToFile(date('y'), $file, false);

    }


    /**
     * Procesa el último año de los archivos fuente de islas y genera islas.csv
     *
     */
    public function executeIslas(array $args, array $options = array()){
        $columns = ['isla_id', 'provincia_id', 'nombre'];

        $box = new ConsoleKit\Widgets\Box($this->getConsole(), 'Islas');
        $box->write(); $this->getConsole()->writeln("");

        $file = fopen(DATA_FOLDER . DS . Config::ISLAS_DEST_FILE . ".csv", 'w+');
        fputcsv($file, $columns);

        $this->parseIslaYearToFile(date('y'), $file, false, true);

    }


    /**
     * Procesa los archivos fuente de las islas del año especificado y anexa los datos al archivo .csv
     *
     * @param $year int año a procesar
     * @param $file resource archivo abierto
     * @param $includeYear boolean determina si se incluye el campo year. Por defecto true
     * @param $group boolean determina si se agrupan los resultados por isla_id. Por defecto false
     *
     */
    private function parseIslaYearToFile($year, $file, $includeYear = true, $group = false){

        // Agrupamos los datos de los tres archivos en un solo array
        $islasRaw = [];
        foreach (unserialize(Config::ISLAS_PROVINCIA_INE_CODES) as $isla) {
            $fileName = sprintf(Config::ISLAS_SOURCE_FILE, $year, $isla);
            $sourceFilename = ARCHIVE_FOLDER . DS . $fileName;
            $excelReader = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($sourceFilename));

            $excelReader->setLoadAllSheets();
            $excelObj = $excelReader->load($sourceFilename);
            $islaRaw = $excelObj->getActiveSheet()->toArray(null, true, true, true);


            //quitamos las tres primeras files
            for ($i = 0; $i == 3; $i++) {
                if (!is_numeric($islaRaw[$i]['A'])) {
                    unset($islaRaw);
                }
            }
            $islaRaw = array_slice($islaRaw, 3);
            $islasRaw = array_merge($islasRaw, $islaRaw);

        }

        // quitamos lineas en blanco (p.e datos para 2012)
        $islasRaw = array_filter($islasRaw, function ($row) {
            return !empty($row['A']);
        });

        $progress = new ProgressBar($this->getConsole(), sizeof($islasRaw));

        // Generamos hash con los atributos correctos para el histórico
        $islas = array_map(function ($row) use ($year, $progress, $includeYear) {

            $provinciaId = str_pad($row['A'], 2, "0", STR_PAD_LEFT);
            $municipioId = str_pad($row['D'], 3, "0", STR_PAD_LEFT);
            $islaId = str_pad($row['B'], 3, "0", STR_PAD_LEFT);

            $output = [
                'municipio_id' => $provinciaId . $municipioId,
                'year' => 2000 + $year,
                'isla_id' => $islaId,
                'provincia_id' => $provinciaId,
                'cmun' => $municipioId,
                'dc' => $row['E'],
                'nombre' => $row['F'],
            ];

            if (!$includeYear) {
                unset($output['year']);
            }

            $progress->incr();
            return $output;

        }, array_values($islasRaw));

        if ($group) {
            // Agrupamos islas y generamos hash con atributos correctos
            $islas = array_reduce($islasRaw, function ($result, $row) {
                $result[$row['B']] = [
                    'isla_id' => str_pad($row['B'], 3, "0", STR_PAD_LEFT),
                    'provincia_id' => str_pad($row['A'], 2, "0", STR_PAD_LEFT),
                    'nombre' => $row['C']];
                return $result;
            }, array());
        }

        foreach ($islas as $isla) {
            fputcsv($file, $isla);
        }

        $progress->stop();

    }


    /**
     * Procesa los archivos fuente de las municipios del año especificado y anexa los datos al archivo .csv
     *
     * @param $year int año a procesar
     * @param $file resource archivo abierto
     * @param $includeYear boolean determina si se incluye el campo year. Por defecto true
     *
     */
    private function parseMunicipioYearToFile($year,$file, $includeYear = true){
        $fileName = sprintf(Config::MUNICIPIOS_SOURCE_FILE, $year);
        if ($year >= 16) $fileName .= "x";  //xlsx a partir de 2016.

        // Procesamos documento xlsx
        $sourceFilename = ARCHIVE_FOLDER . DS . $fileName;
        $excelReader = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($sourceFilename));
        $excelReader->setLoadAllSheets();
        $excelObj = $excelReader->load($sourceFilename);
        $municipiosRaw = $excelObj->getActiveSheet()->toArray(null, true, true, true);

        $progress = new ProgressBar($this->getConsole(), sizeof($municipiosRaw));

        // Eliminamos las dos primeras filas
        array_shift($municipiosRaw); //solo la primera para 2004
        if ($year > 4) array_shift($municipiosRaw); //la segunda también para > 2004

        $municipiosWithYear = array_map(function ($row) use ($year,$progress,$includeYear) {

            if ($year <= 10) { //Antes de 2011: CMUN + DC concatenados. Separamos.
                $provinciaId = str_pad($row['A'],2,"0",STR_PAD_LEFT);
                $municipioId = str_pad((int) ($row['B']/10),3,"0",STR_PAD_LEFT);
                $output = [
                    'municipio_id' =>  $provinciaId . $municipioId,
                    'year' => 2000 + $year,
                    'provincia_id' => $provinciaId,
                    'cmun' => $municipioId,
                    'dc' => $row['B'] % 10,
                    'nombre' => $row['C'],
                ];
            } else {
                $provinciaId = str_pad($row['A'],2,"0",STR_PAD_LEFT);
                $municipioId = str_pad($row['B'],3,"0",STR_PAD_LEFT);
                $output = [
                    'municipio_id' =>  $provinciaId . $municipioId,
                    'year' => 2000 + $year,
                    'provincia_id' => $provinciaId,
                    'cmun' => $municipioId,
                    'dc' => $row['C'],
                    'nombre' => $row['D'],
                ];
            }

            if (!$includeYear) {
                unset($output['year']);
            }
            $progress->incr();

            return $output;

        }, array_values($municipiosRaw));

        foreach ($municipiosWithYear as $municipio) {
            fputcsv($file, $municipio);
        }

        $progress->stop();
    }
}