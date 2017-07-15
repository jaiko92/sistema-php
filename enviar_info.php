<?php 
//Operacion 0 - Iniciar sesion
include "clase_conexion.php";

function iniciar_sesion(){
	$usuario = $_POST["usuario"];
	$pass = $_POST["pass"];
	$fecha = $_POST["fecha"];
	$hora = $_POST["hora"];
	$op = $_POST["op"];

	$nom_tabla = "usuario";
	$conexion = new ConexionBDD();
	$sql = "SELECT * FROM ".$nom_tabla." WHERE nickname = '".$usuario."' AND password = '".$pass."'";
	$bandera = $conexion->existeDato($sql);

	if($bandera == true){
		$datos = $conexion->obtenerDatos($sql);
		foreach ($datos as $data) {
			$sesion = $data;
		}
		$conexion->cerrarConexion();
		session_start();
		$_SESSION["usuario"] = $sesion["id_usuario"];
		$_SESSION["permiso"] = $sesion["permiso"];
		$_SESSION["nombre"] = $sesion["nombre"];
		echo "<script language='javascript'>
				location.href='system'
			</script>";
	}else{
		$conexion->cerrarConexion();
		echo "<br><div class='alert alert-danger alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Datos incorrectos</strong></div>";
	}
}

//Operacion 1 - Agregar venta/utilidades al sistema
function agregar_venta(){
	$nom_tabla = "registro_venta";

	$conexion = new ConexionBDD();
	$sql = "SELECT * FROM ".$nom_tabla." WHERE fecha='".$_POST["fecha"]."' AND num_empleado='".$_POST["empleado"]."'";
	$bandera = $conexion->existeDato($sql);

	if($bandera == false){
		$datos = array(
	      array( 'fecha' , $_POST["fecha"]) , array( 'num_empleado' , $_POST["empleado"]),
	      array( 'id_usuario' , $_POST["usuario"])
	  	);

	  	if($_POST["venta_dia"] != ""){
	  		array_push($datos , array( 'venta_dia' , $_POST["venta_dia"] ));
	  	}

	  	if($_POST["utilidad_dia"] != ""){
	  		array_push($datos , array( 'utilidad_dia' , $_POST["utilidad_dia"] ));
	  	}

		$bandera = $conexion->insertarDato($nom_tabla, $datos);
		if($bandera == true){
			echo "<div class='alert alert-success alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Datos guardados correctamente.</strong></div>";
		}else{
			echo "<div class='alert alert-danger alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>No se pudo guardar el registro, intentelo más tarde.</strong></div>";
		}
	}else{
		echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Registro repetido, intente con otra fecha y/o empleado.</strong></div>";
	}
	$conexion->cerrarConexion();
}
//!--Operacion 1 - FIN

//Operacion 2 - Agregar gastos al sistema
function agregar_gastos(){
	$nom_tabla = "registro_gasto";

	$conexion = new ConexionBDD();
	$sql = "SELECT * FROM ".$nom_tabla." WHERE fecha='".$_POST["fecha"]."'";
	$bandera = $conexion->existeDato($sql);

	if($bandera == false){
		$datos = array(
	      array( 'fecha' , $_POST["fecha"]) , array( 'id_usuario' , $_POST["usuario"])
	  	);

	  	if($_POST["gasto_real_dia"] != ""){
	  		array_push($datos , array( 'gasto_real_dia' , $_POST["gasto_real_dia"] ));
	  	}

	  	if($_POST["gasto_estimado_dia"] != ""){
	  		array_push($datos , array( 'gasto_estimado_dia' , $_POST["gasto_estimado_dia"] ));
	  	}

		$bandera = $conexion->insertarDato($nom_tabla, $datos);
		if($bandera == true){
			echo "<div class='alert alert-success alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Datos guardados correctamente.</strong></div>";
		}else{
			echo "<div class='alert alert-danger alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>No se pudo guardar el registro, intentelo más tarde.</strong></div>";
		}
	}else{
		echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Registro repetido, intente con otra fecha.</strong></div>";
	}
	$conexion->cerrarConexion();
}
//!--Operacion 2 - FIN

