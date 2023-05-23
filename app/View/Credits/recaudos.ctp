<?php

$whitelist = array(
            '127.0.0.1',
            '::1'
        );
 ?>
<div class="page-title">
	<div class="row">
		<div class="col-md-9">
			<h3><?php echo __('Panel de informes - Recaudos'); ?></h3>

		</div>

		<?php if (in_array(AuthComponent::user("role"),[1])): ?>
		<div class="col-md-12">
			<div class="form-group topsearch">
				<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="">
								Código de proveedor
							</label>
							<?php echo $this->Form->input('commerce', array('placeholder'=>__('Buscar por código de proveedor'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($commerce) ? $commerce : "" )) ?>
						</div>
					</div>
					<div class="col-md-4">
						<label for="">
							Rango de fechas
						</label>
						<div class="rangofechas input-group ">
							<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
							<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
							<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo $this->Form->input('type_payment', array('label'=>__('Tipo de pago'), "options" => ["1"=>"Recaudo General","Recaudo WEB","Recaudo Júridico"],"empty"=>"Todos los tipos", 'class'=>'form-control','div'=>false,'value'=> isset($type_payment) ? $type_payment : "" )) ?>
						</div>
					</div>

					<div class="col-md-2 pt-4">
						<span class="input-group-btn">
							<button class="btn btn-success" type="submit" id="busca">
								<i class="fa fa-search"></i>
							</button>
						<?php if (isset($commerce) || isset($fechas) ): ?>
							<a href="<?php echo Router::url(["action"=>"recaudos"],true) ?>" class="btn btn-warning deleteWar">
				          		<i class="fa fa-times"></i>
				          	</a>

						<?php endif ?>
						<a href="<?php echo $this->Html->url(["action"=>"recaudos_export","?"=>$this->request->query],true) ?>" target="_blank" class="btn btn-danger" id="exportar212">
				          		Exportar excel <i class="fa fa-file"></i>
				          	</a>
						</span>
					</div>

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
		<div class="x_panel">
			<div class="x_title">
				<h2 class="h3 text-info text-center float-none">
					<b>Total Recaudado: $   <?php echo number_format($totalReceipt) ?></b>
				</h2>
			</div>
			<div class="x_content">
				<div class="table-responsive">
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="">
							<tr>
								<th><?php echo $this->Paginator->sort('CreditsPlan.created', __('Fecha Recaudo')); ?></th>
								<th><?php echo $this->Paginator->sort('CreditsPlan.credits_request_id', __('Obligación')); ?></th>
								<th><?php echo __('Cédula'); ?></th>
								<th><?php echo __('Nombre'); ?></th>
								<th><?php echo __('Teléfono'); ?></th>
								<!-- <th><?php echo __('Dirección'); ?></th> -->
								<th><?php echo __('Valor final'); ?></th>
								<th><?php echo __('Valor capital'); ?></th>
								<th><?php echo __('Valor intereses'); ?></th>
								<th><?php echo __('Valor otros cargos'); ?></th>
								<th><?php echo __('Valor interes mora'); ?></th>
								<th><?php echo __('Comercio'); ?></th>
								<th><?php echo __('recaudo'); ?></th>
							</tr>
						</thead>
						<tbody>
						<script type="text/javascript"> console.log(<?php  echo json_encode($receipts);?> )  </script>

						<?php if (!empty($receipts)): ?>
								<?php foreach ($receipts as $key => $value): ?>
									<tr>
										<td>
											<?php echo date("d-m-Y",strtotime($value["Payment"][0]["created"])) ?>
										</td>
										<td>
											<?php echo str_pad($value["Receipt"]["obligacion"], 6, "0", STR_PAD_LEFT); ?>
										</td>
										<td>
											<?php echo $value["customer"]["Customer"]["identification"] ?>
										</td>
										<td class="upper">
											<?php echo $value["customer"]["Customer"]["name"] ?> <?php echo $value["customer"]["Customer"]["last_name"] ?>
										</td>
										<td>
											<?php echo @$value["customer"]["CustomersPhone"]["0"]["phone_number"]; ?>
										</td>
										<!-- <td class="upper">
											<?php echo $value["customer"]["CustomersAddress"]["0"]["address"]; ?> <?php echo $value["customer"]["CustomersAddress"]["0"]["address_city"]; ?> <?php echo $value["customer"]["CustomersAddress"]["0"]["address_street"]; ?>
										</td> -->
										<td>
											$ <?php echo number_format($value["Receipt"]["total_payments"]) ?>
										</td>
										<td>
											$ <?php echo number_format($value["Receipt"]["total_capital"]) ?>
										</td>
										<td>
											$ <?php echo number_format($value["Receipt"]["total_intereses"]) ?>
										</td>
										<td>
											$ <?php echo number_format($value["Receipt"]["total_otros"]) ?>
										</td>
										<td>
											$ <?php echo number_format($value["Receipt"]["total_debts"]) ?>
										</td>
										<td>
											<?php if ($value["Payment"]["0"]["juridic"] == 1 ): ?>
												PAGO JURIDICA
											<?php else: ?>
												<?php if (empty($value["ShopCommerce"]["code"])): ?>
													PAGO WEB
												<?php else: ?>
													<b><?php echo $value["ShopCommerce"]["code"] ?> </b>  <?php echo $value["ShopCommerce"]["shop"] ?> - <?php echo $value["ShopCommerce"]["name"] ?>
												<?php endif ?>
											<?php endif ?>

										</td>
										<td>
										<?php echo $value["User"]["name"] ?>
										</td>
									</tr>
								<?php endforeach ?>
							<?php else: ?>
								<tr>
									<td class="text-center" colspan="8">
										No hay información
									</td>
								</tr>
							<?php endif ?>
						</tbody>
					</table>
				</div>
				<p class="pagination-out">
				    <?php

				    if(!empty($current) && !empty($count)){

					 echo $this->Paginator->counter(array(
					'format' => __('Página {:page} de {:pages}, {:current} registros de {:count} en total')
					));	}

					?>

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














<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<?php echo $this->element("fechas"); ?>

<?php echo $this->Html->script("reports/exports.js?".rand(),           array('block' => 'AppScript')); ?>
