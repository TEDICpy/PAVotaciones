<!DOCTYPE html>
<head>
    <title>D&eacute;cada Votada</title>
    <meta http-equiv="Content-Type" content="text/html; charset=8851-9" />
    <link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/flat-ui/css/flat-ui.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet"/>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="http://andytow.com/scripts/elecciones/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="http://andytow.com/scripts/elecciones/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />
<style>
	.some-box
	{
		width: 16px;
		height: 16px;
		display:inline-block;
		white-space:nowrap;
	}
	.borderless tbody tr td, .borderless thead tr th {
    border: none;
	padding: 0px;
	
	}
</style>	
</head>
<body>
	<script src="assets/js/d3.v3.min.js"></script>
<div class="container">
    <div class="row">
        <div class="span8">
            <h1>D&eacute;cada Votada <small class="diputados">[diputados]</small></h1>
        </div>
        <div class="span4"><br>
            La <a href="http://www.andytow.com/blog/2013/12/02/disciplina-de-bloques/" target="_blank">tasa de disciplina</a> es la proporci&oacute;n de votaciones en las que el legislador adopt&oacute; la misma posici&oacute;n que la mayor&iacute;a de su bloque, teniendo &eacute;ste tres o m&aacute;s miembros.
        </div>		
    </div>
    <div class="row">
	<center>
	<a href="?year=&district=<?echo $district;?>&bloc=<?echo $bloc;?>&order=<?echo $order;?>"><button type="button" class="btn btn-<?php
	if ($year == '') {
	echo "btn btn-primary";
       } else {
	echo "btn btn-info";   
	   }?>"><strong>Todos</strong></button></a>
<?php
	require('qs_connection-d.php');
	require('qs_functions.php');
	$year = qsrequest("year");
	$leg = qsrequest("leg");
	$bloc = qsrequest("bloc");
	$district = qsrequest("district");
	$order = qsrequest("order");

	$i=0;
	$resultperiodos = mysql_query("SELECT ano FROM disciplina WHERE votos_bloque > 0 GROUP BY ano ORDER BY ano ASC");
	while ($row = mysql_fetch_array($resultperiodos)) {
	$ano = $row["ano"];
?>
			<a href="?year=<?echo $ano;?>&district=<?echo $district;?>&bloc=<?echo $bloc;?>&order=<?echo $order;?>"><button type="button" class="<?php
	if ($ano == $year) {
	echo "btn btn-primary";
       } else {
	echo "btn btn-info";   
	   }?>"><?echo $ano;?></button></a>
				
<?php
	$i++;
	}
?>

	</center>
	</div>
	<div class="row">
	<table class="table table-striped" style="overflow: auto; width:960px; display:block">
	<tr>
	<th width="2%">
<?php
	if ($bloc ==!'') 
	{
?>
	<a href="?year=<?echo $year;?>&district=<?echo $district;?>&bloc=&order=<?echo $order;?>"><font color="red">X</font></a>
<?
	}
?>	</th>
	<th width="20%"><small><center><a href="?year=<?echo $year;?>&district=<?echo $district;?>&bloc=<?echo $bloc;?>&order=name<?php
	if ($order=='name') {
	echo "desc";
	}?>
	" title="Ordenar">Nombre</a></center></th>
	<th width="20%"><small><center><a href="?year=<?echo $year;?>&district=<?echo $district;?>&bloc=<?echo $bloc;?>&order=district<?php
	if ($order=='district') {
	echo "desc";
	}?>
	" title="Ordenar">Distrito</a>
<?php
	if ($district ==!'') 
	{
?>
	<a href="?year=<?echo $year;?>&district=&bloc=<?echo $bloc;?>&order=<?echo $order;?>"><font color="red">X</font></a>
<?
	}
