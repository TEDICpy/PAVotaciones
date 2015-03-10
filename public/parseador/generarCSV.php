<?php
    require 'constantes.php';
    //require "php_ft_lib.php";
	/**
 	 * @param Array $p_cabecera
 	 * @param Array $p_votaciones
 	 * @return void
 	 */
	function generarCSV($p_cabecera, $p_votaciones){
		// Se obtiene el json de la Tabla diputados.
		$v_json_diputados = "https://www.googleapis.com/fusiontables/v1/query?sql=SELECT%20*%20FROM%20" . NOMBRE_TABLA_DIPUTADOS ."&key=" . API_KEY_GOOGLE_TABLE_FUSION;
		
		// Se obtiene el json de la Tabla bloque diputados.
		$v_json_bloque_diputados = "https://www.googleapis.com/fusiontables/v1/query?sql=SELECT%20*%20FROM%20" . NOMBRE_TABLA_BLOQUE_DIPUTADOS ."&key=" . API_KEY_GOOGLE_TABLE_FUSION;
		
		// Se obtiene el json de la Tabla asuntos diputados.
		$v_json_asuntos_diputados = "https://www.googleapis.com/fusiontables/v1/query?sql=SELECT%20asuntoId%20FROM%20" . NOMBRE_TABLA_ASUNTOS_DIPUTADOS ."%20ORDER%20BY%20asuntoId%20DESC%20LIMIT%201&key=" . API_KEY_GOOGLE_TABLE_FUSION;
		
		// Convierte el json de diputados a un array de diputados.
		$v_array_diputados = json_decode(file_get_contents($v_json_diputados), true);
		
		// Convierte el json de bloque diputados a un array de bloque diputados.
		$v_array_bloque_diputados = json_decode(file_get_contents($v_json_bloque_diputados, true));
		
		// Convierte el json de asuntos diputados a un array de asuntos diputados.
		$v_array_asuntos_diputados = json_decode(file_get_contents($v_json_asuntos_diputados, true));
		
		
		// Generar votaciones diputados en un archivo CSV.
		
		// Valores de votacion de los diputados.
		// - 0 = Afirmativo
		// - 1 = Negativo
		// - 2 = Abstencion
		// - 3 = Ausente
		$v_caracter_separador = "|";
		$v_path_archivos_generados = "../archivosGenerados/";
		$v_archivo_votaciones_csv = $v_path_archivos_generados . "votos-diputados.csv";
		$v_cabecera_archivo_votaciones_csv = "asuntoId" . $v_caracter_separador . "diputadoId" . 
		    $v_caracter_separador . "bloqueId" . $v_caracter_separador ."voto \n";
		$v_asuntoId = $v_array_asuntos_diputados->rows[0][0] + 1;
		
		if(!$handle = fopen($v_archivo_votaciones_csv, "w+")){
			echo "No puede abrir el archivo: " . $v_archivo_votaciones_csv;
			exit;
		}
		if(fwrite($handle, utf8_decode($v_cabecera_archivo_votaciones_csv)) === FALSE){
			echo "No puede escribir en el archivo: " . $v_archivo_votaciones_csv;
			exit;
		}
		
		foreach($p_votaciones['totales'] as $v_resultado => $v_valor){
		   if(!empty($p_votaciones[$v_resultado])){
		       foreach($p_votaciones[$v_resultado] as $v_nombre_diputados){
                   $v_obj_diputado = devolverObjDiputado($v_array_diputados, $v_nombre_diputados);
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
                       $v_fila_votacion_diputado = $v_asuntoId . $v_caracter_separador . $v_diputado_id . 
                           $v_caracter_separador . $v_bloque_id . $v_caracter_separador . $v_voto . "\n";

                       if(fwrite($handle, utf8_decode($v_fila_votacion_diputado)) === FALSE){
                           echo "No puede escribir en el archivo: " . $v_archivo_votaciones_csv;
                           exit;
                       }
                   }
               }
		    }
		}
		fclose($handle);
		

		// Generar asuntos diputados en un archivo CSV.
		$v_archivo_asuntos_votaciones_csv = $v_path_archivos_generados . "asunto-diputados.csv";
		$v_cabecera_archivo_asuntos_votaciones_csv = "asuntoId" . $v_caracter_separador. "sesion" .
		    $v_caracter_separador . "asunto" . $v_caracter_separador . "ano" . $v_caracter_separador .
		    "fecha" . $v_caracter_separador . "hora" . $v_caracter_separador . "base" . $v_caracter_separador .
		    "mayoria" . $v_caracter_separador . "resultado" . $v_caracter_separador . "presidente" .
		    $v_caracter_separador . "presentes" . $v_caracter_separador . "ausentes" . $v_caracter_separador .
		    "abstenciones" . $v_caracter_separador . "afirmativos" . $v_caracter_separador . "negativos" .
		    $v_caracter_separador . "votopresidente" . $v_caracter_separador . "titulo \n";

		
            if(!$handle = fopen($v_archivo_asuntos_votaciones_csv, "w+")){
			echo "No puede abrir el archivo: " . $v_archivo_asuntos_votaciones_csv;
			exit;
		}
		if(fwrite($handle, utf8_decode($v_cabecera_archivo_asuntos_votaciones_csv)) === FALSE){
			echo "No puede escribir en el archivo: " . $v_archivo_asuntos_votaciones_csv;
			exit;
		}
		
		//print_r($p_cabecera);
		//print_r($p_votaciones);
		
		
		
		$v_sesion = "";
		$v_asunto = $p_cabecera['asunto'];
		$v_ano = $p_cabecera['ano'];
		$v_fecha= $p_cabecera['fecha'];
		$v_hora = $p_cabecera['hora'];
		$v_base = "";
		$v_mayoria = "";
		$v_resultado = $p_cabecera['resultado'];
		$v_presidente = utf8_decode($p_cabecera['presidente']);
		
		// Se suma la cantidad de si, no, abstencion para los presentes.
		$v_presentes = $p_votaciones['totales']['si'] +  $p_votaciones['totales']['no'] + $p_votaciones['totales']['abstencion'];
		
		$v_ausentes = $p_votaciones['totales']['ausentes'];
		$v_abstenciones = $p_votaciones['totales']['abstencion'];
		$v_afirmativos = $p_votaciones['totales']['si'];
		$v_negativos = $p_votaciones['totales']['no'];
		$v_votopresidente = "";
		$v_titulo = "";
		
		// Los datos de la fila a insertar (asunto-diputado).
		$v_fila_asuntos_votacion_diputado = $v_asuntoId . $v_caracter_separador . $v_sesion .
		$v_caracter_separador . $v_asunto . $v_caracter_separador . $v_ano . $v_caracter_separador . $v_fecha .
		    $v_caracter_separador . $v_hora . $v_caracter_separador . $v_base . $v_caracter_separador . $v_mayoria .
		    $v_caracter_separador . $v_resultado . $v_caracter_separador . $v_presidente . $v_caracter_separador . $v_presentes .
		    $v_caracter_separador . $v_ausentes .$v_caracter_separador . $v_abstenciones . $v_caracter_separador . $v_afirmativos .
		    $v_caracter_separador . $v_negativos . $v_caracter_separador . $v_votopresidente .$v_caracter_separador . 
		    $v_titulo . "\n";
		
		if(fwrite($handle, utf8_decode($v_fila_asuntos_votacion_diputado)) === FALSE){
			echo "No puede escribir en el archivo: " . $v_archivo_asuntos_votaciones_csv;
			exit;
		}
		fclose($handle);
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
			//}
			//echo "p_nombre diputado: ". $p_nombre_diputado;
			//echo "<br>p_nombre diputado1: ".$v_diputado[1];

			if($v_diputado[1] == $p_nombre_diputado){
				$v_obj_diputado = new stdClass();
				$v_obj_diputado->diputadoID = $v_diputado[0];
				$v_obj_diputado->id_bloque = $v_diputado[3];
				return $v_obj_diputado;
			}
		}
		return null; 
	}