
			<div class="tab-pane" role="tabpanel" id="elegir_fecha">
				<section class="row">
					<form method="post" id="form_elegir_fecha">
						<div class="col-md-3 text-center titulo_elegir_mes fecha">
							<label for="mes_inicio">Fecha de inicio</label>
							<input type="date" class="form-control" name="mes_inicio" id="mes_inicio" required>
						</div>
						<div class="col-md-3 text-center titulo_elegir_mes fecha">
							<label for="mes_inicio">Fecha de terminación</label>
							<input type="date" class="form-control" name="mes_final" id="mes_final" required>
						</div>
						<div class="col-md-6 text-center titulo_elegir_mes">
							<label><input type="checkbox" name="rep_venta" id="rep_venta" value="1"> Ventas/Utilidades totales</label>
							<label><input type="checkbox" name="rep_estimado" id="rep_estimado" value="1"> Estimados</label>
							<label><input type="checkbox" name="rep_venta" id="rep_venta" value="1"> Ventas/Utilidades por empleado</label>
							<label><input type="checkbox" name="rep_gastos" id="rep_gastos" value="1"> Gastos</label>
						</div>
						<div class="col-xs-12 text-center titulo_elegir_mes btn-espacio">
							<input type="submit" value="Generar reporte" class="btn btn-primary">
						</div>
					</form>
				</section>
				
				<section class="col-xs-12 contenido">
					
				</section>
			</div>
		</div>
	</div>