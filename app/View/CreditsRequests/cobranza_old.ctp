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
							<div class="col-md-5 mt-3 mb-2">
								<div class="title-tables">
									<h3 class="upper text-info d-inline">
										COMPROMISOS DE GESTIÓN DE COBRANZA
									</h3>
								</div>						
							</div>						
							<div class="form-group">
								<select class="form-control" id="changeUserData">
									<option value="">Ver todos los agentes de cobranza</option>
									<?php foreach ($users as $key => $value): ?>
										<option value="<?php echo $this->Html->url(["controller"=>"credits_requests","action"=>"cobranza","?"=>["tab"=>1, "user" => $this->Utilidades->encrypt($value["User"]["id"]) ] ]); ?>" <?php echo isset($this->request->query["user"]) && $this->Utilidades->encrypt($value["User"]["id"]) == $this->request->query["user"] ? "selected" : ""  ?>><?php echo $value["User"]["name"] ?></option>										
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="x_content p-0">
							<div class="col-md-2">
		                      <div class="card-header">
									<b>VER TAREAS</b>
							  </div>
		                      <div class="nav nav-tabs flex-column  bar_tabs" id="v-pills-tab" role="tablist" aria-orientation="vertical">
		                        <a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="false">HOY</a>
		                        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Esta semana</a>
		                        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Vencidos sin gestión</a>
		                        <a class="nav-link" id="v-pills-messages-tab2" data-toggle="pill" href="#v-pills-messages2" role="tab" aria-controls="v-pills-messages" aria-selected="false">Cumplidos</a>
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
																<b><?php echo str_pad($value["CreditsPlan"]["Credit"]["credits_request_id"], 6, "0", STR_PAD_LEFT); ?></b>
															</td>
															<td class="capt">
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["name"] ?> <?php echo $value["CreditsPlan"]["Credit"]["Customer"]["last_name"] ?>
															</td>
															<td>
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["identification"] ?>
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
																<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($value["CreditsPlan"]["Credit"]['id']))); ?>" class="card-link btn btn-primary btn-sm detailCredit2">
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
																<b><?php echo str_pad($value["CreditsPlan"]["Credit"]["credits_request_id"], 6, "0", STR_PAD_LEFT); ?></b>
															</td>
															<td class="capt">
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["name"] ?> <?php echo $value["CreditsPlan"]["Credit"]["Customer"]["last_name"] ?>
															</td>
															<td>
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["identification"] ?>
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
																<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($value["CreditsPlan"]["Credit"]['id']))); ?>" class="card-link btn btn-primary btn-sm detailCredit2" >
          															<i class="fa fa-file" data-toggle="tooltip"  data-placement="top" title="" data-original-title="Detalle del crédito"></i>
        														</a>
        														<a href="#" data-toggle="modal" class="card-link btn btn-primary btn-sm adminQuote" data-quote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" data-tab="1" > 
																	<i class="fa fa-eye"  data-toggle="tooltip"  data-placement="top" title="" data-original-title="Gestionar"></i>
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
																<b><?php echo str_pad($value["CreditsPlan"]["Credit"]["credits_request_id"], 6, "0", STR_PAD_LEFT); ?></b>
															</td>
															<td class="capt">
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["name"] ?> <?php echo $value["CreditsPlan"]["Credit"]["Customer"]["last_name"] ?>
															</td>
															<td>
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["identification"] ?>
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
																<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($value["CreditsPlan"]["Credit"]['id']))); ?>" class="card-link btn btn-primary btn-sm detailCredit2">
          															<i class="fa fa-file" data-toggle="tooltip"  data-placement="top" title="" data-original-title="Detalle del crédito"></i>
        														</a>
        														<a data-toggle="modal" class="card-link btn btn-primary btn-sm text-white adminQuote" data-quote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" data-tab="1" > 
																	<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" data-original-title="Gestionar"></i>
																</a>	
															</td>	
														</tr>
													<?php endforeach ?>
												<?php endif ?>                                
											</tbody>
										</table>				
									</div>
		                        </div>
		                        <div class="tab-pane fade" id="v-pills-messages2" role="tabpanel" aria-labelledby="v-pills-messages-tab2">
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
												<?php if (empty($commitmentsEnd)): ?>
													<tr>
														<td class="text-center" colspan="8">
															No hay compromisos vencidos sin gestionar
														</td>
													</tr>
												<?php else: ?>
													<?php foreach ($commitmentsEnd as $key => $value): ?>
														<tr>
															<td>
																<b><?php echo str_pad($value["CreditsPlan"]["Credit"]["credits_request_id"], 6, "0", STR_PAD_LEFT); ?></b>
															</td>
															<td class="capt">
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["name"] ?> <?php echo $value["CreditsPlan"]["Credit"]["Customer"]["last_name"] ?>
															</td>
															<td>
																<?php echo $value["CreditsPlan"]["Credit"]["Customer"]["identification"] ?>
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
																<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($value["CreditsPlan"]["Credit"]['id']))); ?>" class="card-link btn btn-primary btn-sm detailCredit2">
          															<i class="fa fa-file" data-toggle="tooltip"  data-placement="top" title="" data-original-title="Detalle del crédito"></i>
        														</a>
        														<!-- <a data-toggle="modal" class="card-link btn btn-primary btn-sm text-white adminQuote" data-quote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" data-tab="1" > 
																	<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" data-original-title="Gestionar"></i>
																</a> -->	
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
				</div>
			</div>	
		</div>
	<?php else: ?>

		<div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			<div class="row">
				<div class="col-md-12">
					<div class="x_panel">
						<div class="x_content">
							<div class="row mt-2">
								<form action="" role="form" class="form100" id="" method="get" accept-charset="utf-8">		
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

									<?php if (AuthComponent::user("role") == 9 && !empty($dataPhone)): ?>

										<div class="col-md-2">
											<div class="form-group">
												<button class="btn btn-warning" id="sendAll">
													<i class="fa fa-envelope-o"></i>
												</button>
											</div>
										</div>

									<?php endif ?>


							
							</div>
							<div class="table-responsive">
								<div class="row">
									<div class="col-md-10">
										<nav aria-label="Page navigation">
											<ul class="pagination">
											<li>
												<span aria-hidden="true">&laquo;</span>
											<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests","action_index","?"=>["tab"=>2,"range"=>$iniDay.";".$endDay,"page"=>$Previous])); ?>" aria-label="Previous">
											</a>
											</li>
											<?php for($i = 1; $i<= $pages; $i++) : ?>
												<li class="<?php echo $page == $i ? "active" : "" ?>" ><a href="<?php echo $this->Html->url(array("controller"=>"credits_requests","action_index","?"=>["tab"=>2,"range"=>$iniDay.";".$endDay,"page"=>$i])); ?>" ><?= $i; ?></a></li>
											<?php endfor; ?>
											<li>
											<a href="<?php echo $this->Html->url(array("controller"=>"credits_requests","action_index","?"=>["tab"=>2,"range"=>$iniDay.";".$endDay,"page"=>$Next])); ?>" aria-label="Next">
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
											<th>Mora</th>
											<!-- <th>Honorarios</th> -->
											<th>Valor Cuota</th>
											<th>
												Intereses
											</th>
											<th>Saldo Cuota</th>
											<th>Saldo Crédito</th>
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
													<td><?php echo $value["0"]["dias"] ?> dias</td>
			
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
														$<?php echo number_format($value["Credit"]["value_pending"]) ?>
													</td>
													<td>
														<?php echo strtoupper($value["User"]["name"]) ?>  <?php echo is_null($value["Credit"]["admin_date"]) ? "" : " / ".date("d-m-Y H:i:A",strtotime($value["Credit"]["admin_date"])) ?>
													</td>
													<td class="td-actions">
														<a data-toggle="modal" class="card-link btn btn-primary btn-sm text-white adminQuote" data-quote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" data-datplani="<?php echo $value["CreditsPlan"]["id"] ?>" data-datplanc="<?php echo $value["CreditsPlan"]["credit_id"] ?>" data-tab="1">
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
</style>

<script>
	var urlTabOne = "<?php echo $this->Html->url(["controller"=>"credits_requests","action"=>"cobranza","?"=>["tab"=>1] ]); ?>"
	var iniDay = <?php echo $iniDay ?>;
	var endDay = <?php echo $endDay ?>;
</script>

<?php echo $this->element("/modals/detail_payment"); ?>  
<?php echo $this->element("/modals/request"); ?>	

<?php echo $this->Html->script("/vendors/ion.rangeSlider/js/ion.rangeSlider.min.js?".rand(),           array('block' => 'AppScript')); ?>
<?php echo $this->Html->script("cobranzas/admin.js?".rand(),           array('block' => 'AppScript')); ?>

<?php echo $this->Html->css("/vendors/ion.rangeSlider/css/ion.rangeSlider.css?".rand()); ?>
<?php echo $this->Html->css("/vendors/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css?".rand()); ?>


