
			<div class="tab-pane" role="tabpanel" id="generar_reporte">
				<section class="row">
					<form method="post" id="form_generar_reporte" action="enviar_info.php">
						<input type="hidden" name="user" id="user" value="<?php echo $user; ?>">
						<input type="hidden" name="op" id="op" value="7">
						<div class="col-xs-3 text-center titulo_elegir_mes fecha">
							<label for="mes_inicio">Fecha de inicio</label>
							<input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required>
						</div>
						<div class="col-xs-3 text-center titulo_elegir_mes fecha">
							<label for="mes_inicio">Fecha de terminación</label>
							<input type="date" class="form-control" name="fecha_final" id="fecha_final" required>
						</div>
						<div class="col-xs-6 text-center titulo_elegir_mes">
							<div class="col-xs-6">
								<label><input type="checkbox" name="rep_empleado" id="rep_empleado" value="1"> Ventas/Utilidades totales</label>
							</div>
							<div class="col-xs-6">
								<label><input type="checkbox" name="rep_total" id="rep_total" value="2"> Ventas/Utilidades por empleado</label>
							</div>
							<div class="col-xs-6">
								<label><input type="checkbox" name="rep_estimado" id="rep_estimado" value="1"> Estimados</label>
							</div>
							<div class="col-xs-6">
								<label><input type="checkbox" name="rep_gastos" id="rep_gastos" value="1"> Gastos</label>
							</div>
						</div>
						<div class="col-xs-12 text-center titulo_elegir_mes btn-espacio">
							<input type="submit" id="btn_generar_reporte" value="Generar reporte" class="btn btn-primary">
						</div>
					</form>
				</section>
				
				<section class="col-xs-12 contenido">
					
				</section>
			</div>
		</div>
	</div>