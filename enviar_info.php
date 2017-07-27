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
		$_SESSION["username"] = $sesion["nickname"];
		$_SESSION["email"] = $sesion["correo"];
		echo "<script language='javascript'>
				location.href='system'
			</script>";
	}else{
		$conexion->cerrarConexion();
		echo "<br><div class='alert alert-danger alert-dismissible text-center' role='alert'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
					<strong>Datos incorrectos</strong>
				  </div>";
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

	  	if($_POST["venta_dia"] != "" && $_POST["venta_dia"] >= 1){
	  		array_push($datos , array( 'venta_dia' , $_POST["venta_dia"] ));
	  	}

	  	if($_POST["utilidad_dia"] != "" && $_POST["utilidad_dia"] >= 1){
	  		array_push($datos , array( 'utilidad_dia' , $_POST["utilidad_dia"] ));
	  	}

		$bandera = $conexion->insertarDato($nom_tabla, $datos);
		if($bandera == true){
			echo "<div class='alert alert-success alert-dismissible text-center' role='alert'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
					<strong>Datos guardados correctamente.</strong>
				  </div>";
		}else{
			echo "<div class='alert alert-danger alert-dismissible text-center' role='alert'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
					<strong>No se pudo guardar el registro, intentelo más tarde.</strong>
				  </div>";
		}
	}else{
		echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span>
				</button><strong>Registro repetido, intente con otra fecha y/o empleado.</strong>
			</div>";
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
			echo "<div class='alert alert-success alert-dismissible text-center' role='alert'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
					<strong>Datos guardados correctamente.</strong>
				  </div>";
		}else{
			echo "<div class='alert alert-danger alert-dismissible text-center' role='alert'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
					<strong>No se pudo guardar el registro, intentelo más tarde.</strong>
				  </div>";
		}
	}else{
		echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>&times;</span>
				</button>
				<strong>Registro repetido, intente con otra fecha.</strong>
			  </div>";
	}
	$conexion->cerrarConexion();
}
//!--Operacion 2 - FIN

//Operacion 3 - Visualización de pendientes
//Recuperar de la BDD los registros de ventas/utilidades pendientes
function recuperar_ventas_utilidades_pendientes($usuario){
	$conexion = new ConexionBDD();
	$columnasVenUti = "id_venta, num_empleado, fecha";
	$sql = "SELECT ".$columnasVenUti." FROM registro_venta WHERE id_usuario = ".$usuario." AND venta_dia IS NULL AND utilidad_dia IS NULL";
	$datosVenUti = $conexion->obtenerDatos($sql);
	$conexion->cerrarConexion();
	return $datosVenUti;
}

//Recuperar de la BDD los registros de solo ventas pendientes
function recuperar_ventas_pendientes($usuario){
	$conexion = new ConexionBDD();
	$columnasVenta = "id_venta, num_empleado, fecha, utilidad_dia";
	$sql = "SELECT ".$columnasVenta." FROM registro_venta WHERE id_usuario = ".$usuario." AND venta_dia IS NULL AND utilidad_dia IS NOT NULL";
	$datosVenta = $conexion->obtenerDatos($sql);
	$conexion->cerrarConexion();
	return $datosVenta;
}

//Recuperar de la BDD los registros de solo utilidades pendientes
function recuperar_utilidades_pendientes($usuario){
	$conexion = new ConexionBDD();
	$columnasUtilidad = "id_venta, num_empleado, fecha, venta_dia";
	$sql = "SELECT ".$columnasUtilidad." FROM registro_venta WHERE id_usuario = ".$usuario." AND venta_dia IS NOT NULL AND utilidad_dia IS NULL";
	$datosUtilidad = $conexion->obtenerDatos($sql);
	$conexion->cerrarConexion();
	return $datosUtilidad;
}

