<?php
/**
*@author bauerpy
 */
if(!session_id()){
    session_start();
}
require_once  'constantes.php';

$conexion = mysql_connect(HOST, $_SESSION['user'], $_SESSION['pass']);
if(!$conexion){
    header('Location: '.'salir.php');
}
$db = mysql_select_db(BD, $conexion);