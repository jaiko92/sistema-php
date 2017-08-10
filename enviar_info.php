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
	$sql = "SELECT * FROM ".$nom_tabla." WHERE usuario = '".$usuario."' AND password = '".$pass."'";
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
		$_SESSION["nombre"] = $sesion["nom_completo"];
		$_SESSION["username"] = $sesion["usuario"];
		$_SESSION["email"] = $sesion["correo"];
		echo "<script language='javascript'>
				location.href='system'
			</script>";
	}else{
		$conexion->cerrarConexion();
		?>
		<br><div class='alert alert-danger alert-dismissible text-center' role='alert'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span>
			</button>
			<strong>Datos incorrectos</strong>
		</div>
		<?php
	}
}

//Operacion 1 - Generar facturas pendientes para costeo
//Operaciones auxiliares
//Realizar la conversión de los números en formato moneda
function formato_moneda($dato){
	if($dato != "N/A" && $dato != NULL){
		$valor = "$ ".number_format($dato, 2);
	}else{
		$valor = "N/A";
	}
	return $valor;
}

//Realizar la conversión de la fecha al formato dia/mes/año
function formato_fecha($fecha){
	if($fecha != NULL){
		$formato = substr($fecha, 8, 2)."/".substr($fecha, 5, 2)."/".substr($fecha, 0, 4);
		return $formato;
	}else{
		return NULL;
	}
}