//Recuperar de la BDD los registros de gastos pendientes
function recuperar_gastos_pendientes($usuario){
	$conexion = new ConexionBDD();
	$columnasGasto = "id_gasto, fecha, gasto_real_dia, gasto_estimado_dia";
	$sql = "SELECT ".$columnasGasto." FROM registro_gasto WHERE id_usuario = ".$usuario." AND (gasto_real_dia IS NULL OR gasto_estimado_dia IS NULL)";
	$datosGasto = $conexion->obtenerDatos($sql);
	$conexion->cerrarConexion();
	return $datosGasto;
}

//Generar los datos deseados
function mostrar_pendientes($usuario){
//Recuperar ventas y utilidades pendientes
	$num_pendientes = 0;
	if(isset($_POST["pen_venta_utilidad"])){
		$datos = recuperar_ventas_utilidades_pendientes($usuario);
		echo "<div class='panel panel-primary'>
				  <div class='panel-heading'>
				    <h3 class='panel-title'>Ventas y utilidades pendientes</h3>
				  </div>
				  <div class='panel-body'>
					  <div class='form-inline'>
						<div class='col-xs-2 form_espacio'><h4>Fecha</h4></div>
						<div class='col-xs-2 form_espacio'><h4># Empleado</h4></div>
						<div class='col-xs-3 form_espacio'><h4>Venta</h4></div>
						<div class='col-xs-3 form_espacio'><h4>Utilidad</h4></div>
						<div class='col-xs-2 form_espacio'><h4>Aceptar</h4></div>
					  ";
		foreach ($datos as $data) {
				$num_pendientes++;
				$fechaBD = date("d-m-Y", strtotime($data["fecha"]));
				echo "<form method='post' id='form_actualizar_pendiente_".$num_pendientes."'>
						<input type='hidden' name='op' id='op' value='4'>
						<input type='hidden' name='id_venta' id='id_venta' value='".$data["id_venta"]."'>
						<input type='hidden' name='usuario' id='usuario' value='".$usuario."'>
						<input type='hidden' name='tabla' id='tabla' value='registro_venta'>
						<div class='col-xs-2 form_espacio'>".$fechaBD."</div>
						<div class='col-xs-2 form_espacio'>".$data["num_empleado"]."</div>
						<div class='col-xs-3 form_espacio'><input type='number' class='form-control' id='venta_dia' name='venta_dia' step='.01' min='0'></div>
						<div class='col-xs-3 form_espacio'><input type='number' class='form-control' id='utilidad_dia' name='utilidad_dia' step='.01' min='0'></div>
						<div class='col-xs-2 form_espacio'><input type='hidden' name='num_pendiente' id='num_pendiente' value='".$num_pendientes."'><input type='submit' value='Enviar' id='actualizar_pendiente' class='btn_envio btn btn-primary'></div>
						</form>";
		}
			echo "	</div>
				</div>
			  </div>";
	}

//Recuperar solo ventas pendientes
	if(isset($_POST["pen_venta"])){
		$datos = recuperar_ventas_pendientes($usuario);
		echo "<div class='panel panel-primary'>
				  <div class='panel-heading'>
				    <h3 class='panel-title'>Sólo ventas pendientes</h3>
				  </div>
				  <div class='panel-body'>
					  <div class='form-inline'>
						<div class='col-xs-2 form_espacio'><h4>Fecha</h4></div>
						<div class='col-xs-2 form_espacio'><h4># Empleado</h4></div>
						<div class='col-xs-3 form_espacio'><h4>Venta</h4></div>
						<div class='col-xs-3 form_espacio'><h4>Utilidad</h4></div>
						<div class='col-xs-2 form_espacio'><h4>Aceptar</h4></div>
					  ";
		foreach ($datos as $data) {
				$num_pendientes++;
				$fechaBD = date("d-m-Y", strtotime($data["fecha"]));
				echo "<form method='post' id='form_actualizar_pendiente_".$num_pendientes."'>
						<input type='hidden' name='op' id='op' value='4'>
						<input type='hidden' name='id_venta' id='id_venta' value='".$data["id_venta"]."'>
						<input type='hidden' name='usuario' id='usuario' value='".$usuario."'>
						<input type='hidden' name='tabla' id='tabla' value='registro_venta'>
						<div class='col-xs-2 form_espacio'>".$fechaBD."</div>
						<div class='col-xs-2 form_espacio'>".$data["num_empleado"]."</div>
						<div class='col-xs-3 form_espacio'><input type='number' class='form-control' id='venta_dia' name='venta_dia' step='.01' min='0'></div>
						<div class='col-xs-3 form_espacio'>".$data["utilidad_dia"]."</div>
						<div class='col-xs-2 form_espacio'><input type='hidden' name='num_pendiente' id='num_pendiente' value='".$num_pendientes."'><input type='submit' value='Enviar' id='actualizar_pendiente' class='btn_envio btn btn-primary'></div>
						</form>";
		}
			echo "	</div>
				</div>
			  </div>";
	}

//Recuperar solo utilidades pendientes
	if(isset($_POST["pen_utilidad"])){
		$datos = recuperar_utilidades_pendientes($usuario);
		echo "<div class='panel panel-primary'>
				  <div class='panel-heading'>
				    <h3 class='panel-title'>Sólo utilidades pendientes</h3>
				  </div>
				  <div class='panel-body'>
					  <div class='form-inline'>
						<div class='col-xs-2 form_espacio'><h4>Fecha</h4></div>
						<div class='col-xs-2 form_espacio'><h4># Empleado</h4></div>
						<div class='col-xs-3 form_espacio'><h4>Venta</h4></div>
						<div class='col-xs-3 form_espacio'><h4>Utilidad</h4></div>
						<div class='col-xs-2 form_espacio'><h4>Aceptar</h4></div>
					  ";
		foreach ($datos as $data) {
				$num_pendientes++;
				$fechaBD = date("d-m-Y", strtotime($data["fecha"]));
				echo "<form method='post' id='form_actualizar_pendiente_".$num_pendientes."'>
						<input type='hidden' name='op' id='op' value='4'>
						<input type='hidden' name='id_venta' id='id_venta' value='".$data["id_venta"]."'>
						<input type='hidden' name='usuario' id='usuario' value='".$usuario."'>
						<input type='hidden' name='tabla' id='tabla' value='registro_venta'>
						<div class='col-xs-2 form_espacio'>".$fechaBD."</div>
						<div class='col-xs-2 form_espacio'>".$data["num_empleado"]."</div>
						<div class='col-xs-3 form_espacio'>".$data["venta_dia"]."</div>
						<div class='col-xs-3 form_espacio'><input type='number' class='form-control' id='utilidad_dia' name='utilidad_dia' step='.01' min='0'></div>
						<div class='col-xs-2 form_espacio'><input type='hidden' name='num_pendiente' id='num_pendiente' value='".$num_pendientes."'><input type='submit' value='Enviar' id='actualizar_pendiente' class='btn_envio btn btn-primary'></div>
						</form>";
		}
			echo "	</div>
				</div>
			  </div>";
	}

//Recuperar gastos pendientes
	if(isset($_POST["pen_gastos"])){
		$datos = recuperar_gastos_pendientes($usuario);
		echo "<div class='panel panel-danger'>
				  <div class='panel-heading'>
				    <h3 class='panel-title'>Gastos pendientes</h3>
				  </div>
				  <div class='panel-body'>
					  <div class='form-inline'>
						<div class='col-xs-3 form_espacio'><h4>Fecha</h4></div>
						<div class='col-xs-3 form_espacio'><h4>Gasto real</h4></div>
						<div class='col-xs-3 form_espacio'><h4>Gasto estimado</h4></div>
						<div class='col-xs-3 form_espacio'><h4>Aceptar</h4></div>
					  ";
		foreach ($datos as $data) {
				$num_pendientes++;
				$fechaBD = date("d-m-Y", strtotime($data["fecha"]));
				echo "<form method='post' id='form_actualizar_pendiente_".$num_pendientes."'>
						<input type='hidden' name='op' id='op' value='4'>
						<input type='hidden' name='id_gasto' id='id_gasto' value='".$data["id_gasto"]."'>
						<input type='hidden' name='usuario' id='usuario' value='".$usuario."'>
						<input type='hidden' name='tabla' id='tabla' value='registro_gasto'>
						<div class='col-xs-3 form_espacio'>".$fechaBD."</div>
						<div class='col-xs-3 form_espacio'>";

						if($data["gasto_real_dia"] != NULL){
				echo 		$data["gasto_real_dia"];
						}else{
				echo "		<input type='number' class='form-control' id='gasto_real_dia' name='gasto_real_dia' step='.01' min='0'>";
						}
						echo "</div>
						<div class='col-xs-3 form_espacio'>";

						if($data["gasto_estimado_dia"] != NULL){
				echo 		$data["gasto_estimado_dia"];
						}else{
				echo "		<input type='number' class='form-control' id='gasto_estimado_dia' name='gasto_estimado_dia' step='.01' min='0'>";
						}
						echo "</div>";

			echo "		<div class='col-xs-3 form_espacio'><input type='hidden' name='num_pendiente' id='num_pendiente' value='".$num_pendientes."'><input type='submit' value='Enviar' id='actualizar_pendiente' class='btn_envio btn btn-danger'></div>
						</form>";
		}
			echo "	</div>
				</div>
			  </div>";
	}
}
//!--Operacion 3 - FIN

