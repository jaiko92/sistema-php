<?php 
//Recuperar fecha actual
$hoy = getdate();

//Revisar la sesión de usuario
session_start();
if(!isset($_SESSION["usuario"], $_SESSION["permiso"])){
	header('Location: inicio');
}else{
	$user = $_SESSION["usuario"];
	$permiso = $_SESSION["permiso"];
	$nombre = $_SESSION["nombre"];
	$username = $_SESSION["username"];
	$email = $_SESSION["email"];
}

include "clase_conexion.php";

include "header.php";

include "ingresar_valores.php";

include "pendientes.php";

include "datos_usuario.php";

include "reporte.php";

include "footer.php";
?>