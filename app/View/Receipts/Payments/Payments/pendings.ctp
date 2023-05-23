<div class="page-title">
	<div class="row">
		<div class="col-md-6">
		  	<?php if (in_array(AuthComponent::user("role"), [1,2])): ?>
		    <h3>Pagos solicitados por las tiendas </h3>

		    <h3><?php echo __('Saldos a favor:'); ?>
		    <b><?php echo isset($debt_credishop) ? "$".number_format($debt_credishop) : "0" ?></b>
		  	<?php else: ?>
	    	<h3><?php echo __('Historial de deudas a ZÍRO'); ?>
		  		<?php endif ?>
		    </h3>

		</div>
		<div class="col-md-6 pt-4">
			  <?php if (in_array(AuthComponent::user("role"), [1,2])): ?>
			    	<!-- <div class="form-group top_search pull-right">
			    		<a href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"index"]) ?>" class="btn btn-success">
			    			Recaudos consolidados <i class="fa fa-arrow-right"></i>
			    		</a>
			    	</div> -->
			    	<?php if (isset($debt_credishop)): ?>

			    	<div class="form-group top_search pull-right">
			    		<a href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"payment_actual"]) ?>" class="btn btn-warning" id="marcaFull" style="display:none;">
			    			Marcar como recogidos <i class="fa fa-check"></i>
			    		</a>
			    	</div>

			    	<?php endif ?>
			  <?php endif ?>
		</div>
	</div>
</div>



<div class="clearfix"></div>

