<?php
    require 'limpiarArchivoRTF.php';
    require_once('conexion.php');
    require 'insertar_bd.php';
    if(!session_id()){
        session_start();
    }
    if(!$_SESSION['logged']){
        header('Location: '.'salir.php');
        exit;
    }

    // Datos del archivo.
    $tmp_name = $_FILES["votacion"]["tmp_name"];
    $name = $_FILES["votacion"]["name"];
    //move_uploaded_file($tmp_name, getcwd().'/'.$name);
    $v_ruta_completa_archivo_subido =PATH_ARCHIVOS_GENERADOS.'/'.$name;
    $v_archivo_movido_exitosamente = move_uploaded_file($tmp_name, $v_ruta_completa_archivo_subido);

    // Si el archivo de alguna manera no pudo colocar en la ruta archivosGenerados, va a mostrar
    // un mensaje avisando, y suspendiendo el proceso.
    if(!$v_archivo_movido_exitosamente){
        echo "<center><h2>Error al mover el archivo - Reporte el error a los responsables con el archivo que quiso subir.</h2></center>";
        echo "<br>.<center><a href = 'subir-diputados.php'>Subir</a></center>";
        return;
    }
    if($name == "" || empty($_POST['presidente'])){
        echo "<a href='salir.php'>Salir</a>";
        echo "<br><br><center><h1>No ha seleccionado ning&uacute;n archivo; o no ha seleccionado presidente.<h1></center>";
        echo "<center><a href = 'subir-diputados.php'>Subir</a></center>";
        return;
    }
//    $v_objeto = new limpiarArchivoRTF();

  //  $v_archivo_rtf_limpio = $v_objeto->rtf2text($v_ruta_completa_archivo_subido);

echo  exec("unrtf --text '$v_ruta_completa_archivo_subido' > '$v_ruta_completa_archivo_subido'.2");
$v_archivo_rtf_limpio = file_get_contents($v_ruta_completa_archivo_subido.'.2');
//die();

    //$v_archivo_rtf_limpio = utf8_decode($v_objeto->rtf2text($name));
    //print_r($v_archivo_rtf_limpio);
    //die();
    //unlink(getcwd().'/'.$name);
    unlink($v_ruta_completa_archivo_subido);
unlink($v_ruta_completa_archivo_subido.'2');
    $primero = preg_split("/[\n]+|[\t]+/",$v_archivo_rtf_limpio);                         // Romper el string en elementos por salto de lineas.
    //$segundo = array_filter($primero, 'trim');				// Eliminar elementos vacios del array.
  //  $tercero = array_map('trim', $primero); 				// Limpiar los caracteres en blanco de los elementos.

$tercero = $primero;

//ya no hace falta pasar a array porque el unrtf ya devuelve en array


    //print_r($segundo);
    //die();


 //print_r($tercero);
 //die();

    $cabecera = array();
    $votaciones = array();
    $key = 'otros';
    $cantidad = 0;
    $flag_asunto = false;
