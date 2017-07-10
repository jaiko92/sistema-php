<?php 
//Variables iniciales
	//Calculo de Venta Acumulada, Utilidad Acumulada y Utilidad Mensual
	//Calculo del mes actual
	$fecha_actual = getdate();
	$anio_actual = $fecha_actual["year"];
	$mes_actual = $fecha_actual["mon"];
	$cantidad_dias_mes_actual = cal_days_in_month(CAL_GREGORIAN, $mes_actual, $anio_actual);

	//Calculo de días habiles en el mes actual
	$dias_habiles = $cantidad_dias_mes_actual;
	for($i = 1; $i <= $cantidad_dias_mes_actual; $i++){
		$fecha_calculada = mktime(NULL, NULL, NULL, $mes_actual, $i, $anio_actual);
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

	//Estimación de ventas en base a la cantidad de días habiles en el mes
	//Venta diaria con 2000k
	$venta_diaria_estimada_2000k = 2000000/$dias_habiles;
	$utilidad_diaria_estimada_2000k = 700000/$dias_habiles;
	//Venta diaria con 1500k
	$venta_diaria_estimada_1500k = 1500000/$dias_habiles;
	$utilidad_diaria_estimada_1500k = 500000/$dias_habiles;
	//Venta diaria con 1000k
	$venta_diaria_estimada_1000k = 1000000/$dias_habiles;
	$utilidad_diaria_estimada_1000k = 455000/$dias_habiles;
	//Venta diaria con 700k
	$venta_diaria_estimada_700k = 700000/$dias_habiles;
	$utilidad_diaria_estimada_700k = 400000/$dias_habiles;

	//Estimacion de ventas por dia en base a la estimación
	$dias_habiles = 0;

	$nueva_conexion = new mysqli("localhost", "root", "", "projectbt");
	if($nueva_conexion->connect_errno){
		echo "Fallo al conectar a la base de datos: (".$nueva_conexion->connect_errno.")".$nueva_conexion->connect_err;
		echo "Reporte este fallo con el administrador del sistema";
	}
	//echo $nueva_conexion->host_info." <br>";

	$query2 = "SELECT DISTINCT num_empleado FROM projectbt.dia";
	$resultado2 = $nueva_conexion->query($query2);
	foreach ($resultado2 as $r2) {
		$venta_empleado[$r2["num_empleado"]] = 0;
		$utilidad_empleado[$r2["num_empleado"]] = 0;
		$gasto_real_empleado[$r2["num_empleado"]] = 0;
		$gasto_estimado_empleado[$r2["num_empleado"]] = 0;
	}
	
?>

<!--Inicio de pagina-->
			<div class="tab-pane" role="tabpanel" id="calcular_valores">
				<section class="row">
					<div class="col-xs-12 text-center titulo_fecha">
						<h4>Mes actual</h4>
						<h4><?php echo mes($fecha_calculada_valores["mon"]); ?></h4>
					</div>

				<?php 
					$venta_acumulada_total = 0;
					$utilidad_acumulada_total = 0;
					$gasto_real_acumulado_total = 0;
					$gasto_estimado_acumulado_total = 0;

					for($i = 1; $i <= $cantidad_dias_mes_actual; $i++){

						$venta_dia_total = 0;
						$utilidad_dia_total = 0;
						$gasto_real_dia_total = 0;
						$gasto_estimado_dia_total = 0;

						$query = "SELECT num_empleado, venta_dia, utilidad_dia FROM projectbt.dia WHERE fecha_mes='".$mes_actual."' AND fecha_anio='".$anio_actual."' AND fecha_dia = '".$i."' ORDER BY num_empleado ASC";
						$resultado = $nueva_conexion->query($query);
				?>
				<?php
						$fecha_calculada = mktime(NULL, NULL, NULL, $mes_actual, $i, $anio_actual);
						$fecha_calculada_valores = getdate($fecha_calculada);
						echo "<div class='col-xs-12 text-center subtitulo_fecha'><h5>".dia($fecha_calculada_valores["wday"])." ".$i." de ".mes($fecha_calculada_valores["mon"])." del ".$fecha_calculada_valores["year"]."</h5></div>";
						if($fecha_calculada_valores["wday"] != 0){
							if($resultado->num_rows != 0){
				?>

					<!--Titulos de ventas-->
					<div class='col-xs-12 col-md-1 col-sm-6 text-center titulo_venta'>
						<h5 class="blanco">Num_Emp</h5>
					</div>

					<div class='col-xs-12 col-md-2 col-sm-6 text-center titulo_venta'>
						<h5 class="blanco">Venta_Dia</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h5 class="blanco">Venta_Acumulada</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h5 class="blanco">Utilidad_dia</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h5 class="blanco">Utilidad_acumulada</h5>
					</div>
					
				<?php

								if($fecha_calculada_valores["wday"] == 6){
									$dias_habiles = $dias_habiles + 0.5;
								}else{
									$dias_habiles++;
								}
								
								foreach($resultado as $r){

				?>
				<!--Ventas y utilidades-->
					<!--Valores-->
					<div class="col-xs-12 col-md-1 col-sm-6 text-center subtitulo_venta">
						<h4 class="blanco bold"><?php echo "# ".$r["num_empleado"]; ?></h4>
					</div>

					<div class='col-xs-12 col-md-2 col-sm-6 text-center subtitulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($r["venta_dia"], 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
						<h4 class="blanco bold"><?php 
						$venta_dia_total = $venta_dia_total+$r["venta_dia"];
						$venta_acumulada_total = $venta_acumulada_total+$r["venta_dia"];
						$venta_empleado[$r["num_empleado"]] = $venta_empleado[$r["num_empleado"]]+floatval($r["venta_dia"]);
						echo "$ ".number_format($venta_empleado[$r["num_empleado"]], 2); 
						?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($r["utilidad_dia"], 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
						<h4 class="blanco bold"><?php 
						$utilidad_dia_total = $utilidad_dia_total+$r["utilidad_dia"];
						$utilidad_acumulada_total = $utilidad_acumulada_total+$r["utilidad_dia"];
						$utilidad_empleado[$r["num_empleado"]] = $utilidad_empleado[$r["num_empleado"]]+floatval($r["utilidad_dia"]);
						echo "$ ".number_format($utilidad_empleado[$r["num_empleado"]], 2); 
						?></h4>
					</div>

					<?php
								}//foreach-ventas
					?>
					
					<!--Total ventas-->
					<div class="col-xs-12 col-md-1 col-sm-6 text-center titulo_venta">
						<h4 class="blanco bold">Total</h4>
					</div>

					<div class='col-xs-12 col-md-2 col-sm-6 text-center titulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($venta_dia_total, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($venta_acumulada_total, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($utilidad_dia_total, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($utilidad_acumulada_total, 2); ?></h4>
					</div>

					<!--Titulos de gastos-->
					<div class='col-xs-12 col-md-1 col-sm-6 text-center titulo_gasto'>
						<h5 class="blanco"> # </h5>
					</div>

					<div class='col-xs-12 col-md-2 col-sm-6 text-center titulo_gasto'>
						<h5 class="blanco">Gasto_Real_Dia</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h5 class="blanco">Gasto_Real_Acumulado</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h5 class="blanco">Gasto_Estimado_Dia</h5>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h5 class="blanco">Gasto_Estimado_Acumulado</h5>
					</div>

					<?php
								$query = "SELECT num_empleado, gasto_real_dia, gasto_estimado_dia FROM projectbt.dia WHERE fecha_mes='".$mes_actual."' AND fecha_anio='".$anio_actual."' AND fecha_dia = '".$i."' ORDER BY num_empleado ASC";
								$resultado = $nueva_conexion->query($query);

								foreach ($resultado as $r) {
					?>

				<!--Gastos-->
					<!--Valores-->
					<div class="col-xs-12 col-md-1 col-sm-6 text-center subtitulo_gasto">
						<h4 class="blanco bold"><?php echo "# ".$r["num_empleado"]; ?></h4>
					</div>

					<div class='col-xs-12 col-md-2 col-sm-6 text-center subtitulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($r["gasto_real_dia"], 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_gasto'>
						<h4 class="blanco bold"><?php 
						$gasto_real_dia_total = $gasto_real_dia_total+$r["gasto_real_dia"];
						$gasto_real_acumulado_total = $gasto_real_acumulado_total+$r["gasto_real_dia"];
						$gasto_real_empleado[$r["num_empleado"]] = $gasto_real_empleado[$r["num_empleado"]]+floatval($r["gasto_real_dia"]);
						echo "$ ".number_format($gasto_real_empleado[$r["num_empleado"]], 2); 
						?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($r["gasto_estimado_dia"], 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_gasto'>
						<h4 class="blanco bold"><?php 
						$gasto_estimado_dia_total = $gasto_estimado_dia_total+$r["gasto_estimado_dia"];
						$gasto_estimado_acumulado_total = $gasto_estimado_acumulado_total+$r["gasto_estimado_dia"];
						$gasto_estimado_empleado[$r["num_empleado"]] = $gasto_estimado_empleado[$r["num_empleado"]]+floatval($r["gasto_estimado_dia"]);
						echo "$ ".number_format($gasto_estimado_empleado[$r["num_empleado"]], 2); 
						?></h4>
					</div>
					
				<?php 
								}//foreach-gastos
				 ?>
					
					<!--Total gastos-->
					<div class="col-xs-12 col-md-1 col-sm-6 text-center titulo_gasto">
						<h4 class="blanco bold">Total</h4>
					</div>

					<div class='col-xs-12 col-md-2 col-sm-6 text-center titulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($gasto_real_dia_total, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($gasto_real_acumulado_total, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($gasto_estimado_dia_total, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($gasto_estimado_acumulado_total, 2); ?></h4>
					</div>

					<!--Estimaciones totales-->
					<?php 
						$estimacion_700k_1000k = ($utilidad_diaria_estimada_700k*$dias_habiles) + ((($utilidad_diaria_estimada_1000k*$dias_habiles)-($utilidad_diaria_estimada_700k*$dias_habiles))/2);
						$estimacion_1000k_1500k = ($utilidad_diaria_estimada_1000k*$dias_habiles) + ((($utilidad_diaria_estimada_1500k*$dias_habiles)-($utilidad_diaria_estimada_1000k*$dias_habiles))/2);
						$estimacion_1500k_2000k = ($utilidad_diaria_estimada_1500k*$dias_habiles) + ((($utilidad_diaria_estimada_2000k*$dias_habiles)-($utilidad_diaria_estimada_1500k*$dias_habiles))/2);
					 ?>

					<div class='col-xs-12 text-center subtitulo_estimacion'>
						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h5 class="negro bold">Venta_Dia_Estimada</h5>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h5 class="negro bold">Utilidad_Dia_Estimada</h5>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h5 class="negro bold">Utilidad_Mes_Estimada</h5>
						</div>
					</div>

					<div class=<?php 
					if($estimacion_700k_1000k > $utilidad_acumulada_total){
						echo "'estimado_aprox ";
						}else{
							echo "'subtitulo_estimacion ";
							} ?>
						col-xs-12 text-center'>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($venta_diaria_estimada_700k*$dias_habiles, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($utilidad_diaria_estimada_700k*$dias_habiles, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold">400</h4>
						</div>
					</div>

					<div class=<?php 
					if($estimacion_1000k_1500k > $utilidad_acumulada_total and $estimacion_700k_1000k < $utilidad_acumulada_total){
						echo "'estimado_aprox ";
						}else{
							echo "'subtitulo_estimacion ";
							} ?>
						col-xs-12 text-center'>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($venta_diaria_estimada_1000k*$dias_habiles, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($utilidad_diaria_estimada_1000k*$dias_habiles, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold">450</h4>
						</div>
					</div>

					<div class=<?php 
					if($estimacion_1500k_2000k > $utilidad_acumulada_total and $estimacion_1000k_1500k < $utilidad_acumulada_total){
						echo "'estimado_aprox ";
						}else{
							echo "'subtitulo_estimacion ";
							} ?>
						col-xs-12 text-center'>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($venta_diaria_estimada_1500k*$dias_habiles, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($utilidad_diaria_estimada_1500k*$dias_habiles, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold">500</h4>
						</div>
					</div>

					<div class=<?php 
					if($estimacion_1500k_2000k < $utilidad_acumulada_total){
						echo "'estimado_aprox ";
						}else{
							echo "'subtitulo_estimacion ";
							} ?>
						col-xs-12 text-center'>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($venta_diaria_estimada_2000k*$dias_habiles, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($utilidad_diaria_estimada_2000k*$dias_habiles, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold">700</h4>
						</div>
					</div>

					<?php

							}/*ifresultados*/else{
								echo "<div class='col-xs-12 text-center venta_estimacion'><h5 class='negro'>No existen resultados</h5></div>";
							}//else
						}/*ifwday*/else{
							echo "<div class='col-xs-12 text-center subtitulo_descanso'><h4 class='negro'>Día de descanso</h4></div>";
						}//else
					}//for
					/*}*/

	$nueva_conexion->close();
		 			?>
				</section>
			</div>
