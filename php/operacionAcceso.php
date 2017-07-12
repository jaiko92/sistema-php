<?php
	require 'validacionFormulario.php';

	$Correo = $_POST['correo'] ? $_POST['correo'] : null;
	$Contraseña = $_POST['contraseña'] ? $_POST['contraseña'] : null;

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$bandera = validaVacio($Correo);
		$bandera2 = validaVacio($Contraseña);

		if($bandera == true && $bandera2 == true){

			$bandera = validaCorreo($Correo);

			if($bandera == true){

				$nomTabla = "usuario";
				$conexion =  new ConexionBDD();
				$contraseñaEncrp = md5("unishare".$Contraseña."erahsinu");
				$sql = "SELECT * FROM `$nomTabla` WHERE `correo` = '$Correo' AND `contrasena` = '$contraseñaEncrp'";
				$bandera = $conexion->existeDato( $sql );

				if( $bandera == false ){

					$datos = $conexion->obtenerDatos( $sql );
					foreach ($datos as $p) {
						$r = $p;
					}

					$bandera = $r['estatus'];

					if($bandera != 0){
						session_start();

						$_SESSION["idUsuario"] = $r['idUsuario'];
						$_SESSION["nombreUsuario"] = $r['nombre']." ".$r['paterno']." ".$r['materno'];
						$_SESSION["permisoU"] = $r["permisoU"];

						echo "<script language='javascript'>
								
								var pagina='menu_trabajo.php'
								function redireccionar()
								{
									location.href=pagina
								}
								setTimeout ('redireccionar()', 5);
							</script>";	
					}else{
						echo "<p>AUN NO SE HA APROBADO LA SOLICITUD</p>";
					}
					
				}else{
					echo "<p>CORREO NO EXISTENTE O CONTRASEÑA INCORRECTA</p>";
				}

			}else{
				echo "<p>ESCRIBE UN CORREO VALIDO</p>";
			}

		}else{
			echo "<p>LLENA TODOS LOS CAMPOS</p>";
		}

	}
?>
