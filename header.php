<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Sistema administrativo</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>

</head>
<body>

	<div class="container-fluid">
		<header class="row">
			<div class="col-xs-12 text-center cabecera">
				<h4>Project PHP - Bootstrap</h4>
			</div>
			<nav class="col-xs-12">
				<ul class="nav nav-tabs nav-justified menu_nav" role="tablist">
					<li role="presentation" class="elemento_menu_nav"><a href="#ingresar_valores" aria-controls="ingresar_valores" role="tab" data-toggle="tab">Ingresar valores</a></li>
					<li role="presentation" class="elemento_menu_nav"><a href="#calcular_valores" aria-controls="calcular_valores" role="tab" data-toggle="tab">Calcular valores del mes actual</a></li>
					<li role="presentation" class="active elemento_menu_nav"><a href="#elegir_mes" aria-controls="elegir_mes" role="tab" data-toggle="tab">Elegir mes para calcular</a></li>
				</ul>
			</nav>
		</header>

		<div class="tab-content">