<?php

$whitelist = array(
            '127.0.0.1',
            '::1'
        );
 ?>
<div class="page-title">
	<div class="row">
		<div class="col-md-12">
			<h3 class="d-inline mr-2"><?php echo __('Panel de informes - Cartera'); ?></h3>
			<ul class="nav nav-pills tabscontrols mb-3">
			  <li class="nav-item <?php echo $tab == 1 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"credits","action"=>"cartera","?" => ["tab" => 1]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Créditos Retirados
			    </a>
			  </li>
			  <li class="nav-item <?php echo $tab == 2 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"credits","action"=>"cartera","?" => ["tab" => 2]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Créditos vigentes
			    </a>
			  </li>
			  <li class="nav-item <?php echo $tab == 3 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"credits","action"=>"cartera","?" => ["tab" => 3]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Créditos Cancelados
			    </a>
			  </li>
			  <li class="nav-item <?php echo $tab == 4 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"credits","action"=>"cartera","?" => ["tab" => 4]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Créditos Vigentes en mora
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
					<div class="col-md-5">
						<div class="form-group">
							<label for="">
								Código de proveedor
							</label>
							<?php echo $this->Form->input('commerce', array('placeholder'=>__('Buscar por código de proveedor'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($commerce) ? $commerce : "" )) ?>
							<?php echo $this->Form->input('tab', array("value" => $tab, "type"=>"hidden")) ?>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="">
								Cédula
							</label>
							<?php echo $this->Form->input('cedula', array('placeholder'=>__('Buscar por cedula'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($cedula) ? $cedula : "" )) ?>
							<?php echo $this->Form->input('tab', array("value" => $tab, "type"=>"hidden")) ?>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="">
								Numero de obligación
							</label>
							<?php echo $this->Form->input('n_obligacion', array('placeholder'=>__('Buscar por # obligación'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($n_obligacion) ? $n_obligacion : "" )) ?>
							<?php echo $this->Form->input('tab', array("value" => $tab, "type"=>"hidden")) ?>
						</div>
					</div>
					<div class="col-md-3">
						<label for="">
							Rango de fechas
						</label>
						<div class="rangofechas input-group ">
							<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
							<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
							<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
						</div>
					</div>

					<?php if ($tab == 3): ?>
						<div class="col-md-2">
							<?php echo $this->Form->input('type_date', array('label'=>__('Filtrar por fecha'), 'class'=>'form-control','div'=>false,'value'=> isset($type_date) ? $type_date : "1", "options" => ["1"=>"Fecha retirado","2" => "Fecha finalización"] )) ?>
						</div>
					<?php endif ?>
					<div class="col-md-2 pt-4">
						<span class="input-group-btn">
							<button class="btn btn-primary"
								type="submit"
								name="accion"
								value="buscar">
								<?php echo __('Buscar '); ?> <i class="fa fa-search"></i>
							</button>
						<?php if (isset($rango) || isset($commerce) || isset($fechas) || isset($type_date) ): ?>
							<a href="<?php echo $this->Html->url(["action"=>"cartera","?" => ["tab" => $tab]]) ?>" class="btn btn-warning deleteWar">
				          		Eliminar filtro
				          	</a>
							  <button class="btn btn-danger"
								type="submit"
								name="accion"
								value="exportar">
									Exportar excel <i class="fa fa-file"></i>
							</button>
							<!-- <a href="<?php echo $this->Html->url(["action"=>"cartera_exporte_final",],true) ?>" class="btn btn-danger" id="exportar">
				          		Exportar excel <i class="fa fa-file"></i>
				          	</a> -->
						</span>
						<?php endif ?>
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
						<b> Total dinero Retirado:  $  <?php  echo number_format($totalCartera) ?></b>
					</h2>
				</div>
				<div class="x_content">
					<div class="table-responsive">
						<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
							<thead class="">
								<tr>
									<th>Comercio</th>
									<th><?php echo $this->Paginator->sort('Credit.created', __('Fecha retirado')); ?></th>
									<th><?php echo $this->Paginator->sort('Credit.credits_request_id', __('Obligación')); ?></th>
									<th><?php echo __('Cédula'); ?></th>
									<th><?php echo __('Nombre completo'); ?></th>
									<th><?php echo __('Teléfono'); ?></th>
									<!-- <th><?php echo __('Dirección'); ?></th> -->
									<th><?php echo __('Valor Aprobado'); ?></th>
									<th><?php echo __('Frecuencia'); ?></th>
									<th><?php echo __('Valor Retirado'); ?></th>
									<th><?php echo __('Nro. Coutas'); ?></th>
									<th><?php echo __('Fecha pago'); ?></th>
									<th><?php echo __('Estado crédito'); ?></th>
									<th><?php echo __('Fecha pago cliente'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($credits)): ?>
									<tr>
										<td colspan="10">
											No hay información
										</td>
									</tr>
								<?php else: ?>
									<?php foreach ($credits as $key => $value): ?>
										<tr>
											<td>
												<?php echo $value["Comercio"]["Shop"]["social_reason"] ?> -
												<?php echo $value["Comercio"]["ShopCommerce"]["name"] ?> -
												<b><?php echo $value["Comercio"]["ShopCommerce"]["code"] ?></b>
											</td>
											<td>
												<?php echo date("d-m-Y",strtotime($value["Credit"]["created"])) ?>
											</td>

											<td>

												<?php echo $value["Credit"]["code_pay"]; ?>
											</td>
											<td>
												<?php echo $value["Customer"]["Customer"]["identification"] ?>
											</td>
											<td class="upper">
												<?php echo $value["Customer"]["Customer"]["name"] ?>
												<?php echo $value["Customer"]["Customer"]["last_name"] ?>
											</td>
											<td>
												<?php echo $value["Customer"]["CustomersPhone"]["0"]["phone_number"] ?>
											</td>
											<!-- <td class="upper">
												<?php echo $value["Customer"]["CustomersAddress"]["0"]["address"]; ?> <?php echo $value["Customer"]["CustomersAddress"]["0"]["address_city"]; ?> <?php echo $value["Customer"]["CustomersAddress"]["0"]["address_street"]; ?>
											</td> -->
											<td>
												$ <?php echo number_format($value["Credit"]["value_aprooved"]) ?>
											</td>

											<!-- Frecuencia -->
											<?php if ($value["Credit"]["type"] == 1) : ?>
												<td>
													<?php echo "Mensual"; ?>
												</td>

											<?php elseif($value["Credit"]["type"] == 3) : ?>
												<td>
													<?php echo "45 días"; ?>
												</td>

											<?php elseif($value["Credit"]["type"] == 4) : ?>
												<td>
													<?php echo "60 días"; ?>
												</td>
											<?php else : ?>
												<td>
													<?php echo "Quincenal"; ?>
												</td>
											<?php endif ?>


											<td>
												$ <?php echo number_format($value["Credit"]["value_request"]) ?>
											</td>
											<td>
												<?php echo $value["Credit"]["number_fee"] ?>
											</td>

											<td>
												<?php foreach ($value['CreditsPlan'] as $plan): ?>
													-<?php echo $plan["deadline"] ?><br>
												<?php endforeach ?>
											</td>

											<td>
												<?php if ($value["Credit"]["debt"] == 1): ?>
													Mora
												<?php else: ?>
													<?php echo $value["Credit"]["state"] == 1 ? "Cancelado" : "No finalizado" ?>

												<?php endif ?>
											</td>
											<td>
												<?php echo $value["Credit"]["last_payment_date"]; ?><br>
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
<?php elseif($tab==2): ?>
	<div class="row" style="display: block;">
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2 class="h3 text-info text-center float-none">
						<b> Dinero total:  $  <?php  echo number_format($totalCartera) ?></b>
					</h2>
				</div>
				<div class="x_content">
					<div class="table-responsive">
						<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
							<thead class="">
								<tr>
									<th>Proveedor</th>
									<th><?php echo $this->Paginator->sort('Credit.created', __('Fecha retirado')); ?></th>
									<th><?php echo $this->Paginator->sort('Credit.credits_request_id', __('Obligación')); ?></th>
									<th><?php echo __('Cédula'); ?></th>
									<th><?php echo __('Nombre completo'); ?></th>
									<th><?php echo __('Teléfono'); ?></th>
									<!-- <th><?php echo __('Dirección'); ?></th> -->
									<th><?php echo __('Valor Aprobado'); ?></th>
									<th><?php echo __('Valor Retirado'); ?></th>
									<th><?php echo __('Saldo'); ?></th>
									<th><?php echo __('Fecha pago'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($credits)): ?>
									<tr>
										<td colspan="10">
											No hay información
										</td>
									</tr>
								<?php else: ?>
									<?php foreach ($credits as $key => $value): ?>
										<tr>
											<td>
												<?php echo $value["Comercio"]["Shop"]["social_reason"] ?> -
												<?php echo $value["Comercio"]["ShopCommerce"]["name"] ?> -
												<b><?php echo $value["Comercio"]["ShopCommerce"]["code"] ?></b>
											</td>
											<td>
												<?php echo date("d-m-Y",strtotime($value["Credit"]["created"])) ?>
											</td>

											<td>
												<?php echo $value["Credit"]["code_pay"]; ?>
											</td>
											<td>
												<?php echo $value["Customer"]["Customer"]["identification"] ?>
											</td>
											<td class="upper">
												<?php echo $value["Customer"]["Customer"]["name"] ?>
												<?php echo $value["Customer"]["Customer"]["last_name"] ?>
											</td>
											<td>
												<?php echo $value["Customer"]["CustomersPhone"]["0"]["phone_number"] ?>
											</td>
											<!-- <td class="upper">
												<?php echo $value["Customer"]["CustomersAddress"]["0"]["address"]; ?> <?php echo $value["Customer"]["CustomersAddress"]["0"]["address_city"]; ?> <?php echo $value["Customer"]["CustomersAddress"]["0"]["address_street"]; ?>
											</td> -->
											<td>
												$ <?php echo number_format($value["Credit"]["value_aprooved"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["Credit"]["value_request"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["saldos"]["saldo"]) ?>
											</td>
											<td>
												<?php foreach ($value['CreditsPlan'] as $plan): ?>
													-<?php echo $plan["deadline"] ?><br>
												<?php endforeach ?>
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
<?php elseif($tab==3): ?>
	<div class="row" style="display: block;">
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2 class="h3 text-info text-center float-none">
						<b> Dinero total:  $  <?php  echo number_format($totalCartera) ?></b>
					</h2>
				</div>
				<div class="x_content">
					<div class="table-responsive">
						<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
							<thead class="">
								<tr>
									<th>Proveedor</th>
									<th><?php echo $this->Paginator->sort('Credit.created', __('Fecha retirado')); ?></th>
									<th><?php echo $this->Paginator->sort('Credit.credits_request_id', __('Obligación')); ?></th>
									<th><?php echo __('Cédula'); ?></th>
									<th><?php echo __('Nombre completo'); ?></th>
									<th><?php echo __('Teléfono'); ?></th>
									<!-- <th><?php echo __('Dirección'); ?></th> -->
									<th><?php echo __('Valor Aprobado'); ?></th>
									<th><?php echo __('Valor Retirado'); ?></th>
									<th><?php echo __('Fecha Finalización'); ?></th>
									<th class="w-5"><?php echo __('C. pagadas en mora'); ?></th>
									<th><?php echo __('Fecha pago'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($credits)): ?>
									<tr>
										<td colspan="10">
											No hay información
										</td>
									</tr>
								<?php else: ?>
									<?php foreach ($credits as $key => $value): ?>
										<tr>
											<td>
												<?php echo $value["Comercio"]["Shop"]["social_reason"] ?> -
												<?php echo $value["Comercio"]["ShopCommerce"]["name"] ?> -
												<b><?php echo $value["Comercio"]["ShopCommerce"]["code"] ?></b>
											</td>
											<td>
												<?php echo date("d-m-Y",strtotime($value["Credit"]["created"])) ?>
											</td>

											<td>
												<?php echo $value["Credit"]["code_pay"]; ?>
											</td>
											<td>
												<?php echo $value["Customer"]["Customer"]["identification"] ?>
											</td>
											<td class="upper">
												<?php echo $value["Customer"]["Customer"]["name"] ?>
												<?php echo $value["Customer"]["Customer"]["last_name"] ?>
											</td>
											<td>
												<?php echo $value["Customer"]["CustomersPhone"]["0"]["phone_number"] ?>
											</td>
											<!-- <td class="upper">
												<?php echo $value["Customer"]["CustomersAddress"]["0"]["address"]; ?> <?php echo $value["Customer"]["CustomersAddress"]["0"]["address_city"]; ?> <?php echo $value["Customer"]["CustomersAddress"]["0"]["address_street"]; ?>
											</td> -->
											<td>
												$ <?php echo number_format($value["Credit"]["value_aprooved"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["Credit"]["value_request"]) ?>
											</td>
											<td>
												<?php echo $value["Credit"]["deadline"] ?>
											</td>
											<td class="w-5">
												<?php echo $value["debts"] ?>
											</td>
											<td>
												<?php foreach ($value['CreditsPlan'] as $plan): ?>
													-<?php echo $plan["deadline"] ?><br>
												<?php endforeach ?>
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
<?php elseif($tab==4): ?>
	<div class="row" style="display: block;">
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2 class="h3 text-info text-center float-none">
						<?php if (!is_null($totalCartera)): ?>

						<b> Dinero total:  $  <?php  echo number_format($totalCartera) ?></b>
						<?php endif ?>
					</h2>
				</div>
				<div class="x_content">
					<div class="table-responsive">
						<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
							<thead class="">
								<tr>
									<th>Proveedor</th>
									<th><?php echo $this->Paginator->sort('Credit.created', __('Fecha retirado')); ?></th>
									<th><?php echo $this->Paginator->sort('Credit.credits_request_id', __('Obligación')); ?></th>
									<th><?php echo __('Cédula'); ?></th>
									<th><?php echo __('Nombre completo'); ?></th>
									<th><?php echo __('Teléfono'); ?></th>
									<!-- <th><?php echo __('Dirección'); ?></th> -->
									<!-- <th><?php echo __('Valor Aprobado'); ?></th> -->
									<th><?php echo __('Valor Retirado'); ?></th>
									<th><?php echo __('Saldo vigente'); ?></th>
									<th><?php echo __('Saldo Mora'); ?></th>
									<th><?php echo __('Valor cuota'); ?></th>
									<th><?php echo __('Valor cuota con mora'); ?></th>
									<th><?php echo __('Cuotas mora'); ?></th>
									<th><?php echo __('Dias mora'); ?></th>
									<th><?php echo __('Fecha pago'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($credits)): ?>
									<tr>
										<td colspan="13">
											No hay información
										</td>
									</tr>
								<?php else: ?>
									<?php foreach ($credits as $key => $value): ?>
										<tr>
											<td>
												<?php echo $value["Comercio"]["Shop"]["social_reason"] ?> -
												<?php echo $value["Comercio"]["ShopCommerce"]["name"] ?> -
												<b><?php echo $value["Comercio"]["ShopCommerce"]["code"] ?></b>
											</td>
											<td>
												<?php echo date("d-m-Y",strtotime($value["Credit"]["created"])) ?>
											</td>

											<td>
												<?php echo $value["Credit"]["code_pay"]; ?>
											</td>
											<td>
												<?php echo $value["Customer"]["Customer"]["identification"] ?>
											</td>
											<td class="upper">
												<?php echo $value["Customer"]["Customer"]["name"] ?>
												<?php echo $value["Customer"]["Customer"]["last_name"] ?>
											</td>
											<td>
												<?php echo $value["Customer"]["CustomersPhone"]["0"]["phone_number"] ?>
											</td>
											<!-- <td class="upper">
												<?php echo $value["Customer"]["CustomersAddress"]["0"]["address"]; ?> <?php echo $value["Customer"]["CustomersAddress"]["0"]["address_city"]; ?> <?php echo $value["Customer"]["CustomersAddress"]["0"]["address_street"]; ?>
											</td> -->
											<!-- <td>
												$ <?php echo number_format($value["Credit"]["value_aprooved"]) ?>
											</td>	 -->
											<td>
												$ <?php echo number_format($value["Credit"]["value_request"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["saldos"]["saldo"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["saldos"]["debt"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["Credit"]["quota_value"]) ?>
											</td>
											<td>
												$ <?php echo number_format($value["saldos"]["min_value"]) ?>
											</td>
											<td>

												<?php echo ($value["saldos"]["totalDebt"]) ?>
											</td>
											<td>
												<?php echo ($value["Credit"]["quote_days"]) ?>
											</td>
											<td>
												<?php foreach ($value['CreditsPlan'] as $plan): ?>
													-<?php echo $plan["deadline"] ?><br>
												<?php endforeach ?>
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
<?php endif; ?>







<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<?php if (in_array($tab, [1,2,3,4])): ?>

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