//Operacion 4 - Actualizacion de pendientes
function actualizar_pendientes(){
	$conexion = new ConexionBDD();

	$tabla = $_POST["tabla"];
	$datos = array();
	if($tabla == "registro_venta"){
		if(isset($_POST["venta_dia"]) && $_POST["venta_dia"] != ""){
			array_push($datos , array( 'venta_dia' , $_POST["venta_dia"]));
		}
		if(isset($_POST["utilidad_dia"]) && $_POST["utilidad_dia"] != ""){
			array_push($datos , array( 'utilidad_dia' , $_POST["utilidad_dia"]));
		}
		$sql = " id_venta = ".$_POST["id_venta"];
	}else{
		if(isset($_POST["gasto_real_dia"]) && $_POST["gasto_real_dia"] != ""){
			array_push($datos , array( 'gasto_real_dia' , $_POST["gasto_real_dia"]));
		}
		if(isset($_POST["gasto_estimado_dia"]) && $_POST["gasto_estimado_dia"] != ""){
			array_push($datos , array( 'gasto_estimado_dia' , $_POST["gasto_estimado_dia"]));
		}
		$sql = "id_gasto = ".$_POST["id_gasto"];
	}
	$conexion->actualizarDato($tabla, $datos, $sql);
	echo "<div class='alert alert-success alert-dismissible text-center' role='alert'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span>
			</button>
			<strong>Datos actualizados correctamente.</strong>
		  </div>";
	mostrar_pendientes($_POST["usuario"]);
	unset($_POST);

	$conexion->cerrarConexion();
}
//!--Operacion 4 - FIN

