# ds-organizacion-administrativa
Listado de comunidades, provincias, municipios con sus correspondientes códigos INE

## Datos


### Municipios


URL: [Relación de municipios y códigos por provincias](http://www.ine.es/daco/daco42/codmun/codmun16/16codmunmapa.htm)
Tipo: Excel (xlsx)
Descripción: http://www.ine.es/daco/daco42/codmun/codmun00i.htm 
Script: [/scripts/municipios/](/scripts/municipios/)
Datos procesaods: [/data/municipios.json](data/municipios.json) | [/data/municipios.csv](data/municipios.csv) 

La lista de municipios sufre modificaciones todos los años para reflejar tanto los nuevos municipios (segregados), los que han desaparecido (incorporaciones o fusiones) y los cambios en su denominaciones oficiales.
    

### Formato de los datos


Incluye los campos

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
            "DC":   "
            "NOMBRE": "Alegría-Dulantzi""       // DENOMINACIÓN DEL MUNICIPIO 
        },
        {
            "CPRO": "01",
            "CMUN": "002",
            "NOMBRE": "Amurrio"
        },
        {
            "CPRO": "01",
            "CMUN": "049",
            "NOMBRE": "Añana"
        },
        {
            "CPRO": "01",
            "CMUN": "003",
            "NOMBRE": "Aramaio"
        },


### Historico

En la carpeta [/data/historical](/data/historical) se almacena un histórico con los datos correspondientes a otros años.  

### Comunidades Autonomas (PENDIENTE)

URL: http://www.ine.es/daco/daco42/codmun/cod_ccaa.htm
Tipo: HTML

No ha sufrido modificaciones en los últimos años.  



### Provincias (PENDIENTE)

URL: http://www.ine.es/daco/daco42/codmun/cod_provincia.htm
Tipo: HTML

Las modificaciones que se producen son en su denominación oficial.
