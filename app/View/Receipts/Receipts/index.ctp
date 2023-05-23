<div class="page-title">
  <div class="title_left">
    <h3><?php echo __('Pagos realizados por clientes'); ?>    	
    </h3>

  </div>

  <div class="title_right">
    <div class="col-md-5 col-sm-5 form-group pull-right top_search">
    	<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'form-inline')); ?>	   	<div class="input-group">
	        <?php echo $this->Form->input('q', array('placeholder'=>__('Buscar...'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>$q)) ?>	        
	        <span class="input-group-btn">
	          <input type="submit" value="Buscar" class="btn btn-success">
	        </span>
	      </div>
	      
      <?php echo $this->Form->end(); ?>    
  </div>
  </div>
</div>

<div class="clearfix"></div>

<div class="row" style="display: block;">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<?php if(empty($receipts) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>						
										<th><?php echo $this->Paginator->sort('value', __('Valor')); ?></th>
										<th><?php echo $this->Paginator->sort('id', __('Número de recibo')); ?></th>
										<th><?php echo $this->Paginator->sort('user_id', __('Usuario')); ?></th>
										<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Comercio')); ?></th>
										<!-- <th class="text-center"><?php echo __('Número de pagos'); ?></th> -->
										<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($receipts)): ?>
								<?php 	foreach ($receipts as $receipt): ?>
									<tr>
											<td>$<?php echo number_format($receipt['Receipt']['value']); ?>&nbsp;</td>
										<td>
											<?php echo str_pad($receipt["Receipt"]["id"], 6, "0", STR_PAD_LEFT);  ?>
										</td>
										<td>
											<?php echo $this->Html->link($receipt['User']['name'], array('controller' => 'users', 'action' => 'view', $receipt['User']['id'])); ?>
										</td>
										<td>
											<?php echo $receipt['Shop']." - ".$receipt['ShopCommerce']['name']; ?>
										</td>
										<!-- <td class="text-center"> <?php echo count($receipt["Payment"]) ;?> </td> -->
										<td class="td-actions">
											    <a rel="tooltip" href="<?php echo $this->Html->url(array("controller"=>"payments",'action' => 'detail',$this->Utilidades->encrypt($receipt['Receipt']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info btn-xs viewPaymentDetail">
											        <i class="fa fa-eye"></i>
											    </a>

											    <a rel="tooltip" href="<?php echo $this->Html->url(array("controller"=>"payments",'action' => 'return_payments',$this->Utilidades->encrypt($receipt['Receipt']['id']))); ?>" title="<?php echo __('Retornar pagos'); ?>" class="btn btn-info btn-xs returnPayments">
											        <i class="fa fa-arrow-left"></i>
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


<div class="modal fade " id="pagoModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">
          <div class="content-tittles">
            <div class="line-tittles">|</div>
            <div>  
              <h1>RECIBO</h1>
              <h2>DE PAGOS</h2>
            </div>
          </div> 
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>        
      </div>
      <div class="modal-body" id="pagoBody">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<?php echo $this->Html->script("payments/admin.js?".rand(),array('block' => 'AppScript')); ?>