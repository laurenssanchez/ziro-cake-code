<div class="page-title">
	<div class="row">
		<div class="col-md-6">
			<h3>Sucursales de este proveedor</h3>
		</div>
		<div class="col-md-3 text-right">
    	<?php if ($created): ?>
	    	<a class="btn btn-secondary" href="<?php echo $this->Html->url(array('controller'=>$this->request->controller,'action'=>'add')); ?>">
				<?php echo __('Adicionar nueva sucursal'); ?>
			</a>
    	 <?php endif ?>
		</div>
		<div class="col-md-3">
			<div class="form-group top_search">
				<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
				<div class="input-group">
					<?php echo $this->Form->input('q', array('placeholder'=>__('Buscar...'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>$q)) ?>
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" type="submit">
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
					<?php if(empty($shopCommerces) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>
								<th><?php echo $this->Paginator->sort('name', __('Código')); ?></th>
								<th><?php echo $this->Paginator->sort('name', __('Nombre')); ?></th>
								<th><?php echo $this->Paginator->sort('address', __('Dirección')); ?></th>
								<th><?php echo $this->Paginator->sort('phone', __('Teléfono')); ?></th>
								<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
								<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($shopCommerces)): ?>
								<?php foreach ($shopCommerces as $shopCommerce): ?>
								<tr>
									<td><?php echo h($shopCommerce['ShopCommerce']['code']); ?>&nbsp;</td>
									<td><?php echo h($shopCommerce['ShopCommerce']['name']); ?>&nbsp;</td>
									<td><?php echo h($shopCommerce['ShopCommerce']['address']); ?>&nbsp;</td>
									<td><?php echo h($shopCommerce['ShopCommerce']['phone']); ?>&nbsp;</td>
									<td><?php echo h($shopCommerce['ShopCommerce']['state'] == 1 ? "Habilitado" : "Deshabilitado");  ?>&nbsp;</td>
									<td class="td-actions">
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view',$this->Utilidades->encrypt($shopCommerce['ShopCommerce']['id']))); ?>" title="<?php echo __('Ver detalle'); ?>" class="btn btn-outline-primary btn-sm">
									        <i class="fa fa-eye"></i>
									    </a>
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'edit',$this->Utilidades->encrypt($shopCommerce['ShopCommerce']['id']))); ?>" title="<?php echo __('Editar'); ?>" class="btn btn-outline-secondary btn-sm">
									        <i class="fa fa-edit"></i>
									    </a>
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'add_user_commerce')); ?>" title="<?php echo __('Adicionar nuevo usuario'); ?>" class="btn btn-outline-success btn-sm">
									        <i class="fa fa-user-plus"></i>
									    </a>
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete_data',$this->Utilidades->encrypt($shopCommerce['ShopCommerce']['id']))); ?>" title="<?php echo $shopCommerce['ShopCommerce']['state'] == 1 ? __('Deshabilitar') : __('Habilitar'); ?>" class="btn btn-outline-danger btn-xs changeState">
												<?php if($shopCommerce['ShopCommerce']['state'] == 1): ?>
													<i class="fa fa-times-circle-o"></i>
												<?php else:  ?>
													<i class="fa fa-check-circle"></i>
												<?php endif;  ?>
											</a>
									</td>
								</tr>
							<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td class='text-center' colspan='<?php echo 1; ?>'>No existen resultados</td>
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