//print_r($tercero);
    foreach ($tercero as $elemento){
        // Fecha hora.
        if(!isset($cabecera['fecha'])){
            if(preg_match('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', substr($elemento,0,10))==1){
                $v_fecha_hora = preg_split("/[\s]+/",$elemento);

                // $v_fecha_hora:
                // $v_fecha_hora[0] -> fecha.
                // $v_fecha_hora[1] -> hora.

	    // Se obtiene el mes y el dia, porque en Google Table fusion debe ser MM/dd/yyyy
                // y en los documentos estan como dd/MM/yyyy
                $v_dia = substr($v_fecha_hora[0],0,2);
                $v_mes = substr($v_fecha_hora[0],3,2);
                $v_ano = substr($v_fecha_hora[0],6,4);

                $cabecera['fecha'] = $v_mes . "/". $v_dia . "/" . $v_ano;
                $cabecera['hora'] = $v_fecha_hora[1];
                $cabecera['ano'] = $v_ano;
                continue;
            }
        }

        if(!isset($cabecera['fecha'])){
            if(preg_match('/([0-9]{2})\/([0-9]{2})\/([0-9]{2})/', substr($elemento,0,8))==1){
                $v_fecha_hora = preg_split("/[\s]+/",$elemento);

                // $v_fecha_hora:
                // $v_fecha_hora[0] -> fecha.
                // $v_fecha_hora[1] -> hora.

            // Se obtiene el mes y el dia, porque en Google Table fusion debe ser MM/dd/yyyy
                // y en los documentos estan como dd/MM/yyyy
                $v_dia = substr($v_fecha_hora[0],0,2);
                $v_mes = substr($v_fecha_hora[0],3,2);
                $v_ano = '20'.substr($v_fecha_hora[0],6,2);

                $cabecera['fecha'] = $v_mes . "/". $v_dia . "/" . $v_ano;
                $cabecera['hora'] = $v_fecha_hora[1];
                $cabecera['ano'] = $v_ano;
                continue;
            }
        }

        // Nombre propuesta.
        if(!isset($cabecera['asunto'])){
            if(strtolower(substr($elemento,0,6)) == 'nombre'){
                $cabecera['asunto'] = '';
                $flag_asunto = true;
                continue;
            }
        }

        // Concatenar todo el nombre.
        if($flag_asunto){
            $asunto = trim($cabecera['asunto']);
            if(!empty($elemento) || empty($asunto)){
                $cabecera['asunto'] .= ' '.$elemento;
            }else{
                 $flag_asunto = false;
            }
            continue;
        }
        // Resultado.
        $cabecera['resultado'] = $_POST['resultado'];
      /*  if(!isset($cabecera['resultado'])){
            if(strpos(strtolower($elemento) ,'aprobado')!== false){
                $cabecera['resultado'] = 'AFIRMATIVO';
            }elseif(strpos(strtolower($elemento) ,'rechazado')!== false){
                 $cabecera['resultado'] = 'NEGATIVO';
            }elseif(strpos(strtolower($elemento) , 'ratificado')!== false){
                 $cabecera['resultado'] = 'ANULADA';
            }
            continue;
        }*/

        // Presidente.
        $cabecera['presidente'] = $_POST['presidente'];
        $cabecera['voto_presidente'] = $_POST['votopresidente'];

        if (empty($elemento)) {
            continue;
        }

        // Votos.
        if($cantidad > 0){
            $cantidad--;
            $votaciones[$key][] = $elemento;
        }else{


//	     $si=utf8_encode('sí ');
//	     $si2=utf8_encode('s(');
	//	echo strtolower(substr($elemento,0,3)).'<br>';
	$aux = utf8_encode(strtolower(substr($elemento,0,3)));
$aux = str_replace('í','i',$aux);
//echo $aux.'<br>';
             switch ($aux){
                case 'si ':case 'si(':
                    $key = 'si';
                    $cantidad = preg_replace("/[^0-9]/", "", $elemento);
                    break;
                case 'no ':case 'no(':
                    $key = 'no';
                    $cantidad = preg_replace("/[^0-9]/", "", $elemento);
                    break;
                case 'no-':
                    $key = 'ausentes';
                    $cantidad = preg_replace("/[^0-9]/", "", $elemento);
                    break;
                case 'abs':
                    $key = 'abstencion';
                    $cantidad = preg_replace("/[^0-9]/", "", $elemento);
                    break;
                default :
                    $key = 'otros';
                    break;
              }
              $votaciones['totales'][$key]=$cantidad;
         }
   } // Fin del foreach.
  // print_r($cabecera);
 //  print_r($votaciones);
//	 die();

    // Llama a la funcion insertarBd del archivo
    if(!insertarBd($cabecera, $votaciones)){
        header('Location: '.'error.php');
        exit;
    }

    // Redirecciona automaticamente.
    header('Location: '.'subido.php');

    // Cuando no se ejecuta el redireccionamiento.
    exit;
