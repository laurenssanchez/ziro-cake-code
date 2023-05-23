<?php
	$Previous = $page == 1 ? 1 : $page - 1;
	$Next = $page == $pages ? $page : $page + 1;
 ?>
<div class="page-title">
	<h3 class="">Módulo de Cobranzas</h3>
</div>

<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link <?php echo $tab == 1 ? "active" : "" ?>" id="home-tab" href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "cobranza", "?" => ["tab" => 1] ]) ?>" role="tab" aria-controls="home" aria-selected="true">COMPROMISOS DE GESTIÓN DE COBRANZA</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php echo $tab == 2 ? "active" : "" ?>" id="profile-tab" href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "cobranza", "?" => ["tab" => 2] ]) ?>" role="tab" aria-controls="profile" aria-selected="false">PANEL DE GESTIÓN DE COBRANZAS</a>
	</li>
</ul>

<div class="tab-content" id="myTabContent">

	<?php if ($tab == 1): ?>


		<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

			<div class="row">
				<div class="col-md-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="col-md-12 mt-3 mb-2">
								<div class="title-tables">
									<h3 class="upper text-info d-inline">
										COMPROMISOS DE GESTIÓN DE COBRANZA
									</h3>
								</div>
							</div>
							<div class="col-md-12">
								<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="changeUserData">
												Agente de cobranza
											</label>
											<select class="form-control" name="user">
												<option value="">Ver todos los agentes de cobranza</option>
												<?php foreach ($users as $key => $value): ?>
													<option value="<?php echo $value["User"]["id"]	; ?>" <?php echo isset($this->request->query["user"]) && ($value["User"]["id"]) == $this->request->query["user"] ? "selected" : ""  ?>><?php echo $value["User"]["name"] ?></option>
												<?php endforeach ?>
											</select>
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
									<div class="col-md-4">
										<div class="form-group mb-0">
											<?php echo $this->Form->input('commerce', array('label'=>__('Por Código Proveedor'),'placeholder'=>__('Selecciona el proveedor'), 'class'=>'form-control','div'=>false,'value'=> isset($commerce) ? $commerce : "", "options" => $list, "empty" => "Seleccionar" )) ?>
											<?php echo $this->Form->input('tab', array( "value" => 1, "type" => "hidden" )) ?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group mb-0">
											<?php echo $this->Form->input('ccCustomer', array('label'=>__('Cliente por cédula'),'placeholder'=>__('Ingresa la cédula'), 'class'=>'form-control','div'=>false,'value'=> isset($ccCustomer) ? $ccCustomer : "" )) ?>
										</div>
									</div>
									<div class="col-md-4">
										<?php echo $this->Form->input('state', array('label'=>__('Por estado'),'options'=> [ "0"=> "Sin terminar", 1=>"Terminado"] , 'class'=>'form-control','div'=>false,'value'=> isset($state_comp) ? $state_comp : "","empty" => "Seleccionar","required" => false )) ?>
									</div>
									<div class="col-md-4">
										<span class="input-group-btn">
											<button class="btn btn-primary mt-4" type="submit" id="busca">
												<?php echo __('Buscar '); ?> <i class="fa fa-search"></i>
											</button>
											<?php if (isset($ccCustomer) || isset($commerce) || isset($fechas) || isset($state_comp) || isset($usuario) ): ?>
												<a href="<?php echo $this->Html->url(["action"=>"intereses","?" => ["tab" => $tab]]) ?>" class="btn btn-warning deleteWar mt-4">
									          		Eliminar filtros
									          	</a>
											<?php endif ?>
											<input type="submit" class="btn btn-info mt-4" value="Exportar excel" name="excel_data">
										</span>
									</div>
								</div>
							</div>

							<?php echo $this->Form->end(); ?>

						</div>
						<div class="x_content p-0">

		                    <div class="col-md-12">
		                      <!-- Tab panes -->
		                       <div class="tab-content" id="v-pills-tabContent">
		                        <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
		                        	<div class="table-responsive">
										<table cellpadding="0" cellspacing="0" class="table table-striped table-hover mt-2">
											<thead>
												<tr>
													<th>Crédito</th>
													<th>Cliente</th>
													<th>Cédula</th>
													<th>Detalle Compromiso</th>
													<th>F.Creación</th>
													<th>F. Límite</th>
													<th>Gestiona</th>
													<th>Teléfono cliente</th>
													<th>Dirección cliente</th>
													<th>Acciones</th>
												</tr>
											</thead>
											<tbody>
												<?php if (empty($commitments)): ?>
													<tr>
														<td class="text-center" colspan="10">
															No hay compromisos el día de hoy
														</td>
													</tr>
												<?php else: ?>
													<?php foreach ($commitments as $key => $value): ?>
														<tr>
															<td>
																<b><?php echo str_pad($value["CreditsRequest"]["id"], 6, "0", STR_PAD_LEFT); ?></b>
															</td>
															<td class="capt">
																<?php echo $value["Customer"]["name"] ?> <?php echo $value["Customer"]["last_name"] ?>
															</td>
															<td>
																<?php echo $value["Customer"]["identification"] ?>
															</td>
															<td width="30%" class="upper">
																<?php echo $value["Commitment"]["commitment"] ?>
															</td>
															<td>
																<?php echo date("d-m-Y",strtotime($value["Commitment"]["created"])) ?>
															</td>
															<td>
																<?php echo date("d-m-Y",strtotime($value["Commitment"]["deadline"])) ?>
															</td>
															<td>
																<?php echo $value["User"]["name"] ?>
															</td>
															<td>
																<?php echo $value["CustomersPhone"]["phone_number"] ?>
															</td>
															<td>
																<?php echo $value["CustomersAddress"]["address_city"] ?> -
																<?php echo $value["CustomersAddress"]["address"] ?> -
																<?php echo $value["CustomersAddress"]["address_street"] ?>
															</td>
															<td>
																<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($value["Credit"]['id']))); ?>" class="card-link btn btn-primary btn-sm detailCredit2">
          															<i class="fa fa-file" data-toggle="tooltip"  data-placement="top" title="" data-original-title="Detalle del crédito"></i>
        														</a>
        														<a data-toggle="modal" class="card-link btn btn-primary btn-sm text-white adminQuote" data-quote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" data-tab="1" data-toggle="tooltip" data-placement="top" title="Gestionar">
																	<i class="fa fa-eye" ></i>
																</a>
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
		                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
		                        	<div class="table-responsive" id="commitmentsWeek">

									</div>
		                        </div>
		                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
		                        	<div class="table-responsive" id="commitmentsNoAdmin">

									</div>
		                        </div>
		                        <div class="tab-pane fade" id="v-pills-messages2" role="tabpanel" aria-labelledby="v-pills-messages-tab2">
		                        	<div class="table-responsive" id="commitmentsEnd" >

									</div>
		                        </div>
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

		<?php if (in_array($tab, [1,11])): ?>

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


		<?php endif ?>


	<?php else: ?>

		<div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			<div class="row">
				<div class="col-md-12">
					<div class="x_panel">
						<div class="x_content">
							<div class="row mt-2">
								<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'form100')); ?>
									<div class="col-md-12 mt-2">
										<div class="title-tables">
											<h3 class="upper text-info d-inline">
												Panel de gestión de Cobranzas
											</h3>
										</div>
									</div>

									<div class="col-md-6">
										<input type="text" id="filtroDias" class="rangeDays" value="" name="range" />
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="changeUserData">
												Agente de cobranza
											</label>
											<select class="form-control" name="user" id="changeUserData2">
												<option value="">Ver todos los agentes de cobranza</option>
												<?php foreach ($users as $key => $value): ?>
													<option value="<?php echo $value["User"]["id"]; ?>" <?php echo isset($this->request->query["user"]) && ($value["User"]["id"]) == $this->request->query["user"] ? "selected" : ""  ?>><?php echo $value["User"]["name"] ?></option>
												<?php endforeach ?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group mb-0">
											<?php echo $this->Form->input('commerce', array('label'=>__('Por Código Proveedor'),'placeholder'=>__('Selecciona el proveedor'), 'class'=>'form-control','div'=>false,'value'=> isset($commerce) ? $commerce : "","options" => $list, "empty" => "Seleccionar"  )) ?>
										</div>
									</div>


									<div class="col-md-4">
			    						<div class="form-group top_search">

											<div class="form-group">

												<?php echo $this->Form->input('q', array('placeholder'=>__('Buscar por cédula'), 'class'=>'form-control','label'=>"Buscar por cédula",'div'=>false,'value'=>isset($this->request->query["q"]) ?  $this->request->query["q"] : "","type"=>"number",)) ?>
												<?php echo $this->Form->hidden('tab', array('placeholder'=>__('Buscar por cédula'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>'2')) ?>

											</div>

										</div>
									</div>
									<div class="col-md-4">
										<input type="submit" class="btn btn-primary mt-4" value="Filtrar">
										<input type="submit" class="btn btn-primary mt-4" value="Exportar a excel" name="excel_data">
									</div>

									<?php if (AuthComponent::user("role") == 9 && !empty($dataPhone)): ?>

										<div class="col-md-2">
											<div class="form-group">
												<button class="btn btn-warning" id="sendAll">
													<i class="fa fa-envelope-o"></i>
												</button>
											</div>
										</div>

									<?php endif ?>

								<?php echo $this->Form->end(); ?>

							</div>
							<div class="table-responsive">
								<div class="row">
									<div class="col-md-12 table-responsive">
										<nav aria-label="Page navigation">
											<ul class="pagination">
											<li>
												<span aria-hidden="true">&laquo;</span>
												<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests","action_index","?"=>["tab"=>2,"range"=>$iniDay.";".$endDay,"page"=>$Previous])); ?>" aria-label="Previous">
												</a>
											</li>
											<?php $queryData = $this->request->query; ?>
											<?php for($i = 1; $i<= $pages; $i++) : ?>
												<?php $copyOne = $queryData; $copyOne["page"] = $i; ?>
												<li class="<?php echo $page == $i ? "active" : "" ?>" ><a href="<?php echo $this->Html->url(array("controller"=>"credits_requests","action_index","?"=>$copyOne)); ?>" ><?= $i; ?></a></li>
											<?php endfor; ?>
											<li>
												<?php $copyOne = $queryData; $copyOne["page"] = $Next; ?>
												<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests","action_index","?"=>$copyOne)); ?>" aria-label="Next">
													<span aria-hidden="true"> &raquo;</span>
												</a>
											</li>
										</ul>
											<select name="limit-records" id="limit-records">
												<option disabled="disabled" selected="selected">50</option>
											</select>
										</nav>
									</div>
								</div>
								<table cellpadding="0" cellspacing="0" class="table table-striped table-hover ">
									<thead class="text-primary">
										<tr>
											<!-- <th><?php echo $this->Paginator->sort('id', __('Obligación')); ?></th>-->
											<th>Obligación</th>
											<th>Cliente</th>
											<th>Cédula</th>
											<th>Dias Mora</th>
											<!-- <th>Honorarios</th> -->
											<th>Valor Cuota</th>
											<th>
												Interéses Mora
											</th>
											<th>Saldo Cuota</th>
											<th>Saldo Total Restante</th>
											<th>Fecha compromiso</th>
											<th>Última Gestión</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php if (empty($datosCuotas)): ?>
											<td class="text-center">
												No hay registro de mora
											</td>
										<?php else:
												//echo "<br>" .json_encode($datosCuotas);
												/*echo "<br>".json_encode($start);
												echo "<br> li".json_encode($limit);
												echo "<br> page ". $page;*/
												?>
											<?php $creditsData = []; ?>
											<?php foreach ($datosCuotas as $key => $value): ?>
												<?php if (!in_array($value["Credit"]["credits_request_id"],$creditsData)): ?>
													<?php
														$creditsData[] = $value["Credit"]["credits_request_id"];
													 ?>
												<?php else: ?>
													<?php continue; ?>
												<?php endif ?>
												<tr>
													<td>
														<b><?php echo $value["Credit"]["code_pay"]; ?></b>
													</td>
													<td class="capt">
														<?php echo $value["Customer"]["name"] ?> <?php echo $value["Customer"]["last_name"] ?>
													</td>
													<td>
														<?php echo $value["Customer"]["identification"] ?>
													</td>
													<td><?php echo $value["0"]["Credit__dias"] ?> dia(s)</td>

													<td>
														$<?php echo number_format($value["Credit"]["quota_value"]) ?>
													</td>
													<td>
														$<?php echo number_format($value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]) ?>
													</td>
													<td>
														<?php $totalDeuda = $value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"] + ($value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"]) + ($value["CreditsPlan"]["interest_value"]-$value["CreditsPlan"]["interest_payment"]) + ($value["CreditsPlan"]["others_value"]-$value["CreditsPlan"]["others_payment"]) ?>
														$<?php echo number_format($totalDeuda) ?>
													</td>
													<td>
														$<?php /*echo number_format($value["Credit"]["value_pending"])*/
														echo number_format( $value["capital_restante"] <= 1 || $value["Credit"]["state"] == 1 ? 0 : $value["capital_restante"])
														?>
													</td>
													<td>
														<?php echo strtoupper($value["User"]["name"]) ?>  <?php echo is_null($value["Credit"]["admin_date"]) ? "" : " / ".date("d-m-Y H:i:A",strtotime($value["Credit"]["admin_date"])) ?>
													</td>
													<td class="td-actions">
														<a data-toggle="modal" class="card-link btn btn-primary btn-sm text-white adminQuote" data-quote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" data-datplani="<?php echo $value["CreditsPlan"]["id"] ?>" data-datplanc="<?php echo $value["CreditsPlan"]["credit_id"] ?>" data-tab="1" data-parametro="<?php echo htmlspecialchars(json_encode($this->request->query), ENT_QUOTES, 'UTF-8') ?>">
															Gestión <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title=""></i>
														</a>
													</td>
												</tr>
											<?php endforeach ?>
										<?php endif ?>
									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>