//Operacion 5 - Mostrar los datos del usuario actual
//Convertir numero de permiso al puesto correspondiente
function puesto($permiso){
	switch ($permiso) {
		case '0':
			$puesto = "Administrador";
			break;
		
		case '1':
			$puesto = "Ventas";
			break;
		
		case '2':
			$puesto = "Compras";
			break;
		
		default:
			echo "Error grave de sistema! Comunicarse con el administrador";
			break;
	}

	return $puesto;
}

//Obtener los datos de un usuario
function mostrar_usuario($usuario){
	$conexion = new ConexionBDD();
	$sql = "SELECT * FROM usuario WHERE id_usuario = '".$usuario."'";
	$datos = $conexion->obtenerDatos($sql);
	foreach ($datos as $tmp) {
		$data = $tmp;
	}
	echo "<div class='col-md-4 col-md-offset-4 text-center container-xs-vertical'>
			<div class='panel panel-default container-xs-vertical'>
				<div class='panel-heading'>
					<h2 class='panel-title'>".$data["nombre"]." ".$data["apellido"]."</h2>
				</div>
				<div class='panel-body'>
					<label for='nickname'>Nombre de usuario: </label>
					<input type='text' name='nickname' class='form-control' id='nickname' value='".$data["nickname"]."' readonly>
					<label for='email'>Correo electronico: </label>
					<input type='email' name='email' class='form-control' id='email' value='".$data["correo"]."' readonly>
					<label for='posicion'>Posición: </label>
					<input type='text' name='posicion' class='form-control' id='posicion' value='".puesto($data["permiso"])."' readonly>
					<div class='btn-espacio'>
						<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#editar_info'>Editar información</button>	
					</div>
				</div>
			</div>
		</div>";
	$conexion->cerrarConexion();
}
//!--Operacion 5 - FIN

