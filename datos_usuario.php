<?php 
	$datos = obtener_datos_usuario($user);
	foreach ($datos as $data) {
		$usuario = $data;
	}
?>

<!--Inicio de pagina-->
			<div class="tab-pane" role="tabpanel" id="datos_usuario">
				<section class="row">
					<div class="col-md-4 col-md-offset-4 text-center container-xs-vertical">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h2 class="panel-title"><?php echo $nombre." ".$usuario["apellido"]; ?></h2>
							</div>
							<div class="panel-body">
								<label for="nickname">Nombre de usuario: </label>
								<input type="text" name="nickname" class="form-control" id="nickname" value="<?php echo $usuario['nickname']; ?>" readonly>
								<label for="email">Correo electronico: </label>
								<input type="email" name="email" class="form-control" id="email" value="<?php echo $usuario['correo']; ?>" readonly>
								<label for="posicion">Posición: </label>
								<input type="text" name="posicion" class="form-control" id="posicion" value="<?php echo puesto($permiso); ?>" readonly>
								<div class="btn-espacio">
									<input type="button" id="#editar_info" class="btn btn-primary" value="Editar información">
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
