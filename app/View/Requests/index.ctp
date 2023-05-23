<div class="page-title">
  <div class="title_left">
    <h3><?php echo __('Códigos para pagos online'); ?>    
    	<?php if (in_array(AuthComponent::user("role"), [4,6])): ?>
	    	<?php if (!empty($requestsNoPayment) > 0): ?>
	    		<?php foreach ($requestsNoPayment as $key => $value): ?>
	    			<a class="btn btn-warning pull-right" href="<?php echo $this->Html->url(array('controller'=>"requests_payments",'action'=>'add',$this->Utilidades->encrypt($value["ShopCommerce"]["id"]))); ?>">
						<?php echo __('Solicitar pago '); ?> <?php echo $value["ShopCommerce"]["name"]." ".$value["ShopCommerce"]["Shop"]["social_reason"] ?> | $(<?php echo number_format($value["0"]["total"]) ?>)
					</a>
	    		<?php endforeach ?>    			
	    	<?php endif ?>	

    	<?php endif ?>
    	<a class="btn btn-info pull-right" href="<?php echo $this->Html->url(array('controller'=>$this->request->controller,'action'=>'add')); ?>">
			<?php echo __('Crear nuevo código'); ?>	 <i class="fa fa-plus"></i>		
		</a>
    </h3>
    <hr>
    <a class="btn btn-warning pull-right" href="<?php echo $this->Html->url(array('controller'=>"requests_payments",'action'=>'index')); ?>">
		<?php echo __('Solicitudes de pago '); ?> 
	</a>

  </div>

  <div class="title_right">
    <div class="col-md-5 col-sm-5 form-group top_search">
    	<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>	      <div class="input-group">
	        <?php echo $this->Form->input('q', array('placeholder'=>__('Buscar...'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>$q)) ?>	        <span class="input-group-btn">
	          <button class="btn btn-default" type="button" type="submit">
	          	<?php echo __('Buscar'); ?>	          </button>
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
					<?php if(empty($requests) && !empty($this->request->query['q'])) : ?>
								<script type='text/javascript'>$(function(){demo.showNotification('<?php echo __('No se encontraron datos');?>', 'top','center','info');})</script>
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>						
										<th><?php echo $this->Paginator->sort('identification', __('Identificación del usuario')); ?></th>
										<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Comercio')); ?></th>
										<th><?php echo $this->Paginator->sort('code', __('Código')); ?></th>
										<th><?php echo $this->Paginator->sort('value', __('Valor a pagar')); ?></th>
										<th><?php echo $this->Paginator->sort('state', __('Estado del pago')); ?></th>
										<th><?php echo $this->Paginator->sort('date_payment', __('Fecha del pago')); ?></th>
										<th><?php echo $this->Paginator->sort('state_request_payment', __('Estado de la solicitud de pago')); ?></th>
										<th><?php echo $this->Paginator->sort('user_id', __('Usuario')); ?></th>
										<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
				 if(!empty($requests)): ?>
<?php 					 foreach ($requests as $request): ?>
							<tr>
								<td><?php echo h($request['Request']['identification']); ?>&nbsp;</td>
								<td>
									<?php echo $request['ShopCommerce']['name']; ?>
								</td>
								<td><?php echo h($request['Request']['code']); ?>&nbsp;</td>
								<td><?php echo h($request['Request']['value']); ?>&nbsp;</td>
								<td> <?php echo $request['Request']['state'] == 1 ? __('Pagado') : __('Sin pagar') ;?> </td>	
								<td><?php echo is_null($request['RequestsPayment']['date_payment']) ? "" : $request['RequestsPayment']['date_payment']; ?>&nbsp;</td>
								<td><?php

										if (is_null($request['Request']['state_request_payment'])) {
											echo "Sin solicitar";
										}else{
											echo $request['Request']['state_request_payment'] == 1 ? "Pagado" : "Sin pagar";
										}


								 ?>&nbsp;</td>
								<td>
									<?php echo $this->Html->link($request['User']['name'], array('controller' => 'users', 'action' => 'view', $request['User']['id'])); ?>
								</td>
									<td class="td-actions">
									    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view',$this->Utilidades->encrypt($request['Request']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info  btn-xs">
									        <i class="fa fa-eye"></i>
									    </a>
									    <?php if ($request["Request"]["state"] == 0): ?>
									    	
										    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'edit',$this->Utilidades->encrypt($request['Request']['id']))); ?>" title="<?php echo __('Editar'); ?>" class="btn btn-success btn-xs">
										        <i class="fa fa-edit"></i>
										    </a>
										<?php else: ?>
											<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'view_recipe',$this->Utilidades->encrypt($request['Request']['id']))); ?>" title="<?php echo __('Ver recibo'); ?>" class="btn btn-danger viewPaymentDetail btn-xs">
										        <i class="fa fa-file"></i>
										    </a>
									    <?php endif ?>
									</td>
								</tr>
					<?php endforeach; ?>
					<?php else: ?>
							<tr><td class='text-center' colspan='<?php echo 9; ?>'>No existen resultados</td><tr>
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
            <div>  
              <h1>Comprobante</h1>
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

<?php echo $this->Html->script("requests/others.js?".rand(),array('block' => 'AppScript')); ?>

<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>
<script>
    $("body").on("click","#imprimeData",function(event) {
        var mode = 'iframe';
        var close = mode == "popup";
        var options = { mode : mode, popClose : close};
        $("div#pdfPayment").printArea( options );
    });
</script>