[![Twitter Follow](https://img.shields.io/twitter/follow/codeforspain.svg?style=social?maxAge=2592000)](https://twitter.com/codeforspain)

# ds-organizacion-administrativa
Listado de comunidades, provincias, municipios con sus correspondientes códigos INE

Este dataset es parte del proyecto abierto y colaborativo CodeForSpain. Puedes obtener más información en:

- [CodeForSpain Wiki](https://github.com/codeforspain/datos/wiki)
- [Twitter](https://twitter.com/codeforspain)
- [www.codeforspain.org](www.codeforspain.org)


## Municipios


- URL: [Relación de municipios y códigos por provincias](http://www.ine.es/daco/daco42/codmun/codmun16/16codmunmapa.htm)
- Tipo: Excel (xlsx)
- Descripción: http://www.ine.es/daco/daco42/codmun/codmun00i.htm 
- Script: [/scripts/municipios/](/scripts/municipios/)
- Datos procesaods: [/data/municipios.json](data/municipios.json) | [/data/municipios.csv](data/municipios.csv) 

La lista de municipios sufre modificaciones todos los años para reflejar tanto los nuevos municipios (segregados), los que han desaparecido (incorporaciones o fusiones) y los cambios en sus denominaciones oficiales.
    

### Formato de los datos


Incluye los siguientes campos:

    CPRO:   Codigo INE de la provincia
    CMUN:   Codigo INE del municipio en relacion a la provincia
    DC:     Digito de control
    NOMBRE: Denominacion oficial 

Ejemplo en CSV:

| CPRO | CMUN | DC | NOMBRE           | 
|------|------|----|------------------| 
| 01   | 001  | 4  | Alegría-Dulantzi | 
| 01   | 002  | 9  | Amurrio          | 
| 01   | 049  | 3  | Añana            | 
| 01   | 003  | 5  | Aramaio          | 


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
        {
            "CPRO": "01",
            "CMUN": "003",
            "DC":   "5",
            "NOMBRE": "Aramaio"
        },


### Historico

En la carpeta [/data/historical](/data/historical) se almacena un histórico con los datos correspondientes a otros años.  

        
         
## Provincias


- URL: http://www.ine.es/daco/daco42/codmun/cod_provincia.htm
- Tipo: HTML
- Script: [/scripts/autonomias_provincias/](/scripts/autonomias_provincias/)
- Datos procesaods: [/data/provincias.json](data/provincias.json) | [/data/provincias.csv](data/provincias.csv) 

Las modificaciones que se producen son solo en las denominaciones oficiales de las provincias.

### Formato de los datos


Incluye los siguientes campos:

    codigo:   Codigo INE de la provincia
    nombre:   Denominacion oficial
    

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
- Script: [/scripts/autonomias_provincias/](/scripts/autonomias_provincias/)
- Datos procesaods: [/data/autonomias.json](data/autonomias.json) | [/data/autonomias.csv](data/autonomias.csv) 

No ha sufrido modificaciones en los últimos años.  

### Formato de los datos


Incluye los siguientes campos:

    codigo:   Codigo INE de la autonomia
    nombre:   Denominacion oficial
      


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
