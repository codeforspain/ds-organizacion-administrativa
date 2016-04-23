# Script

Procesa los archivos fuente de autonomias, provincias, municipios, etc. alojados en `../archive`.

Si no los encuentra, los descarga.


## Modo de Uso

    $ ./php script.php [COMMANDS [source]] [OPTIONS]

Si se invoca sin argumentos, procesa todo.


### Opciones

    COMMANDS
    
        download [source]   Descarga los archivos fuente, pero no los procesa. 
                    
                            Toma como parametro opcional uno o mas de los siguientes:
                    
                            [autonomias, provincias, municipios]

                        
        process [source]    Procesa los archivos fuente, pero no los descarga.
        
                            Toma como parametro opcional uno o mas de los siguientes:
                                        
                            [autonomias, provincias, municipios]                        
                        
    OPTIONS
    
        --force, -f         Fuerza la descarga de los archivos fuente, aunque existan                         
        
       


## Requisitos

PHP 5.4+