//Operacion 6 - Actualizar los datos del usuario actual
function comprobar_igualdad($cadena1, $cadena2){
	if($cadena1 == $cadena2){
		return true;
	}else{
		return false;
	}
}

function editar_usuario(){
	$conexion = new ConexionBDD();
	$nom_tabla = "usuario";
	$pass = $_POST["old_pass"];
	$usuario = $_POST["user"];
	$condicion = "id_usuario = '".$usuario."' AND password = '".$pass."'";
	$sql = "SELECT * FROM ".$nom_tabla." WHERE ".$condicion;
	//echo $sql;
	$bandera = $conexion->existeDato($sql);
	if($bandera == true){
		$bandera = comprobar_igualdad($_POST["new_pass"], $_POST["new_pass2"]);
		if($bandera == true){
			$datos = array( 
				array( 'nickname', $_POST["username"] ) , array( 'correo' , $_POST["email"] ) , array( 'password' , $_POST["new_pass"] )
			);
			session_start();
			$_SESSION["username"] = $_POST["username"];
			$_SESSION["email"] = $_POST["email"];
			$user = $_SESSION["username"];
			$email = $_SESSION["email"];
			$conexion->actualizarDato($nom_tabla, $datos, $condicion);
			echo "<script language='javascript'>
					var pagina='system'
					function redireccionar()
					{
						location.href=pagina
					}
					alert('Datos actualizados correctamente');
					setTimeout ('redireccionar()', 5);
				</script>";
		}else{
			echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span>
				</button><strong>Escriba la nueva contraseña en ambos campos.</strong>
			</div>";
		}
	}else{
		echo "<br><div class='alert alert-danger alert-dismissible text-center' role='alert'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
					<strong>Ingrese la contraseña correcta.</strong>
				  </div>";
	}
}
//!--Operacion 6 -FIN

//Operacion 7 - Generar reporte en la página
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

//Calcular el valor del estimado por día respecto al mes
function calcular_estimado_por_dia($fecha){
	$fecha_mes = substr($fecha, 5, 2);
	$fecha_anio = substr($fecha, 0, 3);

	$cantidad_dias_mes = cal_days_in_month(CAL_GREGORIAN, $fecha_mes, $fecha_anio);

	//Calculo de días habiles en el mes elegido
	$dias_habiles = $cantidad_dias_mes;
	for($i = 1; $i <= $cantidad_dias_mes; $i++){
		$fecha_calculada = mktime(NULL, NULL, NULL, $fecha_mes, $i, $fecha_anio);
		$fecha_calculada_valores = getdate($fecha_calculada);
		//echo $i." Valor de I <br>";
		if($fecha_calculada_valores["wday"] == 0){
			//echo $dias_habiles." Valor dia habil <br> <br> ";
			$dias_habiles_sel--;
		}else{
			if($fecha_calculada_valores["wday"] == 6){
				$dias_habiles = $dias_habiles - 0.5;
			}
		}
	}

	//Estimación de ventas en base a la cantidad de días habiles en el mes
	//Venta diaria con 2000k
	$venta_est_2000k = 2000000/$dias_habiles_sel;
	$utilidad_est_2000k = 700000/$dias_habiles_sel;
	//Venta diaria con 1500k
	$venta_est_1500k = 1500000/$dias_habiles_sel;
	$utilidad_est_1500k = 500000/$dias_habiles_sel;
	//Venta diaria con 1000k
	$venta_est_1000k = 1000000/$dias_habiles_sel;
	$utilidad_est_1000k = 455000/$dias_habiles_sel;
	//Venta diaria con 700k
	$venta_est_700k = 700000/$dias_habiles_sel;
	$utilidad_est_700k = 400000/$dias_habiles_sel;

	$arr_estimado_dia = array(
		array( '0' , $venta_est_2000k), array( '1' , $utilidad_est_2000k), 
		array( '2' , $venta_est_1500k), array( '3' , $utilidad_est_1500k), 
		array( '4' , $venta_est_1000k), array( '5' , $utilidad_est_1000k), 
		array( '6' , $venta_est_700k), array( '7' , $utilidad_est_700k),
	);

	return $arr_estimado_dia;
}