</div>

<style type="text/css">
	ul.pagination li.active {
		background: #cce5ff;
	}
	div.bar_tabs a.active {
		background-color : #cce5ff !important;
	}
</style>

<?php echo $this->Html->css("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css",["block"=>"styleApp"]) ?>
<?php echo $this->Html->css("https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css",["block"=>"styleApp"]) ?>
<?php echo $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js",           array('block' => 'AppScript')); ?>

<?php $this->start("AppScript") ?>

<script>
	var urlTabOne = "<?php echo $this->Html->url(["controller"=>"credits_requests","action"=>"cobranza","?"=>["tab"=>1] ]); ?>"
	var iniDay = <?php echo $iniDay ?>;
	var endDay = <?php echo $endDay ?>;

	$(document).on("click", ".btn-allot-modal", function(){
		$("#preloader").show();
		var nom_array 	= $(this).data("nom_array");

		$.ajax({
			url: root+"credits_requests/cobranzatab?nom_array="+nom_array,
			type: "GET",
			nom_array: nom_array
		}).done(function(respuesta){
				var result =  respuesta.result;
				var html = "<table cellpadding='0' cellspacing='0' class='table table-striped table-hover mt-2'> ";
					html = html + "<thead>";
					html = html + "	<tr> ";
					html = html + "		<th>Crédito</th> ";
					html = html + "		<th>Cliente</th> ";
					html = html + "		<th>Cédula</th> ";
					html = html + "		<th>Detalle Compromiso</th> ";
					html = html + "		<th>F.Creación</th> ";
					html = html + "		<th>F. Límite</th> ";
					html = html + "		<th>Gestiona</th> ";
					html = html + "		<th>Acciones</th> ";
					html = html + "	</tr> ";
					html = html + "	</thead> ";
					html = html + "	<tbody> ";

				if (result.length == 0) {
					html = html + "<tr> ";
					html = html + "	<td class='text-center' colspan='8'> ";
					switch(nom_array) {
						case "commitmentsWeek":
							html = html + "			No hay compromisos en la semana ";
							break;
						case "commitmentsNoAdmin":
							html = html + "			No hay compromisos vencidos sin gestionar";
							break;
						case "commitmentsEnd":
							html = html + "			No hay compromisos vencidos sin gestionar";
							break;
					}
					html = html + "	</td> ";
					html = html + "</tr> ";
				} else {
					$.each(result, function(index,item) {
						//console.log(item);
						//console.log(item.CreditsPlan.Credit.credits_request_id);
						html = html + "<tr>";
						html = html + "	<td>";
						html = html + "		<b>" + item.CreditsPlan.Credit.credits_request_id.padStart(6, 0)+"</b>";
						html = html + "	</td>";
						html = html + "	<td class='capt'>";
						html = html + 	item.CreditsPlan.Credit.Customer.name + item.CreditsPlan.Credit.Customer.last_name ;
						html = html + "	</td>";
						html = html + "	<td>";
						html = html + 	item.CreditsPlan.Credit.Customer.identification;
						html = html + "	</td>";
						html = html + "	<td width='30%' class='upper'>";
						html = html + 	item.Commitment.commitment ;
						html = html + "	</td>";
						html = html + "	<td>";
						html = html + 	item.Commitment.created;
						//Date.parse(item.Commitment.created);
						// echo date("d-m-Y",strtotime($value["Commitment"]["created"]))
						html = html + "	</td>";
						html = html + "	<td>";
						html = html + 	item.Commitment.deadline;
						// echo date("d-m-Y",strtotime($value["Commitment"]["deadline"]))
						html = html + "	</td>";
						html = html + "	<td>";
						html = html + 	item.User.name;
						html = html + "	</td>";
						html = html + "	<td>";
						var crypto_id = item.CreditsPlan.crypto_id ;
						var crypto_credit_id = item.CreditsPlan.crypto_credit_id ;
						html = html + "<a href='"+root+"credits_requests/credit_detail/"+ crypto_credit_id +"' class='card-link btn btn-primary btn-sm detailCredit2' >";
						html = html + "	<i class='fa fa-file' data-toggle='tooltip'  data-placement='top' title='' data-original-title='Detalle del crédito'></i>";
						html = html + "</a>";
						if (nom_array != "commitmentsEnd") {
							html = html + "<a href='#' data-toggle='modal' class='card-link btn btn-primary btn-sm adminQuote' data-quote='"+	crypto_id +"' data-credit='" + crypto_credit_id +"' data-tab='1' >";
							html = html + "	<i class='fa fa-eye'  data-toggle='tooltip'  data-placement='top' title='' data-original-title='Gestionar'></i>";
							html = html + "</a>";
						}
						html = html + "	</td>";
						html = html + "</tr>";
					});


				}
				html = html + "	</tbody>";
				html = html + "</table>";

				switch(nom_array) {
					case "commitmentsWeek":
						$("#commitmentsWeek").html(html);
						break;
					case "commitmentsNoAdmin":
						$("#commitmentsNoAdmin").html(html);
						break;
					case "commitmentsEnd":
						$("#commitmentsEnd").html(html);
						break;
				}
				$("#preloader").hide();
		});
	});

	$("#CreditsRequestCommerce").select2();

</script>

<?php $this->end() ?>
<?php echo $this->element("/modals/detail_payment"); ?>
<?php echo $this->element("/modals/request"); ?>

<?php echo $this->Html->script("/vendors/ion.rangeSlider/js/ion.rangeSlider.min.js?".rand(),           array('block' => 'AppScript')); ?>
<?php echo $this->Html->script("cobranzas/admin.js?".rand(),           array('block' => 'AppScript')); ?>

<?php echo $this->Html->css("/vendors/ion.rangeSlider/css/ion.rangeSlider.css?".rand()); ?>
<?php echo $this->Html->css("/vendors/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css?".rand()); ?>