//Operacion 3 - Visualización de pendientes

//Recuperar de la BDD los registros de ventas/utilidades pendientes
function recuperar_ventas_utilidades_pendientes($usuario){
	$conexion = new ConexionBDD();
	$columnasVenUti = "id_venta, num_empleado, fecha, venta_dia, utilidad_dia";
	$sql = "SELECT ".$columnasVenUti." FROM registro_venta WHERE id_usuario = ".$usuario." AND venta_dia = NULL AND utilidad_dia = NULL";
	$datosVenUti = $conexion->obtenerDatos($sql);
	$conexion->cerrarConexion();
	return $datosVenUti;
}

//Recuperar de la BDD los registros de solo ventas pendientes
function recuperar_ventas_pendientes($usuario){
	$conexion = new ConexionBDD();
	$columnasVenta = "id_venta, num_empleado, fecha, venta_dia";
	$sql = "SELECT ".$columnasVenta." FROM registro_venta WHERE id_usuario = ".$usuario." AND venta_dia = NULL AND utilidad_dia != NULL";
	$datosVenta = $conexion->obtenerDatos($sql);
	$conexion->cerrarConexion();
	return $datosVenta;
}

//Recuperar de la BDD los registros de solo utilidades pendientes
function recuperar_utilidades_pendientes($usuario){
	$conexion = new ConexionBDD();
	$columnasUtilidad = "id_venta, num_empleado, fecha, utilidad_dia";
	$sql = "SELECT ".$columnasUtilidad." FROM registro_venta WHERE id_usuario = ".$usuario." AND venta_dia != NULL AND utilidad_dia = NULL";
	$datosUtilidad = $conexion->obtenerDatos($sql);
	$conexion->cerrarConexion();
	return $datosUtilidad;
}

//Recuperar de la BDD los registros de gastos pendientes
function recuperar_gastos_pendientes($usuario){
	$conexion = new ConexionBDD();
	$columnasGasto = "id_gasto, fecha, gasto_real_dia, gasto_estimado_dia";
	$sql = "SELECT ".$columnasGasto." FROM registro_gasto WHERE id_usuario = ".$usuario." AND (gasto_real_dia = NULL or gasto_estimado_dia = NULL)";
	$datosGasto = $conexion->obtenerDatos($sql);
	$conexion->cerrarConexion();
	return $datosGasto;
}

//Generar los datos deseados
function mostrar_pendientes($usuario){
	$p_ven_uti = $_POST["pen_venta_utilidad"];
	$p_ven = $_POST["pen_venta"];
	$p_uti = $_POST["pen_utilidad"];
	$p_gts = $_POST["pen_gastos"];

	if($p_ven_uti == 1){
		$datos = recuperar_ventas_utilidades_pendientes($usuario);
		foreach ($datos as $data) {
			//ME QUEDE AQUI
		}
	}

	if($p_ven == 1){
		$datos = recuperar_ventas_pendientes($usuario);
	}

	if($p_uti == 1){
		$datos = recuperar_utilidades_pendientes($usuario);
	}

	if($p_gts == 1){
		$datos = recuperar_gastos_pendientes($usuario);
	}
}
//!--Operacion 3 - FIN

//Aquí inicia a correr el código
//Elección de opción a ejecutar
if(isset($_POST["op"])){
	$op = $_POST["op"];
	switch ($op) {
		case 0:
			iniciar_sesion();
			break;
		
		case 1:
			agregar_venta();
			break;
		
		case 2:
			agregar_gastos();
			break;
		
		default:
			echo "<div class='alert alert-danger alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>ERROR GRAVE: Contacte al administrador</strong></div>";
			break;
	}
}
?>