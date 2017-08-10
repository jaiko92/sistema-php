			<div class="tab-pane" role="tabpanel" id="metas_mensuales">
				<section class="row">
					<div class="col-xs-12 text-center barra_busqueda">
						<h4>Elige las fechas correspondientes a los estimados que deseas consultar.</h4>
						<form method="post" id="form_mostrar_metas">
							<div class="col-xs-3 col-xs-offset-3">
								<label for="fecha_inicio">Fecha inicio</label>
								<input type="month" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
							</div>
							
							<div class="col-xs-3">
								<label for="fecha_final">Fecha final</label>
								<input type="month" name="fecha_final" id="fecha_final" class="form-control" required>
							</div>

							<input type="hidden" name="op" id="op" value="6">
							<div class="col-xs-12 btn-spc">
								<button id="btn_mostrar_metas" class="btn btn-primary">
									Mostrar metas mensuales
								</button>
							</div>
							
						</form>
					</div>
					<div class="col-xs-12 text-center contenido">
						<!-- Aqúí se despliegan todas las facturas pendientes para costear -->
					</div>
				</section>
			</div>
