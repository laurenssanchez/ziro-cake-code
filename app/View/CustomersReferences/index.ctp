<div class="page-title">
  <div class="title_left">
    <h3><?php echo __('Customers References'); ?>    	<a class="btn btn-info pull-right" href="<?php echo $this->Html->url(array('controller'=>$this->request->controller,'action'=>'add')); ?>">
			    <?php echo __('Adicionar nuevo'); ?>			</a>
    </h3>

  </div>

  <div class="title_right">
    <div class="col-md-5 col-sm-5 form-group pull-right top_search">
    	<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>	      <div class="input-group">
	        <?php echo $this->Form->input('q', array('placeholder'=>__('Buscar...'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>$q)) ?>	        <span class="input-group-btn">
	          <button class="btn btn-default" type="button" type="submit">
	          	<?php echo __('Buscar'); ?>	          </button>
	        </span>
	      </div>
	      
      <?php echo $this->Form->end(); ?>    </div>
  </div>
</div>

<div class="clearfix"></div>

<div class="row" style="display: block;">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<?php if(empty($customersReferences) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>						
										<th><?php echo $this->Paginator->sort('name', __('Name')); ?></th>
										<th><?php echo $this->Paginator->sort('phone', __('Phone')); ?></th>
										<th><?php echo $this->Paginator->sort('customer_id', __('Customer')); ?></th>
										<th><?php echo $this->Paginator->sort('relationship', __('Relationship')); ?></th>
										<th><?php echo $this->Paginator->sort('company', __('Company')); ?></th>
										<th><?php echo $this->Paginator->sort('reference_type', __('Reference Type')); ?></th>
										<th><?php echo $this->Paginator->sort('state', __('State')); ?></th>
										<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
				 if(!empty($customersReferences)): ?>
<?php 					 foreach ($customersReferences as $customersReference): ?>
							<tr>
										<td><?php echo h($customersReference['CustomersReference']['name']); ?>&nbsp;</td>
								<td><?php echo h($customersReference['CustomersReference']['phone']); ?>&nbsp;</td>
								<td>
									<?php echo $this->Html->link($customersReference['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $customersReference['Customer']['id'])); ?>
								</td>
								<td><?php echo h($customersReference['CustomersReference']['relationship']); ?>&nbsp;</td>
								<td><?php echo h($customersReference['CustomersReference']['company']); ?>&nbsp;</td>
								<td><?php echo h($customersReference['CustomersReference']['reference_type']); ?>&nbsp;</td>
								<td> <?php echo $customersReference['CustomersReference']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									<td class="td-actions">
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view',$this->Utilidades->encrypt($customersReference['CustomersReference']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info  btn-xs">
									        <i class="fa fa-eye"></i>
									    </a>
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'edit',$this->Utilidades->encrypt($customersReference['CustomersReference']['id']))); ?>" title="<?php echo __('Editar'); ?>" class="btn btn-success btn-xs">
									        <i class="fa fa-edit"></i>
									    </a>
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($customersReference['CustomersReference']['id']))); ?>" title="<?php echo $customersReference['CustomersReference']['state'] == 1 ? __('Deshabilitar') : __('Habilitar'); ?>" class="btn btn-danger btn-xs changeState">
									    	<?php if($customersReference['CustomersReference']['state'] == 1): ?>									        <i class="fa fa-times-circle-o"></i>
									        <?php else:  ?>									        <i class="fa fa-check-circle"></i>
									        <?php endif;  ?>									    </a>
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