?>
	</center></small></th>
	<th width="14%"><small><center><a href="?year=<?echo $year;?>&district=<?echo $district;?>&bloc=<?echo $bloc;?>&order=votos<?php
	if ($order=='votos') {
	echo "desc";
	}?>
	" title="Ordenar" data-toogle="tooltip">Votaciones</small></center></th>
	<th width="16%"><small><center><a href="?year=<?echo $year;?>&district=<?echo $district;?>&bloc=<?echo $bloc;?>&order=disciplin<?php
	if ($order=='disciplin') {
	echo "desc";
	}?>
	" title="Ordenar">Coincidencias</small></center></th>
	<th width="22%" colspan="2"><small><center><a href="?year=<?echo $year;?>&district=<?echo $district;?>&bloc=<?echo $bloc;?>&order=tasa<?php
	if ($order=='tasa') {
	echo "asc";
	}?>
	" title="Ordenar">Tasa de disciplina</a></center></small></th>
	</tr>
	</table>
	<table class="table table-striped" style="overflow: auto; height:402px; width:960px; display:block">
<?php

	
	$ii=0;

	if ($year !== '' and $district=='' and $bloc=='' and $order=='') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}	
//order=name
	if ($year !== '' and $district=='' and $bloc=='' and $order=='name') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='name') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='name') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='name') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='name') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='name') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre ASC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='name') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='name') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre ASC");
	}
//order=namedesc
	if ($year !== '' and $district=='' and $bloc=='' and $order=='namedesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre DESC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='namedesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre DESC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='namedesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre DESC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='namedesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre DESC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='namedesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre DESC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='namedesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre DESC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='namedesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre DESC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='namedesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY nombre DESC");
	}	
	
//order=tasaasc
	if ($year !== '' and $district=='' and $bloc=='' and $order=='tasaasc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) ASC, SUM(disciplinas) ASC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='tasaasc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) ASC, SUM(disciplinas) ASC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='tasaasc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) ASC, SUM(disciplinas) ASC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='tasaasc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) ASC, SUM(disciplinas) ASC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='tasaasc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) ASC, SUM(disciplinas) ASC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='tasaasc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) ASC, SUM(disciplinas) ASC, nombre ASC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='tasaasc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) ASC, SUM(disciplinas) ASC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='tasaasc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) ASC, SUM(disciplinas) ASC, nombre ASC");
	}
//order=tasadesc
	if ($year !== '' and $district=='' and $bloc=='' and $order=='tasa') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='tasa') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='tasa') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='tasa') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='tasa') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='tasa') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='tasa') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='tasa') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY (SUM(disciplinas) / SUM(votos_bloque)) DESC, SUM(disciplinas) DESC, nombre ASC");
	}
//order=district
	if ($year !== '' and $district=='' and $bloc=='' and $order=='district') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito ASC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='district') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito ASC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='district') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito ASC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='district') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito ASC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='district') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito ASC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='district') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito ASC, nombre ASC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='district') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito ASC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='district') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito ASC, nombre ASC");
	}
//order=distritodesc
	if ($year !== '' and $district=='' and $bloc=='' and $order=='districtdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito DESC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='districtdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito DESC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='districtdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito DESC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='districtdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito DESC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='districtdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito DESC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='districtdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito DESC, nombre ASC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='districtdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito DESC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='districtdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY distrito DESC, nombre ASC");
	}


//order=votacionesasc
	if ($year !== '' and $district=='' and $bloc=='' and $order=='votos') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) ASC, SUM(disciplinas) ASC, (SUM(disciplinas) / SUM(votos_bloque)) ASC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='votos') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) ASC, SUM(disciplinas) ASC, (SUM(disciplinas) / SUM(votos_bloque)) ASC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='votos') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) ASC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='votos') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) ASC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='votos') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) ASC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='votos') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) ASC, nombre ASC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='votos') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) ASC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='votos') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) ASC, nombre ASC");
	}
//order=votacionesdesc
	if ($year !== '' and $district=='' and $bloc=='' and $order=='votosdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) DESC, SUM(disciplinas) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='votosdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) DESC, SUM(disciplinas) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='votosdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) DESC, SUM(disciplinas) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='votosdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) DESC, SUM(disciplinas) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='votosdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) DESC, SUM(disciplinas) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='votosdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) DESC, SUM(disciplinas) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='votosdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) DESC, SUM(disciplinas) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='votosdesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) DESC, SUM(disciplinas) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}

