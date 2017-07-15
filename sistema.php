<?php 
session_start();
if(!isset($_SESSION["usuario"], $_SESSION["permiso"])){
	header('Location: inicio');
}else{
	$user = $_SESSION["usuario"];
	$permiso = $_SESSION["permiso"];
	$nombre = $_SESSION["nombre"];
}

include "clase_conexion.php";

include "funciones.php";

include "header.php";

include "ingresar_valores.php";

include "pendientes.php";

include "datos_usuario.php";

include "reporte.php";

include "footer.php";
?>