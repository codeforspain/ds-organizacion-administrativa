# Script

Procesa los archivos fuente de autonomías, provincias, municipios, islas, etc. alojados en `../archive`.

Si no los encuentra, los descarga.


## Modo de Uso

    $ ./php script.php [COMMANDS [source]] [OPTIONS]

Si se invoca sin argumentos, procesa todo.


### Opciones

    COMMANDS
    
        download [source]   Descarga los archivos fuente, pero no los procesa. 
                    
                            Toma como parámetro opcional uno o mas de los siguientes:
                    
                            [autonomías, provincias, municipios, islas]

                        
        process [source]    Procesa los archivos fuente, pero no los descarga.
        
                            Toma como parámetro opcional uno o mas de los siguientes:
                                        
                            [autonomías, provincias, municipios, islas]                        
                        
    OPTIONS
    
        --force, -f         Fuerza la descarga de los archivos fuente, aunque existan                         
        
       


## Requisitos

PHP 5.4+
