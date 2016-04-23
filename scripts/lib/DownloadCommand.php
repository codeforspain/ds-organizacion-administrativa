<?php
/**
 * Si no existen, descarga los archivos fuente y los graba a disco
 *
 * @arg source fuente a procesar (OPCIONAL). Puede ser "municipios", "provincias", "autonomias" o "islas". Si no se especifica  procesa todo.
 * @opt force fuerza la descarga de los ficheros fuente aunque ya existan
 *
 */
class DownloadCommand extends ConsoleKit\Command
{
    public function execute(array $args, array $options = array())
    {
        is_dir(DATA_FOLDER) || mkdir(DATA_FOLDER);
        is_dir(ARCHIVE_FOLDER) || mkdir(ARCHIVE_FOLDER);

        if (empty($args)) {
            foreach (get_class_methods($this) as $method) {
                if (strpos($method, 'download')!==false) {
                    $this->$method($options);
                }
            }
        }

        foreach ($args as $arg){
            $downloadMethod = "download". ucfirst($arg);

            if (!method_exists($this,$downloadMethod)) {
                die("Argumento invalido {$arg}");
            }
            $this->$downloadMethod($options);
        }


        //$this->writeln('hello world!', ConsoleKit\Colors::GREEN);
    }


    private function downloadMunicipios($options)
    {
        for ($i = MUNCIPIOS_YEAR_START % 2000;$i <= date('y');$i++){

            $url=sprintf(MUNCIPIOS_URL,$i,$i);
            if ($i>=16) $url.="x";  //xlsx a partir de 2016.
            $fileName = end(explode('/', $url));

            if (!file_exists(ARCHIVE_FOLDER . "/$fileName") || isset($options['force']) || isset($options['f'])){
                file_put_contents(ARCHIVE_FOLDER . "/$fileName", fopen($url, 'r'));
            }

        }
    }


    private function downloadProvincias($options)
    {
        if (!file_exists(ARCHIVE_FOLDER . DS . PROVINCIAS_SOURCE_FILE) || isset($options['force']) || isset($options['f'])){
            file_put_contents(ARCHIVE_FOLDER . DS . PROVINCIAS_SOURCE_FILE, fopen(PROVINCIAS_URL, 'r'));
        }

    }


    private function downloadAutonomias($options)
    {
        if (!file_exists(ARCHIVE_FOLDER . DS . AUTONOMIAS_SOURCE_FILE) || isset($options['force']) || isset($options['f'])){
            file_put_contents(ARCHIVE_FOLDER . DS . AUTONOMIAS_SOURCE_FILE, fopen(AUTONOMIAS_URL, 'r'));
        }

    }


    private function downloadIslas($options)
    {

        foreach (unserialize(ISLAS_PROVINCIA_INE_CODES) as $provincia) {
            for ($i = ISLAS_YEAR_START % 2000; $i <= date('y'); $i++) {

                $url = sprintf(ISLAS_URL, $i, $i, $provincia);
                $fileName = end(explode('/', $url));

                if (!file_exists(ARCHIVE_FOLDER . DS . $fileName) || isset($options['d']) || isset($options['f'])) {
                    file_put_contents(ARCHIVE_FOLDER . DS . $fileName, fopen($url, 'r'));
                }

            }
        }

    }
}