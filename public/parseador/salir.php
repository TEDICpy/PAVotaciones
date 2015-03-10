<?php
session_start();
$_SESSION['logged']=false;
$_SESSION['token']=false;
session_destroy();
header('Location: '.'index.php');