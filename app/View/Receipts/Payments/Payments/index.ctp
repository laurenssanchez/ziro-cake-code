<?php date_default_timezone_set('America/Bogota');
 ?>

<div class="page-title">
	<div class="row">
		<div class="col-md-12">
			<h3 class="d-inline mr-2"><?php echo __('Panel de informes - Intereses'); ?></h3>
			<ul class="nav nav-pills tabscontrols mb-3">
			  <li class="nav-item <?php echo $tab == 1 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"index","?" => ["tab" => 1]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Pagos fisicos
			    </a>
			  </li>	
			  <li class="nav-item <?php echo $tab == 2 ? "active bg-primary text-white" : "" ?>" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"index","?" => ["tab" => 2]]) ?>" role="tab" aria-controls="profile" aria-selected="false">
			    	Pagos web
			    </a>
			  </li>
			</ul>
		</div>
		<div class="col-md-9">
			<h3><?php echo __('Historial de recaudos'); ?> 							
				<?php if (in_array(AuthComponent::user("role"), [1,2]) && !empty($totales)): ?>
					<a href="<?php echo $this->Html->url(["action"=>"index","?"=>["tab"=>$tab]]) ?>" class="btn btn-primary">
						<i class="fa fa-check"></i> TODOS LOS RECAUDOS
					</a>
				<?php endif ?>	
			</h3>
		</div>
		<div class="col-md-3">
			<div class="form-group top_search">
    			<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>	      
    			<div class="input-group">
	        		<?php echo $this->Form->text('q', array('placeholder'=>__('Buscar...'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>$q,"type" => "date" )) ?>	     
	        		<?php echo $this->Form->text('tab', array('value'=>$tab,"type" => "hidden" )) ?>	     
	        		<span class="input-group-btn">
			          <button class="btn btn-default" type="submit">
			          	<?php echo __('Buscar'); ?>
			          </button>
			          <?php if (!empty($q)): ?>
			          	<a href="<?php echo $this->Html->url(["action"=>"index"]) ?>" class="btn btn-warning">
			          		Eliminar filtro <i class="fa fa-times"></i>
			          	</a>
			          <?php endif ?>
			        </span>
			     </div>
		      <?php echo $this->Form->end(); ?> 
			</div>			
		</div>	
		<?php if (in_array(AuthComponent::user("role"),[1,2,3,4,6])): ?>  	
			<div class="col-md-12">
				<div class="form-group top_search">
					<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
					<?php echo $this->Form->text('tab', array('value'=>$tab,"type" => "hidden" )) ?>	
					<div class="input-group">
						<?php echo $this->Form->input('ccCustomer', array('placeholder'=>__('Buscar cliente por cédula'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($ccCustomer) ? $ccCustomer : "" )) ?>
						<span class="input-group-btn">
							<button class="btn btn-success" type="submit" id="busca">
								<?php echo __('Buscar '); ?> <i class="fa fa-search"></i>
							</button>
						<?php if (isset($ccCustomer)): ?>
							<button class="btn btn-info" type="reset" id="limpia" >
								<?php echo __('Limpiar campos'); ?> <i class="fa fa-times"></i>
							</button>
						<?php endif ?>
						</span>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>	
			</div>		
			<?php endif ?>		
	</div>
</div>


<div class="clearfix"></div>
<div class="row" style="display: block;">
	<div class="col-md-12">
		<?php if (in_array(AuthComponent::user("role"), [1,2]) && !empty($totales)): ?>
		<div class="x_panel">
			<div class="x_content">
					<div class="row">
						<?php foreach ($totales as $key => $value): ?>
							<div class="p-1 w-25 ">
		                        <div class="tile-stats <?php if (isset($this->request->query["commerce"]) && $this->Utilidades->decrypt($this->request->query["commerce"]) == $value["Payment"]["shop_commerce_id"]){ echo "comercio_seleccionado";}?>">
			                          <a href="<?php echo $this->Html->url(["action"=>"index","?" =>["commerce" => $this->Utilidades->encrypt($value["Payment"]["shop_commerce_id"]) ]]) ?>" class="">
			                          	<span><b>$<?php echo number_format($value["0"]["total"]) ?></b> <?php echo $value["Shop"]["social_reason"] ?> - <?php echo $value["ShopCommerce"]["name"] ?> </span>
			                          </a>										
		                        </div>
	                        </div>
						<?php endforeach ?>
					</div>
			</div>
		</div>
		<?php endif ?>
	</div>

	<div class="col-md-12">
		<?php if (in_array(AuthComponent::user("role"), [1,2]) && !empty($totales)): ?>
			<h3 class="mb-4">Pagos recaudados a los clientes</h3>
		<?php endif ?>
		<div class="x_panel">
			<div class="x_content">		
				<div class="row">
				<div class="table-responsive">
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						
						<tbody>
							<?php 
							 if(!empty($customers)): ?>

							 	<?php foreach ($customers as $key => $value): ?>
							 		<tr>
										<td colspan="7" class="p-0">
											<div class="accordion" id="accordionExample">
											  <div class="card">
											    <div class="card-header p-0" id="heading<?php echo $key ?>">
											      <h2 class="m-0">
											        <button class="btn btn-link btn-block text-left capt resetbtn" type="button" data-toggle="collapse" data-target="#collapse<?php echo $key ?>" aria-expanded="true" aria-controls="collapse<?php echo $key ?>">
											         	<b>CC: <?php echo $value["identification"] ?></b> - <?php echo $value["name"] ?> <?php echo $value["last_name"] ?>
											        </button>
											      </h2>
											    </div>

											    <div id="collapse<?php echo $key ?>" class="collapse" aria-labelledby="heading<?php echo $key ?>" data-parent="#accordionExample">
											      <div class="card-body">
											      	<table class="table">
											      		<thead class="text-primary">
															<tr>						
																<th><?php echo __('Obligación'); ?></th>
																<th><?php echo __('Fecha pago'); ?></th>
																<th><?php echo __('Valor pago'); ?></th>
																<th><?php echo $this->Paginator->sort('user_id', __('Recaudó')); ?></th>
																<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Comercio')); ?></th>
																<th><?php echo __('Acciones'); ?></th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($payments[$key] as $payment): ?>
																<tr>
																	<td>
																		<?php echo str_pad($payment["CreditsPlan"]["number_credit"], 6, "0", STR_PAD_LEFT); ?>
																	</td>
																	<td>

																		<?php echo $this->Utilidades->date_castellano($payment["Payment"]["fecha"]) ?>

																	</td>
																	<td>$ <?php echo number_format($payment["0"]["total"]); ?>&nbsp;</td>
																	<td class="capt">
																		<?php echo $payment["User"]["name"]; ?>
																	</td>
																	<td class="capt">
																		<?php if (empty($payment["ShopCommerce"])): ?>
																			PAGO WEB
																		<?php else: ?>
																			<?php echo $payment['ShopCommerce']['shop']; ?> - 
																			<?php echo $payment['ShopCommerce']['name']; ?>
																		<?php endif ?>
																	</td>
																	<td class="td-actions">
																	    <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'detail',$this->Utilidades->encrypt($payment['Payment']['receipt_id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info btn-xs viewPaymentDetail">
																	        <i class="fa fa-eye"></i>
																	    </a>
																	</td>
																</tr>
															<?php endforeach; ?>
														</tbody>											      		
											      	</table>
											      </div>
											    </div>
											  </div>
										</td>
								  </tr>
							 	<?php endforeach ?>

								<?php else: ?>
							<tr><td class='text-center' colspan='<?php echo 1; ?>'>No existen resultados</td><tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
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


<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>
<script>
    $("body").on("click","#imprimeData",function(event) {
        var mode = 'iframe';
        var close = mode == "popup";
        var options = { mode : mode, popClose : close};
        $("div#pdfPayment").printArea( options );
    });
</script>