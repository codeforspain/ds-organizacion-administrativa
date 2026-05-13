<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use ConsoleKit\Widgets\ProgressBar;
use ConsoleKit\Widgets\Box;

/**
 * Procesa los archivos fuente y graba a disco los datos en CSV
 *
 */
class ProcessCommand extends ConsoleKit\Command
{
    private function loadSpreadsheetRows($sourceFilename, $allSheets = false)
    {
        $excelReader = IOFactory::createReader(IOFactory::identify($sourceFilename));
        $excelReader->setLoadAllSheets();
        $excelObj = $excelReader->load($sourceFilename);

        if (!$allSheets) {
            return $excelObj->getActiveSheet()->toArray(null, true, true, true);
        }

        $rows = [];
        foreach ($excelObj->getWorksheetIterator() as $sheet) {
            $rows = array_merge($rows, $sheet->toArray(null, true, true, true));
        }

        return $rows;
    }

    private function isDataRow(array $row, $firstColumn = 'A')
    {
        return isset($row[$firstColumn]) && is_numeric($row[$firstColumn]);
    }

    private function hasColumns(array $row, array $columns)
    {
        foreach ($columns as $column) {
            if (!isset($row[$column]) || $row[$column] === null || $row[$column] === '') {
                return false;
            }
        }

        return true;
    }

    private function parseCodeHtmlFile($sourceFile, $idField)
    {
        $html = iconv("ISO-8859-15", "UTF-8", file_get_contents(ARCHIVE_FOLDER . DS . $sourceFile));

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $rows = [];

        foreach ($xpath->query('//table[contains(concat(" ", normalize-space(@class), " "), " miTabla ")]//tr[td]') as $row) {
            $cells = $xpath->query('./td', $row);
            if ($cells->length < 2) {
                continue;
            }

            $id = trim($cells->item(0)->textContent);
            if (!preg_match('/^\d+$/', $id)) {
                continue;
            }

            $rows[] = [
                $idField => str_pad($id, 2, "0", STR_PAD_LEFT),
                'nombre' => trim(preg_replace('/\s+/', ' ', $cells->item(1)->textContent)),
            ];
        }

        return $rows;
    }

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

        $provincias = $this->parseCodeHtmlFile(Config::PROVINCIAS_SOURCE_FILE, 'provincia_id');

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

        $autonomias = $this->parseCodeHtmlFile(Config::AUTONOMIAS_SOURCE_FILE, 'autonomia_id');

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
        if ($year >= 21) {
            $sourceFilename = ARCHIVE_FOLDER . DS . sprintf(Config::ISLAS_SOURCE_FILE_CURRENT, $year);
            $islasRaw = $this->loadSpreadsheetRows($sourceFilename, true);
        } else {
            foreach (unserialize(Config::ISLAS_PROVINCIA_INE_CODES) as $isla) {
                $fileName = sprintf(Config::ISLAS_SOURCE_FILE, $year, $isla);
                $sourceFilename = ARCHIVE_FOLDER . DS . $fileName;
                $islaRaw = $this->loadSpreadsheetRows($sourceFilename);
                $islasRaw = array_merge($islasRaw, $islaRaw);
            }
        }

        // quitamos lineas en blanco (p.e datos para 2012)
        $islasRaw = array_filter($islasRaw, function ($row) {
            return $this->isDataRow($row) && $this->hasColumns($row, ['A', 'B', 'C', 'D', 'E', 'F']);
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
        $municipiosRaw = $this->loadSpreadsheetRows($sourceFilename, $year >= 21);

        $municipiosRaw = array_filter($municipiosRaw, function ($row) use ($year) {
            switch (true) {
                case $year <= 10:
                    return $this->isDataRow($row, 'A') && $this->hasColumns($row, ['A', 'B', 'C']);
                case $year <= 15:
                    return $this->isDataRow($row, 'A') && $this->hasColumns($row, ['A', 'B', 'C', 'D']);
                case $year <= 20:
                    return $this->isDataRow($row, 'B') && $this->hasColumns($row, ['B', 'C', 'D', 'E']);
                default:
                    return $this->isDataRow($row, 'A') && $this->hasColumns($row, ['A', 'B', 'C', 'D']);
            }
        });

        $progress = new ProgressBar($this->getConsole(), sizeof($municipiosRaw));

        $municipiosWithYear = array_map(function ($row) use ($year,$progress,$includeYear) {

            switch (true) {
                case $year <= 10: //Antes de 2011: CMUN + DC concatenados. Separamos.
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
                    break;
                case $year <= 15:
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
                    break;
                case $year <= 20: //Nuevo columna CODAUTO. Ignoramos y ajustamos indices
                    $provinciaId = str_pad($row['B'],2,"0",STR_PAD_LEFT);
                    $municipioId = str_pad($row['C'],3,"0",STR_PAD_LEFT);
                    $output = [
                        'municipio_id' =>  $provinciaId . $municipioId,
                        'year' => 2000 + $year,
                        'provincia_id' => $provinciaId,
                        'cmun' => $municipioId,
                        'dc' => $row['D'],
                        'nombre' => $row['E'],
                    ];
                    break;
                case $year > 20: //Desde 2021 el INE publica una hoja por provincia, sin CODAUTO.
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
                    break;
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
