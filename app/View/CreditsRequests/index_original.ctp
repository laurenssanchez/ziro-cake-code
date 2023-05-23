<?php

$whitelist = array(
	'127.0.0.1',
	'::1'
);
?>
<div class="page-title">
	<div class="row">
		<div class="col-md-8">
			<h3><?php echo __('Panel de solicitudes'); ?></h3>
		</div>
		<?php if (in_array(AuthComponent::user("role"), [4, 6])) : ?>
			<div class="col-md-3">
				<a href="" class="btn btn-info pull-right" id="btnSearch">
					Buscar cliente <i class="fa fa-search vtc"> </i>
				</a>
			</div>
		<?php endif ?>




		<?php if (in_array(AuthComponent::user("role"), [1, 2, 3])) : ?>
			<div class="col-md-12">
				<div class="form-group topsearch controlmb">
					<?php echo $this->Form->create('', array('role' => 'form', 'type' => 'GET', 'class' => '')); ?>
					<div class="row">
						<div class="col-md-2 mb-2">
							<?php echo $this->Form->input('usoFecha', array('label' => __('¿Fechas en consulta?'), 'class' => 'form-control', 'div' => false, "options" => [0 => "NO", 1 => "SI"], 'value' => isset($usoFecha) ? $usoFecha : "")) ?>
						</div>
						<div class="col-md-2">
							<div class="form-group mb-0">
								<?php echo $this->Form->input('ccCustomer', array('label' => __('Cliente por cédula'), 'placeholder' => __('Ingresa la cédula'), 'class' => 'form-control', 'div' => false, 'value' => isset($ccCustomer) ? $ccCustomer : "")) ?>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group mb-0">
								<?php echo $this->Form->input('idrequest', array('label' => __('Solicitud por Código'), 'placeholder' => __('Ingresa el código'), 'class' => 'form-control', 'div' => false, 'value' => isset($idrequest) ? $idrequest : "")) ?>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group mb-0">
								<?php echo $this->Form->input('commerce', array('label' => __('Por Código Proveedor'), 'placeholder' => __('Ingresa el código'), 'class' => 'form-control', 'div' => false, 'value' => isset($commerce) ? $commerce : "")) ?>
							</div>
						</div>
						<div class="col-md-2">
							<?php echo $this->Form->create('', array('role' => 'form', 'type' => 'GET', 'class' => '')); ?>
							<div class="rangofechas input-group mb-0">
								<label for="">Rango de fechas</label>
								<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
								<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
								<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
							</div>
						</div>
						<div class="mtbtn">
							<span class="input-group-btn ">
								<button class="btn btn-success" type="submit" id="busca">
									<?php echo __('Buscar '); ?> <i class="fa fa-search"></i>
								</button>
								<?php if (isset($ccCustomer) || isset($commerce) || isset($fechas) || isset($idrequest)) : ?>
									<a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index", "?" => ["usoFecha" => "1", "ccCustomer" => "", "commerce" => "", "dateIni" => date("Y-m-d", strtotime("-1 month")), "dateEnd" => date("Y-m-d")]]) ?>" class="btn btn-warning">
										Eliminar filtro
									</a>
								<?php endif ?>
							</span>
						</div>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		<?php endif ?>
	</div>
</div>


