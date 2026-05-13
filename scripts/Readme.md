# Script

Procesa los archivos fuente de autonomías, provincias, municipios, islas, etc. alojados en `../archive`.

Si no los encuentra, los descarga.


## Modo de Uso

    $ php script.php [COMMAND SUBCOMMAND]] [OPTIONS]

Si se invoca sin subcomandos o argumentos, executa:

  1. download all
  2. process all  
  3. update
  4. convert-to-json


### Opciones

    COMMANDS
    
        download [SUBCOMMAND]   Descarga los archivos fuente, pero no los procesa.                    
                                Se puede especificar opcionalmente un subcomando:
                    
            source SOURCE       Descarga únicamente SOURCE, que puede ser
                                
                                    [autonomias, provincias, municipios, islas]

            all                 Descarga todas las fuentes (por defecto).
                                                                  

            OPTIONS
            
                --force, -f     Fuerza la descarga de los archivos fuente, aunque existan                         

                        
                        
        process [SUBCOMMAND]    Procesa los archivos fuente, pero no los descarga.
        
            source SOURCE       Procesa únicamente SOURCE, que puede ser uno de los siguientes:
                                
                                    [autonomias, provincias, municipios, islas]

            all                 Procesa todas las fuentes (por defecto).
                                                       
                                                            
        convert-to-json         Convierte todos los archivos .csv almacenados en /data a .json
                                usando PHP nativo.
            
        update                  Actualiza el archivo datapackage.json                                                
                        
        
       


## Requisitos

* PHP 7.4+ (probado con PHP 8.3)
* Composer
* Extensiones PHP requeridas por `phpoffice/phpspreadsheet`: `ctype`, `dom`, `fileinfo`, `gd`, `iconv`, `libxml`, `mbstring`, `simplexml`, `xml`, `xmlreader`, `xmlwriter`, `zip` y `zlib`.

### Dependencias

Las dependencias PHP se instalan desde este directorio:

    $ composer install

No hace falta instalar `csvkit`: la conversión a `.json` se hace desde PHP.


## Changelog del script

Ver [../CHANGELOG_SCRIPT.md](../CHANGELOG_SCRIPT.md).
