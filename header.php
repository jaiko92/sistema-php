<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Sistema administrativo</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui/jquery-ui.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/funciones.js"></script>
</head>
<body>

	<div class="container-fluid">
		<header class="row">
			<div class="col-xs-12 text-center cabecera">
				<h4 class="col-xs-12">Sistema ERP</h4>
				<h4 class="col-xs-4">Bienvenido "Nombre del usuario"</h4>
				<h4 class="col-xs-4">Puesto "Nombre del puesto"</h4>
				<h4 class="col-xs-4">Fecha de hoy</h4>
			</div>
			<nav class="col-xs-12">
				<ul class="nav nav-tabs nav-justified menu_nav" role="tablist">
					<li role="presentation" class="active elemento_menu_nav"><a href="#facturas_pendientes" aria-controls="facturas_pendientes" role="tab" data-toggle="tab">Facturas Pendientes</a></li>
					<li role="presentation" class="elemento_menu_nav"><a href="#facturas_canceladas" aria-controls="facturas_canceladas" role="tab" data-toggle="tab">Facturas Canceladas</a></li>
					<li role="presentation" class="elemento_menu_nav"><a href="#buscar_factura" aria-controls="buscar_factura" role="tab" data-toggle="tab">Buscar Factura</a></li>
					<li role="presentation" class="elemento_menu_nav"><a href="#metas_mensuales" aria-controls="metas_mensuales" role="tab" data-toggle="tab">Metas mensuales</a></li>
					<li role="presentation" class="elemento_menu_nav"><a href="#generar_reporte" aria-controls="generar_reporte" role="tab" data-toggle="tab">Generar Reporte de Venta</a></li>
				</ul>
			</nav>
		</header>

		<div class="tab-content">