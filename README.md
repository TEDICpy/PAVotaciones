# Parlamento Abierto

## Intro

Esta es una versión modificada de Década Votada. En vez de FusionTables utilizamos MySQL. 

## Documentación

FIXME

##Installación

Requisitos para tener funcionando en un servidor:
* Php 5
* mysql

Pasos:

1. Clonar el repo: `git clone https://github.com/TEDICpy/PAVotaciones.git`
2. Crear la bd votacionespa e importar el archivo `/db/votacionespa.sql`
3. Crear el usuario para consulta publica ejecutando el archivo `/db/usuario-publico.sql`
4. Crear el usuario para carga ejecutando el archivo `/db/usuario-carga.sql`
5. Cambiar los nombres de las tablas sql: vi `public/assets/js/Constantes.js` y luego en vi `public/parseador/constantes.php`
6. Colocar el usuario carga con su clave respectiva en `public/server/conexion.php`
7. Darle permisos 777 a la carpeta parseador: `chmod 777 -R parseador`

##Cargar asunto y votación.

1. Descargar el documento RTF (aplica solo a los RTFs generados por la Cámara de Diputados de Paraguay, por ejemplo, algunos de los RTFs que aparecen en la sesión del [5 de marzo de 2015](http://www.diputados.gov.py/plenaria/150305-SO/))
2. Subirlo con la herramienta  "parseador", logueándose con el usuario *carga* (definido anteriormente)
3. Marcar el resultado correspondiente con el RTF (afirmativo, rechazado, anulado).
4. Seleccionar el presidente de la lista según aparece en el RTF
5. Repetir el proceso por cada asunto