<div class="row" style="display: block;">
	<div class="col-md-12">
		<?php if (in_array(AuthComponent::user("role"), [4,7])): ?>
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item" role="presentation">
			    <a class="nav-link " id="home-tab" href="<?php echo $this->Html->url(["controller"=>"shop_payment_requests","action"=>"index"]) ?>" href="#home" role="tab" aria-controls="home" aria-selected="true">COBRO DE CRÉDITOS</a>
			  </li>
			  <!--osn <li class="nav-item active" role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"pendings"]) ?>" role="tab" aria-controls="profile" aria-selected="false">DINEROS RECAUDADOS PARA CREDISHOP</a>
			  </li> -->
			</ul>
		<?php else: ?>
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item " role="presentation">
			    <a class="nav-link" id="profile-tab" href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"pendings"]) ?>" role="tab" aria-controls="profile" aria-selected="false">DINEROS RECAUDADOS PARA ZÍRO</a>
			  </li>
			  <li class="nav-item active" role="presentation">
			    <a class="nav-link " id="home-tab" href="<?php echo $this->Html->url(["controller"=>"shop_payment_requests","action"=>"index"]) ?>" href="#home" role="tab" aria-controls="home" aria-selected="true">COBRO DE CRÉDITOS</a>
			  </li>

			</ul>
		<?php endif ?>
		<div class="x_panel">
			<div class="x_content">
				<?php if (in_array(AuthComponent::user("role"), [1,2]) && !empty($totales)): ?>
					<div class="row">
						<?php foreach ($totales as $key => $value): ?>
							<div class="p-1 w-25">
		                        <div class="tile-stats <?php if (isset($this->request->query["commerce"]) && $this->Utilidades->decrypt($this->request->query["commerce"]) == $value["Payment"]["shop_commerce_id"]){ echo "comercio_seleccionado";}?>">
			                          <a href="<?php echo $this->Html->url(["action"=>"pendings","?" =>["commerce" => $this->Utilidades->encrypt($value["Payment"]["shop_commerce_id"]) ]]) ?>" class=""><b>$ <?php echo number_format($value["0"]["total"]) ?></b> <?php echo $value["Shop"]["social_reason"] ?> - <?php echo $value["ShopCommerce"]["name"] ?>
										</a>
		                        </div>
	                      </div>

						<?php endforeach ?>
					</div>
				<?php endif ?>
				<div class="table-responsive">
					<?php if (in_array(AuthComponent::user("role"), [1,2]) && !empty($payments) ): ?>
							<input type="checkbox" class="selectAll"> Seleccionar todos
					<?php endif; ?>
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<tbody>
							<?php
				 if(!empty($payments)): ?>
					<?php 	foreach ($payments as $payment): ?>
						<?php $dataPayment = end($payment); ?>
						<tr>
							<td colspan="<?php echo in_array(AuthComponent::user("role"), [1,2]) && !empty($payments) ? 8 : 7 ?>" class="p-0">
								<div class="accordion" id="accordionExample">
								  <div class="card">
								    <div class="card-header p-0" id="heading<?php echo $dataPayment["Payment"]["receipt_id"] ?>">
								      <h2 class="m-0">
								        <button class="btn btn-link btn-block text-left capt resetbtn" type="button" data-toggle="collapse" data-target="#collapse<?php echo $dataPayment["Payment"]["receipt_id"] ?>" aria-expanded="true" aria-controls="collapse<?php echo $dataPayment["Payment"]["receipt_id"] ?>">
								         	<b>Recibo #<?php echo str_pad($dataPayment["Payment"]["receipt_id"], 6, "0", STR_PAD_LEFT);  ?> </b>- Fecha:
								          <?php echo date("d-m-Y H:i:s",strtotime($dataPayment["Payment"]["created"])); ?> - Valor: $<?php echo number_format($dataPayment["Receipt"]["value"]) ?>
								        </button>
								      </h2>
								    </div>

								    <div id="collapse<?php echo $dataPayment["Payment"]["receipt_id"] ?>" class="collapse" aria-labelledby="heading<?php echo $dataPayment["Payment"]["receipt_id"] ?>" data-parent="#accordionExample">
								      <div class="card-body">
								      	<table class="table">
								      		<thead class="text-primary">
												<tr>
													<th></th>
													<th><?php echo __('Fecha pago'); ?></th>
													<th><?php echo __('Valor pago'); ?></th>
													<th><?php echo __('Concepto'); ?></th>
													<th><?php echo __('Número de obligación'); ?></th>
													<th><?php echo __('Número de cuota'); ?></th>
													<th><?php echo $this->Paginator->sort('user_id', __('Usuario')); ?></th>
													<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Comercio')); ?></th>
													<!-- <th><?php echo __('Acciones'); ?></th> -->
												</tr>
											</thead>
								      		<?php foreach ($payment as $keyP => $valueP): ?>
								      			<tr>
													<?php if (in_array(AuthComponent::user("role"), [1,2])): ?>
														<td>
															<input type="checkbox" class="selectPaymentsCobre" data-id="<?php echo $valueP["Payment"]["id"] ?>" >
														</td>
													<?php endif; ?>
													<td>
														<?php echo date("d-m-Y H:i A",strtotime($valueP["Payment"]["created"])) ?>
													</td>
													<td>$ <?php echo number_format($valueP["Payment"]["value"]); ?>&nbsp;</td>
													<td><?php echo Configure::read("TYPES_PAYMENT.".$valueP["Payment"]["type"]) ?></td>
													<td>
														<?php echo str_pad($valueP["CreditsPlan"]["number_credit"], 6, "0", STR_PAD_LEFT); ?>
													</td>
													<td>
														<?php echo $valueP["CreditsPlan"]["number"]; ?>
													</td>
													<td>
														<?php echo $valueP["User"]["name"]; ?>
													</td>
													<td>
														<?php echo $valueP['ShopCommerce']['shop']; ?> -
														<?php echo $valueP['ShopCommerce']['name']; ?>
													</td>
													<!-- <td class="td-actions">
													    <a rel="tooltip" href="<?php //echo $this->Html->url(array('action' => 'detail',$this->Utilidades->encrypt($payment['Payment']['credits_plan_id']),$this->Utilidades->encrypt($payment["0"]["fecha"]))); ?>" title="<?php //echo __('Ver'); ?>" class="btn btn-info btn-xs viewPaymentDetail">
													        <i class="fa fa-eye"></i>
													    </a>
													</td> -->
												</tr>
								      		<?php endforeach ?>
								      	</table>
								      </div>
								    </div>
								  </div>
							</td>
						</tr>

					<?php endforeach; ?>
					<?php else: ?>
							<tr><td class='text-center' colspan='<?php echo 7; ?>'>No existen resultados</td><tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<?php if (!in_array(AuthComponent::user("role"), [1,2])): ?>
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
				<?php endif; ?>

			</div>
		</div>
	</div>
</div>


<?php

/*


 */

 ?>



<?php echo $this->Html->script("payments/admin.js?".rand(),array('block' => 'AppScript')); ?>