//Calcular el valor del estimado de un día especifico
function calcular_estimado_dia($fecha){
	$fecha_dia = substr($fecha, 8, 2);
	$fecha_mes = substr($fecha, 5, 2);
	$fecha_anio = substr($fecha, 0, 3);

	$arr_estimado_dia = calcular_parcial_mes($fecha, $fecha_dia);

	foreach ($arr_estimado_dia as list($variable , $valor)){
		switch($variable){
			case '0': $venta_est_2000k = $valor; break;

			case '1': $utilidad_est_2000k = $valor; break;

			case '2': $venta_est_1500k = $valor; break;

			case '3': $utilidad_est_1500k = $valor; break;

			case '4': $venta_est_1000k = $valor; break;

			case '5': $utilidad_est_1000k = $valor; break;

			case '6': $venta_est_700k = $valor; break;

			case '7': $utilidad_est_700k = $valor; break;

			default: echo "ERROR: Comuniquese con el administrador del sistema"; break;
		}
	}

	//Calculo de días habiles transcurridos en el día seleccionado
	$val_calculo_dia = 0;
	for($i = 1; $i <= $fecha_dia; $i++){
		$fecha_calculada = mktime(NULL, NULL, NULL, $fecha_mes, $i, $fecha_anio);
		$fecha_calculada_valores = getdate($fecha_calculada);
		//echo $i." Valor de I <br>";
		if($fecha_calculada_valores["wday"] == 0){
			//echo $dias_habiles." Valor dia habil <br> <br> ";
			$dias_habiles_sel--;
		}else{
			if($fecha_calculada_valores["wday"] == 6){
				$dias_habiles = $dias_habiles - 0.5;
			}
		}

		if($i == $fecha_dia){
			$num_dia = $fecha_calculada_valores["wday"];//El domingo inicia en 0 y acaba en sabado con el 6
		}
	}

	if($num_dia != 0){
		$venta_dia_2000k = $venta_est_2000k*$val_calculo_dia;
		$utilidad_dia_2000k = $utilidad_est_2000k*$val_calculo_dia;
		$venta_dia_1500k = $venta_est_1500k*$val_calculo_dia;
		$utilidad_dia_1500k = $utilidad_est_1500k*$val_calculo_dia;
		$venta_dia_1000k = $venta_est_1000k*$val_calculo_dia;
		$utilidad_dia_1000k = $utilidad_est_1000k*$val_calculo_dia;
		$venta_dia_700k = $venta_est_700k*$val_calculo_dia;
		$utilidad_dia_700k = $utilidad_est_700k*$val_calculo_dia;

		$arr_dia_valor = array(
			array( '0' , $venta_dia_2000k), array( '1' , $utilidad_dia_2000k), 
			array( '2' , $venta_dia_1500k), array( '3' , $utilidad_dia_1500k), 
			array( '4' , $venta_dia_1000k), array( '5' , $utilidad_dia_1000k), 
			array( '6' , $venta_dia_700k), array( '7' , $utilidad_dia_700k), 
		);

		return $arr_dia_valor;

	}else{
		return NULL;
	}

}

