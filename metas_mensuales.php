			<div class="tab-pane" role="tabpanel" id="metas_mensuales">
				<section class="row">
					<div class="col-xs-12"></div>
					<div class="col-xs-6 text-center barra_busqueda">
						<form method="post" id="form_ingresar_metas">
							<div class='panel panel-primary'>
								<div class='panel-heading'>
									<h4 class='panel-title bold'>Ingresar Meta</h4>
								</div>
								<div class='panel-body del-padding'>
									<h5>Elige los datos correspondientes a la meta mensual que se desea ingresar.</h5>

									<div class="col-xs-4">
										<label for="fecha_inicio">Mes de la meta</label>
										<input type="month" name="mes_meta" id="mes_meta" class="form-control" required>
									</div>
									
									<div class="col-xs-4">
										<label for="fecha_final">Total de la meta</label>
										<input type="number" name="total_meta" id="total_meta" class="form-control" min="0" step="0.01" required>
									</div>	

									<div class="col-xs-4">
										<label for="fecha_final">Porcentaje de utilidad</label>
										<input type="number" name="porcentaje_utilidad" id="porcentaje_utilidad" class="form-control" min="0" max="100" step="0.01" required>
									</div>

									<input type="hidden" name="op" id="op" value="7">
									<input type="hidden" name="user" id="user" value="<?php echo $user; ?>">
									<div class="col-xs-12 btn-spc">
										<button id="btn_ingresar_metas" class="btn btn-primary">
											Ingresar meta mensual
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>

					<div class="col-xs-6 text-center barra_busqueda">
						<form method="post" id="form_mostrar_metas">
							<div class='panel panel-primary'>
								<div class='panel-heading'>
									<h4 class='panel-title bold'>Consultar Metas</h4>
								</div>
								<div class='panel-body del-padding'>
									<h5>Elige las fechas correspondientes a los estimados que deseas consultar.</h5>
									
									<div class="col-xs-6">
										<label for="fecha_inicio">Fecha inicio</label>
										<input type="month" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
									</div>
									
									<div class="col-xs-6">
										<label for="fecha_final">Fecha final</label>
										<input type="month" name="fecha_final" id="fecha_final" class="form-control" required>
									</div>

									<input type="hidden" name="op" id="op" value="6">
									<div class="col-xs-12 btn-spc">
										<button id="btn_mostrar_metas" class="btn btn-primary">
											Mostrar metas mensuales
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>

					<div class="col-xs-12 text-center contenido">
						<!-- Aqúí se despliegan todas las facturas pendientes para costear -->
					</div>
				</section>
			</div>
