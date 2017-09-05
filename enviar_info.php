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
	$datosNombre = array("cost");
	$datosTabla = array($_POST["costeo"]);

	$datos = array($datosNombre ,$datosTabla);
	
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
	$fecha_inicio = $_POST["fecha_inicio"];
	$fecha_final = $_POST["fecha_final"];

	if($fecha_inicio != "" && $fecha_final != ""){
		$fecha_inicio = $fecha_inicio."-01";
		$fecha_final = $fecha_final."-01";
		$nom_tabla = "meta_mensual";

		$conexion = new ConexionBDD();
		$sql = "SELECT * FROM ".$nom_tabla." WHERE mes BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' ORDER BY mes ASC";
		$existe_facturas = $conexion->existeDato($sql);
		if($existe_facturas == true){
			$metas_mensuales = $conexion->obtenerDatos($sql);
			?>
			<div class="col-xs-12">
				<h3>Consultar Metas</h3>
			</div>
			<?php
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

//Operación 7 - Cargar metas mensuales
function ingresar_meta_mensual(){
	$fecha = $_POST["mes_meta"]."-01";
	$valor_meta = $_POST["total_meta"];
	$porcentaje = $_POST["porcentaje_utilidad"];
	$usuario = $_POST["user"];
	$nom_tabla = "meta_mensual";

	if($fecha != "" && $valor_meta != "" && $porcentaje != "" && $usuario != ""){
		$conexion = new ConexionBDD();
		$sql = "SELECT * FROM ".$nom_tabla." WHERE mes = '".$fecha."'";
		$existe_mes = $conexion->existeDato($sql);
		if($existe_mes == false){
			$datosTabla = array(
				"mes", "valor_meta", "porcentaje_utilidad", "id_usuario"
			);

			$datosNombre = array(
				$fecha, $valor_meta, $porcentaje, $usuario
			);

			$datos = array($datosNombre, $datosTabla);

			$bandera = $conexion->insertarDato($nom_tabla, $datos);
			if($bandera == true){
			?>
				<div class='alert alert-success alert-dismissible text-center' role='alert'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
					<strong>Datos guardados correctamente.</strong>
				</div>
			<?php
			}else{
			?>
				<div class='alert alert-danger alert-dismissible text-center' role='alert'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
					<strong>No se han guardado los datos, pruebe nuevamente.</strong>
				</div>
			<?php
			}
		}else{
		?>
			<div class='alert alert-warning alert-dismissible text-center' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>&times;</span>
				</button>
				<strong>Ya existe una meta en la fecha seleccionada, por favor seleccione otra.</strong>
			</div>
		<?php
		}	
	}else{
	?>
		<div class='alert alert-warning alert-dismissible text-center' role='alert'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span>
			</button>
			<strong>Por favor llene todos los campos.</strong>
		</div>
	<?php
	}
}
//Operación 7 - FIN

//Operacion 8 - Generar reporte en la página
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
	$fecha_anio = substr($fecha, 0, 4);
	$mes = $fecha_anio."-".$fecha_mes."-01";

	$cantidad_dias_mes = cal_days_in_month(CAL_GREGORIAN, $fecha_mes, $fecha_anio);

	//Calculo de días habiles en el mes elegido
	$dias_habiles = $cantidad_dias_mes;
	for($i = 1; $i <= $cantidad_dias_mes; $i++){
		$fecha_calculada = mktime(NULL, NULL, NULL, $fecha_mes, $i, $fecha_anio);
		$fecha_calculada_valores = getdate($fecha_calculada);
		//echo $i." Valor de I <br>";
		if($fecha_calculada_valores["wday"] == 0){
			//echo $dias_habiles." Valor dia habil <br> <br> ";
			$dias_habiles--;
		}else{
			if($fecha_calculada_valores["wday"] == 6){
				$dias_habiles = $dias_habiles - 0.5;
			}
		}
	}

	$conexion = new ConexionBDD();

	$tabla = "meta_mensual";
	$condicion = "mes = '".$mes."' ORDER BY mes";

	$sql = "SELECT valor_meta, porcentaje_utilidad FROM ".$tabla." WHERE ".$condicion;

	$bandera = $conexion->existeDato($sql);
	if($bandera == true){
		$arr_meta = $conexion->obtenerDatos($sql);

		foreach ($arr_meta as $mes) {
			$cantidad_meta = $mes["valor_meta"];
			$porcentaje = ($mes["porcentaje_utilidad"])/100;
		}

		//Estimación de ventas en base a la cantidad de días habiles en el mes
		//Venta diaria con 2000k
		$venta_est_meta_100 = bcdiv($cantidad_meta, $dias_habiles, 4);
		$utilidad_est_meta_100 = bcdiv(bcmul($cantidad_meta, $porcentaje, 4), $dias_habiles, 4);
		//Venta diaria con 1500k
		$venta_est_meta_75 = bcdiv(bcmul($cantidad_meta, 0.75, 4), $dias_habiles, 4);
		$utilidad_est_meta_75 = bcdiv(bcmul(bcmul($cantidad_meta, 0.75, 4), $porcentaje, 4), $dias_habiles, 4);
		//Venta diaria con 1000k
		$venta_est_meta_50 = bcdiv(bcmul($cantidad_meta, 0.50, 4), $dias_habiles, 4);
		$utilidad_est_meta_50 = bcdiv(bcmul(bcmul($cantidad_meta, 0.50, 4), $porcentaje, 4), $dias_habiles, 4);
		//Venta diaria con 700k
		$venta_est_meta_25 = bcdiv(bcmul($cantidad_meta, 0.25, 4), $dias_habiles, 4);
		$utilidad_est_meta_25 = bcdiv(bcmul(bcmul($cantidad_meta, 0.25, 4), $porcentaje, 4), $dias_habiles, 4);

		$arr_estimado_dia = array(
			$venta_est_meta_100, $utilidad_est_meta_100, $venta_est_meta_75
			, $utilidad_est_meta_75, $venta_est_meta_50, $utilidad_est_meta_50, $venta_est_meta_25
			, $utilidad_est_meta_25
		);

		return $arr_estimado_dia;
	}else{
		return NULL;
	}
}

//Calcular el valor del estimado de un día especifico
function calcular_estimado_dia($fecha){
	$fecha_dia = substr($fecha, 8, 2);
	$fecha_mes = substr($fecha, 5, 2);
	$fecha_anio = substr($fecha, 0, 4);
	$arr_estimado_dia = calcular_estimado_por_dia($fecha);

	if(is_array($arr_estimado_dia)){
		for($i = 0; $i < sizeof($arr_estimado_dia); $i++){
			switch($i){
				case '0': $venta_est_meta_100 = $valor; break;

				case '1': $utilidad_est_meta_100 = $valor; break;

				case '2': $venta_est_meta_75 = $valor; break;

				case '3': $utilidad_est_meta_75 = $valor; break;

				case '4': $venta_est_meta_50 = $valor; break;

				case '5': $utilidad_est_meta_50 = $valor; break;

				case '6': $venta_est_meta_25 = $valor; break;

				case '7': $utilidad_est_meta_25 = $valor; break;

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
				if($fecha_calculada_valores["wday"] == 6){
					$val_calculo_dia = $val_calculo_dia + 0.5;
				}else{
					$val_calculo_dia++;
				}
			}
		}

		$venta_dia_meta_100 = $venta_est_meta_100*$val_calculo_dia;
		$utilidad_dia_meta_100 = $utilidad_est_meta_100*$val_calculo_dia;
		$venta_dia_meta_75 = $venta_est_meta_75*$val_calculo_dia;
		$utilidad_dia_meta_75 = $utilidad_est_meta_75*$val_calculo_dia;
		$venta_dia_meta_50 = $venta_est_meta_50*$val_calculo_dia;
		$utilidad_dia_meta_50 = $utilidad_est_meta_50*$val_calculo_dia;
		$venta_dia_meta_25 = $venta_est_meta_25*$val_calculo_dia;
		$utilidad_dia_meta_25 = $utilidad_est_meta_25*$val_calculo_dia;

		$div_vt_meta_100_75 = $venta_dia_meta_100-(($venta_dia_meta_100-$venta_dia_meta_75)/2);
		$div_ut_meta_100_75 = $utilidad_dia_meta_100-(($utilidad_dia_meta_100-$utilidad_dia_meta_75)/2);
		$div_vt_meta_75_50 = $venta_dia_meta_75-(($venta_dia_meta_75-$venta_dia_meta_50)/2);
		$div_ut_meta_75_50 = $utilidad_dia_meta_75-(($utilidad_dia_meta_75-$utilidad_dia_meta_50)/2);
		$div_vt_meta_50_25 = $venta_dia_meta_50-(($venta_dia_meta_50-$venta_dia_meta_25)/2);
		$div_ut_meta_50_25 = $utilidad_dia_meta_50-(($utilidad_dia_meta_50-$utilidad_dia_meta_25)/2);

		$arr_dia_valor = array(
			"venta_dia_meta_100" => $venta_dia_meta_100, "utilidad_dia_meta_100" => $utilidad_dia_meta_100, 
			"venta_dia_meta_75" => $venta_dia_meta_75, "utilidad_dia_meta_75" => $utilidad_dia_meta_75, 
			"venta_dia_meta_50" => $venta_dia_meta_50, "utilidad_dia_meta_50" => $utilidad_dia_meta_50, 
			"venta_dia_meta_25" => $venta_dia_meta_25, "utilidad_dia_meta_25" => $utilidad_dia_meta_25, 
			"div_vt_meta_100_75" => $div_vt_meta_100_75, "div_ut_meta_100_75" => $div_ut_meta_100_75, 
			"div_vt_meta_75_50" => $div_vt_meta_75_50, "div_ut_meta_75_50" => $div_ut_meta_75_50, 
			"div_vt_meta_50_25" => $div_vt_meta_50_25, "div_ut_meta_50_25" => $div_ut_meta_50_25, 
		);
	}else{
		$arr_dia_valor = array(
			"venta_dia_meta_100" => NULL, "utilidad_dia_meta_100" => NULL, 
			"venta_dia_meta_75" => NULL, "utilidad_dia_meta_75" => NULL, 
			"venta_dia_meta_50" => NULL, "utilidad_dia_meta_50" => NULL, 
			"venta_dia_meta_25" => NULL, "utilidad_dia_meta_25" => NULL, 
			"div_vt_meta_100_75" => NULL, "div_ut_meta_100_75" => NULL, 
			"div_vt_meta_75_50" => NULL, "div_ut_meta_75_50" => NULL, 
			"div_vt_meta_50_25" => NULL, "div_ut_meta_50_25" => NULL, 
		);
	}
	
	return $arr_dia_valor;

}

//Calcular acumulado de empleado hasta el dia seleccionado
function calcular_dia_agente($agente, $fecha){
	$conexion = new ConexionBDD();

	$tabla = "factura";
	$condicion = "agente = '".$agente."' AND fechaf = '".$fecha."' ORDER BY fechaf";

	$sql = "SELECT subtotal, cost, tc FROM ".$tabla." WHERE ".$condicion;

	$arr_venta = $conexion->obtenerDatos($sql);

	$dato_venta = 0;
	$dato_costeo = 0;

	foreach ($arr_venta as $dia) {
		$dato_venta = $dato_venta + (bcmul($dia["subtotal"], $dia["tc"], 2));
		$dato_costeo = $dato_costeo + (bcmul($dia["cost"], $dia["tc"], 2));
	}

	$arr_dia = array(
		'venta_dia' => $dato_venta, 
		'costeo_dia' => $dato_costeo
	);
	return $arr_dia;
}

//Calcular acumulado de empleado hasta el dia seleccionado
function calcular_acumulado_agente($agente, $fecha){
	$conexion = new ConexionBDD();
	$fecha_mes = substr($fecha, 5, 2);
	$fecha_anio = substr($fecha, 0, 4);
	$fecha_inicial = $fecha_anio."-".$fecha_mes."-01";

	$tabla = "factura";
	$condicion = "agente = '".$agente."' AND fechaf BETWEEN '".$fecha_inicial."' AND '".$fecha."' ORDER BY fechaf";

	$sql = "SELECT subtotal, cost, tc FROM ".$tabla." WHERE ".$condicion;

	$arr_venta = $conexion->obtenerDatos($sql);

	$dato_venta = 0;
	$dato_costeo = 0;

	foreach ($arr_venta as $dia) {
		$dato_venta = $dato_venta + (bcmul($dia["subtotal"], $dia["tc"], 2));
		$dato_costeo = $dato_costeo + (bcmul($dia["cost"], $dia["tc"], 2));
	}

	$arr_acumulado = array(
		'venta_acumulado' => $dato_venta, 
		'costeo_acumulado' => $dato_costeo
	);
	return $arr_acumulado;
}

//Calcular total del dia
function calcular_dia_total($fecha){
	$conexion = new ConexionBDD();

	$tabla = "factura";
	$condicion = "fechaf = '".$fecha."' ORDER BY fechaf";

	$sql = "SELECT subtotal, cost, tc FROM ".$tabla." WHERE ".$condicion;

	$arr_venta = $conexion->obtenerDatos($sql);

	$dato_venta = 0;
	$dato_costeo = 0;

	foreach ($arr_venta as $dia) {
		$dato_venta = $dato_venta + (bcmul($dia["subtotal"], $dia["tc"], 2));
		$dato_costeo = $dato_costeo + (bcmul($dia["cost"], $dia["tc"], 2));
	}

	$arr_total = array(
		'venta_total_dia' => $dato_venta, 
		'costeo_total_dia' => $dato_costeo
	);
	return $arr_total;
}

//Calcular acumulado total hasta el dia seleccionado
function calcular_acumulado_total($fecha){
	$conexion = new ConexionBDD();
	$fecha_mes = substr($fecha, 5, 2);
	$fecha_anio = substr($fecha, 0, 4);
	$fecha_inicial = $fecha_anio."-".$fecha_mes."-01";

	$tabla = "factura";
	$condicion = "fechaf BETWEEN '".$fecha_inicial."' AND '".$fecha."' ORDER BY fechaf";

	$sql = "SELECT subtotal, cost, tc FROM ".$tabla." WHERE ".$condicion;

	$arr_venta = $conexion->obtenerDatos($sql);
	//var_dump($arr_venta);
	$dato_venta = 0;
	$dato_costeo = 0;

	foreach ($arr_venta as $dia) {
		$dato_venta = $dato_venta + (bcmul($dia["subtotal"], $dia["tc"], 2));
		$dato_costeo = $dato_costeo + (bcmul($dia["cost"], $dia["tc"], 2));
	}

	$arr_acumulado = array(
		'venta_total_acumulado' => $dato_venta, 
		'costeo_total_acumulado' => $dato_costeo
	);
	return $arr_acumulado;
}

//Comprobar el numero de registros de ventas con la misma fecha
function comprobar_num_agentes($fecha){
	$conexion = new ConexionBDD();

	$tabla = "factura";
	$campo = "COUNT(agente)";
	$condicion = "fechaf = '".$fecha."'";

	$dato = $conexion->resultadoUnico($tabla, $campo, $condicion);

	return $dato;
}

//Obtener las fechas validas del lapso del reporte
function obtener_fechas($fecha_inicio, $fecha_final){
	$conexion = new ConexionBDD();

	$sql = "SELECT DISTINCT(fechaf) FROM factura WHERE fechaf BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' ORDER BY fechaf";
	$arr_fecha = $conexion->obtenerDatos($sql);

	return $arr_fecha;
}

//Comprobar el numero de registros de ventas con la misma fecha
function obtener_agentes($fecha){
	$conexion = new ConexionBDD();
	$sql = "SELECT DISTINCT(agente) FROM factura WHERE fechaf = '".$fecha."' ORDER BY agente";
	$arr_dato = $conexion->obtenerDatos($sql);

	return $arr_dato;
}

//Definir estructura de reporte
function estructura_reporte_venta($opc_emp, $opc_total){
	$opcF = 0;
	if(isset($opc_emp)){
		$opcF = $opcF + $opc_emp;
	}
	if(isset($opc_total)){
		$opcF = $opcF + $opc_total;
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

	$fecha_inicio = $_POST["fecha_inicio"];
	$fecha_final = $_POST["fecha_final"];

	if($bandera == true){
		//Formulario para realizar la exportación a PDF ?>
		<div class="col-xs-12 text-center btn_pdf">
			<form action="enviar_info.php" method="post" id="form_exportar_pdf" target="_blank">
				<input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
				<input type="hidden" name="fecha_final" id="fecha_final" value="<?php echo $fecha_final; ?>">
				<?php if (isset($_POST["rep_estimado"])): ?>
					<input type="hidden" name="rep_estimado" id="rep_estimado" value="<?php echo $_POST['rep_estimado']; ?>">
				<?php endif ?>
				<?php if (isset($_POST["rep_empleado"])): ?>
					<input type="hidden" name="rep_empleado" id="rep_empleado" value="<?php echo $_POST['rep_empleado']; ?>">
				<?php endif ?>
				<?php if (isset($_POST["rep_gastos"])): ?>
					<input type="hidden" name="rep_gastos" id="rep_gastos" value="<?php echo $_POST['rep_gastos']; ?>">
				<?php endif ?>
				<?php if (isset($_POST["rep_total"])): ?>
					<input type="hidden" name="rep_total" id="rep_total" value="<?php echo $_POST['rep_total']; ?>">
				<?php endif ?>
				<input type="hidden" name="op" id="op" value="9">
				<button class="btn btn-primary" id="btn_exportar_pdf">Exportar PDF</button>
			</form>
		</div>
		
		<?php

		//Recuperar registros de fechas
		$arr_fechas = obtener_fechas($fecha_inicio, $fecha_final);

		foreach ($arr_fechas as $data) {
			//Extraer la fecha del valor actual
			$fecha = $data["fechaf"];
			$fecha_dia = intval(substr($fecha, 8, 2));
			$fecha_mes = intval(substr($fecha, 5, 2));
			$fecha_anio = intval(substr($fecha, 0, 4));

			//Mostrar día de cada reporte
			$fecha_calculada = mktime(NULL, NULL, NULL, $fecha_mes, $fecha_dia, $fecha_anio);
			$fecha_calculada_valores = getdate($fecha_calculada);
			//Mostrar los titulos de las tablas dependiendo la seleccion que el usuario haya hecho
			$opc = estructura_reporte_venta($_POST["rep_empleado"], $_POST["rep_total"]);
			?>
			<div class="panel panel-primary del-padding col-xs-12">
				<div class="panel-heading">
					<h5 class='bold del-padding'><?php echo dia($fecha_calculada_valores["wday"])." ".$fecha_dia." de ".mes($fecha_mes)." del ".$fecha_anio; ?></h5>
				</div>
				<div class="panel-body del-padding">
					<div class="col-xs-1 del-padding">
						<h5 class="bold">DATOS</h5>
					</div>
					<div class="col-xs-2 del-padding inicio_columna">
						<h5 class="bold">VENTA</h5>
					</div>
					<div class="col-xs-2 del-padding inicio_columna">
						<h5 class="bold">UTILIDAD</h5>
					</div>
					<div class="col-xs-3 del-padding inicio_columna">
						<h5 class="bold">ESTIMADO</h5>
					</div>
					<div class="col-xs-4 del-padding inicio_columna">
						<h5 class="bold">GASTOS</h5>
					</div>

					<div class='col-xs-1 text-center del-padding'>
						<h5 class='negro bold'><?php echo $fecha_dia."-".$fecha_mes."-".$fecha_anio; ?></h5>
					</div>
					
					<div class='col-xs-1 text-center del-padding inicio_columna'>
						<h5 class='negro bold'>Al día</h5>
					</div>

					<div class='col-xs-1 text-center del-padding'>
						<h5 class='negro bold'>Acumulado</h5>
					</div>

					<div class='col-xs-1 text-center del-padding inicio_columna'>
						<h5 class='negro bold'>Al día</h5>
					</div>

					<div class='col-xs-1 text-center del-padding'>
						<h5 class='negro bold'>Acumulado</h5>
					</div>

					<div class="col-xs-1 text-center del-padding inicio_columna">
						<h5 class="negro bold">Venta Día</h5>
					</div>

					<div class="col-xs-1 text-center del-padding">
						<h5 class="negro bold">Utilidad Día</h5>
					</div>

					<div class="col-xs-1 text-center del-padding">
						<h5 class="negro bold">Utilidad Mes</h5>
					</div>

					<div class="col-xs-1 text-center del-padding inicio_columna">
						<h5 class="negro bold">Gasto</h5>
					</div>

					<div class="col-xs-1 text-center del-padding">
						<h5 class="negro bold">Gasto</h5>
					</div>

					<div class="col-xs-1 text-center del-padding">
						<h5 class="negro bold">Gasto</h5>
					</div>

					<div class="col-xs-1 text-center del-padding">
						<h5 class="negro bold">Gasto</h5>
					</div>
			<?php
			$lista_agentes = obtener_agentes($fecha);
			?>
				<div class="col-xs-1 del-padding">
					<?php
					foreach ($lista_agentes as $agente) {
					?>
						<div class="col-xs-12 del-padding">
							<h5 class='negro bold'><?php echo $agente["agente"]; ?></h5>
						</div>
					<?php
					}
					?>
					<div class='col-xs-12 text-center del-padding'>
						<h5 class='negro bold'>Total</h5>
					</div>
				</div>

				<div class="col-xs-4 del-padding">
			<?php
			foreach($lista_agentes as $agente){
				$dia_emp = calcular_dia_agente($agente["agente"], $fecha);
				$acumulado_emp = calcular_acumulado_agente($agente["agente"], $fecha);
			?>
					<div class='col-xs-3 text-center del-padding inicio_columna'>
						<h5 class='negro bold'><?php echo formato_moneda($dia_emp["venta_dia"]); ?></h5>
					</div>

					<div class='col-xs-3 text-center del-padding'>
						<h5 class='negro bold'><?php echo formato_moneda($acumulado_emp["venta_acumulado"]); ?></h5>
					</div>

					<div class='col-xs-3 text-center del-padding inicio_columna'>
						<h5 class='negro bold'><?php echo formato_moneda($dia_emp["costeo_dia"]); ?></h5>
					</div>

					<div class='col-xs-3 text-center del-padding'>
						<h5 class='negro bold'><?php echo formato_moneda($acumulado_emp["costeo_acumulado"]); ?></h5>
					</div>
			<?php
			}
			//Mostrar la tabla de las ventas totales si así lo haya decidido el usuario
			$datos_dia_total = calcular_dia_total($fecha);
			$datos_acumulado_total = calcular_acumulado_total($fecha);
			$vt_acumulado = $datos_acumulado_total["venta_total_acumulado"];
			$ct_acumulado = $datos_acumulado_total["costeo_total_acumulado"];
			?>
					<div class='col-xs-3 text-center del-padding inicio_columna'>
						<h5 class='negro bold'><?php echo formato_moneda($datos_dia_total["venta_total_dia"]); ?></h5>
					</div>

					<div class='col-xs-3 text-center del-padding'>
						<h5 class='negro bold'><?php echo formato_moneda($datos_dia_total["costeo_total_dia"]); ?></h5>
					</div>

					<div class='col-xs-3 text-center del-padding inicio_columna'>
						<h5 class='negro bold'><?php echo formato_moneda($vt_acumulado); ?></h5>
					</div>

					<div class='col-xs-3 text-center del-padding'>
						<h5 class='negro bold'><?php echo formato_moneda($ct_acumulado); ?></h5>
					</div>
				</div>

				<div class="col-xs-3 del-padding inicio_columna">
			<?php
			//Mostrar la tabla de estimado si es que el usuario lo haya decidido
			if(isset($_POST["rep_estimado"])){
				$arr_val_estimado = calcular_estimado_dia($fecha);
				if($arr_val_estimado != NULL){
					//Verificación de la meta respecto a la utilidad realizada
					if($ct_acumulado > $arr_val_estimado["div_ut_meta_100_75"]){
						if($ct_acumulado > $arr_val_estimado["utilidad_dia_meta_100"]){
							$clase_meta_100 = "meta_arriba";
						}else{
							$clase_meta_100 = "meta_abajo";
						}
					}else{
						$clase_meta_100 = "sin_meta";
					}

					//Cambio de color de letra respecto a la meta
					if($clase_meta_100 != "sin_meta"){
						$clase_letra_meta_100 = "blanco ";
					}else{
						$clase_letra_meta_100 = "negro ";
					}
					?>
					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_100; ?>">
						<h5 class="<?php echo $clase_letra_meta_100; ?>bold"><?php echo formato_moneda($arr_val_estimado["venta_dia_meta_100"]); ?></h5>
					</div>

					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_100; ?>">
						<h5 class="<?php echo $clase_letra_meta_100; ?>bold"><?php echo formato_moneda($arr_val_estimado["utilidad_dia_meta_100"]); ?></h5>
					</div>

					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_100; ?>">
						<h5 class="<?php echo $clase_letra_meta_100; ?>bold">700k</h5>
					</div>
					
					<?php
					//Verificación de la meta respecto a la utilidad realizada
					if($ct_acumulado > $arr_val_estimado["div_ut_meta_75_50"] && $ct_acumulado < $arr_val_estimado["div_ut_meta_100_75"]){
						if($ct_acumulado > $arr_val_estimado["utilidad_dia_meta_75"]){
							$clase_meta_75 = "meta_arriba";
						}else{
							$clase_meta_75 = "meta_abajo";
						}
					}else{
						$clase_meta_75 = "sin_meta";
					}

					//Cambio de color de letra respecto a la meta
					if($clase_meta_75 != "sin_meta"){
						$clase_letra_meta_75 = "blanco ";
					}else{
						$clase_letra_meta_75 = "negro ";
					}
					?>
					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_75; ?>">
						<h5 class="<?php echo $clase_letra_meta_75; ?>bold"><?php echo formato_moneda($arr_val_estimado["venta_dia_meta_75"]); ?></h5>
					</div>

					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_75; ?>">
						<h5 class="<?php echo $clase_letra_meta_75; ?>bold"><?php echo formato_moneda($arr_val_estimado["utilidad_dia_meta_75"]); ?></h5>
					</div>

					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_75; ?>">
						<h5 class="<?php echo $clase_letra_meta_75; ?>bold">500k</h5>
					</div>
					
					<?php
					//Verificación de la meta respecto a la utilidad realizada
					if($ct_acumulado > $arr_val_estimado["div_ut_meta_50_25"] && $ct_acumulado < $arr_val_estimado["div_ut_meta_75_50"]){
						if($ct_acumulado > $arr_val_estimado["utilidad_dia_meta_50"]){
							$clase_meta_50 = "meta_arriba";
						}else{
							$clase_meta_50 = "meta_abajo";
						}
					}else{
						$clase_meta_50 = "sin_meta";
					}
					
					//Cambio de color de letra respecto a la meta
					if($clase_meta_50 != "sin_meta"){
						$clase_letra_meta_50 = "blanco ";
					}else{
						$clase_letra_meta_50 = "negro ";
					}
					?>
					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_50; ?>">
						<h5 class="<?php echo $clase_letra_meta_50; ?>bold"><?php echo formato_moneda($arr_val_estimado["venta_dia_meta_50"]); ?></h5>
					</div>

					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_50; ?>">
						<h5 class="<?php echo $clase_letra_meta_50; ?>bold"><?php echo formato_moneda($arr_val_estimado["utilidad_dia_meta_50"]); ?></h5>
					</div>

					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_50; ?>">
						<h5 class="<?php echo $clase_letra_meta_50; ?>bold">450k</h5>
					</div>
					
					<?php
					//Verificación de la meta respecto a la utilidad realizada
					if($ct_acumulado < $arr_val_estimado["div_ut_meta_50_25"]){
						if($ct_acumulado > $arr_val_estimado["utilidad_dia_meta_25"]){
							$clase_meta_25 = "meta_arriba";
						}else{
							$clase_meta_25 = "meta_abajo";
						}
					}else{
						$clase_meta_25 = "sin_meta";
					}
					
					//Cambio de color de letra respecto a la meta
					if($clase_meta_25 != "sin_meta"){
						$clase_letra_meta_25 = "blanco ";
					}else{
						$clase_letra_meta_25 = "negro ";
					}
					?>
					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_25; ?>">
						<h5 class="<?php echo $clase_letra_meta_25; ?>bold"><?php echo formato_moneda($arr_val_estimado["venta_dia_meta_25"]); ?></h5>
					</div>

					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_25; ?>">
						<h5 class="<?php echo $clase_letra_meta_25; ?>bold"><?php echo formato_moneda($arr_val_estimado["utilidad_dia_meta_25"]); ?></h5>
					</div>

					<div class="col-xs-4 text-center del-padding <?php echo $clase_meta_25; ?>">
						<h5 class="<?php echo $clase_letra_meta_25; ?>bold">400k</h5>
					</div>
				</div>
			<?php
				}
			}
			?>
			  	</div>
			</div>
			<?php
		}
		?>

		<?php //Formulario para realizar la exportación a PDF ?>
		<div class="col-xs-12 text-center btn_pdf">
			<form action="enviar_info.php" method="post" id="form_exportar_pdf" target="_blank">
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
				<input type="hidden" name="op" id="op" value="9">
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
//!--Operacion 8 - FIN

//Operacion 9 - Exportar a PDF
//Función principal para exportar a PDF
function exportar_pdf(){
	//Conexion a la base de datos
	$conexion = new ConexionBDD();
	$fecha_inicio = $_POST["fecha_inicio"];
	$fecha_final = $_POST["fecha_final"];

	//Nombre por default del archivo generado
	$filename = str_replace("-", "", $fecha_inicio)."_".str_replace("-", "", $fecha_final).".pdf";

	//Recuperar la cantidad de fechas existentes en la elección hecha por el usuario
	$arr_fechas = obtener_fechas($fecha_inicio, $fecha_final);
	//$fecha_ant = "";

	$html = "<!DOCTYPE html>
			<html>
			<head>
				<meta charset='UTF-8'>
				<link rel='stylesheet' href='css/style.css'>
				<title>".$filename."</title>
			</head>
			<body>";

	foreach ($arr_fechas as $data) {
		//Extraer la fecha del valor actual
		$fecha = $data["fechaf"];
		$fecha_dia = intval(substr($fecha, 8, 2));
		$fecha_mes = intval(substr($fecha, 5, 2));
		$fecha_anio = intval(substr($fecha, 0, 4));
		
	//Titulos de cada sección
		$html = $html."
		<table class='datos_tabla'>
				<tr>
					<th class='text-center w_7 cont_columna'>FECHA</th>
					<th class='text-center w_9 cont_columna'>VENTA</th>
					<th class='text-center w_9'></th>
					<th class='text-center w_9 cont_columna'>UTILIDAD</th>
					<th class='text-center w_9'></th>
					<th class='text-center w_9 cont_columna'>ESTIMADO</th>
					<th class='text-center w_9'></th>
					<th class='text-center w_3'></th>
					<th class='text-center w_8 cont_columna' colspan='2'>GASTO REAL</th>
					<th class='text-center w_8 cont_columna' colspan='2'>GASTO ESTIMADO</th>
				</tr>
				<tr>
					<th class='text-center w_7 cont_columna'>".$fecha_dia."-".$fecha_mes."-".$fecha_anio."</th> 
					<th class='text-center w_9 cont_columna'>Al día</th>
					<th class='text-center w_9 cont_columna'>Acumulada</th>
					<th class='text-center w_9 cont_columna'>Al día</th>
					<th class='text-center w_9 cont_columna'>Acumulada</th>
					<th class='text-center w_9 cont_columna'>Venta</th>
					<th class='text-center w_9 cont_columna'>Utilidad</th>
					<th class='text-center w_3 cont_columna'>Mes</th>
					<th class='text-center w_8 cont_columna'>Al día</th>
					<th class='text-center w_8 cont_columna'>Acumulado</th>
					<th class='text-center w_8 cont_columna'>Al día</th>
					<th class='text-center w_8 cont_columna'>Acumulado</th>
				</tr>";

		$lista_agentes = obtener_agentes($fecha);
		$arr_val_estimado = calcular_estimado_dia($fecha);
		$contador = 0;

		foreach ($lista_agentes as $agente) {
			$dia_emp = calcular_dia_agente($agente["agente"], $fecha);
			$acumulado_emp = calcular_acumulado_agente($agente["agente"], $fecha);

			$html = $html."
				<tr>
					<td class='text-center cont_columna w_7'>".$agente["agente"]."</td>
					<td class='text-right cont_columna w_9'>".formato_moneda($dia_emp["venta_dia"])."</td>
					<td class='text-right cont_columna w_9'>".formato_moneda($acumulado_emp["venta_acumulado"])."</td>
					<td class='text-right cont_columna w_9'>".formato_moneda($dia_emp["costeo_dia"])."</td>
					<td class='text-right cont_columna w_9'>".formato_moneda($acumulado_emp["costeo_acumulado"])."</td>";

			switch ($contador) {
				case 0:
					$html = $html."
					<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["venta_dia_meta_100"])."</td>
					<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["utilidad_dia_meta_100"])."</td>
					<td class='text-center w_3 cont_columna bold'>700</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					";
					break;
				
				case 1:
					$html = $html."
					<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["venta_dia_meta_75"])."</td>
					<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["utilidad_dia_meta_75"])."</td>
					<td class='text-center w_3 cont_columna bold'>500</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					";
					break;
				
				case 2:
					$html = $html."
					<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["venta_dia_meta_50"])."</td>
					<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["utilidad_dia_meta_50"])."</td>
					<td class='text-center w_3 cont_columna bold'>450</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					";
					break;
				
				case 3:
					$html = $html."
					<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["venta_dia_meta_25"])."</td>
					<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["utilidad_dia_meta_25"])."</td>
					<td class='text-center w_3 cont_columna bold'>400</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					";
					break;
				
				default:
					$html = $html."
					<td class='text-right w_9 cont_columna'>-</td>
					<td class='text-right w_9 cont_columna'>-</td>
					<td class='text-center w_3 cont_columna bold'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					<td class='text-center w_8 cont_columna'>-</td>
					";
					break;
			}
			$html = $html."</tr>";
			$contador++;
		}


		$datos_dia_total = calcular_dia_total($fecha);
		$datos_acumulado_total = calcular_acumulado_total($fecha);
		$vt_acumulado = $datos_acumulado_total["venta_total_acumulado"];
		$ct_acumulado = $datos_acumulado_total["costeo_total_acumulado"];

		$bandera = false;
		for(; $contador < 4; $contador++){

		$html = $html."<tr>";
			switch ($contador) {
				case 0:
				if($bandera == false){
					$html = $html."
						<td class='text-center w_7 cont_columna bold'>Total</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($datos_dia_total["venta_total_dia"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($vt_acumulado)."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($datos_dia_total["costeo_total_dia"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($ct_acumulado)."</td>
					";	
					$bandera = true;
				}else{
					$html = $html."
						<td class='text-center w_7 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
					";
				}
					$html = $html."
						<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["venta_dia_meta_100"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["utilidad_dia_meta_100"])."</td>
						<td class='text-center w_3 cont_columna bold'>700</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
					";
					break;
				
				case 1:
				if($bandera == false){
					$html = $html."
						<td class='text-center w_7 cont_columna bold'>Total</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($datos_dia_total["venta_total_dia"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($vt_acumulado)."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($datos_dia_total["costeo_total_dia"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($ct_acumulado)."</td>
					";	
					$bandera = true;
				}else{
					$html = $html."
						<td class='text-center w_7 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
					";
				}
					$html = $html."
						<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["venta_dia_meta_75"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["utilidad_dia_meta_75"])."</td>
						<td class='text-center w_3 cont_columna bold'>500</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
					";
					break;
				
				case 2:
				if($bandera == false){
					$html = $html."
						<td class='text-center w_7 cont_columna bold'>Total</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($datos_dia_total["venta_total_dia"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($vt_acumulado)."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($datos_dia_total["costeo_total_dia"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($ct_acumulado)."</td>
					";	
					$bandera = true;
				}else{
					$html = $html."
						<td class='text-center w_7 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
					";
				}
					$html = $html."
						<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["venta_dia_meta_50"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["utilidad_dia_meta_50"])."</td>
						<td class='text-center w_3 cont_columna bold'>450</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
					";
					break;
				
				case 3:
				if($bandera == false){
					$html = $html."
						<td class='text-center w_7 cont_columna bold'>Total</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($datos_dia_total["venta_total_dia"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($vt_acumulado)."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($datos_dia_total["costeo_total_dia"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($ct_acumulado)."</td>
					";	
					$bandera = true;
				}else{
					$html = $html."
						<td class='text-center w_7 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
						<td class='text-center w_9 cont_columna'>-</td>
					";
				}
					$html = $html."
						<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["venta_dia_meta_25"])."</td>
						<td class='text-right w_9 cont_columna'>".formato_moneda($arr_val_estimado["utilidad_dia_meta_25"])."</td>
						<td class='text-center w_3 cont_columna bold'>400</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
					";
					break;
				
				default:
					$html = $html."
						<td class='text-right w_9 cont_columna'>-</td>
						<td class='text-right w_9 cont_columna'>-</td>
						<td class='text-center w_3 cont_columna bold'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
						<td class='text-center w_8 cont_columna'>-</td>
					";
					break;
			}
		}

		$html = $html."</tr>";

		$html = $html."</table>";
	}

		/*
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
		""div_vt_2000k_1500k" => $div_vt_2000k_1500k, "div_ut_2000k_1500k" => $div_ut_2000k_1500k, 
		"div_vt_1500k_1000k" => $div_vt_1500k_1000k, "div_ut_1500k_1000k" => $div_ut_1500k_1000k, 
		"div_vt_1000k_700k" => $div_vt_1000k_700k, "div_ut_1000k_700k" => $div_ut_1000k_700k,
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
				
		""div_vt_2000k_1500k" => $div_vt_2000k_1500k, "div_ut_2000k_1500k" => $div_ut_2000k_1500k, 
		"div_vt_1500k_1000k" => $div_vt_1500k_1000k, "div_ut_1500k_1000k" => $div_ut_1500k_1000k, 
		"div_vt_1000k_700k" => $div_vt_1000k_700k, "div_ut_1000k_700k" => $div_ut_1000k_700k,

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
		";	}*/
	$html = $html."
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
			ingresar_meta_mensual();
			break;

		case 8:
			generar_reporte();
			break;

		case 9:
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