//order=disciplin
	if ($year !== '' and $district=='' and $bloc=='' and $order=='disciplin') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) ASC, SUM(disciplinas) ASC, (SUM(disciplinas) / SUM(votos_bloque)) ASC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='disciplin') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(votos_bloque) ASC, SUM(disciplinas) ASC, (SUM(disciplinas) / SUM(votos_bloque)) ASC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='disciplin') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) ASC, SUM(votos_bloque) ASC, (SUM(disciplinas) / SUM(votos_bloque)) ASC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='disciplin') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) ASC, SUM(votos_bloque) ASC, (SUM(disciplinas) / SUM(votos_bloque)) ASC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='disciplin') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) ASC, SUM(votos_bloque) ASC, (SUM(disciplinas) / SUM(votos_bloque)) ASC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='disciplin') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) ASC, SUM(votos_bloque) ASC, (SUM(disciplinas) / SUM(votos_bloque)) ASC, nombre ASC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='disciplin') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) ASC, SUM(votos_bloque) ASC, (SUM(disciplinas) / SUM(votos_bloque)) ASC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='disciplin') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) ASC, SUM(votos_bloque) ASC, (SUM(disciplinas) / SUM(votos_bloque)) ASC, nombre ASC");
	}
//order=disciplindesc
	if ($year !== '' and $district=='' and $bloc=='' and $order=='disciplindesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) DESC, SUM(votos_bloque) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc=='' and $order=='disciplindesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) DESC, SUM(votos_bloque) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc=='' and $order=='disciplindesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) DESC, SUM(votos_bloque) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc=='' and $order=='disciplindesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) DESC, SUM(votos_bloque) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year == '' and $district=='' and $bloc!=='' and $order=='disciplindesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) DESC, SUM(votos_bloque) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year == '' and $district!=='' and $bloc!=='' and $order=='disciplindesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) DESC, SUM(votos_bloque) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year !== '' and $district=='' and $bloc!=='' and $order=='disciplindesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) DESC, SUM(votos_bloque) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	if ($year !== '' and $district!=='' and $bloc!=='' and $order=='disciplindesc') {
	$resultdisciplina = mysql_query("SELECT nombre, diputadoId, SUM(disciplinas) AS disciplines, SUM(votos_bloque) AS votos_bloques, distrito, color, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY nombre, distrito, color ORDER BY SUM(disciplinas) DESC, SUM(votos_bloque) DESC, (SUM(disciplinas) / SUM(votos_bloque)) DESC, nombre ASC");
	}
	
	while ($row = mysql_fetch_array($resultdisciplina)) {
	$nombre = $row["nombre"];
	$disciplina = $row["disciplines"];
	$disciplinas = number_format($disciplina, 0, ',', '.');
	$distrito = $row["distrito"];
	$votos_bloques = $row["votos_bloques"];
	$votos_bloque = number_format($votos_bloques, 0, ',', '.');
	$color = $row["color"];
	$indice = $row["indice"] * 100;
	$indicef = number_format($indice, 1, ',', ' ');
	$indicefi = number_format($indice, 0);
	$noindicef = 100 - $indicefi;
	$diputadoId = $row["diputadoId"];
	$iin = $ii + 1;
?>
	<tr>
		<td width="30%" colspan="2"><sup><?echo $iin;?> - </sup><a href="?year=<?echo $year;?>&district=<?echo $district;?>&bloc=<?echo $color;?>&order=<?echo $order;?>"><div class="some-box" style="background-color:#<?echo $color;?>;"></div>&nbsp;&nbsp;<small><a class="fancybox fancybox.iframe" href="legislator-d.php?leg=<?echo $diputadoId;?>" target="_blank"><?echo $nombre;?></small></td>
		<td width="18%" style="text-align: center;"><small><a href="?year=<?echo $year;?>&district=<?echo $distrito;?>&bloc=<?echo $bloc;?>&order=<?echo $order;?>"><?echo $distrito;?></small></td>
		<td width="15%" style="text-align: right;"><small><?echo $votos_bloque;?></small></td>
		<td width="15%" style="text-align: right;"><small><?echo $disciplinas;?></small></td>
		<td width="22%" style="text-align: right;"><table class="borderless" width="100%"><tr><td><div align="center"><div id="chart<?echo $ii;?>"></div></div></td><td style="text-align: right;"><small><?echo $indicef;?>%</small></td></tr></table><?php
	if ($year == '') {
	?>
<div align="center">
        <div id="viz<?echo $ii;?>"></div>
</div>		
        <script type="text/javascript">
            
            var w = 200,
            h = 20

            // create canvas
            var svg = d3.select("#viz<?echo $ii;?>").append("svg:svg")
            .attr("class", "chart")
            .attr("width", w)
            .attr("height", h )
            .append("svg:g")
            .attr("transform", "translate(10,20)");

            x = d3.scale.ordinal().rangeRoundBands([0, w-5])
            y = d3.scale.linear().range([0, h-5])
            z = d3.scale.ordinal().range(["#1f77b4", "#ff7f0e", "white"])
	    // 4 columns: ID,c1,c2,c3
            var matrix = [ <?php
	$j=0;
	$resultheat = mysql_query("SELECT ano, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE diputadoId = $diputadoId GROUP BY ano ORDER BY ano ASC");
	while ($row = mysql_fetch_array($resultheat)) {
	$aniostat = $row["ano"];
	$indicehstat = $row["indice"] * 100;
	$indicefihstat = number_format($indicehstat, 0);
	$nindicefihstat = 100 - $indicefihstat;

	if ($nindicefihstat == 100) { $nindicefihstat = 0; }
?>
                [ <?echo $j;?>, <?echo $indicefihstat;?>, <?echo $nindicefihstat;?>, 0 ],
<?php
	$j++;
	}
?>
            ];
            var remapped =["c1","c2","c3"].map(function(dat,i){
                return matrix.map(function(d,ii){
                    return {x: ii, y: d[i+1] };
                })
            });

            var stacked = d3.layout.stack()(remapped)


            x.domain(stacked[0].map(function(d) { return d.x; }));
            y.domain([0, d3.max(stacked[stacked.length - 1], function(d) { return d.y0 + d.y; })]);


            var valgroup = svg.selectAll("g.valgroup")
            .data(stacked)
            .enter().append("svg:g")
            .attr("class", "valgroup")
            .style("fill", function(d, i) { return z(i); });

            // Add a rect for each date.
            var rect = valgroup.selectAll("rect")
            .data(function(d){return d;})
            .enter().append("svg:rect")
            .attr("x", function(d) { return x(d.x); })
            .attr("y", function(d) { return -y(d.y0) - y(d.y); })
            .attr("height", function(d) { return y(d.y); })
            .attr("width", 10);

        </script>
<?php
}
?></td>
<script>
var w = 28;
var h = 28;
var r = h/2;
var color = d3.scale.category10();

var data = [{"label":"", "value":<?echo $indicefi;?>}, 
		   {"label":"", "value":<?echo $noindicef;?>}];


var vis = d3.select('#chart<?echo $ii;?>').append("svg:svg").data([data]).attr("width", w).attr("height", h).append("svg:g").attr("transform", "translate(" + r + "," + r + ")");
var pie = d3.layout.pie().value(function(d){return d.value;});


var arc = d3.svg.arc().outerRadius(r);


var arcs = vis.selectAll("g.slice").data(pie).enter().append("svg:g").attr("class", "slice");
arcs.append("svg:path")
    .attr("fill", function(d, i){
        return color(i);
    })
    .attr("d", function (d) {
        return arc(d);
    });

// add the text
		
</script>
	</tr>
<?php
	$ii++;
	}
