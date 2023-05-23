<div class="page-title">
	<div class="row">
		<div class="col-md-6">
			<h3><?php echo __('Usuarios del sistema'); ?></h3>
		</div>
		<div class="col-md-3 text-right">
			<a class="btn btn-secondary" href="<?php echo $this->Html->url(array('controller'=>$this->request->controller,'action'=>'add')); ?>">
				<?php echo __('Registrar usuario'); ?>
			</a>
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
				<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
					<thead class="text-primary">
						<tr>						
							<th><?php echo $this->Paginator->sort('name', __('Nombre')); ?></th>
							<th><?php echo $this->Paginator->sort('email', __('Correo electrónico')); ?></th>
							<th><?php echo $this->Paginator->sort('role', __('Rol del usuario')); ?></th>
							<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
							<th><?php echo __('Acciones'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(!empty($users)): ?>
							<?php 	foreach ($users as $user): ?>
								<tr>
									<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
									<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
									<td><?php echo h(Configure::read("ROLES.".$user['User']['role'])); ?>&nbsp;</td>
									<td> <?php echo $user['User']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									
									<td class="td-actions">
										<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view',$this->Utilidades->encrypt($user['User']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-outline-info btn-xs">
											<i class="fa fa-eye"></i>
										</a>
										<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'edit',$this->Utilidades->encrypt($user['User']['id']))); ?>" title="<?php echo __('Editar'); ?>" class="btn btn-outline-success btn-xs">
											<i class="fa fa-edit"></i>
										</a>
										<?php if ($user["User"]["role"] != 8): ?>
											
											<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($user['User']['id']))); ?>" title="<?php echo $user['User']['state'] == 1 ? __('Deshabilitar') : __('Habilitar'); ?>" class="btn btn-outline-danger btn-xs changeState">
												<?php if($user['User']['state'] == 1): ?>									        
													<i class="fa fa-times-circle-o"></i>
												<?php else:  ?>									        
													<i class="fa fa-check-circle"></i>
												<?php endif;  ?>									    
											</a>
										<?php endif ?>
										</td>
									</tr>
								<?php endforeach; ?>
								<?php else: ?>
									<tr><td class='text-center' colspan='<?php echo 5; ?>'>No existen resultados</td><tr>
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
