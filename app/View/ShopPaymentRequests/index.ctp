<div class="page-title">
	<div class="title_left">
		<h3><?php echo in_array(AuthComponent::user("role"), [1, 2]) ? __("Pagos solicitados por las tiendas") : __('Saldos y desembolsos'); ?> </h3>
	</div>
</div>

<div class="clearfix"></div>
<?php if (in_array(AuthComponent::user("role"), [1, 2])) : ?>

	<div class="row">
		<div class="col-md-12">
			<div class="paymentsblock">
				<?php echo $this->Form->create('', array('role' => 'form', 'type' => 'GET', 'class' => '')); ?>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<?php echo $this->Form->hidden("tab", ["value" => $tab]) ?>
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
							<label for="">Fecha de solicitud</label>
							<?php echo $this->Form->text('request_date', array('class' => 'form-control', "type" => "date", 'label' => "Fecha de solicitud", 'div' => false, 'value' => $request_date, "placeholder" => "Ingrese el código de proveedor a buscar", "required" => false)) ?>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="">Fecha de pago</label>
							<?php echo $this->Form->text('final_date', array('class' => 'form-control', "type" => "date", 'label' => "Fecha de solicitud", 'div' => false, 'value' => $final_date, "placeholder" => "Ingrese el código de proveedor a buscar")) ?>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="">CC Cliente</label>
							<?php echo $this->Form->text('customer', array('class' => 'form-control', 'label' => false, 'div' => false, 'value' => $customer, "placeholder" => "Ingrese la cédula del cliente a buscar")) ?>
						</div>
					</div>
					<div class="col-md-1">
						<button class="btn btn-success mt-4" type="submit">
							<i class="fa fa-search"></i>
						</button>
						<?php if ($estados != "" || $commerce != "" || $request_date != "" || $final_date != "" || $customer != "") : ?>
							<a href="<?php echo $this->Html->url(["action" => "index"]) ?>" class="btn btn-warning mt-4">
								<i class="fa fa-times"></i>
							</a>
						<?php endif ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-center pt-3">
						<?php if (!empty($shopPaymentRequests)) : ?>
							<?php if (isset($shopPaymentRequests[1])) : ?>
								<b>Se encontrarón <?php echo count($shopPaymentRequests[1]) ?> resultado(s) para
									PAGO1</b>
							<?php endif ?>
							<?php if (isset($shopPaymentRequests[1]) && isset($shopPaymentRequests[2])) : ?>
								<span class="mx-2"> / </span>
							<?php endif ?>
							<?php if (isset($shopPaymentRequests[2])) : ?>
								<b>Se encontrarón <?php echo count($shopPaymentRequests[2]) ?> resultado(s) para
									PAGO2</b>
							<?php endif ?>
						<?php else : ?>
							<b>Se encontrarón 0 resultados</b>
						<?php endif ?>
					</div>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>

		</div>
	</div>

<?php endif ?>
<div class="clearfix"></div>

