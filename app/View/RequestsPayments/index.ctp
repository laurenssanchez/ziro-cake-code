<div class="page-title">
  <div class="title_left">
    <h3><?php echo __('Transacciones web'); ?>
    </h3>

  </div>
</div>

<div class="clearfix"></div>
<?php if (in_array(AuthComponent::user("role"), [1,2])): ?>

<div class="row">
	<div class="col-md-12">
		<div class="paymentsblock">
			<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input('state', array('class'=>'form-control','label'=>"Estados de solicitudes",'div'=>false,'value'=>$estados, "options" => ["0" => "Solicitado","2" => "Pendiente", "1" => "Pagado" ] , "empty" => "Todos los estados", "required" => false )) ?>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<?php echo $this->Form->input('commerce', array('class'=>'form-control','label'=>"Código de proveedor",'div'=>false,'value'=>$commerce, "placeholder" => "Ingrese el código de proveedor" )) ?>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="">Fecha de solicitud</label>
						<?php echo $this->Form->text('request_date', array('class'=>'form-control',"type"=>"date",'label'=>"Fecha de solicitud",'div'=>false,'value'=>$request_date, "placeholder" => "Ingrese el código de proveedor a buscar", "required" => false )) ?>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="">Fecha de pago</label>
						<?php echo $this->Form->text('final_date', array('class'=>'form-control',"type"=>"date",'label'=>"Fecha de solicitud",'div'=>false,'value'=>$final_date, "placeholder" => "Ingrese el código de proveedor a buscar" )) ?>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="">CC Cliente</label>
						<?php echo $this->Form->text('customer', array('class'=>'form-control','label'=>false,'div'=>false,'value'=>$customer, "placeholder" => "Ingrese la cédula del cliente" )) ?>
					</div>
				</div>
				<div class="col-md-1">
					<button class="btn btn-success mt-4" type="submit">
			          	<?php echo __('Buscar'); ?>
			        </button>
			        <?php if ($estados != "" || $commerce != "" || $request_date != "" || $final_date != "" || $customer != "" ): ?>
			          	<a href="<?php echo $this->Html->url(["action"=>"index"]) ?>" class="btn btn-warning">
			          		Eliminar filtro <i class="fa fa-times"></i>
			          	</a>
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
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<?php if(empty($requestsPayments) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>
										<th><?php echo $this->Paginator->sort('created', __('Fecha de solicitud')); ?></th>
										<th><?php echo $this->Paginator->sort('value', __('Valor solicitado')); ?></th>
										<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Comercio')); ?></th>
										<?php if (AuthComponent::user("role") == 1): ?>
											<th>
												Datos para el pago
											</th>
										<?php endif ?>
										<th><?php echo $this->Paginator->sort('comision_percentaje', __('Porcentaje de comisión')); ?></th>
										<th><?php echo $this->Paginator->sort('comision_value', __('Valor comisión')); ?></th>
										<th><?php echo $this->Paginator->sort('date_payment', __('Fecha de pago')); ?></th>
										<th><?php echo $this->Paginator->sort('state', __('Fecha / Nota pendiente')); ?></th>
										<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>

										<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
				 if(!empty($requestsPayments)): ?>
<?php 					 foreach ($requestsPayments as $requestsPayment): ?>
							<tr>
								<td>
									<?php echo $requestsPayment["RequestsPayment"]["created"] ?>
								</td>
								<td>$<?php echo number_format($requestsPayment['RequestsPayment']['value']); ?>&nbsp;</td>
								<td><?php echo h($requestsPayment['ShopCommerce']['name'])." ".$requestsPayment['ShopCommerce']['Shop']["social_reason"]; ?>&nbsp;  <b><?php echo $requestsPayment['ShopCommerce']['code'] ?></b></td>
								<?php if (AuthComponent::user("role") == 1): ?>
									<td>
										<?php echo $requestsPayment["ShopCommerce"]["Shop"]["account_bank"] ?> /
										<?php echo $requestsPayment["ShopCommerce"]["Shop"]["account_number"] ?> /
										<?php echo $requestsPayment["ShopCommerce"]["Shop"]["account_type"] ?>
									</td>
								<?php endif ?>
								<td><?php echo h($requestsPayment['RequestsPayment']['comision_percentaje']); ?>%&nbsp;</td>
								<td>$<?php echo number_format($requestsPayment['RequestsPayment']['comision_value']); ?>&nbsp;</td>
								<td><?php echo h($requestsPayment['RequestsPayment']['date_payment']); ?>&nbsp;<br><?php echo $requestsPayment["RequestsPayment"]["note_payment"] ?></td>

								<td>
									<?php if (!empty($requestsPayment["RequestsPayment"]["date_pending"])): ?>
										<?php echo $requestsPayment["RequestsPayment"]["date_pending"] ?>
										<br>
										<?php echo $requestsPayment["RequestsPayment"]["note"] ?>
									<?php endif ?>
								</td>
								<td> <?php

									if ($requestsPayment['RequestsPayment']['state'] == 0) {
										echo "No pagado";
									}elseif ($requestsPayment['RequestsPayment']['state'] == 2) {
										echo "Pendiente";
									}else{
										echo "Pagado";
									}

								?> </td>

								<td class="td-actions">
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view',$this->Utilidades->encrypt($requestsPayment['RequestsPayment']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info  btn-xs">
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
			</div>
		</div>
	</div>
</div>




<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>
<script>
    $("body").on("click","#imprimeData",function(event) {
        var mode = 'iframe';
        var close = mode == "popup";
        var options = { mode : mode, popClose : close};
        $("div#pdfPayment").printArea( options );
    });
</script>
