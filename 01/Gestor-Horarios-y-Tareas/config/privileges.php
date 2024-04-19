<?php
/*
    Perfiles	 	Nuevo	Editar	Eliminar	 Mostrar	Buscar 	Ordenar 
ADMINISTRADOR	 	SI	SI	SI	 SI	 SI	 SI
EDITOR	 	SI	SI	NO	 SI	 SI	SI 
REGISTRADO	 	NO	NO	NO	 SI	 SI 	 SI

*/

$GLOBALS['employees']['main'] = [1, 2, 3];
$GLOBALS['employees']['new'] = [1, 2];
$GLOBALS['employees']['edit'] = [1, 2];
$GLOBALS['employees']['delete'] = [1];
$GLOBALS['employees']['show'] = [1, 2, 3];
$GLOBALS['employees']['filter'] = [1, 2, 3];
$GLOBALS['employees']['order'] = [1, 2, 3];
$GLOBALS['employees']['export'] = [1];
$GLOBALS['employees']['import'] = [1];

$GLOBALS['workingHours']['main'] = [1, 2, 3];
$GLOBALS['workingHours']['new'] = [1, 2];
$GLOBALS['workingHours']['edit'] = [1, 2];
$GLOBALS['workingHours']['delete'] = [1];
$GLOBALS['workingHours']['show'] = [1, 2, 3];
$GLOBALS['workingHours']['filter'] = [1, 2, 3];
$GLOBALS['workingHours']['order'] = [1, 2, 3];
$GLOBALS['workingHours']['export'] = [1];
$GLOBALS['workingHours']['import'] = [1];

// Variables para Modificación y Borrado de usuarios
$GLOBALS['admin'] = [1];
$GLOBALS['admin']['mostrar'] = [1];
$GLOBALS['admin']['nuevo'] = [1];
$GLOBALS['admin']['editar'] = [1];
$GLOBALS['admin']['delete'] = [1];
$GLOBALS['admin']['order'] = [1];
$GLOBALS['admin']['filter'] = [1];