<div class="row" style="display: block;">
	<div class="col-md-12">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">COBRO DE CRÉDITOS</a>
			</li>
			<!-- osn <li class="nav-item" role="presentation">
		    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller" => "payments", "action" => "pendings"]) ?>" role="tab" aria-controls="profile" aria-selected="false">DINEROS RECAUDADOS PARA CREDIVENTAS</a>
		  </li> -->
		</ul>
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<?php if (in_array(AuthComponent::user("role"), [4])) : ?>
						<h2 class="text-center">
							CRÉDITOS POR COBRAR
						</h2>
						<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th><a href="">Total créditos desembolsados a clientes</a></th>
									<th><a href="">Valor total por solicitudes de créditos</a></th>
									<th><a href="">Saldo por solicitar</a></th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($saldos)) : ?>
									<?php foreach ($saldos as $key => $value) : ?>
										<?php if ($value["saldo"]["response"] <= 0) : ?>
											<?php continue; ?>
										<?php endif ?>
										<tr>
											<td>
												$ <?php echo number_format($value["saldo"]["disbursments"], 2, ".", ","); ?>
											</td>
											<td>
												$ <?php echo number_format($value["saldo"]["debts"], 2, ".", ","); ?>
											</td>
											<td>
												$ <?php echo number_format($value["saldo"]["response"], 2, ".", ","); ?>
											</td>
											<td>
												<a href="<?php echo $this->Html->url(["controller" => "shop_payment_requests", "action" => "add", $this->Utilidades->encrypt($key)]) ?>" class="btn btn-success btn-sm">
													Solicitar pago
												</a>
											</td>
										</tr>
									<?php endforeach ?>
								<?php else : ?>
									<tr>
										<td colspan="5" class="text-center">No hay saldo por cobrar</td>
									</tr>
								<?php endif ?>
							</tbody>
						</table>

						<h2 class="text-center mt-5">
							SOLICITUDES DE PAGO DE CRÉDITOS REALIZADAS
						</h2>

						<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th><?php echo $this->Paginator->sort('request_value', __('Valor solicitado')); ?></th>
									<th><?php echo $this->Paginator->sort('final_value', __('Valor pagado')); ?></th>
									<th><?php echo $this->Paginator->sort('final_date', __('Fecha pago')); ?></th>
									<th><?php echo $this->Paginator->sort('request_date', __('Fecha solicitud')); ?></th>
									<th><?php echo $this->Paginator->sort('user_id', __('Solicitó')); ?></th>
									<th><?php echo $this->Paginator->sort('notes', __('Notas adicionales')); ?></th>
									<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
									<th><?php echo __('Acciones'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($shopPaymentRequests)) : ?>
									<?php foreach ($shopPaymentRequests as $shopPaymentRequest) : ?>
										<tr>
											<td>
												$<?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['request_value'], 2); ?>
												&nbsp;
											</td>
											<td><?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['final_value'], 2); ?>
												&nbsp;
											</td>
											<td><?php echo !is_null($shopPaymentRequest['ShopPaymentRequest']['final_date']) ? date("d-m-Y", strtotime(h($shopPaymentRequest['ShopPaymentRequest']['final_date']))) : ""; ?>
												&nbsp;
											</td>
											<td><?php echo date("d-m-Y", strtotime(h($shopPaymentRequest['ShopPaymentRequest']['request_date']))); ?>
												&nbsp;
											</td>
											<td>
												<?php echo $shopPaymentRequest['User']['name']; ?>
											</td>
											<td><?php echo h($shopPaymentRequest['ShopPaymentRequest']['notes']); ?>&nbsp;</td>
											<td> <?php echo $shopPaymentRequest['ShopPaymentRequest']['state'] == 1 ? __('Pagado') : __('Solicitado'); ?> </td>
											<td class="td-actions">
												<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilidades->encrypt($shopPaymentRequest['ShopPaymentRequest']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info  btn-xs">
													<i class="fa fa-eye"></i>
												</a>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td class='text-center' colspan='<?php echo 1; ?>'>No existen resultados</td>
									<tr>
									<?php endif; ?>
							</tbody>
						</table>
				</div>
				<?php if (AuthComponent::user("role") == 4) : ?>
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
				<?php endif ?>
			<?php endif ?>
			<?php if (in_array(AuthComponent::user("role"), [1, 2])) : ?>

				<ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link <?php echo $tab == 1 ? "active" : "" ?>" href="<?php echo $this->Html->url(["action" => "index", "?" => ["tab" => 1]]) ?>">
							Pagos
						</a>
					</li>
					<!-- <li class="nav-item">
						<a class="nav-link <?php echo $tab == 2 ? "active" : "" ?>" href="<?php echo $this->Html->url(["action" => "index", "?" => ["tab" => 2]]) ?>">
							Pago tipo 2
						</a>
					</li> -->
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade <?php echo $tab == 1 ? "show active" : "" ?>" id="home" role="tabpanel" aria-labelledby="home-tab">
						<div class="table-responsive">
							<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
								<thead class="text-primary">
									<tr>
										<th><?php echo __('Valor solicitado'); ?></th>
										<th><?php echo __('Valor pagado'); ?></th>
										<th><?php echo __('Fecha pago'); ?></th>
										<th><?php echo __('Fecha solicitud'); ?></th>
										<th><?php echo __('Comercio'); ?></th>
										<th><?php echo __('Datos para el pago'); ?></th>
										<th><?php echo __('Clientes con desembolso'); ?></th>
										<th><?php echo __('Solicitó'); ?></th>
										<th><?php echo __('Notas adicionales'); ?></th>
										<th><?php echo __('Estado'); ?></th>
										<th style="width: 102px;"><?php echo __('Acciones'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($shopPaymentRequests[1])) : ?>
										<?php foreach ($shopPaymentRequests[1] as $shopPaymentRequest) : ?>
											<tr>
												<td>
													$<?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['request_value'], 2); ?>
													&nbsp;
												</td>
												<td><?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['final_value'], 2); ?>
													&nbsp;
												</td>
												<td>
													<?php echo is_null($shopPaymentRequest['ShopPaymentRequest']['final_date']) ? "" : date("d-m-Y ", strtotime(h($shopPaymentRequest['ShopPaymentRequest']['final_date']))); ?>
													&nbsp;
												</td>
												<td><?php echo date("d-m-Y ", strtotime(h($shopPaymentRequest['ShopPaymentRequest']['request_date']))); ?>
													&nbsp;
												</td>
												<td>
													<?php echo @$shopPaymentRequest['Shop']['social_reason']; ?>
												</td>
												<td>
													<?php

													/*						echo '<pre>';
																			var_dump($shopPaymentRequest);
																			echo '</pre>';exit();*/

													echo @$shopPaymentRequest['Shop']['account_bank']; ?>
													<?php echo @$shopPaymentRequest['Shop']['account_type']; ?>
													<?php echo @$shopPaymentRequest['Shop']['account_number']; ?>
												</td>
												<td>
													<?php if (!empty($shopPaymentRequest["ShopPaymentRequest"]["customers"])) : ?>
														<ul>
															<?php foreach ($shopPaymentRequest["ShopPaymentRequest"]["customers"] as $keyCus => $valueCus) : ?>
																<li>
																	<?php echo $valueCus ?>
																</li>
															<?php endforeach ?>
														</ul>
													<?php endif ?>
												</td>
												<td>
													<?php echo $shopPaymentRequest['User']['name'] . " - " . $shopPaymentRequest["ShopCommerce"]["code"]; ?>
												</td>
												<td>
													<?php if ($shopPaymentRequest["ShopPaymentRequest"]["state"] == 2 || !empty($shopPaymentRequest['ShopPaymentRequest']['notes'])) : ?>
														<?php echo h($shopPaymentRequest['ShopPaymentRequest']['notes']); ?><?php echo !is_null(@$shopPaymentRequest['ShopPaymentRequest']['date_pending']) ? " <br> " . $shopPaymentRequest['ShopPaymentRequest']['date_pending'] : "" ?></td>
											<?php endif ?>
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
											<td class="td-actions">
												<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilidades->encrypt($shopPaymentRequest['ShopPaymentRequest']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info  btn-xs">
													<i class="fa fa-eye"></i>
												</a>
												<?php if ($shopPaymentRequest['ShopPaymentRequest']['state'] == 0) : ?>
													<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'pending', $this->Utilidades->encrypt($shopPaymentRequest['ShopPaymentRequest']['id']))); ?>" title="<?php echo __('Pasar a pendiente'); ?>" class="btn btn-warning btn-xs pendingRequest">
														<i class="fa fa-pencil"></i>
													</a>
												<?php endif ?>
											</td>

											</tr>
										<?php endforeach; ?>
									<?php else : ?>
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

					<!-- se desabilitan pagos tipo 2 -->

					<!-- <div class="tab-pane fade <?php echo $tab == 2 ? "show active" : "" ?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
						<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
							<?php if (!empty($shopPaymentRequests[2])) : ?>
								<?php foreach ($shopPaymentRequests[2] as $anio => $requests) : ?>
									<div class="panel">
										<a class="panel-heading collapsed" role="tab" id="heading_<?php echo str_replace([" ", "-"], "", $anio) ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo str_replace([" ", "-"], "", $anio) ?>" aria-expanded="false" aria-controls="collapse<?php echo str_replace([" ", "-"], "", $anio) ?>">
											<h4 class="panel-title mb-0">
												SEMANA <?php echo $anio ?>
											</h4>
										</a>
										<div id="collapse<?php echo str_replace([" ", "-"], "", $anio) ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_<?php echo str_replace([" ", "-"], "", $anio) ?>" style="">
											<div class="panel-body p-2">
												<div class="table-responsive">
													<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
														<thead class="text-primary">
															<tr>
																<th><?php echo __('Valor solicitado'); ?></th>
																<th><?php echo __('Valor pagado'); ?></th>
																<th><?php echo __('Fecha pago'); ?></th>
																<th><?php echo __('Fecha solicitud'); ?></th>
																<th><?php echo __('Comercio'); ?></th>
																<th><?php echo __('Clientes con desembolso'); ?></th>
																<th><?php echo __('Solicitó'); ?></th>
																<th><?php echo __('Nota paso Pendiente:'); ?></th>
																<th><?php echo __('Estado'); ?></th>
																<th><?php echo __('Acciones'); ?></th>
															</tr>
														</thead>
														<tbody>
															<?php if (!empty($requests)) : ?>
																<?php foreach ($requests as $request) : ?>
																	<tr>
																		<td>
																			$<?php echo number_format($request['ShopPaymentRequest']['request_value'], 2); ?>
																			&nbsp;
																		</td>
																		<td><?php echo number_format($request['ShopPaymentRequest']['final_value'], 2); ?>
																			&nbsp;
																		</td>
																		<td><?php echo is_null($request['ShopPaymentRequest']['final_date']) ? "" : date("d-m-Y ", strtotime(h($request['ShopPaymentRequest']['final_date']))); ?>
																			&nbsp;
																		</td>
																		<td><?php echo date("d-m-Y", strtotime(h($request['ShopPaymentRequest']['request_date']))); ?>
																			&nbsp;
																		</td>
																		<td>
																			<?php echo @$shopPaymentRequest['ShopCommerce']['Shop']['social_reason']; ?>
																		</td>
																		<td>
																			<?php if (!empty($request["ShopPaymentRequest"]["customers"])) : ?>
																				<ul>
																					<?php foreach ($request["ShopPaymentRequest"]["customers"] as $keyCus => $valueCus) : ?>
																						<li>
																							<?php echo $valueCus ?>
																						</li>
																					<?php endforeach ?>
																				</ul>
																			<?php endif ?>
																		</td>
																		<td>
																			<?php echo $request['User']['name'] . " - " . $request["ShopCommerce"]["code"]; ?>
																		</td>
																		<td>
																			<?php if ($request["ShopPaymentRequest"]["state"] == 2 || !empty($request['ShopPaymentRequest']['notes'])) : ?>
																				<?php echo h($request['ShopPaymentRequest']['notes']); ?><?php echo !is_null(@$request['ShopPaymentRequest']['date_pending']) ? " <br> " . @$request['ShopPaymentRequest']['date_pending'] : "" ?></td>
																	<?php endif ?>
																	<td>
																		<?php

																		switch ($request['ShopPaymentRequest']['state']) {
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
																	<td class="td-actions">
																		<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilidades->encrypt($request['ShopPaymentRequest']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info  btn-xs">
																			<i class="fa fa-eye"></i>
																		</a>
																		<?php if ($request['ShopPaymentRequest']['state'] == 0) : ?>
																			<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'pending', $this->Utilidades->encrypt($request['ShopPaymentRequest']['id']))); ?>" title="<?php echo __('Pasar a pendiente'); ?>" class="btn btn-warning btn-xs pendingRequest">
																				<i class="fa fa-pencil"></i>
																			</a>
																		<?php endif ?>
																	</td>
																	</tr>
																<?php endforeach; ?>
															<?php else : ?>
																<tr>
																	<td class='text-center' colspan='<?php echo 9; ?>'>No existen
																		resultados
																	</td>
																<tr>
																<?php endif; ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									<?php endforeach ?>
								<?php else : ?>
									<h3 class="text-center">
										No hay pagos 2 solicitados
									</h3>
								<?php endif ?>
									</div>
						</div>

					</div>
					<?php if ($tab == 2) : ?>

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

					<?php endif ?> -->

				</div>
				<?php if (AuthComponent::user("role") == 4) : ?>
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
				<?php endif; ?>
			<?php endif ?>
			</div>
		</div>
	</div>
</div>


<?php echo $this->Html->script("payments/requests.js?" . rand(), array('block' => 'AppScript')); ?>
