
			<div class="tab-pane active" role="tabpanel" id="ingresar_valores">
				<section class="row">
					<div class="col-xs-12 text-center titulo_fecha">
						<h4><?php echo dia($hoy["wday"])." ".$hoy["mday"]." de ".mes($hoy["mon"])." del ".$hoy["year"]; ?></h4>
					</div>
					
					<div class="col-xs-12 text-center contenido">
						<!-- Aquí va el mensaje de que se ha realizado el envio de información correctamente -->
					</div>

					<form method="post" id="form_envio_venta" class="form_envio">
					<div class="col-xs-12 text-center">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">Ingresa los valores de la venta correspondiente al día seleccionado</h4>
							</div>
							<div class="panel-body">
								<div class="col-xs-12 text-center">
									<div class="form-inline">
										<input type="hidden" name="op" id="op" value="1">
										<input type="hidden" name="usuario" id="usuario" value="<?php echo $user ?>">
										<div class="form-group">
											<label for="fecha" class="negro">Fecha:</label>
											<input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>" required>
										</div>
										<div class="form-group">
											<label for="empleado" class="negro">Empleado:</label>
											<select name="empleado" class="form-control" id="empleado" required>
											   <option value="1">Empleado #1</option> 
											   <option value="2">Empleado #2</option> 
											   <option value="3">Empleado #3</option>
											   <option value="4">Empleado #4</option>
											   <option value="5">Empleado #5</option>
											   <option value="6">Empleado #6</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-xs-12 text-center">
									<div class="form-inline">
										<div class="form-group">
											<label for="venta_dia" class="negro">Venta del día:</label>
											<input type="number" class="form-control" id="venta_dia" name="venta_dia" step=".01" min="0">
										</div>
										<div class="form-group">
											<label for="utilidad_dia" class="negro">Utilidad del día:</label>
											<input type="number" class="form-control" id="utilidad_dia" name="utilidad_dia" step=".01" min="0">
										</div>
									</div>
								</div>
								<div class="col-xs-12 text-center btn-espacio">
									<input type="submit" value="Enviar" id="envio_venta" class="btn_envio btn btn-primary">
								</div>
							</div>
						</div>
					</div>
					</form>

					<form method="post" id="form_envio_gastos" class="form_envio">
					<div class="col-xs-12 text-center">
						<div class="panel panel-danger">
							<div class="panel-heading">
								<h4 class="panel-title">Ingresa los valores de los gastos correspondientes al día seleccionado</h4>
							</div>
							<div class="panel-body">
								<div class="col-xs-12">
									<div class="form-inline">
										<input type="hidden" name="op" id="op" value="2">
										<input type="hidden" name="usuario" id="usuario" value="<?php echo $user ?>">
										<div class="form-group">
											<label for="fecha" class="negro">Fecha:</label>
											<input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>" required>
										</div>
										<div class="form-group">
											<label for="gasto_real_dia" class="negro">Gastos reales del día:</label>
											<input type="number" class="form-control" id="gasto_real_dia" name="gasto_real_dia" step=".01" min="0">
										</div>
										<div class="form-group">
											<label for="gasto_estimado_dia" class="negro">Gastos estimados del día:</label>
											<input type="number" class="form-control" id="gasto_estimado_dia" name="gasto_estimado_dia" step=".01" min="0">
										</div>
									</div>
									<div class="col-xs-12 text-center btn-espacio">
										<input type="submit" value="Enviar" id="envio_gastos" class="btn_envio btn btn-danger">
									</div>
								</div>
							</div>
						</div>
					</div>
					</form>
				</section>
			</div>
