<div class="page-title">
  <div class="title_left">
    <h3><?php echo __('Historial de créditos'); ?>
    </h3>
  </div>

  <div class="title_right">
    <!-- <div class="col-md-7 col-sm-7 form-group pull-right top_search">
    	<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'form-inline')); ?>	      <div class="input-group">
	        <?php echo $this->Form->input('q', array('placeholder'=>__('Buscar...'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>$q)) ?>	        <span class="input-group-btn">
	          <button class="btn btn-default" type="button" type="submit">
	          	<?php echo __('Buscar'); ?>	          </button>
	        </span>
	      </div>

      <?php echo $this->Form->end(); ?>    </div> -->

	  <div class="export-button">
		<a href="<?php echo $this->Html->url(array('controller' => 'credits', 'action' => 'index_export')); ?>"
			class="btn btn-primary">Exportar a Excel</a>
	</div>
  </div>
</div>

<div class="clearfix"></div>

<div class="row" style="display: block;">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<?php if(empty($credits) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>
										<th><?php echo $this->Paginator->sort('id', __('Número de obligación')); ?></th>
										<th><?php echo $this->Paginator->sort('value_request', __('Valor Retirado')); ?></th>
										<th><?php echo $this->Paginator->sort('number_fee', __('Cuotas')); ?></th>
										<th><?php echo $this->Paginator->sort('interes_rate', __('Tasa interés')); ?></th>
										<th><?php echo $this->Paginator->sort('others_rate', __('Tasa otros')); ?></th>
										<th><?php echo $this->Paginator->sort('debt_rate', __('Tasa de mora')); ?></th>
										<th><?php echo $this->Paginator->sort('quota_value', __('Valor cuota')); ?></th>
										<th><?php echo $this->Paginator->sort('value_pending', __('Valor pendiente')); ?></th>
										<th><?php echo $this->Paginator->sort('deadline', __('Fecha final')); ?></th>
										<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
										<?php if (AuthComponent::user("role") != 5): ?>
										<th><?php echo $this->Paginator->sort('customer_id', __('Cliente')); ?></th>
										<th><?php echo $this->Paginator->sort('customer_identification', __('Cliente')); ?></th>

										<?php endif ?>
										<th><?php echo __('Ver detalle'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 	if(!empty($credits)): ?>
								<?php foreach ($credits as $credit): ?>
									<tr>
										<td><?php echo $credit["CreditsRequest"]["code_pay"]; ?>&nbsp;</td>
										<td>$<?php echo number_format($credit['Credit']['value_request']); ?>&nbsp;</td>
										<td><?php echo h($credit['Credit']['number_fee']); ?>&nbsp;</td>
										<td class="text-center"><?php echo number_format($credit['Credit']['interes_rate']); ?>%&nbsp;</td>
										<td class="text-center"><?php echo ($credit['Credit']['others_rate']); ?>%&nbsp;</td>
										<td class="text-center"><?php echo h($credit['Credit']['debt_rate']); ?>%&nbsp;</td>
										<td>$<?php echo number_format($credit['Credit']['quota_value']); ?>&nbsp;</td>
										<td>$<?php echo number_format($credit['Credit']['value_pending'] < 1 ? 0 : $credit['Credit']['value_pending']); ?>&nbsp;</td>
										<td><?php echo h($credit['Credit']['deadline']); ?>&nbsp;</td>
										<td> <?php echo $credit['Credit']['state'] == 0 ? __('Activo') : __('Finalizado/Pagado') ;?> </td>
										<?php if (AuthComponent::user("role") != 5): ?>
											<td class="capt">
												<?php echo $credit['Customer']['name']?>
											</td>
											<td class="capt">
												<?php echo $credit['Customer']['identification']?>
											</td>
										<?php endif ?>
											<td class="td-actions">
											    <a rel="tooltip" href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($credit['Credit']['id']))); ?>" title="<?php echo __('Ver detalle'); ?>" class="btn btn-info  btn-xs">
											        <i class="fa fa-eye"></i>
											    </a>
											</td>
										</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr><td class='text-center' colspan='<?php echo 11; ?>'>No existen resultados</td><tr>
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