?>
	</table>
<?php
$iii=0;
	if ($year !== '' and $district=='' and $bloc=='') {
	$resultstats = mysql_query("SELECT COUNT(nombre) AS nombres, SUM(disciplinas) AS disciplinesstat, AVG(disciplinas) AS disciplineavg, SUM(votos_bloque) AS votos_bloquesstat, AVG(votos_bloque) AS votos_bloqueavg, (SUM(disciplinas) / SUM(votos_bloque)) AS indicestat, (AVG(disciplinas) / AVG(votos_bloque)) AS indicesavg FROM disciplina WHERE ano = $year AND votos_bloque > 0");
	}
	if ($year == '' and $district=='' and $bloc=='') {
	$resultstats = mysql_query("SELECT COUNT(nombre) AS nombres, SUM(disciplinas) AS disciplinesstat, AVG(disciplinas) AS disciplineavg, SUM(votos_bloque) AS votos_bloquesstat, AVG(votos_bloque) AS votos_bloqueavg, (SUM(disciplinas) / SUM(votos_bloque)) AS indicestat, (AVG(disciplinas) / AVG(votos_bloque)) AS indicesavg FROM disciplina WHERE votos_bloque > 0");
	}
	if ($year == '' and $district!=='' and $bloc=='') {
	$resultstats = mysql_query("SELECT COUNT(nombre) AS nombres, SUM(disciplinas) AS disciplinesstat, AVG(disciplinas) AS disciplineavg, SUM(votos_bloque) AS votos_bloquesstat, AVG(votos_bloque) AS votos_bloqueavg, (SUM(disciplinas) / SUM(votos_bloque)) AS indicestat, (AVG(disciplinas) / AVG(votos_bloque)) AS indicesavg FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0");
	}
	if ($year !== '' and $district!=='' and $bloc=='') {
	$resultstats = mysql_query("SELECT COUNT(nombre) AS nombres, SUM(disciplinas) AS disciplinesstat, AVG(disciplinas) AS disciplineavg, SUM(votos_bloque) AS votos_bloquesstat, AVG(votos_bloque) AS votos_bloqueavg, (SUM(disciplinas) / SUM(votos_bloque)) AS indicestat, (AVG(disciplinas) / AVG(votos_bloque)) AS indicesavg FROM disciplina WHERE ano = $year AND distrito = '$district' AND votos_bloque > 0");
	}
	if ($year == '' and $district=='' and $bloc!=='') {
	$resultstats = mysql_query("SELECT COUNT(nombre) AS nombres, SUM(disciplinas) AS disciplinesstat, AVG(disciplinas) AS disciplineavg, SUM(votos_bloque) AS votos_bloquesstat, AVG(votos_bloque) AS votos_bloqueavg, (SUM(disciplinas) / SUM(votos_bloque)) AS indicestat, (AVG(disciplinas) / AVG(votos_bloque)) AS indicesavg FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0");
	}
	if ($year == '' and $district!=='' and $bloc!=='') {
	$resultstats = mysql_query("SELECT COUNT(nombre) AS nombres, SUM(disciplinas) AS disciplinesstat, AVG(disciplinas) AS disciplineavg, SUM(votos_bloque) AS votos_bloquesstat, AVG(votos_bloque) AS votos_bloqueavg, (SUM(disciplinas) / SUM(votos_bloque)) AS indicestat, (AVG(disciplinas) / AVG(votos_bloque)) AS indicesavg FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0");
	}
	if ($year !== '' and $district=='' and $bloc!=='') {
	$resultstats = mysql_query("SELECT COUNT(nombre) AS nombres, SUM(disciplinas) AS disciplinesstat, AVG(disciplinas) AS disciplineavg, SUM(votos_bloque) AS votos_bloquesstat, AVG(votos_bloque) AS votos_bloqueavg, (SUM(disciplinas) / SUM(votos_bloque)) AS indicestat, (AVG(disciplinas) / AVG(votos_bloque)) AS indicesavg FROM disciplina WHERE ano=$year AND color = '$bloc' AND votos_bloque > 0");
	}
	if ($year !== '' and $district!=='' and $bloc!=='') {
	$resultstats = mysql_query("SELECT COUNT(nombre) AS nombres, SUM(disciplinas) AS disciplinesstat, AVG(disciplinas) AS disciplineavg, SUM(votos_bloque) AS votos_bloquesstat, AVG(votos_bloque) AS votos_bloqueavg, (SUM(disciplinas) / SUM(votos_bloque)) AS indicestat, (AVG(disciplinas) / AVG(votos_bloque)) AS indicesavg FROM disciplina WHERE ano = $year AND distrito = '$district' AND color = '$bloc' AND votos_bloque > 0");
	}	
	while ($row = mysql_fetch_array($resultstats)) {
	$nombres = $row["nombres"];
	$nombresf = number_format($nombres, 0, ',', '.');
	$statdisciplina = $row["disciplinesstat"];
	$statdisciplinas = number_format($statdisciplina, 0, ',', '.');
	$statvotos_bloques = $row["votos_bloquesstat"];
	$statvotos_bloque = number_format($statvotos_bloques, 0, ',', '.');
	$avgdisciplinas = $row["disciplineavg"];
	$avgdisciplinasf = number_format($avgdisciplinas, 0);
	$avgvotos_bloque = $row["votos_bloqueavg"];
	$avgvotos_bloquef = number_format($avgvotos_bloque, 0);
	$indicestat = $row["indicestat"] * 100;
	$indicestatf = number_format($indicestat, 1, ',', ' ');
	$indicestatfi = number_format($indicestat, 0);
	$noindicestatf = 100 - $indicestatfi;
	$avgindice = $row["indicesavg"] * 100;
	$avgindicef = number_format($avgindice, 1, ',', ' ');
	$avgindicefi = number_format($avgindice, 0);
	$noavgindicef = 100 - $avgindicefi;	
	
	?>

	<?
	$iii++;
	}
	?>	

	<table class="table table-striped">
	<tr>
		<td width="27%" style="text-align: center;" rowspan="2"><?echo $nombresf;?> <small>miembros / per&iacute;odos</small></td>
		<td width="17%" style="text-align: center;">Suma</td>
		<td width="13%" style="text-align: right;"><?echo $statvotos_bloque;?></td>
		<td width="13%" style="text-align: right;"><?echo $statdisciplinas;?></td>
		<td width="27%" style="text-align: center;" rowspan="2">
		<table class="borderless" width="100%"><tr><td><div align="center"><div id="chart_stat"></div></div></td><td style="text-align: right;"><strong><?echo $indicestatf;?>%</strong></td></tr></table><?php
	if ($year =='') {
?>
<div align="center">
        <div id="vizstats"></div>
</div>		
        <script>
            
            var w = 200,
            h = 20

            // create canvas
            var svg = d3.select("#vizstats").append("svg:svg")
            .attr("class", "chart")
            .attr("width", w)
            .attr("height", h )
            .append("svg:g")
            .attr("transform", "translate(10,20)");

            x = d3.scale.ordinal().rangeRoundBands([0, w-5])
            y = d3.scale.linear().range([0, h-5])
            z = d3.scale.ordinal().range(["#1f77b4", "#ff7f0e", "white"])
	    // 4 columns: ID,c1,c2,c3
            var matrix = [ <?php
	$jj=0;
	if ($district=='' and $bloc=='') {
	$resultheatstats = mysql_query("SELECT ano, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE votos_bloque > 0 GROUP BY ano ORDER BY ano ASC");
	}

	if ($district!=='' and $bloc=='') {
	$resultheatstats = mysql_query("SELECT ano, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND votos_bloque > 0 GROUP BY ano ORDER BY ano ASC");
	}

	if ($district=='' and $bloc!=='') {
	$resultheatstats = mysql_query("SELECT ano, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE color = '$bloc' AND votos_bloque > 0 GROUP BY ano ORDER BY ano ASC");
	}
	
	if ($district!=='' and $bloc!=='') {
	$resultheatstats = mysql_query("SELECT ano, (SUM(disciplinas) / SUM(votos_bloque)) AS indice FROM disciplina WHERE distrito = '$district' AND color = '$bloc' AND votos_bloque > 0 GROUP BY ano ORDER BY ano ASC");
	}	
	while ($row = mysql_fetch_array($resultheatstats)) {
	$aniostats = $row["ano"];
	$indicehstats = $row["indice"] * 100;
	$indicefihstats = number_format($indicehstats, 0);
	$nindicefihstats = 100 - $indicefihstats;

	if ($nindicefihstats == 100) { $nindicefihstats = 0; }
?>
                [ <?echo $jj;?>, <?echo $indicefihstats;?>, <?echo $nindicefihstats;?>, 0 ],
<?php
	$jj++;
	}
?>
            ];
            var remapped =["c1","c2","c3"].map(function(dat,i){
                return matrix.map(function(d,ii){
                    return {x: ii, y: d[i+1] };
                })
            });

            var stacked = d3.layout.stack()(remapped)


            x.domain(stacked[0].map(function(d) { return d.x; }));
            y.domain([0, d3.max(stacked[stacked.length - 1], function(d) { return d.y0 + d.y; })]);


            var valgroup = svg.selectAll("g.valgroup")
            .data(stacked)
            .enter().append("svg:g")
            .attr("class", "valgroup")
            .style("fill", function(d, i) { return z(i); })
            .style("stroke", function(d, i) { return d3.rgb(z(i)).darker(); });

            // Add a rect for each date.
            var rect = valgroup.selectAll("rect")
            .data(function(d){return d;})
            .enter().append("svg:rect")
            .attr("x", function(d) { return x(d.x); })
            .attr("y", function(d) { return -y(d.y0) - y(d.y); })
            .attr("height", function(d) { return y(d.y); })
            .attr("width", 10);

        </script>
<?php
}
?>		
		</td>
