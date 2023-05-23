<div class="page-title">
  <div class="title_left">
    <h3><?php echo __('Customers Phones'); ?>    	<a class="btn btn-info pull-right" href="<?php echo $this->Html->url(array('controller'=>$this->request->controller,'action'=>'add')); ?>">
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
					<?php if(empty($customersPhones) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>						
										<th><?php echo $this->Paginator->sort('customer_id', __('Customer')); ?></th>
										<th><?php echo $this->Paginator->sort('phone_type', __('Phone Type')); ?></th>
										<th><?php echo $this->Paginator->sort('phone_number', __('Phone Number')); ?></th>
										<th><?php echo $this->Paginator->sort('state', __('State')); ?></th>
										<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
				 if(!empty($customersPhones)): ?>
<?php 					 foreach ($customersPhones as $customersPhone): ?>
							<tr>
										<td>
									<?php echo $this->Html->link($customersPhone['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $customersPhone['Customer']['id'])); ?>
								</td>
								<td><?php echo h($customersPhone['CustomersPhone']['phone_type']); ?>&nbsp;</td>
								<td><?php echo h($customersPhone['CustomersPhone']['phone_number']); ?>&nbsp;</td>
								<td> <?php echo $customersPhone['CustomersPhone']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									<td class="td-actions">
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view',$this->Utilidades->encrypt($customersPhone['CustomersPhone']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info  btn-xs">
									        <i class="fa fa-eye"></i>
									    </a>
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'edit',$this->Utilidades->encrypt($customersPhone['CustomersPhone']['id']))); ?>" title="<?php echo __('Editar'); ?>" class="btn btn-success btn-xs">
									        <i class="fa fa-edit"></i>
									    </a>
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($customersPhone['CustomersPhone']['id']))); ?>" title="<?php echo $customersPhone['CustomersPhone']['state'] == 1 ? __('Deshabilitar') : __('Habilitar'); ?>" class="btn btn-danger btn-xs changeState">
									    	<?php if($customersPhone['CustomersPhone']['state'] == 1): ?>									        <i class="fa fa-times-circle-o"></i>
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
