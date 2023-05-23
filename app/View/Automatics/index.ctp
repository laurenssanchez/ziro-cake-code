<div class="page-title">
  <div class="title_left">
    <h3><?php echo __('Configuraciones automáticas'); ?>    	<a class="btn btn-info pull-right" href="<?php echo $this->Html->url(array('controller'=>$this->request->controller,'action'=>'add')); ?>">
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
					<?php if(empty($automatics) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>						
										<th><?php echo $this->Paginator->sort('min_value', __('Valor min aprobar')); ?></th>
										<th><?php echo $this->Paginator->sort('max_value', __('Valor max aprobar')); ?></th>
										<th><?php echo $this->Paginator->sort('type_value', __('Tipo valor')); ?></th>
										<th><?php echo $this->Paginator->sort('score_min', __('Score Mínimo')); ?></th>
										<th><?php echo $this->Paginator->sort('aplica_cap', __('Aplica min Capacidad de endeudamiento')); ?></th>
										<th><?php echo $this->Paginator->sort('cap', __('Capacidad de endeudamiento')); ?></th>
										<th><?php echo $this->Paginator->sort('min_oblig', __('Min Obligaciones')); ?></th>
										<th><?php echo $this->Paginator->sort('aplica_min_value_oblig', __('Aplica Min Valor Oblig')); ?></th>
										<th><?php echo $this->Paginator->sort('min_value_oblig', __('Min Valor Oblig')); ?></th>
										<th><?php echo $this->Paginator->sort('min_mora', __('Min Mora')); ?></th>
										<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
										<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
				 if(!empty($automatics)): ?>
<?php 					 foreach ($automatics as $automatic): ?>
							<tr>
										<td>$<?php echo number_format($automatic['Automatic']['min_value']); ?>&nbsp;</td>
								<td>$<?php echo number_format($automatic['Automatic']['max_value']); ?>&nbsp;</td>
								<td><?php echo Configure::read("TYPE_APPROBE.".$automatic['Automatic']['type_value']); ?>&nbsp;</td>
								<td><?php echo h($automatic['Automatic']['score_min']); ?>&nbsp;</td>
								<td><?php echo ($automatic['Automatic']['aplica_cap']) == 1 ? "Si" :"No"; ?></td>
								<td><?php echo h($automatic['Automatic']['cap']); ?>%</td>
								<td><?php echo h($automatic['Automatic']['min_oblig']); ?>&nbsp;</td>
								<td><?php echo ($automatic['Automatic']['aplica_min_value_oblig']) == 1 ? "Si" :"No"; ?>&nbsp;</td>
								<td><?php echo empty($automatic['Automatic']['min_value_oblig']) ? "N/A" : "$ ".number_format($automatic['Automatic']['min_value_oblig']); ?>&nbsp;</td>
								<td><?php echo h($automatic['Automatic']['min_mora']); ?>&nbsp;</td>
								<td> <?php echo $automatic['Automatic']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									
								<td class="td-actions">
									    
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'edit',$this->Utilidades->encrypt($automatic['Automatic']['id']))); ?>" title="<?php echo __('Editar'); ?>" class="btn btn-success btn-xs">
									        <i class="fa fa-edit"></i>
									    </a>
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($automatic['Automatic']['id']))); ?>" title="<?php echo $automatic['Automatic']['state'] == 1 ? __('Deshabilitar') : __('Habilitar'); ?>" class="btn btn-danger btn-xs changeState">
									    	<?php if($automatic['Automatic']['state'] == 1): ?>									        <i class="fa fa-times-circle-o"></i>
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
