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
	//Obtener el nombre del dia
	function dia($num_dia){
		switch ($num_dia) {
			case '0': $nom_dia = "Domingo"; break;
			
			case '1': $nom_dia = "Lunes"; break;
			
			case '2': $nom_dia = "Martes"; break;
			
			case '3': $nom_dia = "Miercoles"; break;
			
			case '4': $nom_dia = "Jueves"; break;
			
			case '5': $nom_dia = "Viernes"; break;
			
			case '6': $nom_dia = "Sabado"; break;
			
			default: echo "ERROR GRAVE, CIERRE EL PROGRAMA POR FAVOR"; break;
		}
		return $nom_dia;
	}

	//Obtener el nombre del mes
	function mes($num_mes){
		switch ($num_mes) {
			case '1': $nom_mes = "Enero"; break;
			
			case '2': $nom_mes = "Febrero"; break;
			
			case '3': $nom_mes = "Marzo"; break;
			
			case '4': $nom_mes = "Abril"; break;
			
			case '5': $nom_mes = "Mayo"; break;
			
			case '6': $nom_mes = "Junio"; break;
			
			case '7': $nom_mes = "Julio"; break;
			
			case '8': $nom_mes = "Agosto"; break;
			
			case '9': $nom_mes = "Septiembre"; break;
			
			case '10': $nom_mes = "Octubre"; break;
			
			case '11': $nom_mes = "Noviembre"; break;
			
			case '12': $nom_mes = "Diciembre"; break;
			
			default: echo "ERROR GRAVE, CIERRE EL PROGRAMA POR FAVOR"; break;
		}

		return $nom_mes;
	}

}

include "clase_conexion.php";

include "header.php";

include "facturas_pendientes.php";

include "facturas_canceladas.php";

include "buscar_facturas.php";

include "metas_mensuales.php";

include "generar_reporte.php";

include "footer.php";
?>