//Calcular acumulado de empleado hasta el dia estimado
function calcular_acumulado_empleado($emp, $fecha){
	$conexion = new ConexionBDD();
	$fecha_mes = substr($fecha, 5, 2);
	$fecha_anio = substr($fecha, 0, 4);
	$fecha_inicial = $fecha_anio."-".$fecha_mes."-01";

	$condicion = "num_empleado = '".$emp."' AND fecha BETWEEN '".$fecha_inicial."' AND '".$fecha."'";
	$sql = "SELECT SUM(venta_dia) FROM registro_venta WHERE ";
	$venta_acumulada = $conexion->ejecutarConsulta($sql.$condicion);
	$sql = "SELECT SUM(utilidad_dia) FROM registro_venta WHERE ";
	$utilidad_acumulada = $conexion->ejecutarConsulta($sql.$condicion);

	$arr_acumulada = array(
		'venta_acumulada' => $venta_acumulada, 
		'utilidad_acumulada' => $utilidad_acumulada
	);
	
	return $arr_acumulada;
}

//Calcular acumulado total hasta el dia
function calcular_acumulado_total($fecha){
	$conexion = new ConexionBDD();
	$fecha_mes = substr($fecha, 5, 2);
	$fecha_anio = substr($fecha, 0, 4);
	$fecha_inicial = $fecha_anio."-".$fecha_mes."-01";

	$condicion = "fecha BETWEEN '".$fecha_inicial."' AND '".$fecha."'";
	$sql = "SELECT SUM(venta_dia) FROM registro_venta WHERE ";
	$venta_acumulada = $conexion->ejecutarConsulta($sql.$condicion);
	$sql = "SELECT SUM(utilidad_dia) FROM registro_venta WHERE ";
	$utilidad_acumulada = $conexion->ejecutarConsulta($sql.$condicion);

	$arr_acumulada = array(
		'venta_acumulada' => $venta_acumulada, 
		'utilidad_acumulada' => $utilidad_acumulada
	);
	
	return $arr_acumulada;
}

//Calcular acumulado total hasta el dia
function calcular_dia_total($fecha){
	$conexion = new ConexionBDD();

	$condicion = "fecha = '".$fecha."'";
	$sql = "SELECT SUM(venta_dia) FROM registro_venta WHERE ";
	$venta_dia_total = $conexion->ejecutarConsulta($sql.$condicion);
	$sql = "SELECT SUM(utilidad_dia) FROM registro_venta WHERE ";
	$utilidad_dia_total = $conexion->ejecutarConsulta($sql.$condicion);

	$arr_total = array(
		'venta_dia_total' => $venta_dia_total, 
		'utilidad_dia_total' => $utilidad_dia_total
	);
	
	return $arr_total;
}

//Comprobar el numero de registros de ventas con la misma fecha
function comprobar_num_registro($fecha){
	$conexion = new ConexionBDD();
	$condicion = "fecha = '".$fecha."'";
	$sql = "SELECT COUNT(*) FROM registro_venta WHERE ".$condicion;
	$num_registros = $conexion->ejecutarConsulta($sql);

	return $num_registros;
}

//Definir estructura de reporte
function estructura_reporte_venta(){
	$opcF = 0;
	if(isset($_POST["rep_empleado"])){
		$opcF = $opcF + $_POST["rep_empleado"];
	}
	if(isset($_POST["rep_total"])){
		$opcF = $opcF + $_POST["rep_total"];
	}
	return $opcF;
}

//Comprobar envío de opciones
function comprobar_opciones(){
	if(isset($_POST["rep_empleado"]) || isset($_POST["rep_total"]) || isset($_POST["rep_estimado"]) || isset($_POST["rep_gastos"])){
		return true;
	}else{
		return false;
	}
}

