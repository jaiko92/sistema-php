<!--Inicio de pagina-->
			<div class="tab-pane" role="tabpanel" id="datos_usuario" data-user="<?php echo $user; ?>">
				<section class="row">

					<div class="col-xs-12 text-center usuario">
						<!-- Aquí va la información del usuario -->
					</div>
					
					<div class="modal fade" id="editar_info" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Editar información</h4>
								</div>
								<form method="post" id="editar_usuario">
									<div class="modal-body">
									<div class="contenido"><!--Aquí se mostrará la advertencia en caso de ser necesario--></div>
										<input type="hidden" name="user" id="user" value="<?php echo $user; ?>">
										<input type="hidden" name="op" id="op" value="6">
										<div class="form-group">
											<label for="username">Nombre de usuario:</label>
											<input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>" required>
										</div>
										<div class="form-group">
											<label for="email">Correo electrónico:</label>
											<input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>" required>
										</div>
										<div class="form-group">
											<label for="old_pass">Antigua contraseña:</label>
											<input type="password" name="old_pass" id="old_pass" class="form-control" required>
										</div>
										<div class="form-group">
											<label for="new_pass">Nueva contraseña:</label>
											<input type="password" name="new_pass" id="new_pass" class="form-control" required>
										</div>
										<div class="form-group">
											<label for="new_pass2">Repita la nueva contraseña:</label>
											<input type="password" name="new_pass2" id="new_pass2" class="form-control" required>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										<button type="button submit" class="btn btn-primary" data-dismiss="modal" id="btn_editar_usuario">Guardar cambios</button>
									</div>
								</form>
							</div>
						</div>
					</div>

				</section>
			</div>
