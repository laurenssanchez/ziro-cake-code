<div class="page-title">
	<div class="row">
		<div class="col-md-5">
			<div class="title_left">
			    <h3><?php echo __('Códigos enviados'); ?> </h3>

			</div>
		</div>
		<?php if ($tab == 1): ?>
			<div class="col-md-7">
			    <div class="form-group top_search">
			    	<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
			    	<div class="input-group">
				        	<?php echo $this->Form->input('q', array('placeholder'=>__('Buscar...'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>$q)) ?>
				        	<?php echo $this->Form->input('tab', array('placeholder'=>__('Buscar...'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=>$tab,"type"=>"hidden")) ?>
				        	<span class="input-group-btn">
				          		<input type="submit" class="btn btn-success" value="Buscar">
				        	</span>
				    </div>
			      <?php echo $this->Form->end(); ?>
			    </div>
			</div>
		<?php endif ?>
	</div>
 </div>

<div class="clearfix"></div>

<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link <?php echo $tab == 1 ? "active" : "" ?>" id="home-tab" href="<?php echo $this->Html->url(["controller" => "customers_codes", "action" => "index", "?" => ["tab" => 1] ]) ?>" role="tab" aria-controls="home" aria-selected="true">CÓDIGOS ENVIADOS AL CLIENTE</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php echo $tab == 2 ? "active" : "" ?>" id="profile-tab" href="<?php echo $this->Html->url(["controller" => "customers_codes", "action" => "index", "?" => ["tab" => 2] ]) ?>" role="tab" aria-controls="profile" aria-selected="false">CÓDIGOS ENVIADOS A LOS USUARIOS</a>
	</li>
</ul>

<?php if ($tab == 1): ?>


<div class="row" >
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>
								<th><?php echo $this->Paginator->sort('code', __('Código')); ?></th>
								<th><?php echo $this->Paginator->sort('type_code', __('Tipo de código')); ?></th>
								<th><?php echo $this->Paginator->sort('customer_id', __('Cliente')); ?></th>
								<th><?php echo $this->Paginator->sort('customer_id', __('Identificación')); ?></th>
								<th><?php echo $this->Paginator->sort('deadline', __('Vencimiento')); ?></th>
								<th><?php echo $this->Paginator->sort('credits_request_id', __('Obligación')); ?></th>
								<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
								<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
				 if(!empty($customersCodes)): ?>
						<?php 	foreach ($customersCodes as $customersCode): ?>
							<tr>
								<td><h4 class="mb-0"><b><?php echo h($customersCode['CustomersCode']['code']); ?></b></h4></td>
								<td><?php echo $customersCode['CustomersCode']['type_code'] == "1" ? "Correo electrónico" : "Celular"; ?>&nbsp;</td>
								<td class="upper">
									<?php echo $customersCode['Customer']['name']; ?>
								</td>
								<td>
									<?php echo $customersCode['Customer']['identification']; ?>
								</td>
								<td><?php echo date("d-m-Y H:i A",$customersCode['CustomersCode']['deadline']); ?>&nbsp;</td>
								<td>
									<?php echo !empty($customersCode["CustomersCode"]["credits_request_id"]) ? str_pad($customersCode["CustomersCode"]["credits_request_id"], 6, "0", STR_PAD_LEFT) : ""; ?>&nbsp;
								</td>
								<td class="upper">
									<?php

										switch ($customersCode['CustomersCode']['state']) {
											case '1':
												echo "validado";
												break;
											case '2':
												echo "Código expirado";
												break;
											case '0':
												echo "Sin validar";
												break;

											default:
												# code...
												break;
										}

								 	?>
								</td>
								<td class="td-actions">
								    <?php if ($customersCode["CustomersCode"]["state"] == 0 && $customersCode['CustomersCode']['type_code'] == "2"): ?>
								    	<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'resend',$this->Utilidades->encrypt($customersCode['CustomersCode']['id']))); ?>" title="<?php echo __('Ver'); ?>" class="btn btn-info resendCustomerCode  btn-sm">
									        <i class="fa fa-refresh"></i> Reenviar el código
									    </a>
								    <?php endif ?>
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

<?php else: ?>




<div class="row" style="display: block;">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>
								<th><?php echo $this->Paginator->sort('code', __('Código')); ?></th>
								<th><?php echo $this->Paginator->sort('deadline', __('Vencimiento')); ?></th>
								<th><?php echo $this->Paginator->sort('type_code', __('Usuario')); ?></th>
								<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
								<th><?php echo __('Acciones'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
				 if(!empty($users)): ?>
						<?php 	foreach ($users as $user): ?>
							<tr>
								<td><?php echo h($user['User']['code']); ?>&nbsp;</td>
								<td><?php echo date("d-m-Y H:i A",$user['User']['deadline']); ?>&nbsp;</td>
								<td><?php echo $user['User']['name']; ?>&nbsp;</td>
								<td>
									<?php echo $user['User']['validate'] == 0 ? "Sin validar" : "Validado"  ?>&nbsp;
								</td>
								<td class="td-actions">
								    <?php if ($user["User"]["validate"] == 0 ): ?>
								    	<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'resend_user',$this->Utilidades->encrypt($user['User']['id']))); ?>" class="btn btn-info resendUserCode  btn-sm">
									        <i class="fa fa-refresh"></i> Reenviar el código
									    </a>
								    <?php endif ?>
								</td>
							</tr>
					<?php endforeach; ?>
					<?php else: ?>
							<tr><td class='text-center' colspan='<?php echo 1; ?>'>No existen resultados</td><tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<?php endif ?>


<?php echo $this->Html->script("codes/admin.js?".rand(),array('block' => 'AppScript')); ?>
