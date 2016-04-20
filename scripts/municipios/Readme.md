# Municipios

Procesa los archivos fuente de municipios que publica el INE todos los años, y que están alojados en ../archive.

Si no los encuentra, los descarga.


## Modo de Uso

    $ ./process [-hvd]


### opciones

          -h          muestra esta ayuda y termina
          -d          fuerza la descarga de los ficheros fuente aunque ya existan
          -v          modo verbose


## Requisitos

Hay que tener instalado [csvkit](https://csvkit.readthedocs.org/en/540/index.html). Para este script se ha usado la version 1.0.0.
 
Se instala mediante:

    $ sudo pip install csvkit


 
Así mismo, `pip` tiene que estar instalado. En Ubuntu esto se hace mediante:

    $ sudo apt-get install python-pip python-dev build-essential 
    $ sudo pip install --upgrade pip 
    $ sudo pip install --upgrade virtualenv 