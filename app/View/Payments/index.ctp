<?php date_default_timezone_set('America/Bogota');
 ?>

<div class="page-title">
	<div class="row">
		<div class="col-md-12" style="<?php echo AuthComponent::user("role") == 11 ? "display: none" : ""; ; ?>">
			<h3 class="d-inline mr-2"><?php echo __('Panel de informes - Intereses'); ?></h3>
			<ul class="nav nav-pills tabscontrols mb-3">
			  <li class="nav-item <?php echo $tab == 1 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"index","?" => ["tab" => 1]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Pagos fisicos
			    </a>
			  </li>
			  <li class="nav-item <?php echo $tab == 2 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"index","?" => ["tab" => 2]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Pagos web
			    </a>
			  </li>
			  <li class="nav-item <?php echo $tab == 3 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"index","?" => ["tab" => 3]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Pagos Juridica
			    </a>
			  </li>
			</ul>
		</div>
		<div class="col-md-6">
			<h3><?php echo __('Historial de recaudos'); ?>
				<?php if (in_array(AuthComponent::user("role"), [1,2,9]) && !empty($totales)): ?>
					<a href="<?php echo $this->Html->url(["action"=>"index","?"=>["tab"=>$tab]]) ?>" class="btn btn-primary">
						<i class="fa fa-check"></i> TODOS LOS RECAUDOS
					</a>
				<?php endif ?>
			</h3>
		</div>
		<?php if (in_array(AuthComponent::user("role"),[1,2,3,4,6,9,11])): ?>
			<div class="col-md-12">
				<div class="form-group top_search">
					<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
					<?php echo $this->Form->text('tab', array('value'=>$tab,"type" => "hidden" )) ?>
					<?php echo $this->Form->text('commerce', array('value'=>isset($this->request->query["commerce"]) ? $this->request->query["commerce"] : "","type" => "hidden" )) ?>
					<div class="row">
						<div class="col-md-3">
							<div class="input-group">
								<?php echo $this->Form->input('ccCustomer', array('placeholder'=>__('Buscar cliente por cédula'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($ccCustomer) ? $ccCustomer : "" )) ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<?php echo $this->Form->input('commerce_code', array('placeholder'=>__('Buscar por código de proveedor'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($commerce_code) ? $commerce_code : "" )) ?>
							</div>
						</div>
						<?php if (AuthComponent::user("role") == 11): ?>
							<div class="col-md-3">
								<div class="form-group">
									<?php echo $this->Form->input('credit', array('placeholder'=>__('Número de Obligación'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($credit) ? $credit : "" )) ?>
								</div>
							</div>
						<?php endif ?>
						<div class="col-md-3">
							<span class="input-group-btn">
								<button class="btn btn-success" type="submit" id="busca">
									<?php echo __('Buscar '); ?> <i class="fa fa-search"></i>
								</button>
								<?php if (AuthComponent::user("role") == 11): ?>
									<a target="_blank" href="<?php echo $this->Html->url(["action"=>"index_excel","?"=>$this->request->query]) ?>" class="btn btn-warning">
										<i class="fa fa-file"></i> Descargar excel
									</a>
								<?php endif ?>
							<?php if (isset($ccCustomer)): ?>
								<button class="btn btn-info" type="reset" id="limpia" >
									<?php echo __('Limpiar campos'); ?> <i class="fa fa-times"></i>
								</button>
							<?php endif ?>
							</span>
						</div>
					</div>

					<?php if ($this->request->query["tab"] == 1): ?>
						<div class="form-group">
							<select name="type_view" id="type_view" class="form-control">
								<option value="1" <?php echo isset($this->request->query["type_view"]) && $this->request->query["type_view"] == 1 ? "selected" : "" ?>>Vista general por cuadros</option> == 1 ? "selected" : ""
								<option value="2" <?php echo isset($this->request->query["type_view"]) && $this->request->query["type_view"] == 1 ? "selected" : "" ?>>Vista en tabla</option>
							</select>
						</div>
					<?php endif ?>



					<div class="rangofechas input-group ">
						<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
						<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
						<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
						<input name="tab" placeholder="Buscar..." class="form-control" value="<?php echo $this->request->query["tab"] ?>" type="hidden" id="tab">
						<span class="input-group-btn">
							<button class="btn-secondary btn text-white" id="btn_find_adviser" type="submit">Filtrar Fechas</button>
						</span>
						<?php if (isset($fechas)): ?>
							<a href="<?php echo $this->Html->url(["action"=>"index", "?" => [ "tab" => $this->request->query["tab"] ] ]) ?>" class="btn btn-warning">Borrar fechas <i class="fa fa-times"></i></a>
						<?php endif ?>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
			<?php endif ?>



		<?php if (in_array(AuthComponent::user("role"),[5])): ?>
			<div class="col-md-12">
				<div class="form-group top_search">
					<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
					<?php echo $this->Form->text('tab', array('value'=>$tab,"type" => "hidden" )) ?>
					<?php echo $this->Form->text('commerce', array('value'=>isset($this->request->query["commerce"]) ? $this->request->query["commerce"] : "","type" => "hidden" )) ?>


					<div class="rangofechas input-group ">
						<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
						<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
						<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
						<input name="tab" placeholder="Buscar..." class="form-control" value="<?php echo $this->request->query["tab"] ?>" type="hidden" id="tab">
						<span class="input-group-btn">
							<button class="btn-secondary btn text-white" id="btn_find_adviser" type="submit">Filtrar Fechas</button>
						</span>
						<?php if (isset($fechas)): ?>
							<a href="<?php echo $this->Html->url(["action"=>"index", "?" => [ "tab" => $this->request->query["tab"] ] ]) ?>" class="btn btn-warning">Borrar fechas <i class="fa fa-times"></i></a>
						<?php endif ?>
					</div>

					<?php echo $this->Form->end(); ?>
				</div>
			</div>
			<?php endif ?>


	</div>
</div>


<div class="clearfix"></div>
<div class="row" style="display: block;">


	<div class="col-md-12">
		<?php if (in_array(AuthComponent::user("role"), [1,2,9]) && !empty($totales)): ?>
			<h3 class="mb-4">Pagos recaudados a los clientes</h3>
		<?php endif ?>
		<div class="x_panel">
			<div class="x_content">
				<div class="row">
				<div class="table-responsive">
							<?php
							 if(!empty($customers)): ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">

						<tbody>

							 	<?php foreach ($customers as $key => $value): ?>
							 		<tr>
										<td colspan="7" class="p-0">
											<div class="accordion" id="accordionExample">
											  <div class="card">
											    <div class="card-header p-0" id="heading<?php echo $key ?>">
											      <h2 class="m-0">
											        <button class="btn btn-link btn-block text-left capt resetbtn" type="button" data-toggle="collapse" data-target="#collapse<?php echo $key ?>" aria-expanded="true" aria-controls="collapse<?php echo $key ?>">
											         	<b>CC: <?php echo $value["identification"] ?></b> - <?php echo $value["name"] ?> <?php echo $value["last_name"] ?>
											        </button>
											      </h2>
											    </div>

											    <div id="collapse<?php echo $key ?>" class="collapse" aria-labelledby="heading<?php echo $key ?>" data-parent="#accordionExample">
											      <div class="card-body">
											      	<table class="table">
											      		<thead class="text-primary">
															<tr>
																<th><?php echo __('Obligación'); ?></th>
																<th><?php echo __('Fecha pago'); ?></th>
																<th><?php echo __('Valor pago'); ?></th>
																<th><?php echo $this->Paginator->sort('user_id', __('Recaudó')); ?></th>
																<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Comercio')); ?></th>
																<th><?php echo __('Acciones'); ?></th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($payments[$key] as $payment): ?>
																<tr>
																	<td>
																		<?php echo str_pad($payment["CreditsPlan"]["number_credit"], 6, "0", STR_PAD_LEFT); ?>
																	</td>
																	<td>

																		<?php echo $this->Utilidades->date_castellano($payment["Payment"]["fecha"]) ?>

																	</td>
																	<td>$ <?php echo number_format($payment["0"]["total"]); ?>&nbsp;</td>
																	<td class="capt">
																		<?php echo $payment["User"]["name"]; ?>
																	</td>
																	<td class="capt">
																		<?php if (empty($payment["ShopCommerce"])): ?>
																			PAGO WEB
																		<?php else: ?>
																			<?php echo $payment['ShopCommerce']['shop']; ?> -
																			<?php echo $payment['ShopCommerce']['name']; ?>
																		<?php endif ?>
																	</td>
																	<td class="td-actions">
																	    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'detail',$this->Utilidades->encrypt($payment['Payment']['receipt_id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info btn-xs viewPaymentDetail">
																	        <i class="fa fa-eye"></i>
																	    </a>
																	</td>
																</tr>
															<?php endforeach; ?>
														</tbody>
											      	</table>
											      </div>
											    </div>
											  </div>
										</td>
								  </tr>
							 	<?php endforeach ?>

						</tbody>
					</table>
				</div>
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
								<?php elseif(empty($customers) && !empty($this->request->query["commerce"])): ?>
							<tr><td class='text-center' colspan='<?php echo 1; ?>'>No existen resultados</td><tr>
							<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
		<?php if (in_array(AuthComponent::user("role"), [1,2,9]) && !empty($totales)): ?>
		<div class="x_panel">
			<div class="x_title">
				<h2 class="text-center float-none" >
					Recaudos por proveedor
				</h2>
			</div>
			<div class="x_content">
					<div class="row">
						<?php if (!isset($this->request->query["type_view"]) || ( isset($this->request->query["type_view"]) && $this->request->query["type_view"] == 1 ) ): ?>

							<?php foreach ($totales as $key => $value): ?>
								<div class="p-1 w-25 ">
                  <div class="tile-stats <?php if (isset($this->request->query["commerce"]) && $this->Utilidades->decrypt($this->request->query["commerce"]) == $value["Payment"]["shop_commerce_id"]){ echo "comercio_seleccionado";}?>">
                      <a href="<?php echo $this->Html->url(["action"=>"index","?" => array_merge($this->request->query,["commerce" => $this->Utilidades->encrypt($value["Payment"]["shop_commerce_id"]),"tab" => $tab ]) ]) ?>" class="">
                      	<span><b>$<?php echo number_format($value["0"]["total"]) ?></b> <?php echo $value["Shop"]["social_reason"] ?> - <?php echo $value["ShopCommerce"]["name"] ?> </span>
                      </a>
                  </div>
                </div>
							<?php endforeach ?>
						<?php else: ?>
							<div class="table-responsive">
								<table class="table table-hovered dataTable" id="tableTotalesId">
									<thead>
										<tr>
											<th>
												Proveedor
											</th>
											<th>
												Sucursal
											</th>
											<th>
												Valor
											</th>
											<th>
												Ver
											</th>
										</tr>
									</thead>
									<tbody>
										<?php if (empty($totales)): ?>
											<tr>
												<td colspan="3"> No hay información</td>
											</tr>
										<?php else: ?>
											<?php foreach ($totales as $key => $value): ?>

                      	<tr class="<?php if (isset($this->request->query["commerce"]) && $this->Utilidades->decrypt($this->request->query["commerce"]) == $value["Payment"]["shop_commerce_id"]){ echo "bg-red";}?>">
                      		<td>
                      			<?php echo $value["Shop"]["social_reason"] ?>
                      		</td>
                      		<td>
                      			<span><?php echo $value["ShopCommerce"]["name"] ?> </span>
                      		</td>
                      		<td>
                      			<b>$<?php echo number_format($value["0"]["total"]) ?></b>
                      		</td>
                      		<td>
                      			<a href="<?php echo $this->Html->url(["action"=>"index","?" => array_merge($this->request->query,["commerce" => $this->Utilidades->encrypt($value["Payment"]["shop_commerce_id"]),"tab" => $tab,"type_view" => 2 ]) ]) ?>" class="btn btn-info">
                      				<i class="fa fa-eye"></i>
                      			</a>
                      		</td>
                      	</tr>
											<?php endforeach ?>
										<?php endif ?>
									</tbody>
								</table>
							</div>
						<?php endif ?>
					</div>
			</div>
		</div>
		<?php endif ?>
	</div>


<div class="modal fade " id="pagoModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">
          <div class="content-tittles">
            <div class="line-tittles">|</div>
            <div>
              <h1>RECIBO</h1>
              <h2>DE PAGOS</h2>
            </div>
          </div>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="pagoBody">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<?php echo $this->Html->script("payments/admin.js?".rand(),array('block' => 'AppScript')); ?>


<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>

<?php $this->start("AppScript") ?>
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

	    if($("#btn_find_adviser").length){
	        $("#btn_find_adviser").trigger('click')
	    }


	});

</script>
<?php $this->end() ?>
