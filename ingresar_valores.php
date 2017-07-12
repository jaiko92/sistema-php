
			<div class="tab-pane" role="tabpanel" id="ingresar_valores">
				<section class="row">
					<div class="col-xs-12 text-center titulo_fecha">
						<h4>Fecha de hoy</h4>
						<h4><?php echo dia($hoy["wday"])." ".$hoy["mday"]." de ".mes($hoy["mon"])." del ".$hoy["year"]; ?></h4>
					</div>

					<form method="post" id="form_envio_venta">
						<div class="col-sm-6 col-md-4 text-center formulario">
							<input type="hidden" name="operacion" id="operacion" value="1">
							<label for="fecha" class="negro">Fecha:</label><br>
							<input type="date" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>"><br>

							<label for="empleado" class="negro">Empleado:</label><br>
							<select name="empleado" id="empleado" required>
							   <option value="1">Empleado #1</option> 
							   <option value="2">Empleado #2</option> 
							   <option value="3">Empleado #3</option>
							   <option value="4">Empleado #4</option>
							   <option value="5">Empleado #5</option>
							   <option value="6">Empleado #6</option>
							</select><br> 
							
							<label for="venta_dia" class="negro">Venta del día:</label><br>
							<input type="number" id="venta_dia" name="venta_dia" step=".01" min="0" required><br>

							<input type="submit" value="Enviar" id="envio_venta">
						</div><br>
					</form>

					<form method="post" id="form_envio_utilidad">
						<div class="col-sm-6 col-md-4 text-center formulario">
							<input type="hidden" name="operacion" id="operacion" value="2">
							<label for="fecha" class="negro">Fecha:</label><br>
							<input type="date" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>"><br>

							<label for="empleado" class="negro">Empleado:</label><br>
							<select name="empleado" id="empleado" required>
							   <option value="1">Empleado #1</option> 
							   <option value="2">Empleado #2</option> 
							   <option value="3">Empleado #3</option>
							   <option value="4">Empleado #4</option>
							   <option value="5">Empleado #5</option>
							   <option value="6">Empleado #6</option>
							</select><br> 
							
							<label for="utilidad_dia" class="negro">Utilidad del día:</label><br>
							<input type="number" id="utilidad_dia" name="utilidad_dia" step=".01" min="0" required><br>
						
							<input type="submit" value="Enviar" id="envio_utilidad">
						</div><br>
					</form>

					<form method="post" id="form_envio_gastos">
						<div class="col-sm-6 col-md-4 text-center formulario">
							<input type="hidden" name="operacion" id="operacion" value="3">
							<label for="fecha" class="negro">Fecha:</label><br>
							<input type="date" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>"><br>

							<label for="gasto_real_dia" class="negro">Gastos reales del día:</label><br>
							<input type="number" id="gasto_real_dia" name="gasto_real_dia" step=".01" min="0" required><br>

							<label for="gasto_estimado_dia" class="negro">Gastos estimados del día:</label><br>
							<input type="number" id="gasto_estimado_dia" name="gasto_estimado_dia" step=".01" min="0" required><br>

							<input type="submit" value="Enviar" id="envio_gastos">
						</div><br>
					</form>
				</section>
			</div>