<div class="clearfix"></div>
<?php if (AuthComponent::user("role") == 5) : ?>
	<ul class="nav nav-tabs controltabs">
		<li class="nav-item">
			<a class="nav-link  <?php echo $this->request->query["tab"] == 1 ? "active" : "" ?>" href="<?php echo $this->Html->url(array("controller" => "credits_requests", "action" => "index", "?" => ["tab" => 1])) ?>">Solicitudes en Proceso</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?php echo $this->request->query["tab"] == 2 ? "active" : "" ?>" href="<?php echo $this->Html->url(array("controller" => "credits_requests", "action_index", "?" => ["tab" => 2])) ?>">Solicitudes Aprobadas</a>
		</li>
		<!--   <li class="nav-item">
    <a class="nav-link <?php // echo $this->request->query["tab"] == 3 ? "active" : ""
						?>" href="<?php // echo $this->Html->url(array("controller"=>"credits_requests","action_index","?"=>["tab"=>3]))
																									?>">Créditos Finalizados</a>
  </li> -->
	</ul>
	<?php if (AuthComponent::user("role") == 5 && $this->request->query["tab"] == 1) : ?>


		<div class="row" style="display: block;">
			<div class="col-md-12">
				<div class="x_panel">
					<div class="x_content">
						<div class="table-responsive">
							<?php if (empty($creditsRequests) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>
									$(function() {
										demo.showNotification('<?php echo __('No se encontraron datos'); ?>', 'top', 'center', 'info');
									})
								</script>
							<?php endif; ?>
							<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
								<thead class="">
									<tr>
										<th><?php echo $this->Paginator->sort('id', __('Obligación')); ?></th>
										<th><?php echo $this->Paginator->sort('request_Value', __('Valor Solicitado')); ?></th>
										<th><?php echo $this->Paginator->sort('request_type', __('Frecuencia')); ?></th>
										<th><?php echo $this->Paginator->sort('request_number', __('Cuotas')); ?></th>
										<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Proveedor donde se solicitó')); ?></th>
										<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
										<th><?php echo $this->Paginator->sort('created', __('Fecha de solicitud')); ?></th>

										<th><?php echo __('Acciones'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($creditsRequests)) : ?>
										<?php foreach ($creditsRequests as $creditsRequest) : ?>
											<tr>
												<td>
													<?php echo $creditsRequest["CreditsRequest"]["code_pay"]; ?>
												</td>
												<td><?php echo number_format($creditsRequest['CreditsRequest']['request_value']); ?>&nbsp;</td>
												<!-- Frecuencia -->
												<td>
													<?php
													if ($creditsRequest["CreditsRequest"]["request_type"] == 1)
														$tipoCredito = "Mensual";
													else if ($creditsRequest["CreditsRequest"]["request_type"] == 3)
														$tipoCredito = "45 días";
													else if ($creditsRequest["CreditsRequest"]["request_type"] == 4)
														$tipoCredito = "60 días";
													else
														$tipoCredito = "Quincenal";

													echo $tipoCredito;
													?>
												</td>

												<!-- <td><?php echo $creditsRequest["CreditsRequest"]["request_type"] == 1 ? "Mensual" : "Quincenal" ?></td> -->

												<td><?php echo h($creditsRequest['CreditsRequest']['request_number']); ?>&nbsp;</td>
												<td><?php echo h($creditsRequest['ShopCommerce']['name'] . " - " . $creditsRequest['ShopCommerce']["Shop"]['social_reason']); ?>&nbsp;</td>

												<td>
													<?php switch ($creditsRequest['CreditsRequest']['state']) {
														case '0':
															echo "Solicitud";
															break;
														case '1':
														case '2':
															echo "Estudio";
															break;
														case '3':
															echo "Aprobado sin desembolsar";
															break;
														case '4':
															echo "Rechazado";
															break;
														case '5':
															echo "Aprobado con desembolso";
															break;
													} ?>
												</td>

												<td><?php echo date("d-m-Y h:i A", strtotime($creditsRequest['CreditsRequest']['created'])); ?>&nbsp;</td>
												<td class="td-actions">
													<?php if (AuthComponent::user("role") == 5 && $creditsRequest['CreditsRequest']['state'] == 0) : ?>
														<a rel="tooltip" href="javascript:void(0)" class="btn btn-info btn-sm deatil_payment" data-total="<?php echo $creditsRequest['CreditsRequest']['request_value'] ?>" data-frecuency="<?php echo $creditsRequest["CreditsRequest"]["request_type"] ?>" data-number="<?php echo $creditsRequest['CreditsRequest']['request_number'] ?>">
															<i class="fa fa-eye"></i>
														</a>
													<?php else : ?>

														<a rel="tooltip" href="javascript:void(0)" title="<?php echo __('Ver detalle solicitud'); ?>" class="btn btn-outline-info  btn-sm viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($creditsRequest["Customer"]["id"]) ?>">
															<i class="fa fa-eye"></i>
														</a>

													<?php endif ?>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td class='text-center' colspan='<?php echo 6; ?>'>No existen resultados</td>
										<tr>
										<?php endif; ?>
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
	<?php elseif (AuthComponent::user("role") == 5 && $this->request->query["tab"] == 2) : ?>
		<div class="row" style="display: block;">
			<div class="col-md-12">
				<div class="x_panel">
					<div class="x_content">
						<div class="table-responsive">
							<?php if (empty($creditsRequests) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>
									$(function() {
										demo.showNotification('<?php echo __('No se encontraron datos'); ?>', 'top', 'center', 'info');
									})
								</script>
							<?php endif; ?>
							<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
								<thead class="">
									<tr>
										<th><?php echo $this->Paginator->sort('id', __('Obligación')); ?></th>
										<?php if (AuthComponent::user("role") != 5) : ?>
											<th><?php echo $this->Paginator->sort('customer_id', __('Cliente')); ?></th>
										<?php endif ?>
										<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Proveedor')); ?></th>
										<th><?php echo $this->Paginator->sort('created', __('Fecha solicitud')); ?></th>
										<th><?php echo $this->Paginator->sort('request_type', __('Frecuencia')); ?></th>
										<th><?php echo $this->Paginator->sort('date_admin', __('Fecha aprobación')); ?></th>
										<th><?php echo $this->Paginator->sort('value_approve', __('Valor')); ?></th>
										<th><?php echo $this->Paginator->sort('number_approve', __('Cuotas')); ?></th>
										<th><?php echo $this->Paginator->sort('number_approve', __('Retirado')); ?></th>
										<th><?php echo __('Acciones'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($creditsRequests)) : ?>
										<?php foreach ($creditsRequests as $creditsRequest) : ?>
											<tr>
												<?php if (AuthComponent::user("role") != 5) : ?>
													<td>
														<?php echo $creditsRequest['Customer']['name']; ?>
													</td>
												<?php endif ?>
												<td>
													<?php echo $creditsRequest["CreditsRequest"]["code_pay"]; ?>
												</td>
												<td><?php echo h($creditsRequest['ShopCommerce']['name'] . " - " . $creditsRequest['ShopCommerce']["Shop"]['social_reason']); ?>&nbsp;</td>


												<td><?php echo date("d-m-Y h:i A", strtotime($creditsRequest['CreditsRequest']['created'])); ?>&nbsp;</td>

												<td>
													<?php if (!empty($creditsRequest["Credit"])) : ?>
														<?php
														if ($creditsRequest["Credit"]["type"] == 1)
															$tipoCredito = "Mensual";
														else if ($creditsRequest["Credit"]["type"] == 3)
															$tipoCredito = "45 días";
														else if ($creditsRequest["Credit"]["type"] == 4)
															$tipoCredito = "60 días";
														else
															$tipoCredito = "Quincenal";
														?>
														<?php echo $tipoCredito ?>

													<?php else : ?>
														<?php
														if ($creditsRequest["CreditsRequest"]["request_type"] == 1)
															$tipoCredito = "Mensual";
														else if ($creditsRequest["CreditsRequest"]["request_type"] == 3)
															$tipoCredito = "45 días";
														else if ($creditsRequest["CreditsRequest"]["request_type"] == 4)
															$tipoCredito = "60 días";
														else
															$tipoCredito = "Quincenal";
														?>
														<?php echo $tipoCredito ?>
													<?php endif ?>
												</td>

												<td>
													<?php echo date("d-m-Y h:i A", strtotime($creditsRequest['CreditsRequest']['date_admin'])); ?>
												</td>
												<td>$<?php echo !empty($creditsRequest["CreditsRequest"]["value_approve"]) ? number_format($creditsRequest["CreditsRequest"]["value_approve"]) : "" ?></td>
												<td><?php echo !empty($creditsRequest["CreditsRequest"]["number_approve"]) ? $creditsRequest["CreditsRequest"]["number_approve"] : "" ?></td>
												<td>$<?php echo !empty($creditsRequest["CreditsRequest"]["value_disbursed"]) ? number_format($creditsRequest["CreditsRequest"]["value_disbursed"]) : "" ?></td>
												<td class="td-actions">
													<?php if (AuthComponent::user("role") == 5 && $creditsRequest['CreditsRequest']['state'] == 0) : ?>
														<a rel="tooltip" href="javascript:void(0)" class="btn btn-info btn-sm deatil_payment" data-total="<?php echo $creditsRequest['CreditsRequest']['request_value'] ?>" data-number="<?php echo $creditsRequest['CreditsRequest']['request_number'] ?>">
															<i class="fa fa-eye"></i>
														</a>
													<?php else : ?>

														<a rel="tooltip" href="javascript:void(0)" title="<?php echo __('Ver detalle de credito'); ?>" class="btn btn-outline-info  btn-sm viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($creditsRequest["Customer"]["id"]) ?>">
															<i class="fa fa-eye"></i>
														</a>
														<?php if (isset($creditsRequest["Credit"]['id'])) : ?>
															<a rel="tooltip" href="<?php echo $this->Html->url(array("controller" => "credits_requests", 'action' => 'credit_detail', $this->Utilidades->encrypt($creditsRequest["Credit"]['id']))); ?>" title="<?php echo __('Ver detalle de crédito'); ?>" class="btn btn-outline-primary btn-sm detailCredit2">
																<i class="fa fa-usd"></i>
															</a>
														<?php endif ?>
													<?php endif ?>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td class='text-center' colspan='<?php echo 6; ?>'>No existen resultados</td>
										<tr>
										<?php endif; ?>
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

	<?php elseif (AuthComponent::user("role") == 5 && $this->request->query["tab"] == 3) : ?>

		<div class="row" style="display: block;">
			<div class="col-md-12">
				<div class="x_panel">
					<div class="x_content">
						<div class="table-responsive">
							<?php if (empty($creditsRequests) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>
									$(function() {
										demo.showNotification('<?php echo __('No se encontraron datos'); ?>', 'top', 'center', 'info');
									})
								</script>
							<?php endif; ?>
							<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
								<thead class="">
									<tr>
										<?php if (AuthComponent::user("role") != 5) : ?>
											<th><?php echo $this->Paginator->sort('customer_id', __('Cliente')); ?></th>
										<?php endif ?>
										<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Proveedor')); ?></th>
										<th><?php echo $this->Paginator->sort('request_type', __('Frecuencia de pago')); ?></th>
										<th><?php echo $this->Paginator->sort('created', __('Fecha de solicitud')); ?></th>
										<th><?php echo $this->Paginator->sort('date_admin', __('Fecha de Finalización')); ?></th>
										<th><?php echo $this->Paginator->sort('value_approve', __('Valor aprobado')); ?></th>
										<th><?php echo $this->Paginator->sort('value_approve', __('Valor Pagado')); ?></th>
										<th><?php echo $this->Paginator->sort('number_approve', __('Cuotas Pagadas')); ?></th>
										<th><?php echo __('Acciones'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($creditsRequests)) : ?>
										<?php foreach ($creditsRequests as $creditsRequest) : ?>
											<tr>
												<?php if (AuthComponent::user("role") != 5) : ?>
													<td>
														<?php echo $creditsRequest['Customer']['name']; ?>
													</td>
												<?php endif ?>
												<td><?php echo h($creditsRequest['ShopCommerce']['name'] . " - " . $creditsRequest['ShopCommerce']["Shop"]['social_reason']); ?>&nbsp;</td>


												<td>
													<?php if (!empty($creditsRequest["Credit"])) : ?>
														<?php
														if ($creditsRequest["Credit"]["type"] == 1)
															$tipoCredito = "Mensual";
														else if ($creditsRequest["Credit"]["type"] == 3)
															$tipoCredito = "45 días";
														else if ($creditsRequest["Credit"]["type"] == 4)
															$tipoCredito = "60 días";
														else
															$tipoCredito = "Quincenal";

														echo $tipoCredito;
														?>
														<!-- <?php echo $creditsRequest["Credit"]["type"] == 1 ? "Mensual" : "Quincenal" ?> -->

													<?php else : ?>

														<?php
														if ($creditsRequest["CreditsRequest"]["request_type"] == 1)
															$tipoCredito = "Mensual";
														else if ($creditsRequest["CreditsRequest"]["request_type"] == 3)
															$tipoCredito = "45 días";
														else if ($creditsRequest["CreditsRequest"]["request_type"] == 4)
															$tipoCredito = "60 días";
														else
															$tipoCredito = "Quincenal";

														echo $tipoCredito;
														?>

														<!-- <?php echo $creditsRequest["CreditsRequest"]["request_type"] == 1 ? "Mensual" : "Quincenal" ?> -->
													<?php endif ?>
												</td>



												<td><?php echo date("d-m-Y h:i A", strtotime($creditsRequest['CreditsRequest']['created'])); ?>&nbsp;</td>

												<td><?php echo $creditsRequest["CreditsRequest"]["date_admin"] ?></td>
												<td>$<?php echo !empty($creditsRequest["CreditsRequest"]["value_approve"]) ? number_format($creditsRequest["CreditsRequest"]["value_approve"]) : "" ?></td>
												<td>sdd</td>
												<td><?php echo !empty($creditsRequest["CreditsRequest"]["number_approve"]) ? $creditsRequest["CreditsRequest"]["number_approve"] : "" ?></td>
												<td class="td-actions">
													<?php if (AuthComponent::user("role") == 5 && $creditsRequest['CreditsRequest']['state'] == 0) : ?>
														<a rel="tooltip" href="javascript:void(0)" class="btn btn-info btn-sm deatil_payment" data-total="<?php echo $creditsRequest['CreditsRequest']['request_value'] ?>" data-frecuency="<?php echo $creditsRequest["CreditsRequest"]["request_type"] ?>" data-number="<?php echo $creditsRequest['CreditsRequest']['request_number'] ?>">
															<i class="fa fa-eye"></i>
														</a>
													<?php else : ?>

														<a rel="tooltip" href="javascript:void(0)" title="<?php echo __('Ver detalle solicitud'); ?>" class="btn btn-outline-info  btn-sm viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($creditsRequest["Customer"]["id"]) ?>">
															<i class="fa fa-eye"></i>
														</a>
														<a rel="tooltip" href="javascript:void(0)" title="<?php echo __('Pagar Cuota'); ?>" class="btn btn-primary btn-sm">
															Paz y Salvo
														</a>

													<?php endif ?>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td class='text-center' colspan='<?php echo 7; ?>'>No existen resultados</td>
										<tr>
										<?php endif; ?>
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

<?php elseif (in_array(AuthComponent::user("role"), [1, 2, 3])) : ?>

	<?php

	$urldata = Router::url($this->here, true);
	$urlParams = [];

	$urlParams = array();
	$urlParams = $this->request->query;
	?>

	<div class="container-fluid p-0">
		<div class="x_panel">
			<div class="x_content">
				<div class="row">
					<div class="col-md-12 steps-credits">
						<div class="row">
							<div class="col-xl-3 col-lg-6 col-md-6 credit-step1">
								<div class="tittle-column-credit">
									<h2 class="d-inline">Recibidos</h2>
									<span class="qty-credits pull-right">
										<?php echo count($requestsNoAdmin); ?>
									</span>
								</div>
								<div class="credit-columns">
									<?php $totalSolicitud = 0; ?>
									<?php foreach ($requestsNoAdmin as $key => $value) : ?>
										<?php if ($value["CreditsRequest"]["state"] != 0) : ?>
											<?php continue; ?>
										<?php endif ?>
										<div class="card sizecardtext">
											<div class="card-body">
												<h5 class="card-title"> <strong class="text-right"><?php echo str_pad($value["CreditsRequest"]["id"], 6, "0", STR_PAD_LEFT); ?></strong>
													<?php if (empty($value["Customer"]["email"])) : ?>
														<span class="creditrad">TRADICIONAL</span>
													<?php endif ?>
													<?php if ($value["CreditsRequest"]["extra"] == 1) : ?>
														<span class="creditrad">(AUMENTO DE CUPO)</span>
													<?php endif ?>
													<br><?php echo $value["Customer"]["name"] ?> <?php echo $value["Customer"]["last_name"] ?>
												</h5>
												<div class="pr-2 mb-2 color">
													<p class="card-text d-inline">
														<b>CC:</b> <?php echo $value["Customer"]["identification"] ?>
														<b>Solicita:</b> $<?php echo number_format($value["CreditsRequest"]["request_value"]) ?> x <?php echo $value["CreditsRequest"]["request_number"] ?> cuotas
													</p>
													<p class="card-text d-inline"><b>Celular:</b> <?php echo $value["Customer"]["CustomersPhone"][0]["phone_number"] ?></p>

													<p class="card-text d-inline"><b>Proveedor:</b> <?php echo $value["ShopCommerce"]["Shop"]["social_reason"] ?> - <?php echo $value["ShopCommerce"]["name"] ?> </p>
												</div>
												<?php if (in_array(AuthComponent::user("role"), [2, 3])) : ?>
													<a href="#" class="card-link btn btn-secondary btn-sm pull-right passTome" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>" data-user="<?php echo AuthComponent::user("id") ?>">
														<i class="fa fa-chevron-circle-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pasar a Estudio"></i>
													</a>
												<?php endif ?>
												<a href="#" class="card-link btn btn-outline-secondary btn-sm pull-right viewCustomerRequest mb-1" data-customer="<?php echo $this->Utilidades->encrypt($value["Customer"]["id"]) ?>" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
													<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
												</a>
												<?php if (AuthComponent::user("role") == "2") : ?>
													<a href="#" class="card-link btn btn-outline-secondary btn-sm pull-right asignUser" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
														<i class="fa fa-user" data-toggle="tooltip" data-placement="top" title="" data-original-title="Asignar usuario"></i>
													</a>
												<?php endif ?>
											</div>
										</div>
										<?php $totalSolicitud += $value["CreditsRequest"]["request_value"] ?>
									<?php endforeach ?>
								</div>
								<div class="sumcolum">
									<p>Total <b>$ <?php echo number_format($totalSolicitud) ?></b></p>
								</div>
							</div>
							<div class="col-xl-3 col-lg-6 col-md-6 credit-step2">
								<div class="tittle-column-credit">
									<h2 class="d-inline">En estudio</h2>
									<span class="qty-credits pull-right">
										<?php echo count($requestPendingStop); ?>
									</span>

									<div class="btn-group pull-right filters-request">
										<button type="button" class="btn btn-outline-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<?php if (isset($this->request->query["tab1"])) {
												switch ($this->Utilidades->decrypt($this->request->query["tab1"])) {
													case "2":
														echo "Solo detenidos";
														break;
													case "1":
														echo "Solo pendientes";
														break;

													default:
														echo "Todos";
														break;
												}
											} else {
												echo "Todos";
											} ?>
										</button>
										<div class="dropdown-menu showitems">
											<?php unset($urlParams["tab1"]); ?>
											<a class="dropdown-item " href="<?php echo $this->Html->url(["?" => $urlParams]) ?>">Todos</a>
											<?php $urlParams["tab1"] = $this->Utilidades->encrypt("2"); ?>
											<a class="dropdown-item <?php echo isset($this->request->query["tab1"]) && $this->request->query["tab1"] == $this->Utilidades->encrypt("2") ? "active" : ""; ?>" href="<?php echo $this->Html->url(["?" => $urlParams]) ?>">Solo detenidos</a>
											<?php $urlParams["tab1"] = $this->Utilidades->encrypt("1"); ?>
											<a class="dropdown-item  <?php echo isset($this->request->query["tab1"]) && $this->request->query["tab1"] == $this->Utilidades->encrypt("1") ? "active" : ""; ?>" href="<?php echo $this->Html->url(["?" => $urlParams]) ?>">Solo pendientes</a>
										</div>
									</div>

								</div>
								<div class="credit-columns">
									<?php $totalStudio = 0; ?>
									<?php foreach ($requestPendingStop as $key => $value) : ?>
										<?php if (!in_array($value["CreditsRequest"]["state"], [1, 2])) : ?>
											<?php continue; ?>
										<?php endif ?>

										<div class="sizecardtext card <?php echo count($value["CreditsRequestsComment"]) >= 1 ? "bg-outline-yellow" : "" ?>">
											<div class="card-body">
												<h5 class="card-title"><strong class="text-right"><?php echo str_pad($value["CreditsRequest"]["id"], 6, "0", STR_PAD_LEFT); ?></strong>
													<?php if (empty($value["Customer"]["email"])) : ?>
														<span class="creditrad">TRADICIONAL</span>
													<?php endif ?>
													<?php if ($value["CreditsRequest"]["extra"] == 1) : ?>
														<span class="creditrad">(AUMENTO DE CUPO)</span>
													<?php endif ?>
													<br> <?php echo $value["Customer"]["name"] ?> <?php echo $value["Customer"]["last_name"] ?> <br>
													<span class="asesor-gestor"><?php $pieces = explode(" ", $value['User']['name']);
																				echo $pieces[0]; ?></span>
												</h5>

												<div class="pr-2 mb-2 color">
													<p class="card-text d-inline"><b>CC:</b> <?php echo $value["Customer"]["identification"] ?></p>
													<p class="card-text d-inline"><b>Solicita:</b> $<?php echo number_format($value["CreditsRequest"]["request_value"]) ?> x <?php echo $value["CreditsRequest"]["request_number"] ?> cuotas</p>
													<p class="card-text d-inline"><b>Celular:</b> <?php echo $value["Customer"]["CustomersPhone"][0]["phone_number"] ?></p>
													<p class="card-text d-inline"><b>Proveedor:</b> <?php echo $value["ShopCommerce"]["Shop"]["social_reason"] ?> - <?php echo $value["ShopCommerce"]["name"] ?></p>
												</div>

												<a href="#" class="card-link btn btn-outline-secondary btn-sm viewCustomerRequest mb-1" data-customer="<?php echo $this->Utilidades->encrypt($value["Customer"]["id"]) ?>" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
													<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
												</a>

												<div class="dropdown d-inline">
													<a class="btn btn-outline-secondary btn-sm dropdown-toggle" href="#" role="button" id="img-credit" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<i class="fa fa-address-card-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver documento"></i>
													</a>
													<div class="dropdown-menu" aria-labelledby="img-credit">
														<a class="dropdown-item photoUp" data-url="<?php echo $this->Html->url("/files/customers/" . $value["Customer"]["document_file_up"], true) ?>" data-toggle="modal" data-target="#photoid-modal-ccfront">Cédula Frontal</a>
														<a class="dropdown-item photoDown" data-url="<?php echo $this->Html->url("/files/customers/" . $value["Customer"]["document_file_down"], true) ?>" data-toggle="modal" data-target="#photoid-modal-ccpost">Cédula Posterior</a>
														<a class="dropdown-item photoUser" data-toggle="modal" data-target="#photoid-modal-selfie" data-url="<?php echo $this->Html->url("/files/customers/" . $value["Customer"]["image_file"], true) ?>">Selfie</a>
													</div>
												</div>
												<a href="https://2cs.co/mas/webcall.php?ext=101&telefono=<?php echo $value["Customer"]["CustomersPhone"][0]["phone_number"] ?>&cliente=mascreditos" class="card-link btn btn-outline-secondary btn-sm">
													<i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="" data-original-title="Llamar"></i>
												</a>
												<a class="card-link btn btn-outline-secondary btn-sm viewComments" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
													<i class="fa fa-commenting-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Observaciones"></i> <?php echo count($value["CreditsRequestsComment"]) ?>
												</a>
												<?php if (in_array(AuthComponent::user("role"), [2, 3])) : ?>
													<a class="card-link btn btn-secondary btn-sm pull-right adminCreditFinal" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
														<i class="fa fa-chevron-circle-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pasar a Gestionados"></i>
													</a>
												<?php endif ?>

											</div>
										</div>
										<?php $totalStudio += $value["CreditsRequest"]["request_value"] ?>
									<?php endforeach; ?>
								</div>
								<div class="sumcolum">
									<p>Total <b>$ <?php echo number_format($totalStudio) ?></b></p>
								</div>
							</div>
							<div class="col-xl-3 col-lg-6 col-md-6 credit-step3">
								<div class="tittle-column-credit">
									<h2 class="d-inline">GESTIONADOS</h2>
									<span class="qty-credits pull-right"><?php echo count($requestAdmin) ?></span>

									<div class="btn-group pull-right filters-request">
										<button type="button" class="btn btn-outline-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<?php if (isset($this->request->query["tab2"])) {
												switch ($this->Utilidades->decrypt($this->request->query["tab2"])) {
													case "3":
														echo "Solo aprobados";
														break;
													case "4":
														echo "Solo rechazados";
														break;

													default:
														echo "Todos";
														break;
												}
											} else {
												echo "Todos";
											} ?>
										</button>
										<div class="dropdown-menu showitems">
											<?php if (!isset($this->request->query["tab1"])) : ?>
												<?php unset($urlParams["tab1"]); ?>
											<?php endif ?>
											<?php unset($urlParams["tab2"]); ?>
											<a class="dropdown-item" href="<?php echo $this->Html->url(["?" => $urlParams]) ?>">Todos</a>
											<?php $urlParams["tab2"] = $this->Utilidades->encrypt("3"); ?>
											<a class="dropdown-item <?php echo isset($this->request->query["tab2"]) && $this->request->query["tab2"] == $this->Utilidades->encrypt("3") ? "active" : ""; ?>" href="<?php echo $this->Html->url(["?" => $urlParams]) ?>">Solo Aprobados</a>
											<?php $urlParams["tab2"] = $this->Utilidades->encrypt("4") ?>
											<a class="dropdown-item <?php echo isset($this->request->query["tab2"]) && $this->request->query["tab2"] == $this->Utilidades->encrypt("4") ? "active" : ""; ?>" href="<?php echo $this->Html->url(["?" => $urlParams]) ?>">Solo Rechazados</a>
										</div>
									</div>
								</div>
								<div class="credit-columns">
									<?php $totalAdmin = 0; ?>
									<?php foreach ($requestAdmin as $key => $value) : ?>
										<?php if (!in_array($value["CreditsRequest"]["state"], [3, 4])) : ?>
											<?php continue; ?>
										<?php endif ?>

										<div class="sizecardtext card <?php echo $value["CreditsRequest"]["state"] == 3 ? "bg-outline-green" : "bg-outline-red" ?>">
											<div class="card-body">
												<h5 class="card-title"><strong class="text-right"><?php echo str_pad($value["CreditsRequest"]["id"], 6, "0", STR_PAD_LEFT); ?></strong>
													<?php if (empty($value["Customer"]["email"])) : ?>
														<span class="creditrad">TRADICIONAL</span>
													<?php endif ?>
													<?php if ($value["CreditsRequest"]["extra"] == 1) : ?>
														<span class="creditrad">(AUMENTO DE CUPO)</span>
													<?php endif ?>
													<br><?php echo $value["Customer"]["name"] ?> <?php echo $value["Customer"]["last_name"] ?> <br>
													<span class="asesor-gestor"><?php $pieces = explode(" ", $value['User']['name']);
																				echo $pieces[0]; ?></span>
												</h5>

												<div class="pr-2 mb-2 color ">
													<p class="card-text d-inline"><b>CC:</b> <?php echo $value["Customer"]["identification"] ?></p>
													<p class="card-text d-inline"><b>Solicita:</b> $<?php echo number_format($value["CreditsRequest"]["request_value"]) ?> x <?php echo $value["CreditsRequest"]["request_number"] ?> cuotas</p>
													<p class="card-text d-inline"><b>Celular:</b> <?php echo $value["Customer"]["CustomersPhone"][0]["phone_number"] ?></p>
													<p class="card-text d-inline"><b>Proveedor:</b> <?php echo $value["ShopCommerce"]["Shop"]["social_reason"] ?> - <?php echo $value["ShopCommerce"]["name"] ?></p>
												</div>
												<a href="#" class="card-link btn btn-outline-secondary btn-sm viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($value["Customer"]["id"]) ?>" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
													<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
												</a>

												<div class="dropdown d-inline">
													<a class="btn btn-outline-secondary btn-sm dropdown-toggle" href="#" role="button" id="img-credit" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<i class="fa fa-address-card-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver documento"></i>
													</a>
													<div class="dropdown-menu" aria-labelledby="img-credit">
														<a class="dropdown-item photoUp" data-url="<?php echo $this->Html->url("/files/customers/" . $value["Customer"]["document_file_up"], true) ?>" data-toggle="modal" data-target="#photoid-modal-ccfront">Cédula Frontal</a>
														<a class="dropdown-item photoDown" data-url="<?php echo $this->Html->url("/files/customers/" . $value["Customer"]["document_file_down"], true) ?>" data-toggle="modal" data-target="#photoid-modal-ccpost">Cédula Posterior</a>
														<a class="dropdown-item photoUser" data-toggle="modal" data-target="#photoid-modal-selfie" data-url="<?php echo $this->Html->url("/files/customers/" . $value["Customer"]["image_file"], true) ?>">Selfie</a>
													</div>
												</div>
												<a href="https://2cs.co/mas/webcall.php?ext=101&telefono=<?php echo $value["Customer"]["CustomersPhone"][0]["phone_number"] ?>&cliente=mascreditos" class="card-link btn btn-outline-secondary btn-sm">
													<i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="" data-original-title="Llamar"></i>
												</a>
												<a class="card-link btn btn-outline-secondary btn-sm viewComments" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
													<i class="fa fa-commenting-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Observaciones"></i> <?php echo count($value["CreditsRequestsComment"]) ?>
												</a>


												<?php if ($value["CreditsRequest"]["state"] == 3) : ?>

													<span class="money-approved text-center pull-right">
														<span>APROBADO</span><b>$ <?php echo number_format($value["CreditsRequest"]["value_approve"]) ?> x <?php echo $value["CreditsRequest"]["number_approve"] ?></b>
													</span>
												<?php else : ?>
													<div class="d-none">
														<?php echo $value["CreditsRequest"]["reason_reject"] ?>
													</div>
												<?php endif ?>

												<a href="" class="btn btn-secondary rejectPrev btn-sm " data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
													<i class="fa fa-chevron-left" data-toggle="tooltip" data-placement="top" title="" data-original-title="Regresar a estudio"></i>
												</a>
											</div>
											<div class="motivo-rechazo"><?php echo $value["CreditsRequest"]["reason_reject"] ?></div>
										</div>

										<?php $totalAdmin += $value["CreditsRequest"]["state"] == 3 ? $value["CreditsRequest"]["value_approve"] : 0 ?>
									<?php endforeach; ?>
								</div>
								<div class="sumcolum">
									<p>Total <b>$ <?php echo number_format($totalAdmin) ?></b></p>
								</div>
							</div>
							<div class="col-xl-3 col-lg-6 col-md-6 credit-step4">
								<div class="tittle-column-credit">
									<h2 class="d-inline">Aprobado con Retiro</h2>
									<span class="qty-credits pull-right"><?php echo count($requestApply) ?></span>
									<?php $totalApply = 0; ?>
								</div>
								<div class="credit-columns">
									<?php foreach ($requestApply as $key => $value) : ?>
										<div class="card sizecardtext">
											<div class="card-body">
												<h5 class="card-title"><strong class="text-right"><?php echo str_pad($value["CreditsRequest"]["id"], 6, "0", STR_PAD_LEFT); ?></strong>
													<?php if (empty($value["Customer"]["email"])) : ?>
														<span class="creditrad">TRADICIONAL</span>
													<?php endif ?>
													<?php if ($value["CreditsRequest"]["extra"] == 1) : ?>
														<span class="creditrad">(AUMENTO DE CUPO)</span>
													<?php endif ?>
													<br><?php echo $value["Customer"]["name"] ?> <?php echo $value["Customer"]["last_name"] ?> <br>
													<span class="asesor-gestor"><?php $pieces = explode(" ", $value['User']['name']);
																				echo $pieces[0]; ?></span>
												</h5>

												<div class="pr-2 mb-2 color ">
													<p class="card-text d-inline"><b>CC:</b> <?php echo $value["Customer"]["identification"] ?></p>
													<p class="card-text d-inline"><b>Solicita:</b> $<?php echo number_format($value["CreditsRequest"]["request_value"]) ?> x <?php echo $value["CreditsRequest"]["request_number"] ?> cuotas</p>
													<p class="card-text d-inline"><b>Valor aprobado:</b> $<?php echo number_format($value["CreditsRequest"]["value_approve"]) ?> x <?php echo $value["CreditsRequest"]["number_approve"] ?> cuotas</p>
													<p class="card-text d-inline"><b>Celular:</b> <?php echo $value["Customer"]["CustomersPhone"][0]["phone_number"] ?></p>
													<p class="card-text d-inline"><b>Proveedor:</b> <?php echo $value["ShopCommerce"]["Shop"]["social_reason"] ?> - <?php echo $value["ShopCommerce"]["name"] ?></p>
												</div>
												<a href="#" class="card-link btn btn-outline-secondary btn-sm viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($value["Customer"]["id"]) ?>" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
													<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
												</a>
												<div class="dropdown d-inline">
													<a class="btn btn-outline-secondary btn-sm dropdown-toggle" href="#" role="button" id="img-credit" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<i class="fa fa-address-card-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver documento"></i>
													</a>
													<div class="dropdown-menu" aria-labelledby="img-credit">
														<a class="dropdown-item photoUp" data-url="<?php echo $this->Html->url("/files/customers/" . $value["Customer"]["document_file_up"], true) ?>" data-toggle="modal" data-target="#photoid-modal-ccfront">Cédula Frontal</a>
														<a class="dropdown-item photoDown" data-url="<?php echo $this->Html->url("/files/customers/" . $value["Customer"]["document_file_down"], true) ?>" data-toggle="modal" data-target="#photoid-modal-ccpost">Cédula Posterior</a>
														<a class="dropdown-item photoUser" data-toggle="modal" data-target="#photoid-modal-selfie" data-url="<?php echo $this->Html->url("/files/customers/" . $value["Customer"]["image_file"], true) ?>">Selfie</a>
													</div>
												</div>
												<a href="https://2cs.co/mas/webcall.php?ext=101&telefono=<?php echo $value["Customer"]["CustomersPhone"][0]["phone_number"] ?>&cliente=mascreditos" class="card-link btn btn-outline-secondary btn-sm">
													<i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="" data-original-title="Llamar"></i>
												</a>
												<a class="card-link btn btn-outline-secondary btn-sm viewComments" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
													<i class="fa fa-commenting-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Observaciones"></i> <?php echo count($value["CreditsRequestsComment"]) ?>
												</a>
												<a href="<?php echo $this->Html->url(array("controller" => "credits_requests", 'action' => 'credit_detail', $this->Utilidades->encrypt($value["Credit"]['id']))); ?>" class="card-link btn btn-outline-secondary btn-sm detailCredit2">
													<i class="fa fa-usd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Detalle del crédito"></i>
												</a>
											</div>
											<div class="cupos">
												<p class="cupo-retirado mr-2 d-inline"><span>Retirado </span><b>$ <?php echo number_format($value["CreditsRequest"]["value_disbursed"]) ?> </b></p>
												<p class="cupo-preaprobado d-inline"><span>Preaprobado </span><b>$ <?php echo number_format($value["CreditsRequest"]["value_approve"] - $value["CreditsRequest"]["value_disbursed"]) ?></b></p>
											</div>
										</div>
										<?php $totalApply +=  $value["CreditsRequest"]["value_approve"]; ?>
									<?php endforeach ?>
								</div>
								<div class="sumcolum">
									<p>Total <b>$ <?php echo number_format($totalApply) ?></b></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php elseif (AuthComponent::user("role") == "4" || AuthComponent::user("role") == "6" || AuthComponent::user("role") == "7") : ?>
	<ul class="nav nav-tabs controltabs">
		<li class="nav-item">
			<a class="nav-link  <?php echo $this->request->query["tab"] == 1 ? "active" : "" ?>" href="<?php echo $this->Html->url(array("controller" => "credits_requests", "action" => "index", "?" => ["tab" => 1])) ?>">Solicitudes en Proceso de gestion</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?php echo $this->request->query["tab"] == 2 ? "active" : "" ?>" href="<?php echo $this->Html->url(array("controller" => "credits_requests", "action_index", "?" => ["tab" => 2])) ?>">Solicitudes aprobadas y sin retiro</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?php echo $this->request->query["tab"] == 3 ? "active" : "" ?>" href="<?php echo $this->Html->url(array("controller" => "credits_requests", "action_index", "?" => ["tab" => 3])) ?>">Solicitudes aprobadas y crédito desembolsado</a>
		</li>
		<!-- 	  <li class="nav-item">
	    <a class="nav-link <?php //echo $this->request->query["tab"] == 4 ? "active" : ""
							?>" href="<?php //echo $this->Html->url(array("controller"=>"credits_requests","action_index","?"=>["tab"=>4]))
																									?>">Creditos desembolsados </a>
	  </li> -->
	</ul>
	<div class="row">
		<div class="col-md-12 mb-4">
			<div class="x_panel">
				<div class="x_content form100">
					<div class="row mt-2">
						<div class="col-md-6 mt-2">
							<div class="title-tables">
								<h3 class="upper text-info d-inline">
									<?php
									switch ($this->request->query["tab"]) {
										case '1':
											$title = "Solicitudes en proceso de gestión";
											break;
										case '2':
											$title = "Solicitudes aprobadas sin retiro";
											break;
										case '3':
											$title = "Solicitudes aprobadas y crédito desembolsado";
											break;
										case '4':
											$title = "Solicitudes rechazadas";
											break;

										default:
											$title = "Solicitudes en Proceso";
											break;
									}
									?>
									<?php echo $title; ?>
								</h3>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group top_search">
								<?php echo $this->Form->create('', array('role' => 'form', 'type' => 'GET', 'class' => '')); ?>
								<div class="input-group">
									<input name="txt_search" placeholder="Buscar..." class="form-control" value="<?php echo isset($txt_search) ? $txt_search : "" ?>" type="text" id="txt_search">
									<input name="tab" placeholder="Buscar..." class="form-control" value="<?php echo $this->request->query["tab"] ?>" type="hidden" id="tab">
									<span class="input-group-btn">
										<button class="btn btn-default" type="submit">Buscar</button>
									</span>
								</div>
							</div>
							<?php echo $this->Form->end(); ?>
						</div>

						<div class="col-md-3">
							<?php echo $this->Form->create('', array('role' => 'form', 'type' => 'GET', 'class' => '')); ?>
							<div class="rangofechas input-group ">
								<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
								<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
								<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
								<input name="tab" placeholder="Buscar..." class="form-control" value="<?php echo $this->request->query["tab"] ?>" type="hidden" id="tab">
								<span class="input-group-btn">
									<button class="btn-secondary btn text-white" id="btn_find_adviser" type="submit">Filtrar Fechas</button>
								</span>
								<?php if (isset($fechas)) : ?>
									<a href="<?php echo $this->Html->url(["action" => "index", "?" => ["tab" => $this->request->query["tab"]]]) ?>" class="btn btn-warning">Borrar fechas <i class="fa fa-times"></i></a>
								<?php endif ?>
							</div>
							</form>
						</div>
						</form>
					</div>
					<div class="table-responsive mt-2">
						<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
							<thead class="">
								<tr>
									<th><?php echo __('Cliente'); ?></th>
									<th><?php echo __('Documento'); ?></th>
									<th><?php echo in_array($this->request->query["tab"], [2, 3]) ? __("Dinero aprobado") : __("Dinero solicitado"); ?></th>
									<th><?php echo __('Coutas'); ?></th>
									<th><?php echo __('Fecha Solicitud'); ?></th>
									<?php if (in_array($this->request->query["tab"], [2, 3])) : ?>
										<th><?php echo __("Fecha Aprobación") ?></th>
									<?php endif ?>
									<?php if (in_array($this->request->query["tab"], [3])) : ?>
										<th><?php echo __("Fecha Retiro") ?></th>
										<!-- <th><?php echo __("Registró") ?></th> -->
										<th><?php echo __("Retirado") ?></th>
									<?php endif ?>
									<?php if (in_array($this->request->query["tab"], [4])) : ?>
										<th><?php echo __("Fecha Rechazo") ?></th>
										<th><?php echo __("Motivo Rechazo") ?></th>
									<?php endif ?>
									<th><?php echo __('Acciones'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($creditsRequestsCommerce)) : ?>
									<?php foreach ($creditsRequestsCommerce as $key => $value) : ?>
										<tr>
											<td class="upper">
												<?php echo $value["Customer"]["name"] ?>
												<?php echo $value["Customer"]["last_name"] ?>
											</td>
											<td>
												<?php echo $value["Customer"]["identification"] ?>
											</td>
											<td>
												$<?php echo in_array($value["CreditsRequest"]["state"], [3, 5]) ? number_format($value["CreditsRequest"]["value_approve"]) :  number_format($value["CreditsRequest"]["request_value"]) ?>
											</td>
											<td>
												<?php echo in_array($value["CreditsRequest"]["state"], [3, 5]) ? $value["CreditsRequest"]["number_approve"] :  $value["CreditsRequest"]["request_number"] ?>
											</td>
											<td>
												<?php echo date("d-m-Y", strtotime($value['CreditsRequest']['created'])); ?>

											</td>
											<?php if (in_array($this->request->query["tab"], [2, 3, 4])) : ?>
												<td>
													<?php echo date("d-m-Y h:i A", strtotime($value['CreditsRequest']['date_admin'])); ?>
												</td>
											<?php endif ?>
											<?php if (in_array($this->request->query["tab"], [3])) : ?>
												<td>
													<?php echo date("d-m-Y h:i A", strtotime($value['CreditsRequest']['date_disbursed'])); ?></td>
												<!-- <td><?php echo $value["UserDisbursed"]["name"] ?></td> -->
												<td>$<?php echo !empty($value["CreditsRequest"]["value_disbursed"]) ? number_format($value["CreditsRequest"]["value_disbursed"]) : "" ?></td>
											<?php endif ?>
											<?php if (in_array($this->request->query["tab"], [4])) : ?>
												<td><?php echo $value["CreditsRequest"]["reason_reject"] ?></td>
											<?php endif ?>
											<td>
												<?php if (in_array($this->request->query["tab"], [1, 4])) : ?>
													<a href="#" class="card-link btn btn-outline-secondary viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($value["Customer"]["id"]) ?>">
														<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
													</a>
													<a class="card-link btn btn-outline-secondary viewComments" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
														<i class="fa fa-commenting-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Observaciones"></i> <?php echo count($value["CreditsRequestsComment"]) ?>
													</a>
												<?php elseif (in_array($this->request->query["tab"], [2])) : ?>
													<a href="#" class="card-link btn btn-outline-secondary viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($value["Customer"]["id"]) ?>">
														<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
													</a>
													<a rel="tooltip" href="" class="btn btn-secondary deatil_payment" data-total="<?php echo $value['CreditsRequest']['value_approve'] ?>" data-number="<?php echo $value['CreditsRequest']['number_approve'] ?>" data-frecuency="<?php echo $value["CreditsRequest"]["request_type"] ?>">
														<i class="fa fa-usd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Plan de pagos"></i>
													</a>
													<a class="card-link btn btn-outline-secondary viewComments" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
														<i class="fa fa-commenting-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Observaciones"></i> <?php echo count($value["CreditsRequestsComment"]) ?>
													</a>
													<a data-toggle="modal" data-target="#credit_applied" class="card-link btn btn-success btn-xs text-white applyCredit" data-type="<?php echo empty($value["Customer"]["email"]) ? 0 : 1; ?>" data-frecuency="<?php echo $value["CreditsRequest"]["request_type"] ?>" data-value="<?php echo $value["CreditsRequest"]["value_approve"] ?>" data-numberq="<?php echo $value["CreditsRequest"]["number_approve"] ?>" data-return="<?php echo $value["CreditsRequest"]["returned"] ?>" data-id="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]); ?>">
														<i class="fa fa-check"></i> Solicitar Retiro
													</a>
												<?php elseif (in_array($this->request->query["tab"], [3])) : ?>
													<a href="#" class="card-link btn btn-outline-secondary viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($value["Customer"]["id"]) ?>">
														<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
													</a>
													<a class="card-link btn btn-outline-secondary viewComments" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
														<i class="fa fa-commenting-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Observaciones"></i> <?php echo count($value["CreditsRequestsComment"]) ?>
													</a>
													<a href="<?php echo $this->Html->url(array("controller" => "credits_requests", 'action' => 'credit_detail', $this->Utilidades->encrypt($value['CreditsRequest']['credit_id']))); ?>" class="card-link btn btn-outline-secondary detailCredit2">
														<i class="fa fa-usd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Detalle de credito"></i>
													</a>
													<!-- <a class="card-link btn btn-outline-secondary viewVoucher" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]); ?>">
													  <i class="fa fa-file-text-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Soporte"></i>
													</a> -->
													<a href="<?php echo $this->Html->url(array("controller" => "credits", 'action' => 'plan_payemts_pdf', $this->Utilidades->encrypt($value['CreditsRequest']['id']))); ?>" class="card-link btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Generar plan de pagos">
														<i class="fa fa-file"></i>
													</a>
												<?php endif ?>
											</td>
										</tr>
									<?php endforeach ?>
								<?php endif ?>
							</tbody>
						</table>
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

