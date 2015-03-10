<?php
    function insertarBd($p_cabecera, $p_votaciones){
        //$token = $_SESSION['token'];
        //$ft = new FusionTable($token, API_KEY_GOOGLE_TABLE_FUSION);

        //$v_consulta_diputados = "SELECT * FROM " . NOMBRE_TABLA_DIPUTADOS;
        // $v_array_diputados = json_decode($ft->query($v_consulta_diputados)):
        $v_caracter_separador = ",";

        // Valores de votacion de los diputados.
        // - 0 = Afirmativo
        // - 1 = Negativo
        // - 2 = Abstencion
        // - 3 = Ausente
// Parche rapido.
$link=mysql_connect(HOST, $_SESSION['user'], $_SESSION['pass']);
mysql_select_db(BD,$link) OR DIE ("Error: No es posible establecer la conexión");

        //$v_asunto = preg_replace(,' ',utf8_encode($p_cabecera['asunto']));
        //$v_asunto = utf8_decode(preg_replace('/&.*;/', ' ', $p_cabecera['asunto']));

        $v_asunto =  utf8_decode($p_cabecera['asunto']);
        $conv = array("?" => "-", "&#147;" => '"', "&#148;" => '"');
        $v_asunto = strtr($v_asunto, $conv);
        $v_ano = $p_cabecera['ano'];
        $v_fecha = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$1-$2",$p_cabecera['fecha']);
        $v_hora = $p_cabecera['hora'];
        $v_base = "";
        $v_mayoria = "";
        $v_resultado = $p_cabecera['resultado'];
        $v_presidente = $p_cabecera['presidente'];

        // Se suma la cantidad de si, no, abstencion para los presentes.
        $v_presentes = $p_votaciones['totales']['si'] +  $p_votaciones['totales']['no'] + $p_votaciones['totales']['abstencion'];

        $v_ausentes = $p_votaciones['totales']['ausentes'];
        $v_abstenciones = $p_votaciones['totales']['abstencion'];
        $v_afirmativos = $p_votaciones['totales']['si'];
        $v_negativos = $p_votaciones['totales']['no'];

        $v_votopresidente = $p_cabecera['voto_presidente'];
        $v_titulo = "";

        // Los datos de la fila a insertar (asunto-diputado).
        $v_fila_asuntos_votacion_diputado = "(" . "'". $v_asunto . "'". $v_caracter_separador . "'". $v_ano . "'". $v_caracter_separador . "'". $v_fecha . "'".
            $v_caracter_separador . "'". $v_hora . "'". $v_caracter_separador . "'". $v_base . "'". $v_caracter_separador . "'". $v_mayoria . "'".
            $v_caracter_separador . "'". $v_resultado . "'". $v_caracter_separador . "'". $v_presidente . "'". $v_caracter_separador . "'". $v_presentes . "'".
            $v_caracter_separador . "'". $v_ausentes . "'". $v_caracter_separador . "'". $v_abstenciones . "'". $v_caracter_separador . "'". $v_afirmativos . "'".
            $v_caracter_separador . "'". $v_negativos . "'". $v_caracter_separador . "'". $v_votopresidente . "'".$v_caracter_separador . "'".
            $v_titulo . "'". ")";
        mysql_query("SET AUTOCOMMIT=0");
        mysql_query("START TRANSACTION");

        // Insertar el registro en asuntos de los diputados en la base de datos.
        $table = NOMBRE_TABLA_ASUNTOS_DIPUTADOS;
        $v_query = "INSERT INTO $table ( asunto,ano, fecha, hora, base, mayoria, resultado, presidente,
		    presentes, ausentes, abstenciones, afirmativos, negativos, votopresidente, titulo) VALUES " . $v_fila_asuntos_votacion_diputado;
        $v_result = mysql_query($v_query);
        if(!$v_result){
            mysql_query("ROLLBACK");
	    echo 'no inserto asunto';
		die();
            return false;
        }
        // Hacer  un update en asuntos de los diputados en la base de datos.
        $v_asuntoId = mysql_insert_id();
        $v_query = "update $table set permalink = '$v_asuntoId' where asuntoId = '$v_asuntoId'";
        $v_result = mysql_query($v_query);

        if(!$v_result){
            mysql_query("ROLLBACK");
	    echo 'no actualizo permalink';
die();
            return false;
        }

        // Insertar  votaciones de diputados en la base de datos.
        $v_query = '';
        $table = NOMBRE_TABLA_VOTACIONES_DIPUTADOS;
        foreach($p_votaciones['totales'] as $v_resultado => $v_valor){
            if(!empty($p_votaciones[$v_resultado])){
                foreach($p_votaciones[$v_resultado] as $v_nombre_diputados){
	//	$sub_lista = preg_split("/[\t]+/",$v_nombre_diputados_lista);
	//	foreach($sub_lista as $v_nombre_diputados){
                    //$v_nombre_diputados = utf8_decode($v_nombre_diputados);
                    // Parche rapido. No se porque viene un ).
                    if($v_nombre_diputados != ")"){
                        $v_query1 = "select diputadoId, bloqueId from diputados where nombre = \"$v_nombre_diputados\"";
                        $result = mysql_query($v_query1);

//echo "<br>query: ". $v_query1;
                        if(mysql_num_rows($result) <= 0){
			
                            mysql_query("ROLLBACK");
print_r($v_nombre_diputados);
//print_r($sub_lista);
die();
                            return false;
                        }
                        $v_obj_diputado = mysql_fetch_array($result);
                        if(!empty($v_obj_diputado)){
                            $v_diputado_id = $v_obj_diputado['diputadoId'];
                            $v_bloque_id = $v_obj_diputado['bloqueId'];
                            switch($v_resultado){
                                case 'si':
		                    $v_voto = 0;            // El valor 0 indica afirmativo.
                                    break;
                                case 'no':
                                    $v_voto = 1;            // El valor 1 indica negativo.
                                    break;
                                case 'abstencion':
                                    $v_voto = 2;            // El valor 2 indica abstencion.
                                    break;
                                case 'ausentes':
                                    $v_voto = 3;            // El valor 3 indica ausente.
                                    break;
                            } // Fin del switch.
                            $v_valores_votacion_diputado = "(" . $v_asuntoId . $v_caracter_separador . $v_diputado_id .
                            $v_caracter_separador . $v_bloque_id . $v_caracter_separador . $v_voto . ")";
                            $v_query= "INSERT INTO $table (asuntoId, diputadoId, bloqueId, voto) VALUES " . $v_valores_votacion_diputado . "; ";
                            $v_result = mysql_query($v_query) or die(mysql_error());
                            if(!$v_result){
                                mysql_query("ROLLBACK");
                                return false;
                            }
                        } // Fin del if.
                    } // Fin del if.
                }
	//	}
            }
        }
        mysql_query("COMMIT");
        return true;
    } // Fiin de la funcioninsertarBd.
