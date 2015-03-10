<?php
require_once  'constantes.php';
session_start();
if(!$_SESSION['logged']){
    if(!empty($_POST['user']) && !empty($_POST['pass'])){
        //$token = GoogleClientLogin($_POST['user'], $_POST['pass'], "fusiontables");
      // print_r(substr($token,0,5));
       // die();
        if(mysql_connect(HOST, $_POST['user'], $_POST['pass'])){
            $_SESSION['logged'] = true;
            $_SESSION['user'] = $_POST['user'];
            $_SESSION['pass'] = $_POST['pass'];
            header('Location: '.'subir-diputados.php');

        }else{
            echo "<center>
                    <br>
                    <br>
                    <h1>Usuario y/o password erroneos</h1>
                    <br>
                    <a href='index.php'>Volver a intentar</a>
                    </center>";
        }
    }else{
        echo "<center>
                    <br>
                    <br>
                    <h1>Ingresar</h1>";
        echo "<form action='' method='post' enctype='multipart/form-data'>";
        echo "<br><div style=' width: 250px;'>";
        echo "<p style='float: right;'>Usuario: <input type='text' name='user'></p>
                <p style='float: right;'>Password: <input type='password' name='pass'></p>";
        echo "<input type='submit' value='Entrar' />";
        echo "</div></form></center>";
    }
}else{
     header('Location: '.'subir-diputados.php');
}
