<?php
    require_once  'constantes.php';
    require 'conexion.php';
    if(!session_id()){
        session_start();
    }
    if(!$_SESSION['logged']){
        header('Location: '.'salir.php');
        exit;
    }
    echo "
        <a href='salir.php'>Salir</a>
        <center>
            <br>
            <br>
            <h1>Subir votos diputados</h1>
	<form action='parseador.php' method='post' enctype='multipart/form-data'>
	    <input type='file' name='votacion' />
	    Resultado:
	    <select name='resultado'>
	        <option value='AFIRMATIVO'>AFIRMATIVO</option>
	        <option value='NEGATIVO'>NEGATIVO</option>
	        <option value='ANULADA'>ANULADA</option>
	    </select>
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Presidente:
	    <select name='presidente'>";
                $result = mysql_query("SELECT * FROM diputados order by nombre");
                $data = array();
                 echo "<option value='' selected='selected'></option>";
                 while ($fila = mysql_fetch_assoc($result)) {
                    echo "<option value='".$fila['nombre']."''>".$fila['nombre']."</option>";
                 }
                 echo "
	     </select>
    	         <select name='votopresidente'>
	         <option value='0' >AFIRMATIVO</option>
	         <option value='1'>NEGATIVO</option>
	         <option value='' selected='selected'>No vot&oacute;</option>
	    </select>
	    <input type='submit' value='Subir' onclick='this.value='Enviando...'; this.disabled='disabled'; this.form.submit();'/>
	</form>
        </center>";