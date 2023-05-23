<div class="page-title">
	<div class="title_left">
		<h3><?php echo __('Saldos y desembolsos'); ?> </h3>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="paymentsblock">
			<?php echo $this->Form->create('', array('role' => 'form', 'type' => 'GET', 'class' => '')); ?>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input('state', array('class' => 'form-control', 'label' => "Estados de solicitudes", 'div' => false, 'value' => $estados, "options" => ["0" => "Solicitado", "2" => "Pendiente", "1" => "Pagado"], "empty" => "Todos los estados", "required" => false)) ?>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<?php echo $this->Form->input('commerce', array('class' => 'form-control', 'label' => "Código de proveedor", 'div' => false, 'value' => $commerce, "placeholder" => "Ingrese el código de proveedor a buscar")) ?>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="">
							Rango de fechas
						</label>
						<div class="rangofechas input-group ">
							<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
							<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
							<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="">Tipo de fechas</label>
						<?php echo $this->Form->input('type_date', array('class' => 'form-control', 'label' => false, 'div' => false, 'value' => isset($type_date) ? $type_date : "", "options" => ["1"=>"Fecha solicitud","2"=>"Fecha de pago"] )) ?>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="">Tipo de pago</label>
						<?php echo $this->Form->input('type', array('class' => 'form-control', 'label' => false, 'div' => false, 'value' => isset($type) ? $type : "", "options" => ["1"=>"Pago 1","2"=>"Pago 2"],"empty" => "Seleccionar ambos" )) ?>
					</div>
				</div>
				<div class="col-md-1">
					<button class="btn btn-success mt-4" type="submit">
						<i class="fa fa-search"></i>
					</button>
					<?php if ($estados != "" || $commerce != "" || $final_date != "" ): ?>
						<a href="<?php echo $this->Html->url(["action" => "index"]) ?>" class="btn btn-warning mt-4">
						   <i class="fa fa-times"></i>
						</a>
					<?php endif ?>
					<a href="<?php echo $this->Html->url(["action"=>"informe_export","?"=>$this->request->query],true) ?>" target="_blank" class="btn btn-danger" id="exportar212">
						Exportar excel <i class="fa fa-file"></i>
					</a>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
				<thead class="text-primary">
				<tr>
					<th>Código de proveedor</th>
					<th>Nit proveedor</th>
					<th><?php echo __('Estado'); ?></th>
					<th><?php echo __('Total solicitado'); ?></th>
					<th>IVA</th>
					<th><?php echo __('Razón'); ?></th>
					<?php
						switch ($type) {
							case 1:
								echo '<th>Pago 1</th>';
								break;
							case 2:
								echo '<th>Pago 2</th>';
								break;
							default:
								echo '<th>Valor Comisión</th>';
								break;
						}
					?>
					<th><?php echo __('Valor pagado'); ?></th>
					<th><?php echo __('Fecha solicitud'); ?></th>
					<th><?php echo __('Fecha pago'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($shopPaymentRequests)): ?>
					<?php foreach ($shopPaymentRequests as $shopPaymentRequest): ?>
						<tr>
							<td>
								<?php echo $shopPaymentRequest["ShopCommerce"]["code"] ?>
							</td>
							<td>
								<?php echo $shopPaymentRequest["Shop"]["nit"] ?>
							</td>
							<td>
								<?php
								switch ($shopPaymentRequest['ShopPaymentRequest']['state']) {
									case '0':
										echo "Solicitado";
										break;
									case '1':
										echo "Pagado";
										break;
									case '2':
										echo "Pendiente";
										break;
								}

								?>
							</td>
							<td>
								$<?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['request_value'], 2); ?>
								&nbsp;
							</td>
							<td>
								$<?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['iva'], 2); ?>
								&nbsp;
							</td>
							<td>
								<?php echo $shopPaymentRequest['ShopsDebts']['reason']; ?>
								&nbsp;
							</td>
							<td>
								$<?php echo number_format($shopPaymentRequest['ShopsDebts']['mo_tipo_pago'], 2); ?>
								&nbsp;
							</td>
							<td><?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['final_value'], 2); ?>
								&nbsp;
							</td>

							<td><?php echo $shopPaymentRequest['ShopPaymentRequest']['request_date']; ?>
								&nbsp;
							</td>
							<td>
								<?php echo $shopPaymentRequest['ShopPaymentRequest']['final_date']; ?>
								&nbsp;
							</td>



						</tr>
					<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td class='text-center' colspan='<?php echo 9; ?>'>No existen resultados</td>
				<tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<p class="pagination-out">

			<?php echo $this->Paginator->counter(array(
					'format' => __('Página {:page} de {:pages}, {:current} registros de {:count} en total')
			)); ?>

		</p>

		<ul class="pagination pagination-info">
			<?php
			echo $this->Paginator->prev('< ' . __('Ant'), array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
			echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'tag' => 'li', 'currentClass' => 'disabled'));
			echo $this->Paginator->next(__('Sig') . ' >', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
			?>
		</ul>
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

	$('#fechasInicioFin2').daterangepicker({
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
	     "startDate": "<?php echo isset($fechaInicioReporte2) ? $fechaInicioReporte2 : date("Y-m-d"); ?>",
	     "endDate": "<?php echo isset($fechaFinReporte2) ? $fechaFinReporte2 : date("Y-m-d"); ?>",
	    "maxDate": "<?php echo date("Y-m-d") ?>"
	}, function(start, end, label) {
		$("#input_date_inicio2,#input_date_inicio_empresa2").val(start.format('YYYY-MM-DD'));
		$("#input_date_fin2,#input_date_fin_empresa2").val(end.format('YYYY-MM-DD'));
	});



</script>
