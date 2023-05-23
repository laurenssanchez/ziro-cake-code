<div class="page-title">
	<h3 class="">Módulo de Cobro Jurídico </h3>
</div>

<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link <?php echo $tab == 1 ? "active" : "" ?>" id="home-tab" href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "juridico", "?" => ["tab" => 1] ]) ?>" role="tab" aria-controls="home" aria-selected="true">COMPROMISOS DE GESTIÓN JURÍDICO</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php echo $tab == 2 ? "active" : "" ?>" id="profile-tab" href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "juridico", "?" => ["tab" => 2] ]) ?>" role="tab" aria-controls="profile" aria-selected="false">PANEL DE GESTIÓN JURÍDICO</a>
	</li>
</ul>

<div class="tab-content" id="myTabContent">

	<?php if ($tab == 1): ?>


		<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

			<div class="row">
				<div class="col-md-12">
					<div class="x_panel">
						<div class="x_title">

							<div class="col-md-5 mt-3 mb-2">
								<div class="title-tables">
									<h3 class="upper text-info d-inline">
										COMPROMISOS DE GESTIÓN DE COBRO JURÍDICO
									</h3>
								</div>
							</div>
							<?php if (AuthComponent::user("role") != 11): ?>
								<div class="form-group">
									<select class="form-control" id="changeUserData">
										<option>Todos los agentes de juridico</option>
										<?php foreach ($users as $key => $value): ?>
											<option value="<?php echo $this->Html->url(["controller"=>"credits_requests","action"=>"juridico","?"=>["tab"=>1, "user" => $this->Utilidades->encrypt($value["User"]["id"]) ] ]); ?>" <?php echo isset($this->request->query["user"]) && $this->Utilidades->encrypt($value["User"]["id"]) == $this->request->query["user"] ? "selected" : ""  ?>><?php echo $value["User"]["name"] ?></option>
										<?php endforeach ?>
									</select>
								</div>
							<?php endif ?>
						</div>
						<div class="x_content">
							<div class="col-md-2">
		                      <div class="card-header">
									<b>VER TAREAS</b>
							  </div>
		                      <div class="nav nav-tabs flex-column  bar_tabs" id="v-pills-tab" role="tablist" aria-orientation="vertical">
		                        <a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="false">HOY</a>
		                        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Esta semana</a>
		                        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Vencidos sin gestión</a>
		                      </div>

		                    </div>
		                    <div class="col-md-10">
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
													<th>Acciones</th>
												</tr>
											</thead>
											<tbody>
												<?php if (empty($commitmentsToday)): ?>
													<tr>
														<td class="text-center" colspan="8">
															No hay compromisos el día de hoy
														</td>
													</tr>
												<?php else: ?>
													<?php foreach ($commitmentsToday as $key => $value): ?>
														<tr>
															<td>
																<?php echo str_pad($value["CreditsPlan"]["Credit"]["credits_request_id"], 6, "0", STR_PAD_LEFT); ?>
															</td>
															<td class="capt">
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["name"] ?>
															</td>
															<td>
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["identification"] ?>
															</td>
															<td>
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
																<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($value["CreditsPlan"]["Credit"]['id']))); ?>" class="card-link btn btn-primary btn-sm detailCredit2">
          															<i class="fa fa-file" data-toggle="tooltip"  data-placement="top" title="" data-original-title="Detalle del crédito"></i>
        														</a>
        														<a data-toggle="modal" class="card-link btn btn-primary btn-xs text-white adminQuote" data-quote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" data-tab="1" data-toggle="tooltip" data-placement="top" title="Gestionar">
																	<i class="fa fa-eye" ></i>
																</a>
															</td>
														</tr>
													<?php endforeach ?>
												<?php endif ?>
											</tbody>
										</table>
									</div>
		                        </div>
		                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
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
													<th>Acciones</th>
												</tr>
											</thead>
											<tbody>
												<?php if (empty($commitmentsWeek)): ?>
													<tr>
														<td class="text-center" colspan="8">
															No hay compromisos en la semana
														</td>
													</tr>
												<?php else: ?>
													<?php foreach ($commitmentsWeek as $key => $value): ?>
														<tr>
															<td>
																<?php echo str_pad($value["CreditsPlan"]["Credit"]["credits_request_id"], 6, "0", STR_PAD_LEFT); ?>
															</td>
															<td class="capt">
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["name"] ?>
															</td>
															<td>
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["identification"] ?>
															</td>
															<td>
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
																<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($value["CreditsPlan"]["Credit"]['id']))); ?>" class="card-link btn btn-primary btn-sm detailCredit2">
          															<i class="fa fa-file" data-toggle="tooltip"  data-placement="top" title="" data-original-title="Detalle del crédito"></i>
        														</a>
        														<a data-toggle="modal" class="card-link btn btn-primary btn-xs text-white adminQuote" data-quote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" data-tab="1" data-toggle="tooltip" data-placement="top" title="Gestionar">
																	<i class="fa fa-eye" ></i>
																</a>
															</td>
														</tr>
													<?php endforeach ?>
												<?php endif ?>
											</tbody>
										</table>
									</div>
		                        </div>
		                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
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
													<th>Acciones</th>
												</tr>
											</thead>
											<tbody>
												<?php if (empty($commitmentsNoAdmin)): ?>
													<tr>
														<td class="text-center" colspan="8">
															No hay compromisos vencidos sin gestionar
														</td>
													</tr>
												<?php else: ?>
													<?php foreach ($commitmentsNoAdmin as $key => $value): ?>
														<tr>
															<td>
																<?php echo str_pad($value["CreditsPlan"]["Credit"]["credits_request_id"], 6, "0", STR_PAD_LEFT); ?>
															</td>
															<td class="capt">
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["name"] ?>
															</td>
															<td>
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["identification"] ?>
															</td>
															<td>
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
																<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($value["CreditsPlan"]["Credit"]['id']))); ?>" class="card-link btn btn-primary btn-sm detailCredit2">
          															<i class="fa fa-file" data-toggle="tooltip"  data-placement="top" title="" data-original-title="Detalle del crédito"></i>
        														</a>
        														<a data-toggle="modal" class="card-link btn btn-primary btn-xs text-white adminQuote" data-quote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" data-tab="1" data-toggle="tooltip" data-placement="top" title="Gestionar">
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
		                      </div>
		                    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php else: ?>
		<div class="row mt-2">
				<div class="col-md-8 mt-2">
					<div class="title-tables">
						<h3 class="upper text-info d-inline">
							Panel de gestión Jurídicos
						</h3>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group top_search">
						<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
							<div class="input-group">
								<?php echo $this->Form->input('q', array('placeholder'=>__('Buscar por cédula'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>isset($this->request->query["q"]) ?  $this->request->query["q"] : "","type"=>"number",)) ?>
								<?php echo $this->Form->hidden('tab', array('placeholder'=>__('Buscar por cédula'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>'2')) ?>

								<input type="submit" class="btn btn-primary" value="Filtrar">
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>

		</div>
		<div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			<div class="row">
				<div class="col-md-12">
					<div class="x_panel">
						<div class="x_content">
							<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
							  <?php if (!empty($datosCuotas)): ?>
							  	<?php foreach ($datosCuotas as $key => $value): ?>
							  		<?php $name = explode("@@", $key) ?>
							  		<div class="panel">
				                        <a class="panel-heading collapsed" role="tab" id="heading<?php echo md5($key) ?>" data-toggle="collapse" data-parent="#accordion" href="#Tab_<?php echo md5($key) ?>" aria-expanded="true" aria-controls="<?php echo md5($key) ?>">
				                          <h4 class="panel-title capt">
				                          	CC: <?php echo $name["1"] ?> - <?php echo $name["2"] ?> - # <?php echo str_pad($name["0"], 6, "0", STR_PAD_LEFT); ?>
				                          </h4>
				                        </a>
				                        <div id="Tab_<?php echo md5($key) ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo md5($key) ?>" style="">
				                          <div class="panel-body p-3">
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
															<th>Saldo total Crédito</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>
														<?php if (empty($value)): ?>
															<td class="text-center" colspan="10">
																No hay registro de mora
															</td>
														<?php else: ?>
															<?php foreach ($value as $keyVal => $valueVal): ?>
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
																		$<?php echo $valueVal["Credit"]["state"] == 1 ? "Pagada" : "Sin pagar" ?>
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
																	<td class="td-actions">
																		
                                                                        <!-- <?php echo $valueVal["CreditsPlan"]["credit_id"] ?> -->
																		<a data-toggle="modal" class="card-link btn btn-primary btn-xs text-white adminQuote"  data-quote="<?php echo $this->Utilidades->encrypt($valueVal["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($valueVal["CreditsPlan"]["credit_id"]) ?>" data-datplani="<?php echo $valueVal["CreditsPlan"]["id"] ?>" data-datplanc="<?php echo $valueVal["CreditsPlan"]["credit_id"] ?>" data-tab="1" data-toggle="tooltip" data-placement="top" data-title="Gestionar" title="Gestionar">
																			 <i class="fa fa-eye" ></i>
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
							  	<?php endforeach ?>
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
							  <?php endif ?>
		                    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>
</div>

<script>
	var urlTabOne = "<?php echo $this->Html->url(["controller"=>"credits_requests","action"=>"cobranza","?"=>["tab"=>1] ]); ?>"
</script>

<?php echo $this->element("/modals/detail_payment"); ?>
<?php echo $this->element("/modals/request"); ?>

<?php echo $this->Html->script("/vendors/ion.rangeSlider/js/ion.rangeSlider.min.js?".rand(),           array('block' => 'AppScript')); ?>
<?php echo $this->Html->script("cobranzas/admin.js?".rand(),           array('block' => 'AppScript')); ?>

<?php echo $this->Html->css("/vendors/ion.rangeSlider/css/ion.rangeSlider.css?".rand()); ?>
<?php echo $this->Html->css("/vendors/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css?".rand()); ?>


