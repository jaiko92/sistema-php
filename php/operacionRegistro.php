<?php
	require 'validacionFormulario.php';

	$Nombre = $_POST['nombre'] ? $_POST['nombre'] : null;
	$Paterno = $_POST['paterno'] ? $_POST['paterno'] : null;
	$Materno = $_POST['materno'] ? $_POST['materno'] : null;
	$Correo = $_POST['correo'] ? $_POST['correo'] : null;
	$Contraseña = $_POST['contraseña'] ? $_POST['contraseña'] : null;
	$C_Contraseña = $_POST['C_contraseña'] ? $_POST['C_contraseña'] : null;

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$bandera = validaRequerido($Nombre);
		$bandera2 = validaRequerido($Paterno);
		$bandera3 = validaRequerido($Materno);
		$bandera4 = validaCorreo($Correo);
		$bandera5 = validaVacio($Contraseña);
		$bandera6 = validaVacio($C_Contraseña);

		if($bandera == true && $bandera2 == true && $bandera3 == true && $bandera4 == true && $bandera5 == true && $bandera6 == true){

			if($Contraseña == $C_Contraseña){

				$contraseñaEncrp = md5("unishare".$Contraseña."erahsinu");
				$nomTabla = "usuario";

				$conexion =  new ConexionBDD();
				$sql = "SELECT * FROM `$nomTabla` WHERE `correo` = '$Correo'";
				$bandera = $conexion->existeDato( $sql );

				if($bandera == false){

					echo "<p>CORREO YA REGISTRADO, UTILICE OTRO</p>";

				}else{

					$datos = array(
						array('nombre', $Nombre), array('paterno', $Paterno),
						array('materno', $Materno), array('correo', $Correo),
						array('contrasena', $contraseñaEncrp)
					);

					$conexion =  new ConexionBDD();

					$bandera = $conexion->insertarDato($nomTabla, $datos);

					if($bandera == true){
						echo "<script language='javascript'>
								
								var pagina='register.php'
								function redireccionar()
								{
									location.href=pagina
								}
								setTimeout ('redireccionar()', 5);
							</script>";

					}else{
						echo "<script language='javascript'>
								
								var pagina='register.php'
								function redireccionar()
								{
									location.href=pagina
								}
								setTimeout ('redireccionar()', 5);
							</script>";
					}
				}

			}else{
				echo "<p>ESCRIBE LA MISMA CONTRASEÑA EN AMBOS CAMPOS</p>";
			}

		}else{
			echo "<p>LLENA TODOS LOS CAMPOS CORRECTAMENTE</p>";
		}
	}
?>
