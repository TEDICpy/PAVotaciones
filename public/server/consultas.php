<?php
/**
*@author bauerpy
 * 
 */

require 'conexion.php';

$consulta = $_GET['sql'];

$result = mysql_query($consulta);
$data = array();
while ($fila = mysql_fetch_assoc($result,2)) {
    $data[] = array_map("utf8_encode",$fila);
}
$json_data = json_encode($data);
echo $_GET['callback'] . '(' . "{'rows' : $json_data}" . ')';
