<?php
/*
    Perfiles	 	Nuevo	Editar	Eliminar	 Mostrar	Buscar 	Ordenar 
ADMINISTRADOR	 	SI	SI	SI	 SI	 SI	 SI
EDITOR	 	SI	SI	NO	 SI	 SI	SI 
REGISTRADO	 	NO	NO	NO	 SI	 SI 	 SI

*/

$GLOBALS['clientes']['main'] = [1, 2, 3];
$GLOBALS['clientes']['new'] = [1, 2];
$GLOBALS['clientes']['edit'] = [1, 2];
$GLOBALS['clientes']['delete'] = [1];
$GLOBALS['clientes']['show'] = [1, 2, 3];
$GLOBALS['clientes']['filter'] = [1, 2, 3];
$GLOBALS['clientes']['order'] = [1, 2, 3];
$GLOBALS['clientes']['export'] = [1];
$GLOBALS['clientes']['import'] = [1];

$GLOBALS['cuentas']['main'] = [1, 2, 3];
$GLOBALS['cuentas']['new'] = [1, 2];
$GLOBALS['cuentas']['edit'] = [1, 2];
$GLOBALS['cuentas']['delete'] = [1];
$GLOBALS['cuentas']['show'] = [1, 2, 3];
$GLOBALS['cuentas']['filter'] = [1, 2, 3];
$GLOBALS['cuentas']['order'] = [1, 2, 3];
$GLOBALS['cuentas']['export'] = [1];
$GLOBALS['cuentas']['import'] = [1];

$GLOBALS['movimientos']['main'] = [1, 2, 3];
$GLOBALS['movimientos']['new'] = [1, 2];
$GLOBALS['movimientos']['edit'] = [1, 2];
$GLOBALS['movimientos']['delete'] = [1];
$GLOBALS['movimientos']['show'] = [1, 2, 3];
$GLOBALS['movimientos']['filter'] = [1, 2, 3];
$GLOBALS['movimientos']['order'] = [1, 2, 3];
$GLOBALS['movimientos']['export'] = [1];
$GLOBALS['movimientos']['import'] = [1];

// Variables para Modificación y Borrado de usuarios
$GLOBALS['admin'] = [1];
$GLOBALS['admin']['mostrar'] = [1];
$GLOBALS['admin']['nuevo'] = [1];
$GLOBALS['admin']['editar'] = [1];
$GLOBALS['admin']['delete'] = [1];
$GLOBALS['admin']['order'] = [1];
$GLOBALS['admin']['filter'] = [1];