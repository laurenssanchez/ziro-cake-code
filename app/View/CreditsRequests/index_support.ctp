<div class="page-title">
	<div class="row">
		<div class="col-md-7">
			<h3><?php echo __('Panel de solicitudes aprobadas con desembolso'); ?></h3>
		</div>
		<?php if (in_array(AuthComponent::user("role"),[10])): ?>
		<div class="col-md-5">
			<div class="form-group top_search">
				<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
				<div class="input-group">
					<?php echo $this->Form->input('ccCustomer', array('placeholder'=>__('Buscar cliente por cédula'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($ccCustomer) ? $ccCustomer : "" )) ?>
					<span class="input-group-btn">
						<button class="btn btn-success" type="submit" id="busca">
							<?php echo __('Buscar '); ?> <i class="fa fa-search"></i>
						</button>
					<?php if (isset($ccCustomer)): ?>
						<button class="btn btn-info" type="reset" id="limpia" >
							<?php echo __('Limpiar campos'); ?> <i class="fa fa-times"></i>
						</button>
					<?php endif ?>
					</span>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<?php endif ?>
	</div>
</div>


<div class="row" style="display: block;">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<?php if(empty($creditsRequests) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="">
							<tr>
								<th><?php echo $this->Paginator->sort('Customer.name', __('Cliente')); ?></th>
								<th><?php echo $this->Paginator->sort('Customer.identification', __('Identificación')); ?></th>
								<th><?php echo $this->Paginator->sort('id', __('Obligación')); ?></th>
								<th><?php echo $this->Paginator->sort('request_Value', __('Valor')); ?></th>
								<th><?php echo $this->Paginator->sort('request_type', __('Frecuencia')); ?></th>
								<th><?php echo $this->Paginator->sort('request_number', __('Cuotas')); ?></th>
								<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Proveedor donde se solicitó')); ?></th>
								<th><?php echo $this->Paginator->sort('created', __('Fecha de solicitud')); ?></th>

								<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 	if(!empty($creditsRequests)): ?>
								<?php foreach ($creditsRequests as $creditsRequest): ?>
									<tr>
										<td>
											<?php echo $creditsRequest["Customer"]["name"] ?> <?php echo $creditsRequest["Customer"]["last_name"] ?>
										</td>
										<td>
											<?php echo $creditsRequest["Customer"]["identification"] ?>
										</td>
										<td>
											<?php echo $creditsRequest["CreditsRequest"]["code_pay"]; ?>
										</td>
										<td><?php echo number_format($creditsRequest['CreditsRequest']['value_disbursed']); ?>&nbsp;</td>

										<td>
											<?php
												if ($creditsRequest["CreditsRequest"]["request_type"]== 1)
													$tipoCredito= "Mensual";
												else if($creditsRequest["CreditsRequest"]["request_type"]== 3)
													$tipoCredito= "45 días";
												else if($creditsRequest["CreditsRequest"]["request_type"]== 4)
													$tipoCredito= "60 días";
												else
													$tipoCredito= "Quincenal";

												echo $tipoCredito;
											?>
											<!-- <?php echo $creditsRequest["CreditsRequest"]["request_type"] == 1 ? "Mensual" : "Quincenal" ?> -->

										</td>


										<td><?php echo h($creditsRequest['CreditsRequest']['request_number']); ?>&nbsp;</td>
										<td><?php echo h($creditsRequest['ShopCommerce']['name']." - ".$creditsRequest['ShopCommerce']["Shop"]['social_reason']); ?>&nbsp;</td>

										<td><?php echo date("d-m-Y h:i A",strtotime($creditsRequest['CreditsRequest']['created'])); ?>&nbsp;</td>
										<td class="td-actions">


											    <a rel="tooltip" href="javascript:void(0)" title="<?php echo __('Ver detalle solicitud'); ?>" class="btn btn-outline-info  btn-sm viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($creditsRequest["Customer"]["id"]) ?>">
											        <i class="fa fa-eye"></i>
											    </a>
												<?php
												$date1 = new datetime(date("d-m-Y"));
												$resultado1 = $date1->diff(new datetime(date("d-m-Y",strtotime($creditsRequest['CreditsRequest']['date_disbursed']))))->days;
												?>
											    <?php if (in_array(AuthComponent::user("role"), [1,10])) : ?>
											    	<a rel="tooltip" href="javascript:void(0)" title="<?php echo __('Regresar crédito'); ?>" class="btn btn-outline-info  btn-sm returnRequestData" data-request="<?php echo $this->Utilidades->encrypt($creditsRequest["CreditsRequest"]["id"]) ?>">
												        <i class="fa fa-arrow-left"></i>
												    </a>
												<?php elseif (in_array(AuthComponent::user("role"), [3]) and ($resultado1 == 0 or $resultado1 == 1) ) : ?>
													<a rel="tooltip" href="javascript:void(0)" title="<?php echo __('Regresar crédito'); ?>" class="btn btn-outline-info  btn-sm returnRequestData" data-request="<?php echo $this->Utilidades->encrypt($creditsRequest["CreditsRequest"]["id"]) ?>">
														<i class="fa fa-arrow-left"></i>
													</a>
											    <?php endif ?>

											</td>
										</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr><td class='text-center' colspan='<?php echo 6; ?>'>No existen resultados</td><tr>
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

<?php echo $this->element("/modals/request"); ?>
<?php echo $this->element("/modals/photoid"); ?>
<?php echo $this->element("/modals/comments"); ?>
<?php echo $this->element("/modals/decision"); ?>
<?php echo $this->element("/modals/credit_applied"); ?>
<?php echo $this->element("/modals/voucher"); ?>
<?php echo $this->element("/modals/credit_detail"); ?>
<?php echo $this->element("/modals/history_payments"); ?>

<?php

	echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));
	echo $this->Html->script("requests/admin.js?".rand(),           array('block' => 'AppScript'));

?>
