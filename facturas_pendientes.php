			<div class="tab-pane active" role="tabpanel" id="facturas_pendientes">
				<section class="row">
					<div class="col-xs-12 text-center barra_busqueda">
						<h4>Elige las fechas correspondientes a las facturas que deseas consultar.</h4>
						<form method="post" id="form_facturas_pendientes">
							<div class="col-xs-3 col-xs-offset-3">
								<label for="fecha_inicio">Fecha inicio</label>
								<input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
							</div>
							
							<div class="col-xs-3">
								<label for="fecha_final">Fecha final</label>
								<input type="date" name="fecha_final" id="fecha_final" class="form-control" required>
							</div>

							<input type="hidden" name="op" id="op" value="1">
							<div class="col-xs-12 btn-spc">
								<button id="btn_facturas_pendientes" class="btn btn-primary">
									Generar facturas pendientes
								</button>
							</div>
							
						</form>
					</div>
					<div class="col-xs-12 text-center contenido">
						<!-- Aqúí se despliegan todas las facturas pendientes para costear -->
					</div>
				</section>
			</div>
