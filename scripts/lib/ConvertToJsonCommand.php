<?php
/**
 * Si no existen, descarga los archivos fuente y los graba a disco
 *
 * @arg source fuente a procesar (OPCIONAL). Puede ser "municipios", "provincias", "autonomias" o "islas". Si no se especifica  procesa todo.
 * @opt force fuerza la descarga de los ficheros fuente aunque ya existan
 *
 */

use ConsoleKit\Widgets\ProgressBar;
use ConsoleKit\Widgets\Box;

/**
 * Convierte todos los archivos .csv en /data a .json.
 *
 */
class ConvertToJsonCommand extends ConsoleKit\Command
{
    private function convertCsvToJson($csvFile, $outputFile)
    {
        $input = fopen($csvFile, 'r');
        $output = fopen($outputFile, 'w');

        $headers = fgetcsv($input);
        fwrite($output, "[\n");

        $first = true;
        while (($row = fgetcsv($input)) !== false) {
            if (count($row) !== count($headers)) {
                continue;
            }

            if (!$first) {
                fwrite($output, ",\n");
            }

            fwrite($output, json_encode(array_combine($headers, $row), JSON_UNESCAPED_UNICODE));
            $first = false;
        }

        fwrite($output, "\n]\n");
        fclose($input);
        fclose($output);
    }

    public function execute(array $args, array $options = array())
    {
        $box = new ConsoleKit\Widgets\Box($this->getConsole(), 'Converting to JSON');
        $box->write();$this->getConsole()->writeln("");

        $files = glob(DATA_FOLDER . DS ."*.csv");
        $progress = new ProgressBar($this->getConsole(), sizeof($files));

        foreach ($files as $csvFile) {
            $outputFile = DATA_FOLDER . DS . basename($csvFile,".csv") . ".json";
            $this->convertCsvToJson($csvFile, $outputFile);
            $progress->incr();
        }

        $progress->stop();
    }

}
