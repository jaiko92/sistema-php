			<div class="tab-pane" role="tabpanel" id="pendientes">
				<section class="row">
					<form method="post" id="form_elegir_pendientes" class="form_envio">
						<div class="col-xs-12 text-center titulo_elegir_mes form-inline">
							<input type="hidden" name="op" id="op" value="3">
							<input type="hidden" name="usuario" id="usuario" value="<?php echo $user; ?>">
							<div class="form-group">
								<label><input type="checkbox" name="pen_venta_utilidad" id="pen_venta_utilidad" value="1"> Ventas y Utilidades pendientes</label>
							</div>
							<div class="form-group">
								<label><input type="checkbox" name="pen_venta" id="pen_venta" value="1"> Sólo ventas pendientes</label>
							</div>
							<div class="form-group">
								<label><input type="checkbox" name="pen_utilidad" id="pen_utilidad" value="1"> Sólo utilidades pendientes</label>
							</div>
							<div class="form-group">
								<label><input type="checkbox" name="pen_gastos" id="pen_gastos" value="1"> Gastos pendientes</label>
							</div>
						</div>
						<div class="col-xs-12 text-center titulo_elegir_mes btn-espacio">
							<input type="submit" value="Buscar pendientes" id="envio_pendientes" class="btn btn-primary">
						</div>
					</form>
				</section>
				
				<section class="col-xs-12 contenido">
						<!-- Aquí se muestra toda la información pedida -->
				</section>
			</div>