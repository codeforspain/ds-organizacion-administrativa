#!/bin/bash

BASE_PATH="`dirname \"$0\"`"                      # relative
BASE_PATH="`( cd \"$BASE_PATH\"/../.. && pwd )`"  # absolute

# Ruta de los archivos fuente
ARCHIVE=$BASE_PATH/archive

# Ruta donde se almacenan los datos procesados
DATA=$BASE_PATH/data

# Ruta donde se almacenan los datos procesados historicos
HISTORICAL_DATA=$BASE_PATH/data/historical

export PYTHONWARNINGS="ignore"
_VERBOSE=0
FORCE_DOWNLOAD=0

# verbose mode
function log () {
    if [[ $_VERBOSE -eq 1 ]]; then
        echo -e "$@"
    fi
}


# Usage
function show_help() {
cat << EOF
Uso: ${0##*/} [-hvd]
Procesa los archivos fuente de municipios que publica el INE todos los años, y que están alojados en ../../archive.
Si no los encuentra, los descarga.

     -h          muestra esta ayuda y termina
     -d          fuerza la descarga de los ficheros fuente aunque ya existan
     -v          modo verbose

EOF
}





# A POSIX variable
OPTIND=1         # Reset

# Initialize our own variables:

while getopts "dh?vf:" opt; do
    case "$opt" in
    h|\?)
        show_help
        exit 0
        ;;
    v)
        _VERBOSE=1
        export PYTHONWARNINGS=
        ;;
    d)
        FORCE_DOWNLOAD=1
        ;;
    esac
done

# Creamos los directorios si no existen
mkdir -p $ARCHIVE
mkdir -p $DATA
mkdir -p $HISTORICAL_DATA


CURRYEAR=`date +%y`
log Año actual: $CURRYEAR
log "----------------------------".

EXT=xls

for i in $( seq 4 $CURRYEAR); do

    if [ "$i" -gt "15" ]; then
        EXT=xlsx
    fi

    if [ "$i" -eq "4" ]; then
       LINES_TO_STRIP=1
    else
       LINES_TO_STRIP=2
    fi

    YEAR=`printf %02d $i`
    SOURCEFILE="$YEAR"codmun.$EXT
    CSVFILE=municipios-20$YEAR.csv
    JSONFILE=municipios-20$YEAR.json

    log Procesando año 20$YEAR

    cd $ARCHIVE
    if [ ! -f $SOURCEFILE ] || [ $FORCE_DOWNLOAD -eq 1 ]; then
        log No existe copia local del archivo $SOURCEFILE. DESCARGAMOS \\n
        log
        curl -O http://www.ine.es/daco/daco42/codmun/codmun"$YEAR"/$SOURCEFILE
        log \\n
    fi

    log Generamos $CSVFILE

    in2csv $SOURCEFILE |tail -n +$LINES_TO_STRIP >$HISTORICAL_DATA/$CSVFILE

    log Generamos $JSONFILE
    csvjson $HISTORICAL_DATA/$CSVFILE > $HISTORICAL_DATA/$JSONFILE
    log "\\n----------------------------"\\n


done

log Creamos symlinks \(último procesado\)\\n

echo ln -f $HISTORICAL_DATA/$CSVFILE  $DATA/municipios.csv
echo ln -f $HISTORICAL_DATA/$JSONFILE $DATA/municipios.json

ln -f $HISTORICAL_DATA/$CSVFILE  $DATA/municipios.csv
ln -f $HISTORICAL_DATA/$JSONFILE $DATA/municipios.json

log  \\nNota: Se puede ignorar el warning \"Discarded range with reserved name\" en caso de que se produzca

