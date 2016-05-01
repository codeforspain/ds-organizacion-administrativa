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

   

### Formato de los datos


Incluye los siguientes campos:

    
    municipio_id:   Código INE del municipio
    provincia_id:   Código INE de la provincia
    cmun:           Código INE del municipio en relación a la provincia
    dc:             Dígito de control
    nombre:         Denominación oficial 

Ejemplo en CSV:

|municipio_id| provincia_id | cmun | dc | nombre           | 
|------------|--------------|------|----|------------------| 
| 01001      | 01           | 001  | 4  | Alegría-Dulantzi | 
| 01002      | 01           | 002  | 9  | Amurrio          | 
| 01049      | 01           | 049  | 3  | Añana            | 


En JSON:


    [
        {
            "municipio_id": "01001",               
            "provincia_id": "01",               
            "cmun": "001",                     
            "dc":   "4",                       
            "nombre": "Alegría-Dulantzi""       
        },
        {
            "municipio_id": "01002",               
            "provincia_id": "01",
            "cmun": "002",
            "dc":   "9",
            "nombre": "Amurrio"
        },
        {
            "municipio_id": "01049",               
            "provincia_id": "01",
            "cmun": "049",
            "dc":   "3",
            "nombre": "Añana"
        },



## Municipios (Histórico) 


- URL: http://www.ine.es/daco/daco42/codmun/codmun[YY]/[YY]codmun.xls ( [YY] es el año, desde 04 hasta hoy)
- Tipo: Excel (xlsx)
- Descripción: http://www.ine.es/daco/daco42/codmun/codmun00i.htm 
- Datos procesados: [/data/municipios_historical.json](data/municipios_historical.json) | [/data/municipios_historical.csv](data/municipios_historical.csv) 

La lista de municipios sufre modificaciones todos los años para reflejar tanto los nuevos municipios (segregados), los que han desaparecido (incorporaciones o fusiones) y los cambios en sus denominaciones oficiales.


### Formato de los datos


Incluye los siguientes campos:

    municipio_id:   Código INE del municipio
    year:           Año del dato         
    provincia_id:   Código INE de la provincia
    cmun:           Código INE del municipio en relación a la provincia
    dc:             Dígito de control
    nombre:         Denominación oficial
    

Ejemplo en CSV:

|municipio_id| year  | provincia_id  | cmun | dc | nombre                   | 
|------------|-------|---------------|------|----|--------------------------| 
| 16167      |  2013 |16             |  167 |  8 |  Pozorrubio              | 
| 16167      |  2014 |16             |  167 |  8 |  Pozorrubio de Santiago  | 


        
         
## Provincias


- URL: http://www.ine.es/daco/daco42/codmun/cod_provincia.htm
- Tipo: HTML
- Datos procesados: [/data/provincias.json](data/provincias.json) | [/data/provincias.csv](data/provincias.csv) 

Las modificaciones que se producen son solo en las denominaciones oficiales de las provincias.


### Formato de los datos

Incluye los siguientes campos:

    provincia_id:   Código INE de la provincia
    nombre:         Denominación oficial
    

Ejemplo en CSV:

| provincia_id | nombre           | 
|--------------|------------------| 
| 02           | Albacete         | 
| 03           | Alicante/Alacant | 
| 04           | Almería          | 


En JSON:

    [
        {
            "provincia_id": "02",
            "nombre":       "Albacete"
        },
        {
            "provincia_id": "03",
            "nombre":       "Alicante\/Alacant"
        },
        {
            "provincia_id": "04",
            "nombre":       "Almería"
        },



## Comunidades Autonomas

- URL: http://www.ine.es/daco/daco42/codmun/cod_ccaa.htm
- Tipo: HTML
- Datos procesados: [/data/autonomias.json](data/autonomias.json) | [/data/autonomias.csv](data/autonomias.csv) 

No ha sufrido modificaciones en los últimos años.  

### Formato de los datos


Incluye los siguientes campos:

    autonomia_id:   Código INE de la autonomia
    nombre:         Denominación oficial
      


Ejemplo en CSV:

| autonomia_id | nombre                    | 
|--------------|---------------------------| 
| 01           | Andalucía                 | 
| 02           | Aragón                    | 
| 03           | "Asturias, Principado de" | 


En JSON:

    [
         {
             "autonomia_id":"01",
             "nombre":"Andalucía"
         },
         {
            "autonomia_id":"02",
            "nombre":"Aragón"
         },
         {
             "autonomia_id":"03",
             "nombre":"Asturias, Principado de"
         },


## Islas


- URL: http://www.ine.es/daco/daco42/codmun/codmun[YY]/[CP]codislas[YY].xls" ( [YY] es el último año, p.e. 16, [CP] es el codigo de la pronvincia))
- Tipo: Excel (xlsx)
- Datos procesados: [/data/islas.json](data/islas.json) | [/data/islas.csv](data/islas.csv) 

  

### Formato de los datos


Incluye los siguientes campos:

    isla_id:        Código INE de la isla
    provincia_id:   Código INE de la provincia
    nombre:         Denominación oficial 

Ejemplo en CSV:

| isla_id | provincia_id  | nombre        | 
|---------|---------------|---------------| 
| 074     | 07            | Menorca       | 
| 351     | 35            | Fuerteventura | 
| 383     | 38            | "Palma, La "  | 



## Municipios por Isla 


- URL: http://www.ine.es/daco/daco42/codmun/codmun[YY]/[CP]codislas[YY].xls" ( [YY] es el ultimo año, p.e. 16,  [CP] es el codigo de la pronvincia)
- Tipo: Excel (xlsx)
- Datos procesados: [/data/municipios_islas.json](data/municipios_islas.json) | [/data/municipios_islas.csv](data/municipios_islas.csv) 

    

### Formato de los datos


Incluye los siguientes campos:

    municipio_id:   Código INE del municipio
    provincia_id:   Código INE de la provincia
    isla_id:        Código INE de la isla
    cmun:           Código INE del municipio en relación a la provincia     
    dc:             Dígito de control
    nombre:         Denominación oficial


Ejemplo en CSV:

| municipio_id | provincia_id  | isla_id | cmun | dc | nombre                       | 
|--------------|---------------|---------|------|----|------------------------------| 
| 07046        | 07            | 072     | 046  | 6  | "Sant Antoni de Portmany"    | 
| 38036        | 38            | 381     | 036  | 8  | "San Sebastián de la Gomera" | 
| 35027        | 35            | 352     | 027  | 1  | Teror                        | 



## Municipios por Isla (Histórico)


- URL: http://www.ine.es/daco/daco42/codmun/codmun[YY]/[CP]codislas[YY].xls" ( [YY] es el año, desde 12 hasta hoy,  [CP] es el codigo de la pronvincia)
- Tipo: Excel (xlsx)
- Datos procesados: [/data/municipios_islas.json](data/municipios_islas.json) | [/data/municipios_islas.csv](data/municipios_islas.csv) 

Existen datos desde 2008, aunque solo a partir de 2012 se incorpora el código INE de la isla.
    

### Formato de los datos

Incluye los siguientes campos:

    municipio_id:   Código INE del municipio  
    year:           Año del dato       
    provincia_id:   Código INE de la provincia
    isla_id:        Código INE de la isla
    cmun:           Código INE del municipio en relación a la provincia     
    dc:             Dígito de control
    nombre:         Denominación oficial


Ejemplo en CSV:

| municipio_id | year | provincia_id  | isla_id | cmun | dc | nombre               | 
|--------------|------|---------------|---------|------|----|----------------------| 
| 38052        | 2014 | 38            | 384     | 052  | 6  | Vilaflor             | 
| 38052        | 2015 | 38            | 384     | 052  | 6  | "Vilaflor de Chasna" | 



## Script

El script se puede encontrar en [/scripts/](/scripts/).