<script>
var w = 48;
var h = 48;
var r = h/2;
var color = d3.scale.category10();

var data = [{"label":"", "value":<?echo $indicestatfi;?>}, 
		   {"label":"", "value":<?echo $noindicestatf;?>}];


var vis = d3.select('#chart_stat').append("svg:svg").data([data]).attr("width", w).attr("height", h).append("svg:g").attr("transform", "translate(" + r + "," + r + ")");
var pie = d3.layout.pie().value(function(d){return d.value;});


var arc = d3.svg.arc().outerRadius(r);


var arcs = vis.selectAll("g.slice").data(pie).enter().append("svg:g").attr("class", "slice");
arcs.append("svg:path")
    .attr("fill", function(d, i){
        return color(i);
    })
    .attr("d", function (d) {
        return arc(d);
    });

// add the text
		
</script>		
		
	</tr>
	<tr>
		<td style="text-align: center;">Promedio</td>
		<td style="text-align: right;"><?echo $avgvotos_bloquef;?></td>
		<td style="text-align: right;"><?echo $avgdisciplinasf;?></td>	
	</tr>
	</table>
	
	</div>
<div class="bottom-menu">
    <div class="container">
        <div class="row">
            <div class="span3 brand">Disciplin&oacute;metro de<br>D&eacute;cada Votada</div>
            <div class="span2">
                <h5 class="title">Secciones</h5>
                <ul class="bottom-links">
                    <li class="lead"><a href="index-s.html">Senado de la Naci&oacute;n</a></li>
					<li class="lead"><a href="index-d.html">C&aacute;mara de Diputados de la Naci&oacute;n</a></li>
                    <li><a href="doc.html" target="_blank">Documentaci&oacute;n</a></li>
                    <li><a href="index.html#intromodal" data-toggle="modal">Acerca de</a></li>
                </ul>
            </div>
            <div class="span5">
			
			<img src="assets/img/disciplinometer.png">

            </div>
            <div class="span2">
                <ul class="bottom-icons">
                    <li><a href="http://hhba.info/" target="_blank"><img src="http://photos4.meetupstatic.com/photos/event/6/8/b/b/global_22346811.jpeg" width="140" alt="HacksHackers Buenos Aires"></a></li>
                    <li><a href="https://github.com/hhba/towlandia" target="_blank"><img src="assets/img/GitHub-Mark-32px.png" title="Fork me on GitHub"></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
<script>
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
	
	$(document).ready(function(){
    $("[data-toggle=tooltip]").tooltip({ placement: 'bottom'});
	});
</script>
</body>
</html>