<?php endif ?>


<?php echo $this->element("/modals/request"); ?>
<?php echo $this->element("/modals/photoid"); ?>
<?php echo $this->element("/modals/comments"); ?>
<?php echo $this->element("/modals/decision"); ?>
<?php echo $this->element("/modals/credit_applied"); ?>
<?php echo $this->element("/modals/voucher"); ?>
<?php echo $this->element("/modals/credit_detail"); ?>
<?php echo $this->element("/modals/history_payments"); ?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<?php

echo $this->Html->script("home.js?" . rand(),           array('block' => 'AppScript'));
echo $this->Html->script("requests/admin.js?" . rand(),           array('block' => 'AppScript'));

?>

<?php if (in_array(AuthComponent::user("role"), [1, 2, 3, 4, 6, 7])) : ?>
	<script>
		var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
		var actual_url = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here, true) : $this->here ?>?";

		function URLToArray(url) {
			var request = {};

			var pairs = url.substring(url.indexOf('?') + 1).split('&');
			if (pairs.length == 1) {
				return request;
			}
			console.log(pairs.length)
			for (var i = 0; i < pairs.length; i++) {
				if (!pairs[i])
					continue;
				var pair = pairs[i].split('=');

				if (actual_url != decodeURIComponent(pair[0]) + "?" && actual_url != decodeURIComponent(pair[0])) {
					request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
				}
			}
			return request;

		}


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

			if ($("#btn_find_adviser").length) {
				$("#btn_find_adviser").trigger('click')
			}


		});
	</script>

