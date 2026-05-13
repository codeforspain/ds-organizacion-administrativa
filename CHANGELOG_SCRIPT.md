# Changelog del script

| Fecha | Cambio |
|-------|--------|
| 2026-05-13 | Migración de `phpoffice/phpexcel` a `phpoffice/phpspreadsheet` para compatibilidad con PHP moderno. |
| 2026-05-13 | Actualización de rutas de descarga del INE: ficheros directos `YYcodmun.xlsx` y `YYcodislas.xlsx`, manteniendo fallback a rutas históricas. |
| 2026-05-13 | Soporte para el formato INE 2021+: municipios en hojas por provincia y municipios-isla en hojas `07`, `35` y `38`. |
| 2026-05-13 | Sustitución del parser HTML antiguo por `DOMDocument`/`DOMXPath` para comunidades y provincias. |
| 2026-05-13 | Conversión CSV a JSON en PHP nativo, sin depender de `csvkit`. |
| 2017-12-12 | Corrección de avisos `E_STRICT`. |
| 2017-03-14 | Actualización de datos a 2017 y ajuste de scripts por cambios en las fuentes. |
| 2016-05-01 | Refactor de configuración, generación de `datapackage.json` y opción `--nojson`. |
| 2016-04-24 | Incorporación de islas. |
| 2016-04-23 | Unificación de scripts en `scripts/script.php`. |
| 2016-04-20 | Primera versión del script de municipios. |
