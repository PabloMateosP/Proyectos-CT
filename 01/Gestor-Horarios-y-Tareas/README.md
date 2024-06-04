# README #

## Instrucciones ##
Voy a pasar a explicar la app para su fácil comprensión, uso y modificación a futuro. 

Para empezar ante un *MVC* (Modelo-Vista-Controlador) que es un patrón en el diseño de software comúnmente usado. 

Como pauta de inicio tenemos el archivo principal index.php que es el que recoge todos los demás archivos y los carga, donde como podemos ver, los primeros son los archivos de librería que son los que hacen que tengamos el patrón de diseño anteriormente nombrado. 

Como primer punto donde parar tenemos los archivos de configuración, en ellos se encuentra el archivo config en el cual declaramos la ruta de la base de datos (en el caso de un servidor se debería de poner la ruta de la base de datos en la máquina con la app), también se declaran las constantes principales como la contraseña de la base de datos, el nombre de la base de datos y diferentes cosas más que serán cargadas en el archivo database de la carpeta libs. Por otra parte el archivo privileges es con el cual definimos variables globales para los privilegios de cada usario y sus acciones.

Siguiendo con la carpeta más importante tenemos la carpeta **libs** con la cual cargaremos los archivos según el orden del patrón Modelo-Vista-Controlador estos archivos tiene una fácil comprensión ya que su principal cometido es cargar otros posteriores archivos.

Posteriormente vemos la carpeta **database** en la cual recogemos el archivo database con la estructura de nuestra base de datos mysql. 

En el siguiente escalafón de importancia vemos ante nosotros la carpeta **controllers** la cual carga la unión entre los **modelos** (donde se encuentran las funciones para recoger datos, escribir, actualizar o borrar de la base de datos) y las **vistas** (Donde mostramos los datos y las acciones). En esta carpeta se encuentra un archivo por vista que queramos mostrar. En los archivos controladores, el método principal es el método render el cual llama al método get del modelo para seleccionar los datos de la base de datos y mandarlos a la vista. Cabe recalcar que en los controladores el nombre debe ser igual que la vista mientras que en los modelos el nombre debe ser igual que en el controlador pero terminado en *Model*. En los archivos controladores es donde se hace el sanemaiento de datos y la validación de privilegios por lo que son nuestros archivos más importantes en términos de seguridad. 

En el escalafón un poco más bajo llegamos a los **modelos** aquellos archivos donde se encuentran las funciones de lectura, escritura, actualización y borrado en la base de datos. Estos archivos son llamados mediante los controladores y reciben los datos de ellos. 

Un paso hacía abajo se encuentran la carpeta **class** en la que definiremos las clases de aquellos datos que querramos crear, actualizar o borrar ya que en caso de lectura no sería necesario una nueva clase. 

En el apartado que vemos que se llama **vendor** es la carpeta necesaria a la instalación del composer para el método importar en los empleados, a su vez se tuvo que modificar el archivo php.ini para descomentar las líneas extension=gd y extension=zip para que el comando require phpoffice/phpspreadsheet funcionase. 

Seguido vemos la carpeta **node_modules** necesaria en la instalación de los módulos necesarios para el uso de fullcalendar, una librería externa que nos permite tener un calendario funcional en nuestra web. 

La carpeta **public** recoge los estilos de bootstrap para la correcta visualización de la web. 

Y finalizando tenemos la carpeta **views** donde se recogen todas las vistas de nuestra web así como en el caso del calendario el archivo js para la modificación de datos de este. En la carpeta views dentro de cada apartado se compone de diferentes carpetas más como puede ser: main, edit, new y partials.

Como última carpeta a hablar tenemos **template** en la cual se recogen archivos que pueden ser cargados en muchas vistas diferentes por lo que para reducir tamaño de la app es mejor mantener uno solo que muchos en diferentes vistas. 

El archivo **.htaccess** contiene reglas de reescritura de URL (Rewrite Rules) para un servidor web Apache. Aquí te dejo una explicación detallada de lo que hace cada línea:

* RewriteEngine On:
Esta línea activa el motor de reescritura de URL. Sin esta línea, las reglas de reescritura que siguen no tendrán efecto.

* RewriteCond %{REQUEST_FILENAME} !-d:
Esta condición verifica si el archivo solicitado no es un directorio. %{REQUEST_FILENAME} representa el nombre del archivo que se está solicitando. !-d significa "no es un directorio".

* RewriteCond %{REQUEST_FILENAME} !-f:
Esta condición verifica si el archivo solicitado no es un archivo regular. !-f significa "no es un archivo".

* RewriteCond %{REQUEST_FILENAME} !-l:
Esta condición verifica si el archivo solicitado no es un enlace simbólico. !-l significa "no es un enlace simbólico".

* RewriteRule ^(.*)$ index.php?url=$1 [L,QSA]:
Esta regla se aplica si se cumplen todas las condiciones anteriores.

* ^(.*)$ es una expresión regular que coincide con cualquier URL solicitada.
index.php?url=$1 indica que cualquier URL solicitada debe ser redirigida a index.php, pasando la URL solicitada como un parámetro url. $1 representa la parte de la URL que coincide con la expresión regular (.*).

* [L,QSA] son banderas que indican lo siguiente:
L: Significa "last rule" (última regla). Esto le dice al servidor que deje de procesar más reglas después de esta.
QSA: Significa "query string append" (añadir cadena de consulta). Esto preserva cualquier cadena de consulta existente en la URL original cuando se redirige.

## Uso ##
Para el uso de la app esta debe estar cimentada en una LAMP (Linux-Apache-MySql-PHP) para su correcto funcionamiento. 

Mis conocimientos todavía no son tan avanzados como para el despegue de la app en un servidor por lo que con la construcción de ella espero dejar el último paso para un especialista en ello. 

## IMPORTANTE ## 
Como la app será de entrada solo para aquellos que el administrador de la web haya añadido el apartado Sign Up del Inicio de la web debe ser eliminado una vez que el administrador se cree su propio usuario, por defecto el primer usuario creado será administrador por lo que al crear ese usuario ya puede hacer todas las acciones  

Por otra parte, el correo del empleado y del usuario de el mismo deben ser iguales ya que al ser únicos es la forma más segurad de referenciar el empleado por su usuario en casos como: 
* Ver sus horas
* Ver los proyectos en los que fue asignado 
* ... 