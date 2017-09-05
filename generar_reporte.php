			<div class="tab-pane" role="tabpanel" id="generar_reporte">
				<section class="row">
					<div class="col-xs-12 text-center barra_busqueda">
						<h4>Elige los datos correspondientes al reporte que deseas generar.</h4>
						<form method="post" id="form_generar_reporte">
							<div class="col-xs-3">
								<label><input type="checkbox" name="rep_empleado" id="rep_empleado" value="1"> Ventas/Utilidades por empleado</label>
							</div>
							<div class="col-xs-3">
								<label><input type="checkbox" name="rep_total" id="rep_total" value="2"> Ventas/Utilidades por totales</label>
							</div>
							<div class="col-xs-3">
								<label><input type="checkbox" name="rep_estimado" id="rep_estimado" value="1"> Estimados</label>
							</div>
							<div class="col-xs-3">
								<label><input type="checkbox" name="rep_gastos" id="rep_gastos" value="1"> Gastos</label>
							</div>
							<div class="col-xs-3 col-xs-offset-3">
								<label for="fecha_inicio">Fecha inicio</label>
								<input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
							</div>
							
							<div class="col-xs-3">
								<label for="fecha_final">Fecha final</label>
								<input type="date" name="fecha_final" id="fecha_final" class="form-control" required>
							</div>

							<input type="hidden" name="op" id="op" value="8">
							<div class="col-xs-12 btn-spc">
								<button id="btn_generar_reporte" class="btn btn-primary">
									Generar reporte
								</button>
							</div>
						</form>
					</div>
					<div class="col-xs-12 text-center contenido">
						<!-- Aqúí se despliegan el resumen de la fecha que el usuario ha seleccionado -->
					</div>
				</section>
			</div>
		</div>
	</div>