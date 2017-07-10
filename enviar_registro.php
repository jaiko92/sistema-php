<?php 
	//Inserción en BDD
	$dia = $_POST["dia"];
	$nom_dia = $_POST["nom_dia"];
	$mes = $_POST["mes"];
	$anio = $_POST["anio"];
	$empleado = $_POST["empleado"];
	$venta = $_POST["venta_dia"];
	$utilidad = $_POST["utilidad_dia"];
	$gasto = $_POST["gasto_real_dia"];
	$gasto_estimado = $_POST["gasto_estimado_dia"];

	if($nom_dia != 0){
		$nueva_conexion = new mysqli("localhost", "root", "", "projectbt");
		if($nueva_conexion->connect_errno){
			echo "Fallo al conectar a la base de datos: (".$nueva_conexion->connect_errno.")".$nueva_conexion->connect_err;
			echo "Reporte este fallo con el administrador del sistema";
		}
		//echo $nueva_conexion->host_info." <br>";
		$query_r = "SELECT * FROM projectbt.dia WHERE fecha_dia='".$dia."' AND fecha_mes='".$mes."' AND fecha_anio='".$anio."' AND num_empleado='".$empleado."'";

		$resultado_r = $nueva_conexion->query($query_r);

		if($resultado_r->num_rows == 0){

			$query_r = "INSERT INTO projectbt.dia (num_empleado, fecha_dia, fecha_mes, fecha_anio, venta_dia, utilidad_dia, gasto_real_dia, gasto_estimado_dia) VALUES ('".$empleado."', '".$dia."', '".$mes."', '".$anio."', '".$venta."', '".$utilidad."', '".$gasto."', '".$gasto_estimado."')";

			if ($nueva_conexion->query($query_r) === TRUE) {

				$nueva_conexion->close();

				unset($_POST);
			    echo "<!DOCTYPE html>
			    <html lang='es'>
			    <head>
			    	<meta charset='UTF-8'>
			    	<title>Document</title>
			    	<script type='text/javascript'>
				    alert('Nuevo valor añadido');
					location.href='index.php';
					</script>
				    </head>
			    <body>
			    	
			    </body>
			    </html>";
			} else {
			    echo "ERROR GRAVE, COMUNICARSE CON EL ADMINISTRADOR";
			}
		}else{
			$nueva_conexion->close();

			unset($_POST);
		    echo "<!DOCTYPE html>
		    <html lang='es'>
		    <head>
		    	<meta charset='UTF-8'>
		    	<title>Document</title>
		    	<script type='text/javascript'>
			    alert('Ya se añadieron las cuentas de este empleado de este día');
				location.href='index.php';
				</script>
			    </head>
		    <body>
		    	
		    </body>
		    </html>";
		}
	}else{
		unset($_POST);
	    echo "<!DOCTYPE html>
	    <html lang='es'>
	    <head>
	    	<meta charset='UTF-8'>
	    	<title>Document</title>
	    	<script type='text/javascript'>
		    alert('No se pueden registrar cuentas en dias de descanso');
			location.href='index.php';
			</script>
		    </head>
	    <body>
	    	
	    </body>
	    </html>";
	}
?>