function generar_reporte(){
	//Comprobar que efectivamente se haya seleccionado una opción
	$bandera = comprobar_opciones();

	if($bandera == true){
		//Conexion a la base de datos
		$conexion = new ConexionBDD();
		$usuario = $_POST["user"];
		$fecha_inicio = $_POST["fecha_inicio"];
		$fecha_final = $_POST["fecha_final"];

		//Recuperar registros de ventas
		$condicion = "id_usuario = '".$usuario."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_final."'";
		$sql = "SELECT * FROM registro_venta WHERE ".$condicion;
		$datos_venta = $conexion->obtenerDatos($sql);
		$fecha_ant = "";

		foreach ($datos_venta as $data) {
			//Extraer la fecha del valor actual
			$fecha = $data["fecha"];
			$fecha_dia = intval(substr($fecha, 8, 2));
			$fecha_mes = intval(substr($fecha, 5, 2));
			$fecha_anio = intval(substr($fecha, 0, 4));

			if($fecha != $fecha_ant){
				//Generar numero de registros existentes con la misma fecha
				$registro_cantidad = comprobar_num_registro($fecha);
				$registro_actual = 0;

				//Generar titulos de los reportes en pantalla
				$fecha_calculada = mktime(NULL, NULL, NULL, $fecha_mes, $fecha_dia, $fecha_anio);
				$fecha_calculada_valores = getdate($fecha_calculada);
				echo "<div class='col-xs-12 text-center subtitulo_fecha'><h5>".dia($fecha_calculada_valores["wday"])." ".$fecha_calculada_valores["mday"]." de ".mes($fecha_calculada_valores["mon"])." del ".$fecha_calculada_valores["year"]."</h5></div>";

				//Generar el contenido dependiendo la seleccion que se haya hecho
				$opc = estructura_reporte_venta();

				if($opc == 1 || $opc == 3){
					?>
					<div class='col-xs-12 col-md-1 col-sm-6 text-center titulo_venta'>
						<h5 class='blanco'>Num_Emp</h5>
					</div>

					<div class='col-xs-12 col-md-2 col-sm-6 text-center titulo_venta'>
						<h5 class='blanco'>Venta_Dia</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h5 class='blanco'>Venta_Acumulada</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h5 class='blanco'>Utilidad_dia</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h5 class='blanco'>Utilidad_acumulada</h5>
					</div>
					<?php
				}else{
					if($opc != 0){
						?>
						<div class='col-xs-12 col-md-1 col-sm-6 text-center titulo_venta'>
							<h5 class='blanco'>///</h5>
						</div>

						<div class='col-xs-12 col-md-2 col-sm-6 text-center titulo_venta'>
							<h5 class='blanco'>Venta_Dia</h5>
						</div>

						<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
							<h5 class='blanco'>Venta_Acumulada</h5>
						</div>

						<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
							<h5 class='blanco'>Utilidad_dia</h5>
						</div>

						<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
							<h5 class='blanco'>Utilidad_acumulada</h5>
						</div>
						<?php
					}else{
						//No se muestra nada debido a que no se selecciono ninguna opción de venta
					}
				}

				$acumulado_emp = calcular_acumulado_empleado($data["num_empleado"]);

				?>

				<div class='col-xs-12 col-md-1 col-sm-6 text-center subtitulo_venta'>
					<h5 class='blanco'><?php echo $data["num_empleado"]; ?></h5>
				</div>

				<div class='col-xs-12 col-md-2 col-sm-6 text-center subtitulo_venta'>
					<h5 class='blanco'><?php echo $data["venta_dia"]; ?></h5>
				</div>

				<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
					<h5 class='blanco'><?php echo $acumulado_emp["venta_acumulada"]; ?></h5>
				</div>

				<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
					<h5 class='blanco'><?php echo $data["utilidad_dia"]; ?></h5>
				</div>

				<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
					<h5 class='blanco'><?php echo $data["utilidad_acumulada"]; ?></h5>
				</div>

				<?php
				if(($opc == 2 || $opc == 3) AND $registro_actual == $registro_cantidad){

				}

				$fecha_ant = $fecha;
			}
		}
	}else{
		echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>&times;</span>
				</button>
				<strong>Seleccione una opción.</strong>
			  </div>";
	}
}
//!--Operacion 7 - FIN

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
		
		case 3:
			mostrar_pendientes($_POST["usuario"]);
			break;

		case 4:
			actualizar_pendientes();
			break;

		case 5:
			mostrar_usuario($_POST["usuario"]);
			break;

		case 6:
			editar_usuario();
			break;

		case 7:
			generar_reporte();
			break;

		default:
			echo "<div class='alert alert-danger alert-dismissible text-center' role='alert'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
					<strong>ERROR GRAVE: Contacte al administrador</strong>
				  </div>";
			break;
	}
}
?>