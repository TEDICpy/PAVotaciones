<?php
    require_once "php_ft_lib.php";
	require_once "constantes.php";

	/**
 	 * @param Array $p_cabecera
 	 * @param Array $p_votaciones
 	 * @return void
 	 */
	function insertarGoogleTableFusion($p_cabecera, $p_votaciones){
        
        $token = $_SESSION['token'];
	$ft = new FusionTable($token, API_KEY_GOOGLE_TABLE_FUSION);
        // Se obtiene el json de la Tabla diputados.
		//$v_json_diputados = "https://www.googleapis.com/fusiontables/v1/query?sql=SELECT%20*%20FROM%20" . NOMBRE_TABLA_DIPUTADOS ."&key=" . API_KEY_GOOGLE_TABLE_FUSION;
        
                $v_consulta_diputados = "SELECT * FROM " . NOMBRE_TABLA_DIPUTADOS;
        
		// Se obtiene el json de la Tabla bloque diputados.
		//$v_json_bloque_diputados = "https://www.googleapis.com/fusiontables/v1/query?sql=SELECT%20*%20FROM%20" . NOMBRE_TABLA_BLOQUE_DIPUTADOS ."&key=" . API_KEY_GOOGLE_TABLE_FUSION;
		
		// Se obtiene el json de la Tabla asuntos diputados.
		//$v_json_asuntos_diputados = "https://www.googleapis.com/fusiontables/v1/query?sql=SELECT%20asuntoId%20FROM%20" . NOMBRE_TABLA_ASUNTOS_DIPUTADOS ."%20ORDER%20BY%20asuntoId%20DESC%20LIMIT%201&key=" . API_KEY_GOOGLE_TABLE_FUSION;
		//$v_consulta
        
		// Convierte el json de diputados a un array de diputados.
		//$v_array_diputados = json_decode(file_get_contents($v_json_diputados), true);
                $v_array_diputados = json_decode($ft->query($v_consulta_diputados));
    print_r($v_consulta_diputados);
      print_r($v_array_diputados);
    die();
		// Convierte el json de bloque diputados a un array de bloque diputados.
		//$v_array_bloque_diputados = json_decode(file_get_contents($v_json_bloque_diputados, true));
		//
		// Convierte el json de asuntos diputados a un array de asuntos diputados.
		//$v_array_asuntos_diputados = json_decode(file_get_contents($v_json_asuntos_diputados, true));
		
              //  $v_array_asuntos_diputados = json_decode($ft->query($v_consulta_diputados))
        
		//$v_asuntoId = $v_array_asuntos_diputados->rows[0][0] + 1;
		//$v_asuntoId = 1;
		
		//$tableid = NOMBRE_TABLA_VOTACIONES_DIPUTADOS;
		//$username = USERNAME_GOOGLE;
		//$password = PASSWORD_USERNAME_GOOGLE;
		//$key = API_KEY_GOOGLE_TABLE_FUSION;
		
		//$token = GoogleClientLogin($username, $password, "fusiontables");
		//$token = $_SESSION['token'];
		//$ft = new FusionTable($token, $key);
		
		$v_caracter_separador = ",";
		
		// Valores de votacion de los diputados.
		// - 0 = Afirmativo
		// - 1 = Negativo
		// - 2 = Abstencion
		// - 3 = Ausente
       
		$v_query = "";
                $v_sesion = "";
		//$v_asunto = preg_replace('/&[*]\;/',' ',utf8_encode($p_cabecera['asunto']));  
		$v_asunto = utf8_encode($p_cabecera['asunto']);
		$v_ano = $p_cabecera['ano'];
		$v_fecha = $p_cabecera['fecha'];
		$v_hora = $p_cabecera['hora'];
		$v_base = "";
		$v_mayoria = "";
		$v_resultado = $p_cabecera['resultado'];
		$v_presidente = utf8_encode($p_cabecera['presidente']);
		
		// Se suma la cantidad de si, no, abstencion para los presentes.
		$v_presentes = $p_votaciones['totales']['si'] +  $p_votaciones['totales']['no'] + $p_votaciones['totales']['abstencion'];
		
		$v_ausentes = $p_votaciones['totales']['ausentes'];
		$v_abstenciones = $p_votaciones['totales']['abstencion'];
		$v_afirmativos = $p_votaciones['totales']['si'];
		$v_negativos = $p_votaciones['totales']['no']; 
                  
		$v_votopresidente = "";
		$v_titulo = "";
		
		// Los datos de la fila a insertar (asunto-diputado).
		$v_fila_asuntos_votacion_diputado = "(" . "'". $v_asunto . "'". $v_caracter_separador . "'". $v_ano . "'". $v_caracter_separador . "'". $v_fecha . "'".
		    $v_caracter_separador . "'". $v_hora . "'". $v_caracter_separador . "'". $v_base . "'". $v_caracter_separador . "'". $v_mayoria . "'".
		    $v_caracter_separador . "'". $v_resultado . "'". $v_caracter_separador . "'". $v_presidente . "'". $v_caracter_separador . "'". $v_presentes . "'".
		    $v_caracter_separador . "'". $v_ausentes . "'". $v_caracter_separador . "'". $v_abstenciones . "'". $v_caracter_separador . "'". $v_afirmativos . "'".
		    $v_caracter_separador . "'". $v_negativos . "'". $v_caracter_separador . "'". $v_votopresidente . "'".$v_caracter_separador . "'". 
		    $v_titulo . "'". ")";
		
		// Insert
		$tableid = NOMBRE_TABLA_ASUNTOS_DIPUTADOS;
		$v_query = "INSERT INTO $tableid ( asunto,ano, fecha, hora, base, mayoria, resultado, presidente, 
		    presentes, ausentes, abstenciones, afirmativos, negativos, votopresidente, titulo) VALUES " . $v_fila_asuntos_votacion_diputado;
		
                $v_result = $ft->query($v_query);
               // print_r($v_result);
                if(!isset($v_result[7])){     
                     
                    return false;
                }
		$v_asuntoId = json_decode($v_result[7]);
        
   
        
                $v_query = "update $tableid set asuntoId = '$v_asuntoId' where rowid = '$v_asuntoId'";
                
                $result = $ft->query($v_query);
                
                $v_query = '';
		foreach($p_votaciones['totales'] as $v_resultado => $v_valor){
		   if(!empty($p_votaciones[$v_resultado])){
		       foreach($p_votaciones[$v_resultado] as $v_nombre_diputados){
                   $v_obj_diputado = devolverObjDiputado($v_array_diputados, $v_nombre_diputados);
                  //echo "contador: ";

;
		

		
		//print_r($v_result);
        
                    $tableid = NOMBRE_TABLA_VOTACIONES_DIPUTADOS;
                          if(!empty($v_obj_diputado)){
                       $v_diputado_id = $v_obj_diputado->diputadoID;
                       $v_bloque_id = $v_obj_diputado->id_bloque;

                       switch($v_resultado){
                           case 'si':
                                $v_voto = 0;		// El valor 0 indica afirmativo.
                                break;
                           case 'no':
                                $v_voto = 1;		// El valor 1 indica negativo.
                                break;
                           case 'abstencion':
                                $v_voto = 2;		// El valor 2 indica abstencion.
                                break;
                           case 'ausentes':
                                $v_voto = 3;		// El valor 3 indica ausente.
                                break;
                       }
                       $v_valores_votacion_diputado = "(" . $v_asuntoId . $v_caracter_separador . $v_diputado_id . 
                           $v_caracter_separador . $v_bloque_id . $v_caracter_separador . $v_voto . ")";
					
                       $v_query.= "INSERT INTO $tableid (asuntoId, diputadoId, bloqueId, voto) VALUES " . $v_valores_votacion_diputado . ";";
                   }
               }
		    }
		}
		//echo "v_query". $v_query;
		//die();
		$v_result = $ft->query($v_query);
        
        return;
	}

	
	/**
	 * Devuelve un objeto diputado que contiene el diputadoID y id_bloque.
 	 * @param Array $p_array_diputados
 	 * @param string $p_nombre_diputado
 	 * @return Objeto|null
 	 */
	function devolverObjDiputado($p_array_diputados, $p_nombre_diputado){
		foreach($p_array_diputados['rows'] as $v_diputado){
			// $v_diputado es un array:
			// - Posicion 0 => diputadoID
			// - Posicion 1 => nombre
			// - Posicion 2 => distrito
			// - Posicion 3 => id_bloque
            //echo mb_detect_encoding($v_diputado[1]);
			//if(mb_detect_encoding($p_nombre_diputado) != 'UTF-8'){
			//$p_nombre_diputado = utf8_encode($p_nombre_diputado);
			//}
			//if(mb_detect_encoding($v_diputado[1]) != 'UTF-8'){
			$v_diputado[1] = utf8_decode($v_diputado[1]);
			
			//print_r($v_diputado[1]);
			//die();
			//}
		//	echo "<br>p_nombre diputado: ". $p_nombre_diputado . "\n";
		//	echo "<br>p_nombre diputado1: ".$v_diputado[1]. "\n\n";;

			if($v_diputado[1] == $p_nombre_diputado){
				$v_obj_diputado = new stdClass();
				$v_obj_diputado->diputadoID = $v_diputado[0];
				$v_obj_diputado->id_bloque = $v_diputado[3];
				return $v_obj_diputado;
			}
		}
		return null; 
	}
