[![Twitter Follow](https://img.shields.io/twitter/follow/codeforspain.svg?style=social?maxAge=2592000)](https://twitter.com/codeforspain)

# ds-organizacion-administrativa
Listado de comunidades, provincias, municipios e islas con sus correspondientes códigos INE

Este dataset es parte del proyecto abierto y colaborativo CodeForSpain. Puedes obtener más información en:

- Wiki: [CodeForSpain Wiki](https://github.com/codeforspain/datos/wiki)
- Twitter: [@codeforspain](https://twitter.com/codeforspain)
- Web: [www.codeforspain.org](http://www.codeforspain.org)
- Roadmap: [Trello Roadmap](https://trello.com/b/uI7MbPg5/codeforspain-ds-organizacion-administrativa)


## Municipios


- URL: http://www.ine.es/daco/daco42/codmun/codmun[YY]/[YY]codmun.xls ( [YY] es el último año, p.e. 16))
- Tipo: Excel (xlsx)
- Descripción: http://www.ine.es/daco/daco42/codmun/codmun00i.htm 
- Datos procesados: [/data/municipios.json](data/municipios.json) | [/data/municipios.csv](data/municipios.csv) 

 

La lista de municipios sufre modificaciones todos los años para reflejar tanto los nuevos municipios (segregados), los que han desaparecido (incorporaciones o fusiones) y los cambios en sus denominaciones oficiales.
    

### Formato de los datos


Incluye los siguientes campos:

    CPRO:   Código INE de la provincia
    CMUN:   Código INE del municipio en relación a la provincia
    DC:     Dígito de control
    NOMBRE: Denominación oficial 

Ejemplo en CSV:

| CPRO | CMUN | DC | NOMBRE           | 
|------|------|----|------------------| 
| 01   | 001  | 4  | Alegría-Dulantzi | 
| 01   | 002  | 9  | Amurrio          | 
| 01   | 049  | 3  | Añana            | 


En JSON:


    [
        {
            "CPRO": "01",                       // CÓDIGO DE LA PROVINCIA
            "CMUN": "001",                      // CÓDIGO DEL MUNICIPIO
            "DC":   "4",                        // DIGITO DE CONTROL
            "NOMBRE": "Alegría-Dulantzi""       // DENOMINACIÓN DEL MUNICIPIO 
        },
        {
            "CPRO": "01",
            "CMUN": "002",
            "DC":   "9",
            "NOMBRE": "Amurrio"
        },
        {
            "CPRO": "01",
            "CMUN": "049",
            "DC":   "3",
            "NOMBRE": "Añana"
        },



## Municipios (Histórico) 


- URL: http://www.ine.es/daco/daco42/codmun/codmun[YY]/[YY]codmun.xls ( [YY] es el año, desde 04 hasta hoy)
- Tipo: Excel (xlsx)
- Descripción: http://www.ine.es/daco/daco42/codmun/codmun00i.htm 
- Datos procesados: [/data/municipios_historical.json](data/municipios_historical.json) | [/data/municipios_historical.csv](data/municipios_historical.csv) 

    

### Formato de los datos


Incluye los siguientes campos:

    CPRO:   Código INE de la provincia
    CMUN:   Código INE del municipio en relación a la provincia
    DC:     Dígito de control
    NOMBRE: Denominación oficial
    YEAR:   Año del dato 

Ejemplo en CSV:

| CPRO | CMUN | DC | NOMBRE                   | YEAR  | 
|------|------|----|--------------------------|-------| 
| 16   |  167 |  8 |  Pozorrubio              |  2013 | 
| 16   |  167 |  8 |  Pozorrubio de Santiago  |  2014 | 


        
         
## Provincias


- URL: http://www.ine.es/daco/daco42/codmun/cod_provincia.htm
- Tipo: HTML
- Datos procesados: [/data/provincias.json](data/provincias.json) | [/data/provincias.csv](data/provincias.csv) 

Las modificaciones que se producen son solo en las denominaciones oficiales de las provincias.

### Formato de los datos


Incluye los siguientes campos:

    codigo:   Código INE de la provincia
    nombre:   Denominación oficial
    

Ejemplo en CSV:

| codigo | nombre           | 
|--------|------------------| 
| 02     | Albacete         | 
| 03     | Alicante/Alacant | 
| 04     | Almería          | 


En JSON:

    [
        {
            "codigo": "02",
            "nombre": "Albacete"
        },
        {
            "codigo": "03",
            "nombre": "Alicante\/Alacant"
        },
        {
            "codigo": "04",
            "nombre": "Almería"
        },


## Comunidades Autonomas

- URL: http://www.ine.es/daco/daco42/codmun/cod_ccaa.htm
- Tipo: HTML
- Datos procesados: [/data/autonomias.json](data/autonomias.json) | [/data/autonomias.csv](data/autonomias.csv) 

No ha sufrido modificaciones en los últimos años.  

### Formato de los datos


Incluye los siguientes campos:

    codigo:   Código INE de la autonomia
    nombre:   Denominación oficial
      


Ejemplo en CSV:

| codigo | nombre                    | 
|--------|---------------------------| 
| 01     | Andalucía                 | 
| 02     | Aragón                    | 
| 03     | "Asturias, Principado de" | 


En JSON:

    [
         {
             "codigo":"01",
             "nombre":"Andalucía"
         },
         {
            "codigo":"02",
            "nombre":"Aragón"
         },
         {
             "codigo":"03",
             "nombre":"Asturias, Principado de"
         },


## Islas


- URL: http://www.ine.es/daco/daco42/codmun/codmun[YY]/[CP]codislas[YY].xls" ( [YY] es el último año, p.e. 16, [CP] es el codigo de la pronvincia))
- Tipo: Excel (xlsx)
- Datos procesados: [/data/islas.json](data/islas.json) | [/data/islas.csv](data/islas.csv) 

  

### Formato de los datos


Incluye los siguientes campos:

    CPRO:    Código INE de la provincia
    CISLA:   Código INE de la isla
    NOMBRE:  Denominación oficial 

Ejemplo en CSV:

| CPRO | CISLA | NOMBRE        | 
|------|-------|---------------| 
| 7    | 074   | Menorca       | 
| 35   | 351   | Fuerteventura | 
| 38   | 383   | "Palma, La "  | 



## Municipios por Isla 


- URL: http://www.ine.es/daco/daco42/codmun/codmun[YY]/[CP]codislas[YY].xls" ( [YY] es el ultimo año, p.e. 16,  [CP] es el codigo de la pronvincia)
- Tipo: Excel (xlsx)
- Datos procesados: [/data/municipios_islas.json](data/municipios_islas.json) | [/data/municipios_islas.csv](data/municipios_islas.csv) 

    

### Formato de los datos


Incluye los siguientes campos:

    CPRO:   Código INE de la provincia
    CISLA:  Código INE de la isla
    CMUN:   Código INE del municipio en relación a la provincia     
    DC:     Dígito de control
    NOMBRE: Denominación oficial

Ejemplo en CSV:

| CPRO | CISLA | CMUN | DC | NOMBRE                       | 
|------|-------|------|----|------------------------------| 
| 7    | 072   | 46   | 6  | "Sant Antoni de Portmany"    | 
| 38   | 381   | 36   | 8  | "San Sebastián de la Gomera" | 
| 35   | 352   | 27   | 1  | Teror                        | 



## Municipios por Isla (Histórico)


- URL: http://www.ine.es/daco/daco42/codmun/codmun[YY]/[CP]codislas[YY].xls" ( [YY] es el año, desde 12 hasta hoy,  [CP] es el codigo de la pronvincia)
- Tipo: Excel (xlsx)
- Datos procesados: [/data/municipios_islas.json](data/municipios_islas.json) | [/data/municipios_islas.csv](data/municipios_islas.csv) 

Existen datos desde 2008, aunque solo a partir de 2012 se incorpora el código INE de la isla.
    

### Formato de los datos

Incluye los siguientes campos:

    CPRO:   Código INE de la provincia
    CISLA:  Código INE de la isla
    CMUN:   Código INE del municipio en relación a la provincia     
    DC:     Dígito de control
    NOMBRE: Denominación oficial
    YEAR:   Año del dato 

Ejemplo en CSV:

| CPRO | CISLA | CMUN | DC | NOMBRE               | YEAR | 
|------|-------|------|----|----------------------|------| 
| 38   | 384   | 52   | 6  | Vilaflor             | 2014 | 
| 38   | 384   | 52   | 6  | "Vilaflor de Chasna" | 2015 | 



## Script

El script se puede encontrar en [/scripts/](/scripts/).
