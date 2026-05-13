<?php
/**
 * Si no existen, descarga los archivos fuente y los graba a disco
 *
 */
class DownloadCommand extends ConsoleKit\Command
{
    private function downloadFirstAvailable(array $urls, $destination, array $options = array())
    {
        if (file_exists($destination) && !isset($options['force']) && !isset($options['f'])) {
            return;
        }

        foreach ($urls as $url) {
            $content = @file_get_contents($url);
            if ($content !== false) {
                file_put_contents($destination, $content);
                return;
            }
        }

        throw new RuntimeException("No se pudo descargar {$destination}");
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
     * Descarga todas los archivos fuente
     *
     * @opt force fuerza la descarga de los ficheros fuente aunque ya existan
     */
    public function executeAll(array $args, array $options = array())
    {

        foreach (get_class_methods($this) as $method) {
            if (strpos($method, 'execute') !== false && $method != 'executeAll' && $method != 'execute') {
                $this->$method($args,$options);
            }
        }
    }


    /**
     * Descarga los archivos fuente de los municipios
     *
     * @opt force fuerza la descarga de los ficheros fuente aunque ya existan
     */
    public function executeMunicipios(array $args, array $options = array())
    {
        for ($i = Config::MUNCIPIOS_YEAR_START % 2000;$i <= date('y');$i++){
            $extension = $i >= 16 ? 'xlsx' : 'xls';
            $fileName = sprintf(Config::MUNICIPIOS_SOURCE_FILE, $i);
            if ($i >= 16) {
                $fileName .= "x";
            }

            $this->downloadFirstAvailable([
                sprintf(Config::MUNCIPIOS_URL_CURRENT, $i, $extension),
                sprintf(Config::MUNCIPIOS_URL, $i, $i) . ($i >= 16 ? "x" : ""),
            ], ARCHIVE_FOLDER . DS . $fileName, $options);

        }
    }


    /**
     * Descarga el archivo fuente de las provincias
     *
     * @opt force fuerza la descarga de los ficheros fuente aunque ya existan
     */
    public function executeProvincias(array $args, array $options = array())
    {
        if (!file_exists(ARCHIVE_FOLDER . DS . Config::PROVINCIAS_SOURCE_FILE) || isset($options['force']) || isset($options['f'])){
            file_put_contents(ARCHIVE_FOLDER . DS . Config::PROVINCIAS_SOURCE_FILE, fopen(Config::PROVINCIAS_URL, 'r'));
        }

    }


    /**
     * Descarga el archivo fuente de las autonomías
     *
     * @opt force fuerza la descarga de los ficheros fuente aunque ya existan
     */
    public function executeAutonomias(array $args, array $options = array())
    {
        if (!file_exists(ARCHIVE_FOLDER . DS . Config::AUTONOMIAS_SOURCE_FILE) || isset($options['force']) || isset($options['f'])){
            file_put_contents(ARCHIVE_FOLDER . DS . Config::AUTONOMIAS_SOURCE_FILE, fopen(Config::AUTONOMIAS_URL, 'r'));
        }

    }

    /**
     * Descarga los archivos fuente de las islas
     *
     * @opt force fuerza la descarga de los ficheros fuente aunque ya existan
     */
    public function executeIslas(array $args, array $options = array())
    {
        for ($i = Config::ISLAS_YEAR_START % 2000; $i <= date('y'); $i++) {
            if ($i >= 21) {
                $this->downloadFirstAvailable([
                    sprintf(Config::ISLAS_URL_CURRENT, $i),
                ], ARCHIVE_FOLDER . DS . sprintf(Config::ISLAS_SOURCE_FILE_CURRENT, $i), $options);
                continue;
            }

            foreach (unserialize(Config::ISLAS_PROVINCIA_INE_CODES) as $provincia) {
                $this->downloadFirstAvailable([
                    sprintf(Config::ISLAS_URL, $i, $i, $provincia),
                ], ARCHIVE_FOLDER . DS . sprintf(Config::ISLAS_SOURCE_FILE, $i, $provincia), $options);
            }
        }
    }
}
