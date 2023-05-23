<div>
<div class="page-title">
	<h3 class="">Informes de Cobro Jurídico </h3>
</div>

<div class="row mt-2">
	<div class="col-md-6 mt-2">
		<div class="title-tables">
			<h3 class="upper text-info d-inline">
				Panel de gestión Jurídicos
			</h3>
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group top_search">
			<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
				<div class="row">
					<div class="col-md-6">
						<div class="col">
							<label for="">
								Rango de fechas de envío a jurídico
							</label>
							<div class="rangofechas input-group ">
								<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
								<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
								<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
							</div>
						</div>
					</div>
					<div class="col-md-6 pt-4">
						<div class="input-group">
							<?php echo $this->Form->input('q', array('placeholder'=>__('Buscar por cédula'), 'class'=>'form-control','label'=>"",'div'=>false,'value'=>isset($this->request->query["q"]) ?  $this->request->query["q"] : "","type"=>"number",)) ?>
							<?php echo $this->Form->hidden('tab', array('placeholder'=>__('Buscar por cédula'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>'2')) ?>
							<input type="submit" class="btn btn-primary" value="Filtrar">

							<?php if ($filter): ?>
								<input type="submit" class="btn btn-warning" name="excel_data" value="Descargar excel">
							<?php endif ?>

						</div>
					</div>
				</div>	
				
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

	<div class="row">
	<div class="col-md-12">
	<div class="x_panel">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
				<thead class="text-primary">
					<tr>
						<th>ID Crédito</th>
						<th>Cliente</th>
						<th>Cédula</th>
						<th>Mora</th>
						<th>Honorarios</th>
						<th>Valor Cuota</th>
						<th>Estado Cuota</th>
						<th>
							Valor Mora <br>
							Honorarios/Intereses
						</th>
						<th>Saldo total Cuota</th>
						<th>Valor capital restante</th>
						<th>Valor total del crédito</th>
						<th>Fecha de retiro credito</th>
						<th>Fecha de reporte jurídico</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($datos)): ?>
						<td class="text-center" colspan="10">
							No hay registro de mora
						</td>
					<?php else: ?>
						<?php foreach ($datos as $keyVal => $valueVal): ?>
							<tr>
								<td>
									<?php echo str_pad($valueVal["Credit"]["credits_request_id"], 6, "0", STR_PAD_LEFT); ?>
								</td>
								<td class="capt">
									<?php echo $valueVal["Customer"]["name"] ?>
								</td>
								<td>
									<?php echo $valueVal["Customer"]["identification"] ?>
								</td>
								<td><?php echo $valueVal["CreditsPlan"]["days"] < 0 ? 0 : $valueVal["CreditsPlan"]["days"]  ?> dias</td>
								<td>
									$<?php echo number_format($valueVal["CreditsPlan"]["debt_honor"]) ?>
								</td>
								<td>
									$<?php echo number_format($valueVal["Credit"]["quota_value"]) ?>
								</td>
								<td>
									<?php echo $valueVal["Credit"]["state"] == 1 ? "Pagada" : "Sin pagar" ?>
								</td>
								<td>
									$<?php echo number_format($valueVal["CreditsPlan"]["debt_value"]+$valueVal["CreditsPlan"]["debt_honor"]) ?>
								</td>
								<td>
									<?php $totalDeuda = $valueVal["CreditsPlan"]["debt_value"]+$valueVal["CreditsPlan"]["debt_honor"] + ($valueVal["CreditsPlan"]["capital_value"]-$valueVal["CreditsPlan"]["capital_payment"]) + ($valueVal["CreditsPlan"]["interest_value"]-$valueVal["CreditsPlan"]["interest_payment"]) + ($valueVal["CreditsPlan"]["others_value"]-$valueVal["CreditsPlan"]["others_payment"]) ?>
									$<?php echo number_format($totalDeuda) ?>
								</td>
								<td>
									$<?php echo number_format($valueVal["Credit"]["value_pending"]) ?>
								</td>
								<td>
									$<?php echo number_format($valueVal["saldos"]) ?>
								</td>
								<td>
									<?php echo $valueVal["Credit"]["created"] ?>
								</td>
								<td>
									<?php echo $valueVal["Credit"]["date_juridico"] ?>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>
		<p class="pagination-out">
								
			<?php echo $this->Paginator->counter(array(
				'format' => __('Página {:page} de {:pages}, {:current} registros de {:count} en total')
			));	?>
			
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