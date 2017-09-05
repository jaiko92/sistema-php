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
	<title>Unishare - Inicio</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

	<header>

		<div class="container">

			<img src="img/logo_unishare_completo.jpg" alt="">

			<div class="btn-register">
				<div class="btn-header">
					<a href="register.php" id="ingresar">Ingresar</a>
				</div>
			</div>

		</div>

	</header>

	<div class="container">
		
		<section class="banner">

			<div class="shadow"></div>
			<!--<img src="img/banner.jpg" alt="">-->

			<div class="banner-text">
				<div class="title">
					<h1>COMPARTE TUS ARCHIVOS DE FORMA RÁPIDA Y EFICAZ.</h1>
				</div>

				<div class="text">
					<p>Unishare es una aplicación web enfocada principalmente en la gestión de archivos. Con esta herramienta podrás compartir y acceder a tus archivos de forma sencilla desde cualquier dispositivo con acceso a internet.</p>
				</div>

				<div class="btn-banner">
					<a href="register.php">Únete a la comunidad</a>
				</div>
			</div>
				
		</section>

	</div>
	
	<section class="banner-green">

		<div class="container">

			<div class="feature">
				<img src="img/libreria.png" alt="">
				<h4>Comunidad universitaria</h4>
				<p>Una aplicación enfocada en resolver las necesidades de los universitarios.</p>
			</div>

			<div class="feature">
				<img src="img/descarga.png" alt="">
				<h4>Comparte fácilmente</h4>
				<p>Comparte carpetas y archivos inmediatamente después de subir tus documentos.</p>
			</div>

			<div class="feature">
				<img src="img/movil.png" alt="">
				<h4>Accede desde cualquier dispositivo</h4>
				<p>Diseño amigable y adaptable a cualquier dispositivo.</p>
			</div>

			<div class="feature">
				<img src="img/usuario.png" alt="">
				<h4>Colabora de forma
sencilla</h4>
				<p>Comparte un archivo con la comunidad sin tener que usar el correo electrónico.</p>
			</div>		

		</div>
	
	</section>

		<footer class="index">

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