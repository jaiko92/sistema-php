<?php
	session_start(); 
	if(isset($_SESSION['idUsuario'], $_SESSION['nombreUsuario'])){
		echo "<script language='javascript'>
				location.href='menu_trabajo.php'
			</script>";
	}
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Unishare - Registro</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<script src="resources/jquery-1.12.4.js"></script>

	<script>
		$(document).ready(function(){
			$("#btn-register").click(function(){
				$.ajax({
					data: $("#operacionRegistro").serialize(),
					url: 'resources/operacionRegistro.php', type: 'post',
					success: function(data) { $(".messageRegister").html(data); } 
				});
				return false;
			});	

			$("#btn-access").click(function(){
				$.ajax({
					data: $("#operacionAcceso").serialize(),
					url: 'resources/operacionAcceso.php', type: 'post',
					success: function(data) { $(".messageAccess").html(data); } 
				});
				return false;
			});	
		});
	</script>
</head>
<body>

	<header>

		<div class="container">

			<img src="img/logo_unishare_completo.jpg" alt="">

			<div class="btn-register-welcome">
				<div class="btn-header">
					<a href="#" id="registrarse">Bienvenido</a>
				</div>

			</div>

		</div>

	</header>

	<div class="container">
		
		<section class="banner-des">

			<!--<img src="img/bannerDes.jpg" alt="">-->

			<div class="banner-register">

				<div class="register-text">

					<h2>Si no tienes cuenta,</h2>
					<h1>REGISTRATE</h1>
					<div class="messageRegister"></div>
					<form id="operacionRegistro" method="POST">
						<input type="text" name="nombre" placeholder="Nombre">
						<input type="text" name="paterno" placeholder="Apellido Paterno">
						<input type="text" name="materno" placeholder="Apellido Materno">
						<input type="email" name="correo" placeholder="Correo Electronico">
						<input type="password" name="contraseña" placeholder="Contraseña">
						<input type="password" name="C_contraseña" placeholder="Confirmar Contraseña">
						<input id="btn-register" type="submit" value="Registrarse">
					</form>

				</div>

				<div class="session-text">

					<h2>Si ya tienes cuenta,</h2>
					<h1>INICIA SESIÓN</h1>
					<div class="messageAccess"></div>
					<form id="operacionAcceso" method="POST">
						<input type="email" name="correo" placeholder="Correo Electronico">
						<input type="password" name="contraseña" placeholder="Contraseña">
						<input id="btn-access" type="submit" value="Iniciar sesión">
					</form>
					
				</div>

			</div>
				
		</section>

	</div>

		<footer class="register">

			<div class="container">

				<div class="logos">
					<div class="logo_universidad">
						<img src="img/logo_utj.png" width="128px" alt="">
					</div>
					<div class="logo_universidad">
						<img src="img/logo_sicyt.png" width="64px"  alt="">
					</div>
					<div class="logo_universidad">
						<img src="img/logo_utp.png" width="64px"  alt="">
					</div>
				</div>
	

				<div class="info">
					<p>Desarrollado por BitByDev<br>contacto@bitbydev.com<br>
(044) 331 138 4156</p>
				</div>
			</div>

		</footer>	
		
</body>
</html>