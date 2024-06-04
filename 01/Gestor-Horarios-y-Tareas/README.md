# README #

## Instrucciones ##
Voy a pasar a explicar la app para su fácil comprensión, uso y modificación a futuro. 

Para empezar ante un *MVC* (Modelo-Vista-Controlador) que es un patrón en el diseño de software comúnmente usado. 

Como pauta de inicio tenemos el archivo principal index.php que es el que recoge todos los demás archivos y los carga, donde como podemos ver, los primeros son los archivos de librería que son los que hacen que tengamos el patrón de diseño anteriormente nombrado. 

Como primer punto donde parar tenemos los archivos de configuración, en ellos se encuentra el archivo config en el cual declaramos la ruta de la base de datos (en el caso de un servidor se debería de poner la ruta de la base de datos en la máquina con la app), también se declaran las constantes principales como la contraseña de la base de datos, el nombre de la base de datos y diferentes cosas más que serán cargadas en el archivo database de la carpeta libs. Por otra parte el archivo privileges es con el cual definimos variables globales para los privilegios de cada usario y sus acciones.

Siguiendo con la carpeta más importante tenemos la carpeta **libs** con la cual cargaremos los archivos según el orden del patrón Modelo-Vista-Controlador estos archivos tiene una fácil comprensión ya que su principal cometido es cargar otros posteriores archivos.

Posteriormente vemos la carpeta **database** en la cual recogemos el archivo database con la estructura de nuestra base de datos mysql. 




