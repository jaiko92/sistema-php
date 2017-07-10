
			<div class="tab-pane" role="tabpanel" id="ingresar_valores">
				<section class="row">
					<div class="col-xs-12 text-center titulo_fecha">
						<h4>Fecha de hoy</h4>
						<h4><?php echo dia($hoy["wday"])." ".$hoy["mday"]." de ".mes($hoy["mon"])." del ".$hoy["year"]; ?></h4>
					</div>

					<form action="enviar_registro.php" method="post" id="form_ingresar_val">
						<input type="hidden" step="1" value="<?php echo $hoy["wday"]; ?>" id="nom_dia" name="nom_dia" > 
						<input type="hidden" step="1" value="<?php echo $hoy["mday"]; ?>" id="dia" name="dia" > 
						<input type="hidden" step="1" value="<?php echo $hoy["mon"]; ?>" id="mes" name="mes" > 
						<input type="hidden" step="1" value="<?php echo $hoy["year"]; ?>" id="anio" name="anio" >
						
						<div class="col-sm-6 col-md-12 text-center enviar_empleado">
							<label for="empleado" class="blanco">Empleado:</label><br>
							<select name="empleado" id="empleado" required>
							   <option value="1">Empleado #1</option> 
							   <option value="2">Empleado #2</option> 
							   <option value="3">Empleado #3</option>
							   <option value="4">Empleado #4</option>
							   <option value="5">Empleado #5</option>
							   <option value="6">Empleado #6</option>
							</select><br> 
						</div><br>

						<div class="col-sm-6 text-center enviar_valores">
							<label for="venta_dia" class="blanco">Venta del día:</label><br>
							<input type="number" id="venta_dia" name="venta_dia" step=".01" min="0" required>
						</div><br>

						<div class="col-sm-6 text-center enviar_valores">
							<label for="utilidad_dia" class="blanco">Utilidad del día:</label><br>
							<input type="number" id="utilidad_dia" name="utilidad_dia" step=".01" min="0" required>
						</div><br>

						<div class="col-sm-6 text-center enviar_valores">
							<label for="gasto_real_dia" class="blanco">Gastos reales del día:</label><br>
							<input type="number" id="gasto_real_dia" name="gasto_real_dia" step=".01" min="0" required>
						</div><br>

						<div class="col-sm-6 text-center enviar_valores">
							<label for="gasto_estimado_dia" class="blanco">Gastos estimados del día:</label><br>
							<input type="number" id="gasto_estimado_dia" name="gasto_estimado_dia" step=".01" min="0" required>
						</div><br>

						<div class="col-xs-12 text-center btn_enviar">
							<input type="submit" value="Enviar">
						</div>
					</form>
				</section>
			</div>