<?php endif ?>

<?php if (in_array(AuthComponent::user("role"), [4, 6, 7])) : ?>
	<div class="modal fade" id="searchCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="">
						<div class="content-tittles">
							<div class="line-tittles">|</div>
							<div>
								<h1>BUSCAR</h1>
								<h2>CLIENTES EN EL SISTEMA</h2>
							</div>
						</div>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="searchCustomerBody">
					<div class="row">
						<div class="col-md-3 pt-2">
							<label for="#ccCustomer">Cédula del cliente</label>
						</div>
						<div class="col-md-6">
							<input type="number" id="ccCustomer" class="form-control">
						</div>
						<div class="col-md-3">
							<a href="" class="btn btn-search btn-primary" id="btnCustomerSearch">
								<i class="fa fa-search btc"></i>
							</a>
						</div>
					</div>
					<div class="row" id="dataCustomerDataPayment"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>

<div class="modal fade " id="panelPayments" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="">
					<div class="content-tittles">
						<div class="line-tittles">|</div>
						<div>
							<h1>PLAN</h1>
							<h2>DE PAGOS</h2>
						</div>
					</div>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="planPaymentBody">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="assignValue" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Asignar ejecutivo al credito</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" id="formAsign">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="userSelect">Seleccionar ejecutivo que deseas asignar</label>
								<select name="userSelect" id="userSelect" class="form-control" required="">
									<option value="">Seleccionar ejecutivo</option>
									<?php foreach ($users as $key => $value) : ?>
										<option value="<?php echo $key ?>"><?php echo $value ?></option>
									<?php endforeach ?>
								</select>
								<input type="hidden" id="requestId">
							</div>
							<div class="form-group">
								<input type="submit" class="btn btn-info" value="Asignar">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
