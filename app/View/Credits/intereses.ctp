<?php
$whitelist = array(
            '127.0.0.1',
            '::1'
        );
 ?>
<div class="page-title">
	<div class="row">
		<div class="col-md-12">
			<h3 class="d-inline mr-2"><?php echo __('Panel de informes - Intereses'); ?></h3>
			<ul class="nav nav-pills tabscontrols mb-3">
			  <li class="nav-item <?php echo $tab == 1 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"credits","action"=>"intereses","?" => ["tab" => 1]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Intereses causados
			    </a>
			  </li>
			  <li class="nav-item <?php echo $tab == 2 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"credits","action"=>"intereses","?" => ["tab" => 2]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Intereses recaudados
			    </a>
			  </li>
			</ul>
		</div>

		<?php if (in_array(AuthComponent::user("role"),[1])): ?>
		<div class="col-md-12 tab-content" >
			<div class="x_panel">
			<div class="form-group topsearch">
				<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
				<div class="row">
					<div class="col-md-12 text-center mb-3">
						<input type="text" id="filtroCosto" class="fintroCosto" value="" name="range" />
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="">
								Código de proveedor
							</label>
							<?php echo $this->Form->input('commerce', array('placeholder'=>__('Buscar por código de proveedor'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($commerce) ? $commerce : "" )) ?>
							<?php echo $this->Form->input('tab', array("value" => $tab, "type"=>"hidden")) ?>
						</div>
					</div>
					<div class="col-md-4">
						<label for="">
							Rango de fechas
						</label>
						<div class="rangofechas input-group ">
							<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
							<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
							<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="">
								Estado del crédito
							</label>
							<?php echo $this->Form->input('state', array("options"=>[""=>"Todos",1=>"Pagado",0=>"No finalizado"], 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($state) ? $state : "" )) ?>
						</div>
					</div>
					<div class="col-md-2 pt-4">
						<span class="input-group-btn">
							<button class="btn btn-primary" type="submit" id="busca">
								<?php echo __('Buscar '); ?> <i class="fa fa-search"></i>
							</button>
						<?php if (isset($rango) || isset($commerce) || isset($fechas) ): ?>
							<a href="<?php echo $this->Html->url(["action"=>"intereses","?" => ["tab" => $tab]]) ?>" class="btn btn-warning deleteWar">
				          		Eliminar filtros
				          	</a>
						<?php endif ?>
							<a href="<?php echo $this->Html->url(["action"=>"intereses_export",],true) ?>" class="btn btn-danger" id="exportar">
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


<div class="clearfix"></div>


<?php if ($tab == 1): ?>
	<div class="row" style="display: block;">
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2 class="h3 text-info text-center float-none">
						<b>Total intereses: $  <?php echo number_format($totalInteres) ?></b>
					</h2>
				</div>
				<div class="x_content">
					<div class="table-responsive">
						<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
							<thead class="">
								<tr>
									<th><?php echo $this->Paginator->sort('Credit.credits_request_id', __('Obligación')); ?></th>
									<th><?php echo $this->Paginator->sort('Credit.created', __('Fecha retiro')); ?></th>
									<th><?php echo $this->Paginator->sort('Credit.state', __('Estado del credito')); ?></th>
									<th><?php echo __('Comercio'); ?></th>
									<th><?php echo __('Capital restante'); ?></th>
									<th><?php echo __('Intereses corrientes'); ?></th>
									<th><?php echo __('Otros cargos'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($valuesQuotes)): ?>
									<tr>
										<td colspan="7">
											No hay información
										</td>
									</tr>
								<?php else: ?>
									<?php foreach ($valuesQuotes as $key => $value): ?>
										<tr>
											<td>
												<?php echo $value["Credit"]["code_pay"]; ?>
											</td>
											<td>
												<?php echo date("d-m-Y",strtotime($value["Credit"]["created"])) ?>
											</td>
											<td>
												<?php echo $value["Credit"]["state"] == 1 ? "Pagado" : "No finalizado" ?>
											</td>
											<td>
												<?php echo $value["Credit"]["comercio"]["ShopCommerce"]["name"] ?> - <?php echo $value["Credit"]["comercio"]["Shop"]["social_reason"] ?> -
												<b><?php echo $value["Credit"]["comercio"]["ShopCommerce"]["code"] ?></b>
											</td>
											<td>
												$ <?php echo number_format($value["Credit"]["value_pending"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["0"]["INTERES"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["0"]["OTROS"]) ?>
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
<?php else: ?>
	<div class="row" style="display: block;">
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2 class="h3 text-info text-center float-none">
						<b>Total intereses:  $  <?php echo number_format($totalInteres) ?></b>
					</h2>
				</div>
				<div class="x_content">
					<div class="table-responsive">
						<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
							<thead class="">
								<tr>
									<th><?php echo $this->Paginator->sort('Credit.credits_request_id', __('Obligación')); ?></th>
									<th><?php echo $this->Paginator->sort('Credit.created', __('Fecha retiro')); ?></th>
									<th><?php echo $this->Paginator->sort('Credit.state', __('Estado credito')); ?></th>
									<th><?php echo __('Comercio'); ?></th>
									<th><?php echo __('Cuotas pagadas'); ?></th>
									<th><?php echo __('Intereses corrientes'); ?></th>
									<th><?php echo __('Otros cargos'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($valuesQuotes)): ?>
									<tr>
										<td colspan="7">
											No hay información
										</td>
									</tr>
								<?php else: ?>
									<?php foreach ($valuesQuotes as $key => $value): ?>
										<tr>
											<td>
												<?php echo $value["Credit"]["code_pay"]; ?>
											</td>
											<td>
												<?php echo date("d-m-Y",strtotime($value["Credit"]["created"])) ?>
											</td>
											<td>
												<?php echo $value["Credit"]["state"] == 1 ? "Pagado" : "No finalizado" ?>
											</td>
											<td>
												<?php echo $value["Credit"]["comercio"]["ShopCommerce"]["name"] ?> - <?php echo $value["Credit"]["comercio"]["Shop"]["social_reason"] ?> -
												<b><?php echo $value["Credit"]["comercio"]["ShopCommerce"]["code"] ?></b>
											</td>
											<td>
												<?php echo number_format($value["Credit"]["totales"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["0"]["INTERES"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["0"]["OTROS"]) ?>
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
<?php endif ?>




<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<?php if (in_array($tab, [1,2])): ?>

	<script>

		var minValue = <?php echo $min ?>;
		var maxValue = <?php echo $max ?>;

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


<?php endif ?>




<?php echo $this->Html->script("/vendors/ion.rangeSlider/js/ion.rangeSlider.min.js?".rand(),           array('block' => 'AppScript')); ?>
<?php echo $this->Html->script("informes/admin.js?".rand(),           array('block' => 'AppScript')); ?>

<?php echo $this->Html->css("/vendors/ion.rangeSlider/css/ion.rangeSlider.css?".rand()); ?>
<?php echo $this->Html->css("/vendors/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css?".rand()); ?>

<?php echo $this->Html->script("reports/exports.js?".rand(),           array('block' => 'AppScript')); ?>
