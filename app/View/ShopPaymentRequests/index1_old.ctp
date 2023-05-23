
<div class="page-title">
  <div class="title_left">
    <h3><?php echo in_array(AuthComponent::user("role"), [1,2]) ? __("Pagos solicitados por las tiendas") : __('Saldos y desembolsos'); ?> </h3>
  </div>
</div>

<div class="clearfix"></div>

<div class="row" style="display: block;">
	<div class="col-md-12">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
		  <li class="nav-item" role="presentation">
		    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">COBRO DE CRÉDITOS</a>
		  </li>
<!-- osn <li class="nav-item" role="presentation">
		    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"pendings"]) ?>" role="tab" aria-controls="profile" aria-selected="false">DINEROS RECAUDADOS PARA CREDISHOP</a>
		  </li> -->
		</ul>
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<?php if (in_array(AuthComponent::user("role"), [4,7])): ?>
						<h2 class="text-center">
							CRÉDITOS POR COBRAR
						</h2>
						<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>						
									<th><a href="">Sede</a></th>
									<th><a href="">Saldo a favor</a></th>
									<th><a href="">Saldo en contra</a></th>
									<th><a href="">Total a cobrar</a></th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>								
								<?php if (!empty($saldos)): ?>
									<?php foreach ($saldos as $key => $value): ?>
										<?php if ($value["saldo"]["response"] <= 0 ): ?>
											<?php continue; ?>
										<?php endif ?>
										<tr>
											<td>
												<?php echo $value["name"]; ?>
											</td>
											<td>
												$ <?php echo number_format($value["saldo"]["disbursments"],2,".",","); ?>
											</td>
											<td>
												$ <?php echo number_format($value["saldo"]["debts"],2,".",","); ?>
											</td>
											<td>
												$ <?php echo number_format($value["saldo"]["response"],2,".",","); ?>
											</td>
											<td>
												<a href="<?php echo $this->Html->url(["controller"=>"shop_payment_requests","action" => "add",$this->Utilidades->encrypt($key)]) ?>" class="btn btn-success btn-sm">
								                    Solicitar pago de este crédito
								                </a>
											</td>
										</tr>
									<?php endforeach ?>
								<?php else: ?>
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
								<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Comercio')); ?></th>
								<th><?php echo $this->Paginator->sort('user_id', __('Solicitó')); ?></th>
								<th><?php echo $this->Paginator->sort('notes', __('Notas adicionales')); ?></th>
								<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
								<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($shopPaymentRequests)): ?>
								<?php foreach ($shopPaymentRequests as $shopPaymentRequest): ?>
									<tr>
										<td>$<?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['request_value'],2); ?>&nbsp;</td>
										<td><?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['final_value'],2); ?>&nbsp;</td>
										<td><?php echo date("d-m-Y h:i A",strtotime(h($shopPaymentRequest['ShopPaymentRequest']['final_date']))); ?>&nbsp;</td>
										<td><?php echo date("d-m-Y h:i A",strtotime(h($shopPaymentRequest['ShopPaymentRequest']['request_date']))); ?>&nbsp;</td>
										<td>
											<?php echo $shopPaymentRequest['ShopCommerce']['name']; ?>
										</td>
										<td>
											<?php echo $shopPaymentRequest['User']['name']; ?>
										</td>
										<td><?php echo h($shopPaymentRequest['ShopPaymentRequest']['notes']); ?>&nbsp;</td>
										<td> <?php echo $shopPaymentRequest['ShopPaymentRequest']['state'] == 1 ? __('Pagado') : __('Solicitado') ;?> </td>									
										<td class="td-actions">
										    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view',$this->Utilidades->encrypt($shopPaymentRequest['ShopPaymentRequest']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info  btn-xs">
										        <i class="fa fa-eye"></i>
										    </a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr><td class='text-center' colspan='<?php echo 1; ?>'>No existen resultados</td><tr>
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
				<?php endif ?>
				<?php if (in_array(AuthComponent::user("role"), [1,2])): ?>

					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>						
								<th><?php echo $this->Paginator->sort('request_value', __('Valor solicitado')); ?></th>
								<th><?php echo $this->Paginator->sort('final_value', __('Valor pagado')); ?></th>
								<th><?php echo $this->Paginator->sort('final_date', __('Fecha pago')); ?></th>
								<th><?php echo $this->Paginator->sort('request_date', __('Fecha solicitud')); ?></th>
								<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Comercio')); ?></th>
								<th><?php echo $this->Paginator->sort('user_id', __('Solicitó')); ?></th>
								<th><?php echo $this->Paginator->sort('notes', __('Notas adicionales')); ?></th>
								<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
								<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($shopPaymentRequests)): ?>
								<?php foreach ($shopPaymentRequests as $shopPaymentRequest): ?>
									<tr>
										<td>$<?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['request_value'],2); ?>&nbsp;</td>
										<td><?php echo number_format($shopPaymentRequest['ShopPaymentRequest']['final_value'],2); ?>&nbsp;</td>
										<td><?php echo date("d-m-Y h:i A",strtotime(h($shopPaymentRequest['ShopPaymentRequest']['final_date']))); ?>&nbsp;</td>
										<td><?php echo date("d-m-Y h:i A",strtotime(h($shopPaymentRequest['ShopPaymentRequest']['request_date']))); ?>&nbsp;</td>
										<td>
											<?php echo $shopPaymentRequest['ShopCommerce']['name']; ?>
										</td>
										<td>
											<?php echo $shopPaymentRequest['User']['name']; ?>
										</td>
										<td><?php echo h($shopPaymentRequest['ShopPaymentRequest']['notes']); ?>&nbsp;</td>
										<td> <?php echo $shopPaymentRequest['ShopPaymentRequest']['state'] == 1 ? __('Pagado') : __('Solicitado') ;?> </td>									
										<td class="td-actions">
										    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view',$this->Utilidades->encrypt($shopPaymentRequest['ShopPaymentRequest']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info  btn-xs">
										        <i class="fa fa-eye"></i>
										    </a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr><td class='text-center' colspan='<?php echo 1; ?>'>No existen resultados</td><tr>
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
				<?php endif ?>
			</div>
		</div>		
	</div>
</div>
