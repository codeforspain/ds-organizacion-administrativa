# Autonomias y Provincias

Procesa los archivos fuente de autonomias alojados en `../../archive`.

Si no los encuentra, los descarga.


## Modo de Uso

    $ ./php process.php [-ephd]


### opciones


          --help, -h                Muestra ayuda y termina
          --download, -d            Fuerza la descarga de los ficheros fuente aunque ya existan
          --pretty_print, -p        Formatea la salida JSON, añadiendo indentación y CR/LF al final de las lineas.
          --escaped_unicode, -e     Codifica caracteres Unicode multibyte escapado como \\uXXXX.


## Requisitos

PHP 5.4+