//Operaciones auxiliares - FIN
function facturas_pendientes(){
	$fecha_inicio = $_POST["fecha_inicio"];
	$fecha_final = $_POST["fecha_final"];
	if($fecha_inicio != "" && $fecha_final != ""){
		$nom_tabla = "factura";

		$conexion = new ConexionBDD();
		$sql = "SELECT fechaf, rfc, folio, agente, pagada, subtotal, cost, rsocial, moneda, tc FROM ".$nom_tabla." WHERE cost IS NULL AND fechaf BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' ORDER BY fechaf ASC";
		$existe_facturas = $conexion->obtenerDatos($sql);
		if($existe_facturas == true){
			$facturas_pendientes = $conexion->obtenerDatos($sql);
			?>
			<div class='panel panel-primary'>
				<div class='panel-heading'>
					<h3 class='panel-title'>Facturas pendientes del <?php echo formato_fecha($fecha_inicio)." al ".formato_fecha($fecha_final); ?></h3>
				</div>
				<div class='panel-body del-padding'>
					<div class="col-xs-12 del-padding tabla-spc">
						<div class='col-xs-1 del-padding'><h6 class="bold">Folio</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">RFC</h6></div>
						<div class='col-xs-2 del-padding'><h6 class="bold">Razón social</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Fecha</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Agente</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Pagado</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Subtotal</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Costeo</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Moneda</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Tipo de cambio</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Actualizar</h6></div>
					</div>
				<?php
				$num_pendiente = 0;
				foreach ($facturas_pendientes as $factura) {
				?>
					<form method='post' id="form_actualizar_costeo_<?php echo $num_pendiente; ?>" class="col-xs-12 del-padding tabla-spc tabla-hover">
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["folio"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["rfc"]; ?></h6>
						</div>
						<div class="col-xs-2 del-padding">
							<h6><?php echo $factura["rsocial"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["fechaf"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["agente"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["pagada"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo formato_moneda($factura["subtotal"]*$factura["tc"]); ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<input type="number" name="costeo" id="costeo" class="form-control" min="0" step=".0001" placeholder="Costeo">
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["moneda"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo formato_moneda($factura["tc"]); ?></h6>
						</div>
						<div class="col-xs-1 del-padding btn-spc">
							<button id="btn_actualizar_costeo" class="btn btn-primary btn-sm">Actualizar</button>
						</div>
					</form>
				<?php
					$num_pendiente++;
				}
				unset($_POST);
				?>
				</div>
			</div>
		<?php
		}else{
		?>
			<div class='alert alert-danger alert-dismissible text-center' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>&times;</span>
				</button>
				<strong>No existen facturas pendientes en la fecha seleccionada.</strong>
			</div>
		<?php
		}
	}else{
	?>
		<div class='alert alert-warning alert-dismissible text-center' role='alert'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span>
			</button>
			<strong>Por favor seleccione una fecha.</strong>
		</div>
	<?php
	}
		
}
//Operación 1 - FIN

//Operacion 2 - Actualizar costeo manualmente
function actualizar_costeo(){
	$conexion = new ConexionBDD();

	$tabla = "factura";
	$datos = array(
		array( "cost" , $_POST["costeo"])
	);
	$sql = "folio = ".$_POST["folio"];
	$conexion->actualizarDato($tabla, $datos, $sql);
	?>
	<div class='alert alert-success alert-dismissible text-center' role='alert'>
		<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			<span aria-hidden='true'>&times;</span>
		</button>
		<strong>Datos actualizados correctamente.</strong>
	</div>
<?php
}
//Operación 2 - FIN

//Operación 3 - Generar facturas canceladas
function facturas_canceladas(){
	$fecha_inicio = $_POST["fecha_inicio"];
	$fecha_final = $_POST["fecha_final"];
	if($fecha_inicio != "" && $fecha_final != ""){
		$nom_tabla = "factura";

		$conexion = new ConexionBDD();
		$sql = "SELECT fechaf, rfc, folio, agente, pagada, subtotal, cost, rsocial, moneda, tc FROM ".$nom_tabla." WHERE subtotal = 0 AND fechaf BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' ORDER BY fechaf ASC";
		$existe_facturas = $conexion->existeDato($sql);
		if($existe_facturas == true){
			$facturas_pendientes = $conexion->obtenerDatos($sql);
			?>
			<div class='panel panel-primary'>
				<div class='panel-heading'>
					<h3 class='panel-title'>Facturas canceladas del <?php echo formato_fecha($fecha_inicio)." al ".formato_fecha($fecha_final); ?></h3>
				</div>
				<div class='panel-body del-padding'>
					<div class="col-xs-12 del-padding tabla-spc">
						<div class='col-xs-1 del-padding'><h6 class="bold">Folio</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">RFC</h6></div>
						<div class='col-xs-3 del-padding'><h6 class="bold">Razón social</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Fecha</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Agente</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Pagado</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Subtotal</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Costeo</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Moneda</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Tipo de cambio</h6></div>
					</div>
				<?php
				$num_pendiente = 0;
				foreach ($facturas_pendientes as $factura) {
				?>
					<form method='post' id="form_actualizar_cancelada_<?php echo $num_pendiente; ?>" class="col-xs-12 del-padding tabla-spc tabla-hover">
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["folio"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["rfc"]; ?></h6>
						</div>
						<div class="col-xs-3 del-padding">
							<h6><?php echo $factura["rsocial"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["fechaf"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["agente"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["pagada"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo formato_moneda($factura["subtotal"]*$factura["tc"]); ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo formato_moneda($factura["cost"]*$factura["tc"]); ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["moneda"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo formato_moneda($factura["tc"]); ?></h6>
						</div>
					</form>
				<?php
					$num_pendiente++;
				}
				unset($_POST);
				?>
				</div>
			</div>
		<?php
		}else{
		?>
			<div class='alert alert-danger alert-dismissible text-center' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>&times;</span>
				</button>
				<strong>No existen facturas canceladas en la fecha seleccionada.</strong>
			</div>
		<?php
		}
	}else{
	?>
		<div class='alert alert-warning alert-dismissible text-center' role='alert'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span>
			</button>
			<strong>Por favor seleccione una fecha.</strong>
		</div>
	<?php
	}
		
}
//Operación 3 - FIN

//Operación 4 - Generar autocompletado
function autocompletado(){
	$dato = $_POST["dato"];
	$nom_tabla = "factura";

	$conexion = new ConexionBDD();
	$sql = "SELECT DISTINCT(".$dato.") FROM ".$nom_tabla." WHERE ".$dato." IS NOT NULL";
	$datos_autocompletado = $conexion->obtenerDatos($sql);
	$cadena_valores = "";
	foreach($datos_autocompletado as $valor){
		$cadena_valores = $cadena_valores.$valor[$dato].',';
	}
	echo $cadena_valores;
}
//Operación 4 - FIN

//Operacion 5 - Generar facturas en general
function facturas_general(){
	$fecha_inicio = $_POST["fecha_inicio"];
	$fecha_final = $_POST["fecha_final"];
	$rfc = $_POST["rfc"];
	$rsocial = $_POST["razon_social"];
	$folio = $_POST["folio"];
	$agente = $_POST["agente"];
	$nom_tabla = "factura";
	$bandera_fecha = false;

	$conexion = new ConexionBDD();
	$sql = "SELECT fechaf, rfc, folio, agente, pagada, subtotal, cost, rsocial, moneda, tc FROM ".$nom_tabla;

	if($fecha_inicio != "" || $fecha_final != "" || $rfc != "" || $rsocial != "" || $folio != "" || $agente != ""){
		$sql = $sql." WHERE";
		$bandera = false;
	}

	if($fecha_inicio != "" && $fecha_final != ""){
		$sql = $sql." fechaf BETWEEN '".$fecha_inicio."' AND '".$fecha_final."'";
		$bandera_fecha = true;
		$bandera = true;
		if($rfc != ""){
			if($bandera == true){
				$sql = $sql." AND";
			}else{
				$bandera == true;
			}
			$sql = $sql." rfc LIKE '%".$rfc."%'";
		}
		
		if($rsocial != ""){
			if($bandera == true){
				$sql = $sql." AND";
			}else{
				$bandera == true;
			}
			$sql = $sql." rsocial LIKE '%".$rsocial."%'";
		}

		if($folio != ""){
			if($bandera == true){
				$sql = $sql." AND";
			}else{
				$bandera == true;
			}
			$sql = $sql." folio = ".$folio;
		}

		if($agente != ""){
			if($bandera == true){
				$sql = $sql." AND";
			}else{
				$bandera == true;
			}
			$sql = $sql." agente LIKE '%".$agente."%'";
		}

		$sql = $sql." ORDER BY fechaf ASC";
		$existe_facturas = $conexion->existeDato($sql);
		if($existe_facturas == true){
			$facturas_pendientes = $conexion->obtenerDatos($sql);
			?>
			<div class='panel panel-primary'>
				<div class='panel-heading'>
				<?php
				if($bandera_fecha == true){
				?>
				<h3 class='panel-title'>Facturas del <?php echo formato_fecha($fecha_inicio)." al ".formato_fecha($fecha_final); ?></h3>
				<?php
				}else{
				?>
				<h3 class='panel-title'>Facturas</h3>
				<?php
				}
				?>
				</div>
				<div class='panel-body del-padding'>
					<div class="col-xs-12 del-padding tabla-spc">
						<div class='col-xs-1 del-padding'><h6 class="bold">Folio</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">RFC</h6></div>
						<div class='col-xs-3 del-padding'><h6 class="bold">Razón social</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Fecha</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Agente</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Pagado</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Subtotal</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Costeo</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Moneda</h6></div>
						<div class='col-xs-1 del-padding'><h6 class="bold">Tipo de cambio</h6></div>
					</div>
				<?php
				$num_factura = 0;
				foreach ($facturas_pendientes as $factura) {
				?>
					<form method='post' id="form_actualizar_cancelada_<?php echo $num_factura; ?>" class="col-xs-12 del-padding tabla-spc tabla-hover">
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["folio"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["rfc"]; ?></h6>
						</div>
						<div class="col-xs-3 del-padding">
							<h6><?php echo $factura["rsocial"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["fechaf"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["agente"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["pagada"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo formato_moneda($factura["subtotal"]*$factura["tc"]); ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo formato_moneda($factura["cost"]*$factura["tc"]); ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo $factura["moneda"]; ?></h6>
						</div>
						<div class="col-xs-1 del-padding">
							<h6><?php echo formato_moneda($factura["tc"]); ?></h6>
						</div>
					</form>
				<?php
					$num_factura++;
				}
				unset($_POST);
				?>
				</div>
			</div>
		<?php
		}else{
		?>
			<div class='alert alert-danger alert-dismissible text-center' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>&times;</span>
				</button>
				<strong>No existen facturas con los datos seleccionados.</strong>
			</div>
		<?php
		}
	}else{
	?>
		<div class='alert alert-danger alert-dismissible text-center' role='alert'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span>
			</button>
			<strong>Por favor seleccione una fecha, es imposible cargar tantos datos al mismo tiempo.</strong>
		</div>
	<?php
	}

		
}
//Operación 5 - FIN

//Operacion 6 - Generar facturas en general
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

function generar_metas_mensuales(){
	$fecha_inicio = $_POST["fecha_inicio"]."-01";
	$fecha_final = $_POST["fecha_final"]."-01";

	if($fecha_inicio != "" && $fecha_final != ""){
		$nom_tabla = "meta_mensual";

		$conexion = new ConexionBDD();
		$sql = "SELECT * FROM ".$nom_tabla." WHERE mes BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' ORDER BY mes ASC";
		$existe_facturas = $conexion->existeDato($sql);
		if($existe_facturas == true){
			$metas_mensuales = $conexion->obtenerDatos($sql);
			foreach ($metas_mensuales as $meta){
			?>
			<div class="col-xs-4 text-center">
				<div class='panel panel-primary'>
					<div class='panel-heading'>
						<h3 class='panel-title'><?php echo mes(substr($meta["mes"], 5, 2))." del ".substr($meta["mes"], 0, 4); ?></h3>
					</div>
					<div class='panel-body del-padding'>
						<div class="col-xs-12 del-padding tabla-spc">
							<div class='col-xs-6 del-padding'><h6 class="bold">Meta mensual</h6></div>
							<div class='col-xs-6 del-padding'><h6 class="bold">Porcentaje de Utilidad</h6></div>
						</div>

						<div class="col-xs-6 del-padding">
							<h6><?php echo formato_moneda($meta["valor_meta"]); ?></h6>
						</div>
						<div class="col-xs-6 del-padding">
							<h6><?php echo $meta["porcentaje_utilidad"]." %"; ?></h6>
						</div>
					</div>
				</div>
			</div>
			<?php
			}
			unset($_POST);
		}else{
		?>
			<div class='alert alert-danger alert-dismissible text-center' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>&times;</span>
				</button>
				<strong>No existen metas con las fechas seleccionadas.</strong>
			</div>
		<?php
		}
	}else{
	?>
		<div class='alert alert-warning alert-dismissible text-center' role='alert'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span>
			</button>
			<strong>Por favor seleccione una fecha.</strong>
		</div>
	<?php
	}

		
}
//Operación 6 - FIN

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
	$venta_est_2000k = 2000000/$dias_habiles;
	$utilidad_est_2000k = 700000/$dias_habiles;
	//Venta diaria con 1500k
	$venta_est_1500k = 1500000/$dias_habiles;
	$utilidad_est_1500k = 500000/$dias_habiles;
	//Venta diaria con 1000k
	$venta_est_1000k = 1000000/$dias_habiles;
	$utilidad_est_1000k = 455000/$dias_habiles;
	//Venta diaria con 700k
	$venta_est_700k = 700000/$dias_habiles;
	$utilidad_est_700k = 400000/$dias_habiles;

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

	$arr_estimado_dia = calcular_estimado_por_dia($fecha);

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
		if($fecha_calculada_valores["wday"] != 0){
			//echo $dias_habiles." Valor dia habil <br> <br> ";
			$val_calculo_dia++;
		}else{
			if($fecha_calculada_valores["wday"] == 6){
				$val_calculo_dia = $val_calculo_dia + 0.5;
			}
		}
	}

	$venta_dia_2000k = $venta_est_2000k*$val_calculo_dia;
	$utilidad_dia_2000k = $utilidad_est_2000k*$val_calculo_dia;
	$venta_dia_1500k = $venta_est_1500k*$val_calculo_dia;
	$utilidad_dia_1500k = $utilidad_est_1500k*$val_calculo_dia;
	$venta_dia_1000k = $venta_est_1000k*$val_calculo_dia;
	$utilidad_dia_1000k = $utilidad_est_1000k*$val_calculo_dia;
	$venta_dia_700k = $venta_est_700k*$val_calculo_dia;
	$utilidad_dia_700k = $utilidad_est_700k*$val_calculo_dia;

	$div_vt_2000k_1500k = $venta_dia_2000k-(($venta_dia_2000k-$venta_dia_1500k)/2);
	$div_ut_2000k_1500k = $utilidad_dia_2000k-(($utilidad_dia_2000k-$utilidad_dia_1500k)/2);
	$div_vt_1500k_1000k = $venta_dia_1500k-(($venta_dia_1500k-$venta_dia_1000k)/2);
	$div_ut_1500k_1000k = $utilidad_dia_1500k-(($utilidad_dia_1500k-$utilidad_dia_1000k)/2);
	$div_vt_1000k_700k = $venta_dia_1000k-(($venta_dia_1000k-$venta_dia_700k)/2);
	$div_ut_1000k_700k = $utilidad_dia_1000k-(($utilidad_dia_1000k-$utilidad_dia_700k)/2);

	$arr_dia_valor = array(
		"venta_dia_2000k" => $venta_dia_2000k, "utilidad_dia_2000k" => $utilidad_dia_2000k, 
		"venta_dia_1500k" => $venta_dia_1500k, "utilidad_dia_1500k" => $utilidad_dia_1500k, 
		"venta_dia_1000k" => $venta_dia_1000k, "utilidad_dia_1000k" => $utilidad_dia_1000k, 
		"venta_dia_700k" => $venta_dia_700k, "utilidad_dia_700k" => $utilidad_dia_700k, 
		"div_vt_2000k_1500k" => $div_vt_2000k_1500k, "div_ut_2000k_1500k" => $div_ut_2000k_1500k, 
		"div_vt_1500k_1000k" => $div_vt_1500k_1000k, "div_ut_1500k_1000k" => $div_ut_1500k_1000k, 
		"div_vt_1000k_700k" => $div_vt_1000k_700k, "div_ut_1000k_700k" => $div_ut_1000k_700k, 
	);

	return $arr_dia_valor;

}

//Calcular acumulado de empleado hasta el dia seleccionado
function calcular_acumulado_empleado($emp, $fecha){
	$conexion = new ConexionBDD();
	$fecha_mes = substr($fecha, 5, 2);
	$fecha_anio = substr($fecha, 0, 4);
	$fecha_inicial = $fecha_anio."-".$fecha_mes."-01";

	$condicion = "num_empleado = '".$emp."' AND fecha BETWEEN '".$fecha_inicial."' AND '".$fecha."'";
	$sql = "SELECT venta_dia, utilidad_dia FROM registro_venta WHERE ".$condicion;
	$datos = $conexion->obtenerDatos($sql);

	$venta_acumulada = 0;
	$utilidad_acumulada = 0;

	foreach($datos as $data){
		if($data["venta_dia"] == NULL){
			$data["venta_dia"] = 0;
		}
		if($data["utilidad_dia"] == NULL){
			$data["utilidad_dia"] = 0;
		}

		$venta_acumulada = $venta_acumulada + $data["venta_dia"];
		$utilidad_acumulada = $utilidad_acumulada + $data["utilidad_dia"];
	}

	$arr_acumulada = array(
		'venta_acumulada' => $venta_acumulada, 
		'utilidad_acumulada' => $utilidad_acumulada
	);
	
	return $arr_acumulada;
}

//Calcular acumulado total hasta el dia seleccionado
function calcular_acumulado_total($fecha){
	$conexion = new ConexionBDD();
	$fecha_mes = substr($fecha, 5, 2);
	$fecha_anio = substr($fecha, 0, 4);
	$fecha_inicial = $fecha_anio."-".$fecha_mes."-01";

	$condicion = "fecha BETWEEN '".$fecha_inicial."' AND '".$fecha."'";
	$sql = "SELECT venta_dia, utilidad_dia FROM registro_venta WHERE ".$condicion;
	$datos = $conexion->obtenerDatos($sql);

	$venta_acumulada = 0;
	$utilidad_acumulada = 0;

	foreach($datos as $data){
		$venta_acumulada = $venta_acumulada + $data["venta_dia"];
		$utilidad_acumulada = $utilidad_acumulada + $data["utilidad_dia"];
	}

	$arr_acumulada = array(
		'venta_acumulada' => $venta_acumulada, 
		'utilidad_acumulada' => $utilidad_acumulada
	);
	
	return $arr_acumulada;
}

//Calcular total del dia
function calcular_dia_total($fecha){
	$conexion = new ConexionBDD();

	$condicion = "fecha = '".$fecha."'";
	$sql = "SELECT venta_dia, utilidad_dia FROM registro_venta WHERE ".$condicion;
	$datos = $conexion->obtenerDatos($sql);

	$venta_dia_total = 0;
	$utilidad_dia_total = 0;

	foreach($datos as $data){
		$venta_dia_total = $venta_dia_total + $data["venta_dia"];
		$utilidad_dia_total = $utilidad_dia_total + $data["utilidad_dia"];
	}

	$arr_total = array(
		'venta_dia_total' => $venta_dia_total, 
		'utilidad_dia_total' => $utilidad_dia_total
	);
	
	return $arr_total;
}

//Calcular acumulado de gastos hasta el dia seleccionado
function calcular_acumulado_gastos($fecha){
	$conexion = new ConexionBDD();
	$fecha_mes = substr($fecha, 5, 2);
	$fecha_anio = substr($fecha, 0, 4);
	$fecha_inicial = $fecha_anio."-".$fecha_mes."-01";

	$condicion = "fecha BETWEEN '".$fecha_inicial."' AND '".$fecha."'";
	$sql = "SELECT gasto_estimado_dia, gasto_real_dia FROM registro_gasto WHERE ".$condicion;
	$datos = $conexion->obtenerDatos($sql);

	$gasto_real_acumulado = 0;
	$gasto_estimado_acumulado = 0;

	foreach($datos as $data){
		$gasto_real_acumulado = $gasto_real_acumulado + $data["gasto_real_dia"];
		$gasto_estimado_acumulado = $gasto_estimado_acumulado + $data["gasto_estimado_dia"];
	}

	$arr_acumulado = array(
		'gasto_real_acumulado' => $gasto_real_acumulado, 
		'gasto_estimado_acumulado' => $gasto_estimado_acumulado
	);
	
	return $arr_acumulado;
}

//Extraer valores de gastos de una fecha seleccionada
function recuperar_gasto($fecha){
	$conexion = new ConexionBDD();
	$condicion = "fecha = '".$fecha."'";
	$sql = "SELECT gasto_real_dia, gasto_estimado_dia FROM registro_gasto WHERE ".$condicion;
	$datos = $conexion->existeDato($sql);
	if($datos == true){
		$datos = $conexion->obtenerDatos($sql);
		foreach ($datos as $data) {
			$obj_gasto = $data;
		}

		return $obj_gasto;
	}else{
		return NULL;
	}
}

//Comprobar el numero de registros de ventas con la misma fecha
function comprobar_num_registro($fecha){
	$conexion = new ConexionBDD();
	$condicion = "fecha = '".$fecha."'";
	$sql = "SELECT COUNT(*) FROM registro_venta WHERE ".$condicion;
	$datos = $conexion->obtenerDatos($sql);
	foreach ($datos as $data) {
		$num_registros = $data["COUNT(*)"];
	}

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

//Generar reporte que se muestra en pantalla
function generar_reporte(){
	//Comprobar que efectivamente se haya seleccionado una opción
	$bandera = comprobar_opciones();

	//Conexion a la base de datos
	$conexion = new ConexionBDD();
	$usuario = $_POST["user"];
	$fecha_inicio = $_POST["fecha_inicio"];
	$fecha_final = $_POST["fecha_final"];

	if($bandera == true){
		//Formulario para realizar la exportación a PDF ?>
		<div class="col-xs-12 text-center btn_pdf">
			<form action="enviar_info.php" method="post" id="form_exportar_pdf" target="_blank">
				<input type="hidden" name="user" id="user" value="<?php echo $usuario; ?>">
				<input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
				<input type="hidden" name="fecha_final" id="fecha_final" value="<?php echo $fecha_final; ?>">
				<?php if (isset($_POST["rep_estimado"])): ?>
					<input type="hidden" name="rep_estimado" id="rep_estimado" value="<?php echo $_POST['rep_estimado']; ?>">
				<?php endif ?>
				<?php if ($_POST["rep_empleado"]): ?>
					<input type="hidden" name="rep_empleado" id="rep_empleado" value="<?php echo $_POST['rep_empleado']; ?>">
				<?php endif ?>
				<?php if ($_POST["rep_gastos"]): ?>
					<input type="hidden" name="rep_gastos" id="rep_gastos" value="<?php echo $_POST['rep_gastos']; ?>">
				<?php endif ?>
				<?php if ($_POST["rep_total"]): ?>
					<input type="hidden" name="rep_total" id="rep_total" value="<?php echo $_POST['rep_total']; ?>">
				<?php endif ?>
				<input type="hidden" name="op" id="op" value="8">
				<button class="btn btn-primary" id="btn_exportar_pdf">Exportar PDF</button>
			</form>
		</div>
		
		<?php

		//Recuperar registros de ventas
		$condicion = "id_usuario = '".$usuario."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' ORDER BY fecha, num_empleado";
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
				//Calcular el numero total de registros diferentes por día
				$registro_cantidad = comprobar_num_registro($fecha);
				$registro_actual = 0;

				//Mostrar fecha por día de los reportes
				$fecha_calculada = mktime(NULL, NULL, NULL, $fecha_mes, $fecha_dia, $fecha_anio);
				$fecha_calculada_valores = getdate($fecha_calculada);
				echo "<div class='col-xs-12 text-center subtitulo_fecha'><h5 class='bold'>".dia($fecha_calculada_valores["wday"])." ".$fecha_calculada_valores["mday"]." de ".mes($fecha_calculada_valores["mon"])." del ".$fecha_calculada_valores["year"]."</h5></div>";

				//Mostrar los titulos de las tablas dependiendo la seleccion que el usuario haya hecho
				$opc = estructura_reporte_venta();

				if($opc == 1 || $opc == 3){
					//Selección de desglose de ventas por empleados o mezclado con total
					?>
					<div class='col-xs-12 col-md-1 col-sm-6 text-center titulo_venta'>
						<h5 class='blanco'># Emp</h5>
					</div>

					<div class='col-xs-12 col-md-2 col-sm-6 text-center titulo_venta'>
						<h5 class='blanco'>Venta del día</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h5 class='blanco'>Venta acumulada</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h5 class='blanco'>Utilidad del día</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h5 class='blanco'>Utilidad acumulada</h5>
					</div>
					<?php
				}else{
					if($opc != 0){
						//Selección de ventas totales únicamente
						?>
						<div class='col-xs-12 col-md-1 col-sm-6 text-center titulo_venta'>
							<h5 class='blanco'>///</h5>
						</div>

						<div class='col-xs-12 col-md-2 col-sm-6 text-center titulo_venta'>
							<h5 class='blanco'>Venta del día</h5>
						</div>

						<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
							<h5 class='blanco'>Venta acumulada</h5>
						</div>

						<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
							<h5 class='blanco'>Utilidad dia</h5>
						</div>

						<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
							<h5 class='blanco'>Utilidad acumulada</h5>
						</div>
						<?php
					}else{
						//No se muestra nada debido a que no se selecciono ninguna opción de venta
					}
				}
			}

			//Cambio de las variables NULL para que muestren N/A
			$registro_actual++;
			$acumulado_emp = calcular_acumulado_empleado($data["num_empleado"], $fecha);
			if($data["venta_dia"] == NULL){
				$data["venta_dia"] = "N/A";
			}
			if($data["utilidad_dia"] == NULL){
				$data["utilidad_dia"] = "N/A";
			}

			?>
			
			<?php //Mostrar los valores de cada venta por empleado en la fecha elegida ?>
			<div class='col-xs-12 col-md-1 col-sm-6 text-center subtitulo_venta'>
				<h5 class='blanco bold'><?php echo $data["num_empleado"]; ?></h5>
			</div>

			<div class='col-xs-12 col-md-2 col-sm-6 text-center subtitulo_venta'>
				<h5 class='blanco bold'><?php echo "$ ".formato_moneda($data["venta_dia"]); ?></h5>
			</div>

			<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
				<h5 class='blanco bold'><?php echo "$ ".formato_moneda($acumulado_emp["venta_acumulada"]); ?></h5>
			</div>

			<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
				<h5 class='blanco bold'><?php echo "$ ".formato_moneda($data["utilidad_dia"]); ?></h5>
			</div>

			<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
				<h5 class='blanco bold'><?php echo "$ ".formato_moneda($acumulado_emp["utilidad_acumulada"]); ?></h5>
			</div>

			<?php
			//Mostrar la tabla de las ventas totales si así lo haya decidido el usuario
			$datos_acumulado_total = calcular_acumulado_total($fecha);
			$vt_acumulada = $datos_acumulado_total["venta_acumulada"];
			$ut_acumulada = $datos_acumulado_total["utilidad_acumulada"];
			if (($opc == 2 || $opc == 3) AND $registro_actual == $registro_cantidad){
				$datos_dia_total = calcular_dia_total($fecha);
			 ?>
				<div class='col-xs-12 col-md-1 col-sm-6 text-center titulo_venta'>
					<h5 class='blanco bold'>Total</h5>
				</div>

				<div class='col-xs-12 col-md-2 col-sm-6 text-center titulo_venta'>
					<h5 class='blanco bold'><?php echo "$ ".formato_moneda($datos_dia_total["venta_dia_total"]); ?></h5>
				</div>

				<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
					<h5 class='blanco bold'><?php echo "$ ".formato_moneda($vt_acumulada); ?></h5>
				</div>

				<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
					<h5 class='blanco bold'><?php echo "$ ".formato_moneda($datos_dia_total["utilidad_dia_total"]); ?></h5>
				</div>

				<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
					<h5 class='blanco bold'><?php echo "$ ".formato_moneda($ut_acumulada); ?></h5>
				</div>

			<?php
			}

			//Variable que verifica que la fecha cambie para no mostrar las siguientes tablas repetidas
			$fecha_ant = $fecha;

			//Mostrar la tabla de los gastos si es que el usuario lo haya decidido
			if(isset($_POST["rep_gastos"]) AND $registro_actual == $registro_cantidad){
				$datos_gasto = recuperar_gasto($fecha);

				if($datos_gasto != NULL){
					$datos_acumulado_gasto = calcular_acumulado_gastos($fecha);

					if($datos_gasto["gasto_real_dia"] == NULL){
						$datos_gasto["gasto_real_dia"] = "N/A";
					}

					if($datos_gasto["gasto_estimado_dia"] == NULL){
						$datos_gasto["gasto_estimado_dia"] = "N/A";
					}
			?>
					<?php //Mostrar los titulos para los gastos ?>
					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h5 class='blanco'>Gasto real día</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h5 class='blanco'>Gasto real acumulado</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h5 class='blanco'>Gasto estimado día</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h5 class='blanco'>Gasto estimado acumulado</h5>
					</div>
					
					<?php //Mostrar los valores de los gastos de la fecha seleccionada ?>
					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_gasto'>
						<h5 class='blanco bold'><?php echo "$ ".formato_moneda($datos_gasto["gasto_real_dia"]); ?></h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_gasto'>
						<h5 class='blanco bold'><?php echo "$ ".formato_moneda($datos_acumulado_gasto["gasto_real_acumulado"]); ?></h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_gasto'>
						<h5 class='blanco bold'><?php echo "$ ".formato_moneda($datos_gasto["gasto_estimado_dia"]); ?></h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_gasto'>
						<h5 class='blanco bold'><?php echo "$ ".formato_moneda($datos_acumulado_gasto["gasto_estimado_acumulado"]); ?></h5>
					</div>
			<?php
				}else{
			?>
					<div class='alert alert-danger text-center alert-auto' role='alert'>
						<strong>No hay gastos existentes en esta fecha</strong>
				  	</div>
			<?php
				}
			}
			//Mostrar la tabla de estimado si es que el usuario lo haya decidido
			if(isset($_POST["rep_estimado"]) AND $registro_actual == $registro_cantidad){
				$arr_val_estimado = calcular_estimado_dia($fecha);
				if($arr_val_estimado != NULL){
					//Mostrar los titulos del estimado mensual
					?>
					<div class="col-xs-4 col-md-4 col-sm-6 text-center subtitulo_estimacion">
						<h5 class="negro bold">Venta del día estimada</h5>
					</div>

					<div class="col-xs-4 col-md-4 col-sm-6 text-center subtitulo_estimacion">
						<h5 class="negro bold">Utilidad del día estimada</h5>
					</div>

					<div class="col-xs-4 col-md-4 col-sm-6 text-center subtitulo_estimacion">
						<h5 class="negro bold">Utilidad del mes estimada</h5>
					</div>
					
					<?php
					//Mostrar el estimado de 700k de utilidad mensual

					//Verificación de la meta respecto a la utilidad realizada
					if($ut_acumulada > $arr_val_estimado["div_ut_2000k_1500k"]){
						if($ut_acumulada > $arr_val_estimado["utilidad_dia_2000k"]){
							$clase_2000k = "meta_arriba";
						}else{
							$clase_2000k = "meta_abajo";
						}
					}else{
						$clase_2000k = "sin_meta";
					}

					//Cambio de color de letra respecto a la meta
					if($clase_2000k != "sin_meta"){
						$clase_letra_2000k = "blanco ";
					}else{
						$clase_letra_2000k = "negro ";
					}
					?>
					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_2000k; ?>">
						<h5 class="<?php echo $clase_letra_2000k; ?>bold"><?php echo "$ ".formato_moneda($arr_val_estimado["venta_dia_2000k"]); ?></h5>
					</div>

					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_2000k; ?>">
						<h5 class="<?php echo $clase_letra_2000k; ?>bold"><?php echo "$ ".formato_moneda($arr_val_estimado["utilidad_dia_2000k"]); ?></h5>
					</div>

					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_2000k; ?>">
						<h5 class="<?php echo $clase_letra_2000k; ?>bold">700k</h5>
					</div>
					
					<?php
					//Mostrar el estimado de 500k de utilidad mensual

					//Verificación de la meta respecto a la utilidad realizada
					if($ut_acumulada > $arr_val_estimado["div_ut_1500k_1000k"] && $ut_acumulada < $arr_val_estimado["div_ut_2000k_1500k"]){
						if($ut_acumulada > $arr_val_estimado["utilidad_dia_1500k"]){
							$clase_1500k = "meta_arriba";
						}else{
							$clase_1500k = "meta_abajo";
						}
					}else{
						$clase_1500k = "sin_meta";
					}

					//Cambio de color de letra respecto a la meta
					if($clase_1500k != "sin_meta"){
						$clase_letra_1500k = "blanco ";
					}else{
						$clase_letra_1500k = "negro ";
					}
					?>
					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_1500k; ?>">
						<h5 class="<?php echo $clase_letra_1500k; ?>bold"><?php echo "$ ".formato_moneda($arr_val_estimado["venta_dia_1500k"]); ?></h5>
					</div>

					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_1500k; ?>">
						<h5 class="<?php echo $clase_letra_1500k; ?>bold"><?php echo "$ ".formato_moneda($arr_val_estimado["utilidad_dia_1500k"]); ?></h5>
					</div>

					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_1500k; ?>">
						<h5 class="<?php echo $clase_letra_1500k; ?>bold">500k</h5>
					</div>
					
					<?php
					//Mostrar el estimado de 450k de utilidad mensual

					//Verificación de la meta respecto a la utilidad realizada
					if($ut_acumulada > $arr_val_estimado["div_ut_1000k_700k"] && $ut_acumulada < $arr_val_estimado["div_ut_1500k_1000k"]){
						if($ut_acumulada > $arr_val_estimado["utilidad_dia_1000k"]){
							$clase_1000k = "meta_arriba";
						}else{
							$clase_1000k = "meta_abajo";
						}
					}else{
						$clase_1000k = "sin_meta";
					}
					
					//Cambio de color de letra respecto a la meta
					if($clase_1000k != "sin_meta"){
						$clase_letra_1000k = "blanco ";
					}else{
						$clase_letra_1000k = "negro ";
					}
					?>
					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_1000k; ?>">
						<h5 class="<?php echo $clase_letra_1000k; ?>bold"><?php echo "$ ".formato_moneda($arr_val_estimado["venta_dia_1000k"]); ?></h5>
					</div>

					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_1000k; ?>">
						<h5 class="<?php echo $clase_letra_1000k; ?>bold"><?php echo "$ ".formato_moneda($arr_val_estimado["utilidad_dia_1000k"]); ?></h5>
					</div>

					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_1000k; ?>">
						<h5 class="<?php echo $clase_letra_1000k; ?>bold">450k</h5>
					</div>
					
					<?php
					//Mostrar el estimado de 400k de utilidad mensual

					//Verificación de la meta respecto a la utilidad realizada
					if($ut_acumulada < $arr_val_estimado["div_ut_1000k_700k"]){
						if($ut_acumulada > $arr_val_estimado["utilidad_dia_700k"]){
							$clase_700k = "meta_arriba";
						}else{
							$clase_700k = "meta_abajo";
						}
					}else{
						$clase_700k = "sin_meta";
					}
					
					//Cambio de color de letra respecto a la meta
					if($clase_700k != "sin_meta"){
						$clase_letra_700k = "blanco ";
					}else{
						$clase_letra_700k = "negro ";
					}
					?>
					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_700k; ?>">
						<h5 class="<?php echo $clase_letra_700k; ?>bold"><?php echo "$ ".formato_moneda($arr_val_estimado["venta_dia_700k"]); ?></h5>
					</div>

					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_700k; ?>">
						<h5 class="<?php echo $clase_letra_700k; ?>bold"><?php echo "$ ".formato_moneda($arr_val_estimado["utilidad_dia_700k"]); ?></h5>
					</div>

					<div class="col-xs-4 col-md-4 col-sm-6 text-center <?php echo $clase_700k; ?>">
						<h5 class="<?php echo $clase_letra_700k; ?>bold">400k</h5>
					</div>

					<?php
				}
			}
		}
		?>

		<?php //Formulario para realizar la exportación a PDF ?>
		<div class="col-xs-12 text-center btn_pdf">
			<form action="enviar_info.php" method="post" id="form_exportar_pdf" target="_blank">
				<input type="hidden" name="user" id="user" value="<?php echo $usuario; ?>">
				<input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
				<input type="hidden" name="fecha_final" id="fecha_final" value="<?php echo $fecha_final; ?>">
				<?php if (isset($_POST["rep_estimado"])): ?>
					<input type="hidden" name="rep_estimado" id="rep_estimado" value="<?php echo $_POST['rep_estimado']; ?>">
				<?php endif ?>
				<?php if ($_POST["rep_empleado"]): ?>
					<input type="hidden" name="rep_empleado" id="rep_empleado" value="<?php echo $_POST['rep_empleado']; ?>">
				<?php endif ?>
				<?php if ($_POST["rep_gastos"]): ?>
					<input type="hidden" name="rep_gastos" id="rep_gastos" value="<?php echo $_POST['rep_gastos']; ?>">
				<?php endif ?>
				<?php if ($_POST["rep_total"]): ?>
					<input type="hidden" name="rep_total" id="rep_total" value="<?php echo $_POST['rep_total']; ?>">
				<?php endif ?>
				<input type="hidden" name="op" id="op" value="8">
				<button class="btn btn-primary" id="btn_exportar_pdf">Exportar PDF</button>
			</form>
		</div>
		<?php		
	}else{
		?>
		<div class='alert alert-warning alert-dismissible text-center' role='alert'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span>
			</button>
			<strong>Seleccione una opción.</strong>
		</div>
		<?php
	}

	unset($_POST);
}
//!--Operacion 7 - FIN

//Operacion 8 - Exportar a PDF
//Recuperar los datos de todas las ventas de una fecha unicamente
function recuperar_datos_venta($fecha, $usuario){
	//Recuperar los datos de venta de una fecha especifica
	$conexion = new ConexionBDD();
	$condicion = "id_usuario = '".$usuario."' AND fecha = '".$fecha."' ORDER BY num_empleado";
	$sql = "SELECT * FROM registro_venta WHERE ".$condicion;
	$datos_venta = $conexion->obtenerDatos($sql);

	return $datos_venta;
}

//Función principal para exportar a PDF
function exportar_pdf(){
	//Conexion a la base de datos
	$conexion = new ConexionBDD();
	$usuario = $_POST["user"];
	$fecha_inicio = $_POST["fecha_inicio"];
	$fecha_final = $_POST["fecha_final"];

	//Nombre por default del archivo generado
	$filename = str_replace("-", "", $fecha_inicio)."_".str_replace("-", "", $fecha_final).".pdf";

	//Recuperar la cantidad de fechas existentes en la elección hecha por el usuario
	$condicion = "id_usuario = '".$usuario."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' ORDER BY fecha";
	$sql = "SELECT DISTINCT fecha FROM registro_venta WHERE ".$condicion;
	$datos_fecha = $conexion->obtenerDatos($sql);
	//$fecha_ant = "";

	$html = "<!DOCTYPE html>
			<html>
			<head>
				<meta charset='UTF-8'>
				<title>Sistema administrativo</title>
				<link rel='stylesheet' href='css/style.css'>
				<title>".$filename."</title>
			</head>
			<body>
				<div class='container'>";
	foreach ($datos_fecha as $data) {
		//Extraer la fecha del valor actual
		$fecha = $data["fecha"];
		$fecha_dia = intval(substr($fecha, 8, 2));
		$fecha_mes = intval(substr($fecha, 5, 2));
		$fecha_anio = intval(substr($fecha, 0, 4));

		//Calcular el numero total de registros diferentes por día
		$registro_cantidad = comprobar_num_registro($fecha);
		$registro_actual = 0;

		//Calcular las ventas totales
		if (isset($_POST["rep_total"])){
			$datos_acumulado_total = calcular_acumulado_total($fecha);
			$vt_acumulada = $datos_acumulado_total["venta_acumulada"];
			$ut_acumulada = $datos_acumulado_total["utilidad_acumulada"];

			$datos_dia_total = calcular_dia_total($fecha);
		}

		//Calcular los estimados
		if (isset($_POST["rep_estimado"])){
			$arr_val_estimado = calcular_estimado_dia($fecha);
		}

		//Recuperar los gastos
		if (isset($_POST["rep_gastos"])){
			$datos_gasto = recuperar_gasto($fecha);
			if($datos_gasto != NULL){
				$datos_gasto_acumulado = calcular_acumulado_gastos($fecha);

				if($datos_gasto["gasto_real_dia"] == NULL){
					$datos_gasto["gasto_real_dia"] = "N/A";
				}

				if($datos_gasto["gasto_estimado_dia"] == NULL){
					$datos_gasto["gasto_estimado_dia"] = "N/A";
				}
			}
		}
		
		//Titulos de cada sección
		$html = $html."
		<table class='datos_tabla'>
				<tr>
					<th class='text-center w_7 inicio_columna cont_columna final_columna'>FECHA</th>
					<th class='text-center w_9 inicio_columna'>VENTA</th>
					<th class='text-center w_9 final_columna'></th>
					<th class='text-center w_9 inicio_columna'>UTILIDAD</th>
					<th class='text-center w_9 final_columna'></th>
					<th class='text-center w_9 inicio_columna'>ESTIMADO</th>
					<th class='text-center w_9'></th>
					<th class='text-center w_3 final_columna'></th>
					<th class='text-center w_8 inicio_columna' colspan='2'>GASTO REAL</th>
					<th class='text-center w_8 inicio_columna final_columna' colspan='2'>GASTO ESTIMADO</th>
				</tr>
				<tr>
					<th class='text-center w_7 inicio_columna cont_columna final_columna'>".$fecha_dia."-".$fecha_mes."-".$fecha_anio."</th> 
					<th class='text-center w_9 inicio_columna'>Acumulada</th>
					<th class='text-center w_9 cont_columna final_columna'>Al día</th>
					<th class='text-center w_9 inicio_columna'>Acumulada</th>
					<th class='text-center w_9 cont_columna final_columna'>Al día</th>
					<th class='text-center w_9 inicio_columna'>Venta</th>
					<th class='text-center w_9 cont_columna'>Utilidad</th>
					<th class='text-center w_3 cont_columna final_columna'>Mensual</th>
					<th class='text-center w_8 inicio_columna'>Al día</th>
					<th class='text-center w_8 cont_columna final_columna'>Acumulado</th>
					<th class='text-center w_8 inicio_columna'>Al día</th>
					<th class='text-center w_8 cont_columna final_columna'>Acumulado</th>
				</tr>";

		$data_venta = recuperar_datos_venta($fecha, $usuario);
		$registro_actual = 1;
		foreach($data_venta as $dato) {
			
			if(isset($_POST["rep_empleado"])){
				//Cambio de las variables NULL para que muestren N/A
				$acumulado_emp = calcular_acumulado_empleado($dato["num_empleado"], $fecha);
				if($dato["venta_dia"] == NULL){
					$dato["venta_dia"] = "N/A";
				}
				if($dato["utilidad_dia"] == NULL){
					$dato["utilidad_dia"] = "N/A";
				}

				$html = $html."
					<tr>
						<td class='text-center w_7 inicio_columna cont_columna final_columna'>".$dato["num_empleado"]."</td>
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($acumulado_emp["venta_acumulada"])."</td>
						<td class='text-right w_9 cont_columna final_columna'>$ ".formato_moneda($dato["venta_dia"])."</td>
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($acumulado_emp["utilidad_acumulada"])."</td>
						<td class='text-right w_9 cont_columna final_columna'>$ ".formato_moneda($dato["utilidad_dia"])."</td>";
			}else{
				$html = $html."
					<tr>
						<td class='text-center w_7 inicio_columna cont_columna final_columna'> - </td>
						<td class='text-center w_9 inicio_columna'> - </td>
						<td class='text-center w_9 cont_columna final_columna'> - </td>
						<td class='text-center w_9 inicio_columna'> - </td>
						<td class='text-center w_9 cont_columna final_columna'> - </td>";
			}
							
			if(isset($_POST["rep_total"])){
				switch ($registro_actual) {
					case 4:
						$html = $html."
						<td class='text-center w_9 inicio_columna bold'> TOTAL </td>
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($vt_acumulada)."</td>
						<td class='text-right w_9 cont_columna final_columna'>$ ".formato_moneda($datos_dia_total["venta_dia_total"])."</td>
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($ut_acumulada)."</td>
						<td class='text-right w_9 cont_columna final_columna'>$ ".formato_moneda($datos_dia_total["utilidad_dia_total"])."</td>
						";
						break;
					
					default:
						break;
				}
			}else{
				switch ($registro_actual) {
					case 4:
						$html = $html."
						<td class='text-center w_9 inicio_columna'> - </td>
						<td class='text-center w_9 inicio_columna'> - </td>
						<td class='text-center w_9 cont_columna final_columna'> - </td>
						<td class='text-center w_9 inicio_columna'> - </td>
						<td class='text-center w_9 cont_columna final_columna'> - </td>
						";
						break;
					
					default:
						break;
				}
			}
		/*""div_vt_2000k_1500k" => $div_vt_2000k_1500k, "div_ut_2000k_1500k" => $div_ut_2000k_1500k, 
		"div_vt_1500k_1000k" => $div_vt_1500k_1000k, "div_ut_1500k_1000k" => $div_ut_1500k_1000k, 
		"div_vt_1000k_700k" => $div_vt_1000k_700k, "div_ut_1000k_700k" => $div_ut_1000k_700k,*/
			if(isset($_POST["rep_estimado"])){
				switch ($registro_actual) {
					case 1:
						$html = $html."
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($arr_val_estimado["venta_dia_700k"])."</td>
						<td class='text-right w_9 cont_columna'>$ ".formato_moneda($arr_val_estimado["utilidad_dia_700k"])."</td>
						<td class='text-center w_3 cont_columna final_columna bold'>400</td>
						";
						break;
					
					case 2:
						$html = $html."
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($arr_val_estimado["venta_dia_1000k"])."</td>
						<td class='text-right w_9 cont_columna'>$ ".formato_moneda($arr_val_estimado["utilidad_dia_1000k"])."</td>
						<td class='text-center w_3 cont_columna final_columna bold'>450</td>
						";
						break;
					
					case 3:
						$html = $html."
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($arr_val_estimado["venta_dia_1500k"])."</td>
						<td class='text-right w_9 cont_columna'>$ ".formato_moneda($arr_val_estimado["utilidad_dia_1500k"])."</td>
						<td class='text-center w_3 cont_columna final_columna bold'>500</td>
						";
						break;
					
					case 4:
						$html = $html."
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($arr_val_estimado["venta_dia_2000k"])."</td>
						<td class='text-right w_9 cont_columna'>$ ".formato_moneda($arr_val_estimado["utilidad_dia_2000k"])."</td>
						<td class='text-center w_3 cont_columna final_columna bold'>700</td>
						";
						break;
					
					default:
						# code...
						break;
				}
			}else{
				$html = $html."
					<td class='text-center w_9 inicio_columna'> - </td>
					<td class='text-center w_9 cont_columna'> - </td>
					<td class='text-center w_3 cont_columna final_columna'> - </td>
				";
			}
			
			if(isset($_POST["rep_gastos"])){
				switch ($registro_actual) {
					case 1:
						$html = $html."
							<td class='text-right w_8 inicio_columna'>$ ".formato_moneda($datos_gasto["gasto_real_dia"])."</td>
							<td class='text-right w_8 cont_columna final_columna'>$ ".formato_moneda($datos_gasto["gasto_estimado_dia"])."</td>
							<td class='text-right w_8 inicio_columna'>$ ".formato_moneda($datos_gasto_acumulado["gasto_real_acumulado"])."</td>
							<td class='text-right w_8 cont_columna final_columna'>$ ".formato_moneda($datos_gasto_acumulado["gasto_estimado_acumulado"])."</td>
						</tr>
						";
						break;
					
					default:
						$html = $html."
							<td class='text-center w_8 inicio_columna'> - </td>
							<td class='text-center w_8 cont_columna final_columna'> - </td>
							<td class='text-center w_8 inicio_columna'> - </td>
							<td class='text-center w_8 cont_columna final_columna'> - </td>
						</tr>
						";
						break;
				}
			}else{
				$html = $html."
						<td class='text-center w_8 inicio_columna'> - </td>
						<td class='text-center w_8 cont_columna final_columna'> - </td>
						<td class='text-center w_8 inicio_columna'> - </td>
						<td class='text-center w_8 cont_columna final_columna'> - </td>
					</tr>
					";
			}

			$registro_actual++;
		}

		//Extracción de datos después de sacar el resultado de los empleados
		for(; $registro_actual < 5; $registro_actual++) {
			if($registro_actual < 4){
				$html = $html."
					<tr>
						<td class='text-center w_7 inicio_columna cont_columna final_columna'> - </td>
						<td class='text-center w_9 inicio_columna'> - </td>
						<td class='text-center w_9 cont_columna final_columna'> - </td>
						<td class='text-center w_9 inicio_columna'> - </td>
						<td class='text-center w_9 cont_columna final_columna'> - </td>";
			}else{
				$html = $html."<tr>";
			}
				
		/*""div_vt_2000k_1500k" => $div_vt_2000k_1500k, "div_ut_2000k_1500k" => $div_ut_2000k_1500k, 
		"div_vt_1500k_1000k" => $div_vt_1500k_1000k, "div_ut_1500k_1000k" => $div_ut_1500k_1000k, 
		"div_vt_1000k_700k" => $div_vt_1000k_700k, "div_ut_1000k_700k" => $div_ut_1000k_700k,*/

			if(isset($_POST["rep_total"])){
				switch ($registro_actual) {
					case 4:
						$html = $html."
						<td class='text-center w_7 inicio_columna cont_columna final_columna bold'> TOTAL </td>
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($vt_acumulada)."</td>
						<td class='text-right w_9 cont_columna final_columna'>$ ".formato_moneda($datos_dia_total["venta_dia_total"])."</td>
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($ut_acumulada)."</td>
						<td class='text-right w_9 cont_columna final_columna'>$ ".formato_moneda($datos_dia_total["utilidad_dia_total"])."</td>
						";
						break;
					
					default:
						break;
				}
			}else{
				switch ($registro_actual) {
					case 4:
						$html = $html."
						<td class='text-center w_9 inicio_columna'> - </td>
						<td class='text-center w_9 inicio_columna'> - </td>
						<td class='text-center w_9 cont_columna final_columna'> - </td>
						<td class='text-center w_9 inicio_columna'> - </td>
						<td class='text-center w_9 cont_columna final_columna'> - </td>
						</tr>
						";
						break;
					
					default:
						break;
				}
			}

			if(isset($_POST["rep_estimado"])){
				switch ($registro_actual) {
					case 1:
						$html = $html."
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($arr_val_estimado["venta_dia_700k"])."</td>
						<td class='text-right w_9 cont_columna'>$ ".formato_moneda($arr_val_estimado["utilidad_dia_700k"])."</td>
						<td class='text-center w_3 cont_columna final_columna bold'>400</td>
						";
						break;
					
					case 2:
						$html = $html."
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($arr_val_estimado["venta_dia_1000k"])."</td>
						<td class='text-right w_9 cont_columna'>$ ".formato_moneda($arr_val_estimado["utilidad_dia_1000k"])."</td>
						<td class='text-center w_3 cont_columna final_columna bold'>450</td>
						";
						break;
					
					case 3:
						$html = $html."
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($arr_val_estimado["venta_dia_1500k"])."</td>
						<td class='text-right w_9 cont_columna'>$ ".formato_moneda($arr_val_estimado["utilidad_dia_1500k"])."</td>
						<td class='text-center w_3 cont_columna final_columna bold'>500</td>
						";
						break;
					
					case 4:
						$html = $html."
						<td class='text-right w_9 inicio_columna'>$ ".formato_moneda($arr_val_estimado["venta_dia_2000k"])."</td>
						<td class='text-right w_9 cont_columna'>$ ".formato_moneda($arr_val_estimado["utilidad_dia_2000k"])."</td>
						<td class='text-center w_3 cont_columna final_columna bold'>700</td>
						";
						break;
					
					default:
						# code...
						break;
				}
			}else{
				$html = $html."
					<td class='text-center w_9 inicio_columna'> - </td>
					<td class='text-center w_9 cont_columna'> - </td>
					<td class='text-center w_3 cont_columna final_columna'> - </td>
				";
			}
			
			if(isset($_POST["rep_gastos"])){
				switch ($registro_actual) {
					case 1:
						$html = $html."
						<td class='text-right w_8 inicio_columna'>$ ".formato_moneda($datos_gasto["gasto_real_dia"])."</td>
						<td class='text-right w_8 cont_columna final_columna'>$ ".formato_moneda($datos_gasto["gasto_estimado_dia"])."</td>
						<td class='text-right w_8 inicio_columna'>$ ".formato_moneda($datos_gasto_acumulado["gasto_real_acumulado"])."</td>
						<td class='text-right w_8 cont_columna final_columna'>$ ".formato_moneda($datos_gasto_acumulado["gasto_estimado_acumulado"])."</td>
						";
						break;
					
					default:
						$html = $html."
						<td class='text-center w_8 inicio_columna'> - </td>
						<td class='text-center w_8 cont_columna final_columna'> - </td>
						<td class='text-center w_8 inicio_columna'> - </td>
						<td class='text-center w_8 cont_columna final_columna'> - </td>
						</tr>
						";
						break;
				}
			}else{
				$html = $html."
					<td class='text-center w_8 inicio_columna'> - </td>
					<td class='text-center w_8 cont_columna final_columna'> - </td>
					<td class='text-center w_8 inicio_columna'> - </td>
					<td class='text-center w_8 cont_columna final_columna'> - </td>
					</tr>
					";
			}
		}

		$html = $html."
		</table>
		";	}
	$html = $html."
		</div>
		</body>
	</html>
	";
	require_once("dompdf/dompdf_config.inc.php");
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->set_paper('letter', 'landscape');
	$dompdf->render();
	$pdf = $dompdf->output();
	$dompdf->stream($filename, array("Attachment" => 0));

}
//!--Operacion 8 - FIN

//Aquí inicia a correr el código
//Elección de opción a ejecutar
if(isset($_POST["op"])){
	$op = $_POST["op"];
	switch ($op) {
		case 0:
			iniciar_sesion();
			break;
		
		case 1:
			facturas_pendientes();
			break;
		
		case 2:
			actualizar_costeo();
			break;
		
		case 3:
			facturas_canceladas();
			break;

		case 4:
			autocompletado();
			break;

		case 5:
			facturas_general();
			break;

		case 6:
			generar_metas_mensuales();
			break;

		case 7:
			generar_reporte();
			break;

		case 8:
			exportar_pdf();
			break;

		default:
			?>
			<div class='alert alert-danger alert-dismissible text-center' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>&times;</span>
				</button>
				<strong>ERROR GRAVE: Contacte al administrador</strong>
			</div>
			<?php
			break;
	}
}
?>