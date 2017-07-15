<?php 
//Destruir sesiones
session_start();

session_destroy();
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Iniciar sesión</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>

	<script>
	//Enviar petición tipo POST sin necesidad de recargar la página
		$(document).ready(function(){
			$("#btn_envio").click(function(){
				$.ajax({
					data: $("#envio_form_sesion").serialize(),
					url: 'enviar_info.php', type: 'post',
					success: function(data) {
						$("#mensaje").html(data);
					}//data
				});//ajax
				return false;
			});//click-function
		});//ready-function
	</script>
</head>
<body>
	<div class="container-fluid">
		<header class="row">
			<div class="col-md-12 text-center container-vertical">
				<h2>Sistema administrativo en PHP</h2>
			</div>
			<div class="col-md-4 col-md-offset-4 text-center">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">Inicia sesión con tu usuario</h2>
					</div>
					<div class="panel-body">
						<form method="post" id="envio_form_sesion" class="form_envio">
							<input type="hidden" name="op" id="op" value="0">
							<input type="hidden" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>">
							<input type="hidden" name="hora" id="hora" value="<?php echo date('H:i:s'); ?>">
							<label for="usuario">Nombre de usuario</label><br>
							<input type="text" name="usuario" class="form-control" id="usuario" required><br>
							<label for="pass">Contraseña</label><br>
							<input type="password" name="pass" class="form-control" id="pass" required><br>
							<input type="submit" value="Iniciar sesión" class="btn btn-primary" id="btn_envio">
						</form>
						<div class="col-xs-12" id="mensaje">
							<!--Aquí se mostrará la advertencia dependiendo si son incorrectos los datos-->
						</div>
					</div>
				</div>
			</div>
		</header>
	</div>
</body>
</html>