<div class="page-title">
  <div class="title_left">
    <h3><?php echo __('Disbursements'); ?>    	<a class="btn btn-info pull-right" href="<?php echo $this->Html->url(array('controller'=>$this->request->controller,'action'=>'add')); ?>">
			    <?php echo __('Adicionar nuevo'); ?>			</a>
    </h3>

  </div>

  <div class="title_right">
    <div class="col-md-5 col-sm-5 form-group pull-right top_search">
    	<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'form-inline')); ?>	      <div class="input-group">
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
					<?php if(empty($disbursements) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>						
										<th><?php echo $this->Paginator->sort('value', __('Value')); ?></th>
										<th><?php echo $this->Paginator->sort('unpaid_value', __('Unpaid Value')); ?></th>
										<th><?php echo $this->Paginator->sort('credit_id', __('Credit')); ?></th>
										<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Shop Commerce')); ?></th>
										<th><?php echo $this->Paginator->sort('state', __('State')); ?></th>
										<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
				 if(!empty($disbursements)): ?>
<?php 					 foreach ($disbursements as $disbursement): ?>
							<tr>
										<td><?php echo h($disbursement['Disbursement']['value']); ?>&nbsp;</td>
								<td><?php echo h($disbursement['Disbursement']['unpaid_value']); ?>&nbsp;</td>
								<td>
									<?php echo $this->Html->link($disbursement['Credit']['id'], array('controller' => 'credits', 'action' => 'view', $disbursement['Credit']['id'])); ?>
								</td>
								<td>
									<?php echo $this->Html->link($disbursement['ShopCommerce']['name'], array('controller' => 'shop_commerces', 'action' => 'view', $disbursement['ShopCommerce']['id'])); ?>
								</td>
								<td> <?php echo $disbursement['Disbursement']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									<td class="td-actions">
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view',$this->Utilidades->encrypt($disbursement['Disbursement']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info  btn-xs">
									        <i class="fa fa-eye"></i>
									    </a>
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'edit',$this->Utilidades->encrypt($disbursement['Disbursement']['id']))); ?>" title="<?php echo __('Editar'); ?>" class="btn btn-success btn-xs">
									        <i class="fa fa-edit"></i>
									    </a>
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($disbursement['Disbursement']['id']))); ?>" title="<?php echo $disbursement['Disbursement']['state'] == 1 ? __('Deshabilitar') : __('Habilitar'); ?>" class="btn btn-danger btn-xs changeState">
									    	<?php if($disbursement['Disbursement']['state'] == 1): ?>									        <i class="fa fa-times-circle-o"></i>
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
