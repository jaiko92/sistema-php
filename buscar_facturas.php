<!--Inicio de pagina-->
			<div class="tab-pane" role="tabpanel" id="buscar_factura">
				<section class="row">
					<div class="col-xs-12 text-center barra_busqueda">
						<h4>Elige los datos correspondientes a las facturas que deseas consultar.</h4>
						<form method="post" id="form_facturas">
							<div class="col-xs-1 col-xs-offset-1">
								<label for="folio">Folio</label>
								<input type="text" name="folio" id="buscador_folio" class="form-control">
							</div>

							<div class="col-xs-2">
								<label for="rfc">RFC</label>
								<input type="text" name="rfc" id="buscador_rfc" class="form-control">
							</div>

							<div class="col-xs-6">
								<label for="razon_social">Razón Social</label>
								<input type="text" name="razon_social" id="buscador_rsocial" class="form-control">
							</div>

							<div class="col-xs-1">
								<label for="agente">Agente</label>
								<input type="text" name="agente" id="buscador_agente" class="form-control">
							</div>

							<div class="col-xs-3 col-xs-offset-3">
								<label for="fecha_inicio">Fecha inicio</label>
								<input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
							</div>
							
							<div class="col-xs-3">
								<label for="fecha_final">Fecha final</label>
								<input type="date" name="fecha_final" id="fecha_final" class="form-control" required>
							</div>

							<input type="hidden" name="op" id="op" value="5">
							<div class="col-xs-12 btn-spc">
								<button id="btn_facturas" class="btn btn-primary">
									Generar facturas
								</button>
							</div>
						</form>
					</div>
					<div class="col-xs-12 text-center contenido">
						<!-- Aqúí se despliegan todas las facturas seleccionadas -->
					</div>
				</section>
			</div>
