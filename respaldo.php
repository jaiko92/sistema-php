
			<div class="tab-pane active" role="tabpanel" id="elegir_mes">
				<section class="row">
					<div class="col-xs-12 text-center titulo_elegir_mes">
						<form method="post" action="" id="form_elegir_mes">
							<input type="month" name="mes_elegido" id="mes_elegido" required>

							<input type="submit" value="Enviar" id="enviar_mes">
						</form>
					</div>
				</section>
		

<?php 
	if(!empty($_POST)){

		//Calculo de Venta Acumulada, Utilidad Acumulada y Utilidad Mensual eligiendo mes
		//Recoleccion de datos del mes

		if($_POST["mes_elegido"] != ""){

		$mes_elegido = substr($_POST["mes_elegido"], 5);
		$anio_elegido = substr($_POST["mes_elegido"], 0, -3);

		$cantidad_dias_mes_elegido = cal_days_in_month(CAL_GREGORIAN, $mes_elegido, $anio_elegido);

		//Calculo de días habiles en el mes elegido
		$dias_habiles_sel = $cantidad_dias_mes_elegido;
		for($i = 1; $i <= $cantidad_dias_mes_elegido; $i++){
			$fecha_calculada_sel = mktime(NULL, NULL, NULL, $mes_elegido, $i, $anio_elegido);
			$fecha_calculada_valores_sel = getdate($fecha_calculada_sel);
			//echo $i." Valor de I <br>";
			if($fecha_calculada_valores_sel["wday"] == 0){
				//echo $dias_habiles." Valor dia habil <br> <br> ";
				$dias_habiles_sel--;
			}else{
				if($fecha_calculada_valores_sel["wday"] == 6){
					$dias_habiles_sel = $dias_habiles_sel - 0.5;
				}
			}
		}

		unset($_POST);
		//Estimación de ventas en base a la cantidad de días habiles en el mes
		//Venta diaria con 2000k
		$venta_diaria_estimada_sel_2000k = 2000000/$dias_habiles_sel;
		$utilidad_diaria_estimada_sel_2000k = 700000/$dias_habiles_sel;
		//Venta diaria con 1500k
		$venta_diaria_estimada_sel_1500k = 1500000/$dias_habiles_sel;
		$utilidad_diaria_estimada_sel_1500k = 500000/$dias_habiles_sel;
		//Venta diaria con 1000k
		$venta_diaria_estimada_sel_1000k = 1000000/$dias_habiles_sel;
		$utilidad_diaria_estimada_sel_1000k = 455000/$dias_habiles_sel;
		//Venta diaria con 700k
		$venta_diaria_estimada_sel_700k = 700000/$dias_habiles_sel;
		$utilidad_diaria_estimada_sel_700k = 400000/$dias_habiles_sel;

		//Estimacion de ventas por dia en base a la estimación
		$dias_habiles_sel = 0;

		$nueva_conexion_sel = new mysqli("localhost", "root", "", "projectbt");
		if($nueva_conexion_sel->connect_errno){
			echo "Fallo al conectar a la base de datos: (".$nueva_conexion_sel->connect_errno.")".$nueva_conexion_sel->connect_err;
			echo "Reporte este fallo con el administrador del sistema";
		}
		//echo $nueva_conexion->host_info." <br>";

		$query2_sel = "SELECT DISTINCT num_empleado FROM projectbt.dia";
		$resultado2_sel = $nueva_conexion_sel->query($query2_sel);
		foreach ($resultado2_sel as $r2_sel) {
			$venta_empleado_sel[$r2_sel["num_empleado"]] = 0;
			$utilidad_empleado_sel[$r2_sel["num_empleado"]] = 0;
			$gasto_real_empleado_sel[$r2_sel["num_empleado"]] = 0;
			$gasto_estimado_empleado_sel[$r2_sel["num_empleado"]] = 0;
		}

?>

				<section class="row">
					<div class="col-xs-12 text-center titulo_fecha">
						<h4>Mes elegido</h4>
						<h4><?php echo mes($fecha_calculada_valores_sel["mon"]); ?></h4>
					</div>

				<?php 
					$venta_acumulada_total_sel = 0;
					$utilidad_acumulada_total_sel = 0;
					$gasto_real_acumulado_total_sel = 0;
					$gasto_estimado_acumulado_total_sel = 0;

					for($i = 1; $i <= $cantidad_dias_mes_elegido; $i++){

						$venta_dia_total_sel = 0;
						$utilidad_dia_total_sel = 0;
						$gasto_real_dia_total_sel = 0;
						$gasto_estimado_dia_total_sel = 0;

						$query_sel = "SELECT num_empleado, venta_dia, utilidad_dia FROM projectbt.dia WHERE fecha_mes='".$mes_elegido."' AND fecha_anio='".$anio_elegido."' AND fecha_dia = '".$i."' ORDER BY num_empleado ASC";
						$resultado_sel = $nueva_conexion_sel->query($query_sel);
				?>
				<?php
						$fecha_calculada_sel = mktime(NULL, NULL, NULL, $mes_elegido, $i, $anio_elegido);
						$fecha_calculada_valores_sel = getdate($fecha_calculada_sel);
						echo "<div class='col-xs-12 text-center subtitulo_fecha'><h5>".dia($fecha_calculada_valores_sel["wday"])." ".$i." de ".mes($fecha_calculada_valores_sel["mon"])." del ".$fecha_calculada_valores_sel["year"]."</h5></div>";
						if($fecha_calculada_valores_sel["wday"] != 0){
							if($resultado_sel->num_rows != 0){
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

								if($fecha_calculada_valores_sel["wday"] == 6){
									$dias_habiles_sel = $dias_habiles_sel + 0.5;
								}else{
									$dias_habiles_sel++;
								}
								
								foreach($resultado_sel as $r_sel){

				?>
				<!--Ventas y utilidades-->
					<!--Valores-->
					<div class="col-xs-12 col-md-1 col-sm-6 text-center subtitulo_venta">
						<h4 class="blanco bold"><?php echo "# ".$r_sel["num_empleado"]; ?></h4>
					</div>

					<div class='col-xs-12 col-md-2 col-sm-6 text-center subtitulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($r_sel["venta_dia"], 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
						<h4 class="blanco bold"><?php 
						$venta_dia_total_sel = $venta_dia_total_sel+$r_sel["venta_dia"];
						$venta_acumulada_total_sel = $venta_acumulada_total_sel+$r_sel["venta_dia"];
						$venta_empleado_sel[$r_sel["num_empleado"]] = $venta_empleado_sel[$r_sel["num_empleado"]]+floatval($r_sel["venta_dia"]);
						echo "$ ".number_format($venta_empleado_sel[$r_sel["num_empleado"]], 2); 
						?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($r_sel["utilidad_dia"], 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_venta'>
						<h4 class="blanco bold"><?php 
						$utilidad_dia_total_sel = $utilidad_dia_total_sel+$r_sel["utilidad_dia"];
						$utilidad_acumulada_total_sel = $utilidad_acumulada_total_sel+$r_sel["utilidad_dia"];
						$utilidad_empleado_sel[$r_sel["num_empleado"]] = $utilidad_empleado_sel[$r_sel["num_empleado"]]+floatval($r_sel["utilidad_dia"]);
						echo "$ ".number_format($utilidad_empleado_sel[$r_sel["num_empleado"]], 2); 
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
						<h4 class="blanco bold"><?php echo "$ ".number_format($venta_dia_total_sel, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($venta_acumulada_total_sel, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($utilidad_dia_total_sel, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_venta'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($utilidad_acumulada_total_sel, 2); ?></h4>
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
								$query_sel = "SELECT num_empleado, gasto_real_dia, gasto_estimado_dia FROM projectbt.dia WHERE fecha_mes='".$mes_elegido."' AND fecha_anio='".$anio_elegido."' AND fecha_dia = '".$i."' ORDER BY num_empleado ASC";
								$resultado_sel = $nueva_conexion_sel->query($query_sel);

								foreach ($resultado_sel as $r_sel) {
					?>

				<!--Gastos-->
					<!--Valores-->
					<div class="col-xs-12 col-md-1 col-sm-6 text-center subtitulo_gasto">
						<h4 class="blanco bold"><?php echo "# ".$r_sel["num_empleado"]; ?></h4>
					</div>

					<div class='col-xs-12 col-md-2 col-sm-6 text-center subtitulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($r_sel["gasto_real_dia"], 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_gasto'>
						<h4 class="blanco bold"><?php 
						$gasto_real_dia_total_sel = $gasto_real_dia_total_sel+$r_sel["gasto_real_dia"];
						$gasto_real_acumulado_total_sel = $gasto_real_acumulado_total_sel+$r_sel["gasto_real_dia"];
						$gasto_real_empleado_sel[$r_sel["num_empleado"]] = $gasto_real_empleado_sel[$r_sel["num_empleado"]]+floatval($r_sel["gasto_real_dia"]);
						echo "$ ".number_format($gasto_real_empleado_sel[$r_sel["num_empleado"]], 2); 
						?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($r_sel["gasto_estimado_dia"], 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center subtitulo_gasto'>
						<h4 class="blanco bold"><?php 
						$gasto_estimado_dia_total_sel = $gasto_estimado_dia_total_sel+$r_sel["gasto_estimado_dia"];
						$gasto_estimado_acumulado_total_sel = $gasto_estimado_acumulado_total_sel+$r_sel["gasto_estimado_dia"];
						$gasto_estimado_empleado_sel[$r_sel["num_empleado"]] = $gasto_estimado_empleado_sel[$r_sel["num_empleado"]]+floatval($r_sel["gasto_estimado_dia"]);
						echo "$ ".number_format($gasto_estimado_empleado_sel[$r_sel["num_empleado"]], 2); 
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
						<h4 class="blanco bold"><?php echo "$ ".number_format($gasto_real_dia_total_sel, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($gasto_real_acumulado_total_sel, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($gasto_estimado_dia_total_sel, 2); ?></h4>
					</div>

					<div class='col-xs-12 col-md-3 col-sm-6 text-center titulo_gasto'>
						<h4 class="blanco bold"><?php echo "$ ".number_format($gasto_estimado_acumulado_total_sel, 2); ?></h4>
					</div>

					<!--Estimaciones totales-->
					<?php 
						$estimacion_700k_1000k_sel = ($utilidad_diaria_estimada_sel_700k*$dias_habiles_sel) + ((($utilidad_diaria_estimada_sel_1000k*$dias_habiles_sel)-($utilidad_diaria_estimada_sel_700k*$dias_habiles_sel))/2);
						$estimacion_1000k_1500k_sel = ($utilidad_diaria_estimada_sel_1000k*$dias_habiles_sel) + ((($utilidad_diaria_estimada_sel_1500k*$dias_habiles_sel)-($utilidad_diaria_estimada_sel_1000k*$dias_habiles_sel))/2);
						$estimacion_1500k_2000k_sel = ($utilidad_diaria_estimada_1500k*$dias_habiles_sel) + ((($utilidad_diaria_estimada_sel_2000k*$dias_habiles_sel)-($utilidad_diaria_estimada_sel_1500k*$dias_habiles_sel))/2);
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
					if($estimacion_700k_1000k_sel > $utilidad_acumulada_total_sel){
						echo "'estimado_aprox ";
						}else{
							echo "'subtitulo_estimacion ";
							} ?>
						col-xs-12 text-center'>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($venta_diaria_estimada_sel_700k*$dias_habiles_sel, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($utilidad_diaria_estimada_sel_700k*$dias_habiles_sel, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold">400</h4>
						</div>
					</div>

					<div class=<?php 
					if($estimacion_1000k_1500k_sel > $utilidad_acumulada_total_sel and $estimacion_700k_1000k_sel < $utilidad_acumulada_total_sel){
						echo "'estimado_aprox ";
						}else{
							echo "'subtitulo_estimacion ";
							} ?>
						col-xs-12 text-center'>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($venta_diaria_estimada_sel_1000k*$dias_habiles_sel, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($utilidad_diaria_estimada_sel_1000k*$dias_habiles_sel, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold">450</h4>
						</div>
					</div>

					<div class=<?php 
					if($estimacion_1500k_2000k_sel > $utilidad_acumulada_total_sel and $estimacion_1000k_1500k_sel < $utilidad_acumulada_total_sel){
						echo "'estimado_aprox ";
						}else{
							echo "'subtitulo_estimacion ";
							} ?>
						col-xs-12 text-center'>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($venta_diaria_estimada_sel_1500k*$dias_habiles_sel, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($utilidad_diaria_estimada_sel_1500k*$dias_habiles_sel, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold">500</h4>
						</div>
					</div>

					<div class=<?php 
					if($estimacion_1500k_2000k_sel < $utilidad_acumulada_total_sel){
						echo "'estimado_aprox ";
						}else{
							echo "'subtitulo_estimacion ";
							} ?>
						col-xs-12 text-center'>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($venta_diaria_estimada_sel_2000k*$dias_habiles_sel, 2); ?></h4>
						</div>

						<div class="col-xs-4 col-md-4 col-sm-6 text-center">
							<h4 class="negro bold"><?php echo "$ ".number_format($utilidad_diaria_estimada_sel_2000k*$dias_habiles_sel, 2); ?></h4>
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

	$nueva_conexion_sel->close();
		 			?>
				</section>

			</div>
		</div>
	</div>

<?php 
		}//if-Verificando datos vacios 
	}//if-Verificando POST vacio
 ?>