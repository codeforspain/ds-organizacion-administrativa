<?php
/**
 * Si no existen, descarga los archivos fuente y los graba a disco
 *
 */
class DownloadCommand extends ConsoleKit\Command
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

            $url=sprintf(Config::MUNCIPIOS_URL,$i,$i);
            if ($i>=16) $url.="x";  //xlsx a partir de 2016.
            $fileName = end(explode('/', $url));

            if (!file_exists(ARCHIVE_FOLDER . "/$fileName") || isset($options['force']) || isset($options['f'])){
                file_put_contents(ARCHIVE_FOLDER . "/$fileName", fopen($url, 'r'));
            }

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

        foreach (unserialize(Config::ISLAS_PROVINCIA_INE_CODES) as $provincia) {
            for ($i = Config::ISLAS_YEAR_START % 2000; $i <= date('y'); $i++) {

                $url = sprintf(Config::ISLAS_URL, $i, $i, $provincia);
                $fileName = end(explode('/', $url));

                if (!file_exists(ARCHIVE_FOLDER . DS . $fileName) || isset($options['d']) || isset($options['f'])) {
                    file_put_contents(ARCHIVE_FOLDER . DS . $fileName, fopen($url, 'r'));
                }

            }
        }

    }
}