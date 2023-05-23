<div class="page-title">
  <div class="title_left">
  	<?php if (in_array(AuthComponent::user("role"), [1,2])): ?>
    	<h3><?php echo __('Pagos recibidos'); ?>
  	<?php else: ?>
    	<h3><?php echo __('Pagos realizados a ZÍRO'); ?>
  	<?php endif ?>
    </h3>

  </div>
  <?php if (in_array(AuthComponent::user("role"), [1,2])): ?>
  	<div class="title_right pull-right">
    	<div class="col-md-12 col-sm-12 form-group  top_search">
    		<a href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"index"]) ?>" class="btn btn-success">
    			Historial de recaudos <i class="fa fa-arrow-right"></i>
    		</a>
    	</div>
   	</div>
  <?php endif ?>

<div class="clearfix"></div>

<div class="row" style="display: block;">
	<div class="col-md-12">
		<?php if (in_array(AuthComponent::user("role"), [4,7])): ?>
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item" role="presentation">
			    <a class="nav-link " id="home-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"pendings"]) ?>" href="#home" role="tab" aria-controls="home" aria-selected="true">Saldo por pagar</a>
			  </li>
			  <li class="nav-item active" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"payments_receipt"]) ?>" role="tab" aria-controls="profile" aria-selected="false">Saldo pagado</a>
			  </li>
			</ul>
		<?php else: ?>
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item active" role="presentation">
			    <a class="nav-link " id="home-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"pendings"]) ?>" href="#home" role="tab" aria-controls="home" aria-selected="true">Saldo por cobrar</a>
			  </li>
			  <li class="nav-item " role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"payments_receipt"]) ?>" role="tab" aria-controls="profile" aria-selected="false">Pagos recibidos</a>
			  </li>
			</ul>
		<?php endif ?>
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<?php if(empty($payments) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>
								<th><?php echo __('Fecha pago'); ?></th>
								<th><?php echo __('Valor pago'); ?></th>
								<th><?php echo __('Concepto'); ?></th>
								<th><?php echo __('Número de obligación'); ?></th>
								<th><?php echo __('Número de cuota'); ?></th>
								<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Comercio')); ?></th>
								<!-- <th><?php echo __('Acciones'); ?></th> -->
							</tr>
						</thead>
						<tbody>
							<?php
				 if(!empty($payments)): ?>
					<?php 	foreach ($payments as $payment): ?>
							<tr>
								<td>
									<?php echo date("d-m-Y H:i A",$payment["Payment"]["date_credishop"]) ?>
								</td>
								<td>$ <?php echo number_format($payment["Payment"]["value"]); ?>&nbsp;</td>
								<td><?php echo Configure::read("TYPES_PAYMENT.".$payment["Payment"]["type"]) ?></td>
								<td>
									<?php echo str_pad($payment["CreditsPlan"]["number_credit"], 6, "0", STR_PAD_LEFT); ?>
								</td>
								<td>
									<?php echo $payment["CreditsPlan"]["number"]; ?>
								</td>
								<td>
									<?php if (empty($payment['ShopCommerce'])): ?>
										PAGO WEB
									<?php else: ?>
										<?php echo $payment['ShopCommerce']['shop']; ?> -
										<?php echo $payment['ShopCommerce']['name']; ?>
									<?php endif ?>
								</td>
								<!-- <td class="td-actions">
								    <a rel="tooltip" href="<?php //echo $this->Html->url(array('action' => 'detail',$this->Utilidades->encrypt($payment['Payment']['credits_plan_id']),$this->Utilidades->encrypt($payment["0"]["fecha"]))); ?>" title="<?php //echo __('Ver'); ?>" class="btn btn-info btn-xs viewPaymentDetail">
								        <i class="fa fa-eye"></i>
								    </a>
								</td> -->
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





<?php echo $this->Html->script("payments/admin.js?".rand(),array('block' => 'AppScript')); ?>
