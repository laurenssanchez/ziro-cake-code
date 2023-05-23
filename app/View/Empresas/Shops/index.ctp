<div class="page-title">
	<div class="row">
		<div class="col-md-6">
			<h3><?php echo __('Proveedores registrados'); ?></h3>
		</div>
		<div class="col-md-3 text-right">
			<a class="btn btn-secondary" href="<?php echo $this->Html->url(array('controller'=>$this->request->controller,'action'=>'add')); ?>">
				<?php echo __('Registrar nuevo proveedor'); ?>
			</a>
		</div>
		<div class="col-md-3">
			<div class="form-group top_search">
				<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
				<div class="input-group">
					<?php echo $this->Form->input('q', array('placeholder'=>__('Buscar...'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>$q)) ?>
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit">
							<?php echo __('Buscar'); ?>
						</button>
					</span>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>

<div class="clearfix"></div>
<div class="row" style="display: block;">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<?php if(empty($shops) && !empty($this->request->query['q'])) : ?>
					<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
				<?php endif; ?>
				<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
					<thead class="text-primary">
						<tr>
							<th><?php echo $this->Paginator->sort('type', __('Tipo registro')); ?></th>

									</td>
							<th><?php echo $this->Paginator->sort('nit', __('Nit')); ?></th>
							<th><?php echo $this->Paginator->sort('social_reason', __('Razón solcial')); ?></th>
							<!-- <th><?php echo $this->Paginator->sort('guild', __('Gremio')); ?></th> -->
							<th><?php echo $this->Paginator->sort('name_admin', __('Administrador')); ?></th>
							<th><?php echo $this->Paginator->sort('plan', __('Plan')); ?></th>
							<th><?php echo $this->Paginator->sort('payment_type', __('Tipo de pago')); ?></th>
							<th><?php echo $this->Paginator->sort('number_commerces', __('Sucursales')); ?></th>
							<th><?php echo $this->Paginator->sort('payment_total', __('Pago total')); ?></th>
							<th><?php echo $this->Paginator->sort('cost_min', __('PAGO 1')); ?></th>
							<th><?php echo $this->Paginator->sort('cost_max', __('PAGO 2')); ?></th>
							<th><?php echo  __('Deuda actual'); ?></th>
							<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
							<th><?php echo __('Acciones'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(!empty($shops)): ?>
							<?php 					 foreach ($shops as $shop): ?>
								<tr>
									<td>
										<?php echo $shop["Shop"]["type"] == 0 ? "Autónomo" : "Normal" ?>
									</td>
									<td><?php echo h($shop['Shop']['nit']); ?>&nbsp;</td>
									<td><?php echo h($shop['Shop']['social_reason']); ?>&nbsp;</td>
									<!-- <td><?php echo h($shop['Shop']['guild']); ?>&nbsp;</td> -->
									<td><?php echo h($shop['Shop']['name_admin']); ?>&nbsp;</td>
									<td><?php echo h($shop['Shop']['plan']); ?>&nbsp;</td>
									<td><?php echo Configure::read("PAYMENT_TYPE_SHOPS.".$shop['Shop']['payment_type']) ; ?>&nbsp;</td>
									<td><?php echo h($shop['Shop']['number_commerces']); ?>&nbsp;</td>
									<td><?php echo h(number_format($shop['Shop']['payment_total'])); ?>&nbsp;</td>
									<td><?php echo h($shop['Shop']['cost_min']); ?>%</td>
									<td><?php echo h($shop['Shop']['cost_max']); ?>%</td>
									<td>
										<?php echo number_format($shop["Shop"]["debt"])?>
							<th><?php echo $this->Paginator->sort('nit', __('Nit')); ?></th>

									</td>
									</td>
									<td>
										<?php
										switch ($shop['Shop']['state']) {
											case '0':
											echo "Sin pagar";
											break;
											case '1':
											echo "Pago realizado";
											break;
											default:
												# code...
											break;}
										?>
									</td>
									<td class="td-actions">
										<a href="" id="debt_id_<?php echo $this->Utilidades->encrypt($shop["Shop"]["id"]) ?>" class="btn btn-outline-warning btn-xs addDebt" data-id="<?php echo $this->Utilidades->encrypt($shop["Shop"]["id"]) ?>">
											<i class="fa fa-cloud-upload" data-toggle="tooltip" data-placement="bottom" title="<?php echo __('Añadir deuda'); ?>"></i>
										</a>

										<a href="<?php echo $this->Html->url(array('action' => 'view',$this->Utilidades->encrypt($shop['Shop']['id']))); ?>"  class="btn btn-outline-primary btn-xs">
											<i class="fa fa-eye" data-toggle="tooltip" data-placement="bottom" title="<?php echo __('Ver'); ?>"></i>
										</a>
										<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'edit',$this->Utilidades->encrypt($shop['Shop']['id']))); ?>" title="<?php echo __('Editar'); ?>" class="btn btn-outline-secondary btn-xs">
											<i class="fa fa-edit"></i>
										</a>
										<a href="<?php echo $this->Html->url(array('action' => $shop["Shop"]["state"] == "1" ? "delete" : "change_state",$this->Utilidades->encrypt($shop['Shop']['id']))); ?>" class="btn btn-outline-danger btn-xs changeState">
											<?php if($shop['Shop']['state'] == 1): ?>
												<i class="fa fa-times-circle-o" data-toggle="tooltip" data-placement="top" title="Deshabilitar"></i> <span class="showfull">Deshabilitar</span>
											<?php else:  ?>
												<i class="fa fa-check-circle" data-toggle="tooltip" data-placement="top" title="Habilitar"></i> <span class="showfull">Habilitar</span>
											<?php endif;  ?>
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






<?php echo $this->element("/modals/debt"); ?>

<?php echo $this->Html->script("shops/admin.js?".rand(),           array('block' => 'AppScript')); ?>
