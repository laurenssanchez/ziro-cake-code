<?php

$whitelist = array(
            '127.0.0.1',
            '::1'
        );
 ?>
<div class="page-title">
	<div class="row">
		<div class="col-md-12">
			<h3 class="d-inline mr-2"><?php echo __('Panel de informes - Recaudos y Ventas'); ?></h3>
			<?php if (!empty($commerces)): ?>
				<ul class="nav nav-pills tabscontrols mb-3">
					<?php foreach ($commerces as $key => $value): ?>
						<li class="nav-item <?php echo $tab == $this->Utilidades->encrypt($key) ? "active bg-primary text-white" : "" ?>" role="presentation">
						    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"reports","?" => ["tab" => $this->Utilidades->encrypt($key)]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
						    	<?php echo $value; ?>
						    </a>
						</li>
					<?php endforeach ?>
				</ul>
			<?php endif ?>
		</div>

		<?php if (in_array(AuthComponent::user("role"),[4]) && !empty($commerces) ): ?>
		<div class="col-md-12">
		<div class="x_panel">
			<div class="form-group topsearch">
				<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
				<div class="row">
					<div class="col-md-9">
						<label for="">
							Rango de fechas
						</label>
						<div class="rangofechas input-group ">
							<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
							<input type="hidden" id="CreditTab" name="tab" value="<?php echo $tab ?>">
							<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
							<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
						</div>
					</div>

					<div class="col-md-3 pt-4">
						<span class="input-group-btn">
							<button class="btn btn-success" type="submit" id="busca">
								<?php echo __('Buscar '); ?> <i class="fa fa-search"></i>
							</button>
						<?php if (isset($fechas) ): ?>
							<a href="<?php echo Router::url(["action"=>"reports"],true) ?>?tab=<?php echo $tab ?> " class="btn btn-warning deleteWar">
				          		Eliminar filtros
				          	</a>

						<?php endif ?>
						<a href="<?php echo $this->Html->url(["action"=>"reports_export",],true) ?>" class="btn btn-danger" id="exportar">
				          		Exportar excel <i class="fa fa-file"></i>
				          	</a>
						</span>
					</div>

				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		</div>
		<?php endif ?>
	</div>
</div>

<?php if (!empty($commerces)): ?>



	<div class="row">
		<div class="col-md-6">
			<div class="row" style="display: block;">
				<div class="col-md-12">
					<div class="x_panel">
						<div class="x_title">
							<h2 class="h5 text-info text-center float-none">
								Recaudos
								<br>
								<b> <?php echo $commerce["ShopCommerce"]["name"] ?> / <?php echo $commerce["ShopCommerce"]["address"] ?> / <?php echo $commerce["ShopCommerce"]["phone"] ?></b>
							</h2>
						</div>
						<div class="x_content">
							<div class="table-responsive">
								<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
									<thead>
										<tr>
											<th>Nro Obligación</th>
											<th>CC Cliente</th>
											<th>Recaudado</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($datos)): ?>
											<?php $total = 0; ?>
											<?php foreach ($datos as $key => $value): ?>
												<tr>
													<td>
														<?php echo str_pad($value["credits"]["credits_request_id"], 6, "0", STR_PAD_LEFT); ?>
													</td>
													<td>
														<?php echo $value["customers"]["identification"] ?>
													</td>
													<td>
														<?php $total += $value["0"]["total"]; ?>
														$<?php echo number_format($value["0"]["total"]) ?>
													</td>
												</tr>
											<?php endforeach ?>
											<tr>
												<td>

												</td>
												<td colspan="1">
													<h2 class="h5 text-info float-none"><b>TOTAL</b></h2>
												</td>
												<td>
													<h2 class="h5 text-info float-none"><b>$<?php echo number_format($total) ?></b></h2>
												</td>
											</tr>
										<?php else: ?>
											<tr>
												<td colspan="3">
													No hay datos
												</td>
											</tr>
										<?php endif ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row" style="display: block;">
				<div class="col-md-12">
					<div class="x_panel">
						<div class="x_title">
							<h2 class="h5 text-info text-center float-none">
								Ventas
								<br>
								<b> <?php echo $commerce["ShopCommerce"]["name"] ?> / <?php echo $commerce["ShopCommerce"]["address"] ?> / <?php echo $commerce["ShopCommerce"]["phone"] ?></b>
							</h2>
						</div>
						<div class="x_content">
							<div class="table-responsive">
								<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
									<thead>
										<tr>
											<th>Nro Obligación</th>
											<th>CC Cliente</th>
											<th>Desembolso</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($allDisbursement)): ?>

											<?php $total = 0; ?>
											<?php foreach ($allDisbursement as $key => $value): ?>
												<?php if ($value["Credit"]["credits_request_id"] == 0): ?>
													<?php continue; ?>
												<?php endif ?>
												<tr>
													<td>
														<?php echo $value["Credit"]["code_pay"]; ?>
													</td>
													<td>
														<?php echo $value["Credit"]["customer"] ?>
													</td>
													<td>
														<?php $total += $value["Disbursement"]["value"]; ?>
														$<?php echo number_format($value["Disbursement"]["value"]) ?>
													</td>
												</tr>
											<?php endforeach ?>
											<tr>
												<td>

												</td>
												<td colspan="1">
													<h2 class="h5 text-info float-none"><b>TOTAL</b></h2>
												</td>
												<td>
													<h2 class="h5 text-info float-none"><b>$<?php echo number_format($total) ?></b></h2>
												</td>
											</tr>
										<?php else: ?>
											<tr>
												<td colspan="3">
													No hay datos
												</td>
											</tr>
										<?php endif ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>




	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />



		<script>
			$('#fechasInicioFin').daterangepicker({
			    "showDropdowns": false,
			    "opens": "center",
			    ranges: {
			        'Hoy': [moment(), moment()],
			        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
			        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
			        'Este mes': [moment().startOf('month'), moment()],
			        'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			    },
			    "locale": {
			        "format": "YYYY-MM-DD",
			        "separator": " - ",
			        "applyLabel": "Aplicar",
			        "cancelLabel": "Cancelar",
			        "fromLabel": "Desde",
			        "toLabel": "Hasta",
			        "customRangeLabel": "Definir rango",
			        "weekLabel": "W",
			        "daysOfWeek": [
			            "Do",
			            "Lu",
			            "Ma",
			            "Mi",
			            "Ju",
			            "Vi",
			            "Sa"
			        ],
			        "monthNames": [
			            "Enero",
			            "Febrero",
			            "Marzo",
			            "Abril",
			            "Mayo",
			            "Junio",
			            "Julio",
			            "Agosto",
			            "Septiembre",
			            "Octubre",
			            "Noviembre",
			            "Diciembre"
			        ],
			        "firstDay": 1
			    },
			    "alwaysShowCalendars": true,
			     "startDate": "<?php echo isset($fechaInicioReporte) ? $fechaInicioReporte : date("Y-m-d"); ?>",
			     "endDate": "<?php echo isset($fechaFinReporte) ? $fechaFinReporte : date("Y-m-d"); ?>",
			    "maxDate": "<?php echo date("Y-m-d") ?>"
			}, function(start, end, label) {
				$("#input_date_inicio,#input_date_inicio_empresa").val(start.format('YYYY-MM-DD'));
				$("#input_date_fin,#input_date_fin_empresa").val(end.format('YYYY-MM-DD'));
			});



		</script>



	<?php echo $this->Html->script("reports/exports.js?".rand(),           array('block' => 'AppScript')); ?>

<?php else: ?>

	<h3>No existen proveedores</h3>

<?php endif ?>
