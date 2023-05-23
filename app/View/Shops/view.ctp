<div class="page-title">
	<div class="row">
		<div class="col-md-6">
			<h3><?php echo __('Detalle del Proveedor Registrado'); ?></h3>
		</div>
		<div class="col-md-6 text-right">
			<a class="btn btn-secondary"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
				<i class="fa fa-list-alt"></i>
				<?php echo __('Volver a los proveedores'); ?>
			</a>
			<a class="btn btn-success"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
				<i class="fa fa-plus-circle"></i>
				<?php echo __('Registrar otro proveedor'); ?>
			</a>

			<a rel="tooltip" href="<?php echo $this->Html->url(array('action' => $shop["Shop"]["state"] == "1" ? "delete" : "change_state",$this->Utilidades->encrypt($shop['Shop']['id']))); ?>" class="btn btn-danger changeState">
				<?php if($shop['Shop']['state'] == 1): ?>
				<i class="fa fa-times-circle-o"></i> Deshabilitar
				<?php  else: ?>
					<i class="fa fa-check-circle"></i> Habilitar y acreditar pago
				<?php endif;  ?>
			</a>
		</div>
	</div>
</div>

	<div class="clearfix"></div>
	<div class="row mt-2">
		<div class="col-md-6 col-sm-6 ">
			<div class="x_panel">
				<div class="table-responsive">
					<div class="title-tables mb-2 mt-2">
						<h3 class="upper text-primary d-inline">Información del proveedor</h3>
					</div>
					<table class="table table-hover">
						<tbody>
							<tr>
								<td><?php echo __('Nit'); ?>: </td>
								<td><?php echo h($shop['Shop']['nit']); ?>&nbsp;</td>
							</tr>
							<tr>
								<td><?php echo __('Razón social'); ?>:</td>
								<td><?php echo h($shop['Shop']['social_reason']); ?>&nbsp;</td>
							</tr>
							<tr>
								<td><?php echo __('Gremio'); ?>: </td>
								<td><?php echo h($shop['Shop']['guild']); ?>&nbsp;</td>
							</tr>
							<tr>
								<td><?php echo __('Ciudad'); ?>:</td>
								<td><?php echo h($shop['Shop']['city']); ?> , <?php echo h($shop['Shop']['department']); ?></td>
							</tr>
							<tr>
								<td><?php echo __('Dirección'); ?>:</td>
								<td><?php echo h($shop['Shop']['address']); ?>&nbsp;</td>
							</tr>
							<tr>
								<td><?php echo __('Teléfono'); ?>:</td>
								<td><?php echo h($shop['Shop']['phone']); ?>&nbsp;</td>
							</tr>
							<tr>
								<td><?php echo __('Cámara de comercio'); ?>:</td>
								<td>
									<a href="<?php echo $this->Html->url("/files/shops/".$shop['Shop']['chamber_commerce_file']) ?>" class="btn btn-outline-secondary btn-sm" target="_href">Ver archivo <i class="fa fa-file"></i></a>
								</td>
							</tr>
							<tr>
								<td><?php echo __('RUT'); ?>:</td>
								<td>
									<a href="<?php echo $this->Html->url("/files/shops/".$shop['Shop']['rut_file']) ?>" class="btn btn-outline-secondary btn-sm" target="_href">Ver archivo <i class="fa fa-file"></i></a>
								</td>
							</tr>
							<tr>
								<td><?php echo __('Estado'); ?>:</td>
								<td> <?php echo $shop['Shop']['state'] == 1 ? __('Activo') : __('Sin confirmar el pago / Inactivo') ;?> </td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xl-6">
			<div class="x_panel">
				<div class="table-responsive">
					<div class="title-tables mb-2 mt-2">
						<h3 class="upper text-primary d-inline">Información del Administrador</h3>
					</div>
					<table class="table table-hover">
						<tbody>
							<tr>
								<td><?php echo __('Identificación del administrador'); ?>:</td>
								<td><?php echo h($shop['Shop']['identification_admin']); ?>&nbsp;</td>
							</tr>
							<tr>
								<td><?php echo __('Nombre administrador'); ?>:</td>
								<td><?php echo h($shop['Shop']['name_admin']); ?>&nbsp;</td>
							</tr>
							<tr>
								<td><?php echo __('Correo electrónico'); ?>:</td>
								<td><?php echo h($shop['Shop']['email']); ?>&nbsp;</td>
							</tr>
							<tr>
								<td><?php echo __('Celular administrador'); ?>:</td>
								<td><?php echo h($shop['Shop']['cellpone_admin']); ?>&nbsp;</td>
							</tr>
							<tr>
								<td><?php echo __('Foto administrador'); ?>:</td>
								<td>
									<a href="#" class="btn btn-outline-secondary btn-sm photoid1" >Ver foto <i class="fa fa-picture-o"></i></a>
									<img class="d-none photoAdmin" src="<?php echo $this->Html->url("/files/shops/".$shop['Shop']['image_admin']) ?>">
								</td>
							</tr>
							<tr>
								<td><?php echo __('Foto cédula parte delantera'); ?>:</td>
								<td>
									<a href="#" class="btn btn-outline-secondary btn-sm photoid2" >Ver foto <i class="fa fa-picture-o"></i></a>
									<img class="d-none photoCedDel" src="<?php echo $this->Html->url("/files/shops/".$shop['Shop']['identification_up_file']) ?>" >
								</td>
							</tr>
							<tr>
								<td><?php echo __('Foto cédula parte trasera'); ?>:</td>
								<td>
									<a href="#" class="btn btn-outline-secondary btn-sm photoid3" >Ver foto <i class="fa fa-picture-o"></i></a>
									<img class="d-none photoCedTras" src="<?php echo $this->Html->url("/files/shops/".$shop['Shop']['identification_down_file']) ?>">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xl-6">
			<div class="x_panel">
				<div class="table-responsive">
					<div class="title-tables mb-2 mt-2">
						<h3 class="upper text-primary d-inline">Información bancaria</h3>
					</div>
					<table class="table table-hover">
						<tbody>
							<tr>
								<td><?php echo __('Identificación titular'); ?>:</td>
								<td>
									<?php echo h($shop['Shop']['identification_account']); ?>&nbsp;
								</td>
							</tr>
							<tr>
								<td><?php echo __('Banco'); ?>:</td>
								<td>
									<?php echo h($shop['Shop']['account_bank']); ?>&nbsp;
								</td>
							</tr>
							<tr>
								<td><?php echo __('Número de cuenta'); ?>:</td>
								<td>
									<?php echo h($shop['Shop']['account_number']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Tipo de cuenta'); ?>:</td>
								<td>
									<?php echo h($shop['Shop']['account_type']); ?>&nbsp;
								</td>
							</tr>
							<?php if ($shop["Shop"]["type"] == 0): ?>

							<tr>
								<td><?php echo __('Referencia bancaría'); ?>:</td>
								<td>
									<a href="<?php echo $this->Html->url("/files/shops/".$shop['Shop']['account_file']) ?>" class="btn btn-outline-secondary btn-sm" target="_href">Ver archivo <i class="fa fa-file"></i></a>
								</td>
							</tr>
							<?php endif ?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xl-6">
			<div class="x_panel">
				<div class="table-responsive">
					<div class="title-tables mb-2 mt-2">
						<h3 class="upper text-primary d-inline">Información de afiliación</h3>
					</div>
					<table class="table table-hover">
						<tbody>
							<tr>
								<td><?php echo __('Plan'); ?>:</td>
								<td><?php echo Configure::read("PLANES.".$shop['Shop']['plan']); ?> - Comisión PAGO 1: <b>
									<?php echo $shop['Shop']['cost_min']; ?></b> -
									Comisión PAGO 2: <b><?php echo $shop['Shop']['cost_max']; ?></b></td>
							</tr>

							<tr>
								<td><?php echo __('Tipo de pago'); ?>:</td>
								<td><?php echo Configure::read("PAYMENT_TYPE_SHOPS.".$shop['Shop']['payment_type']); ?>&nbsp;</td>
							</tr>

							<tr>
								<td><?php echo __('Número de proveedores'); ?>:</td>
								<td><?php echo h($shop['Shop']['number_commerces']); ?>&nbsp;</td>
							</tr>

							<tr>
								<td><?php echo __('Pago total'); ?>:</td>
								<td><?php echo number_format($shop['Shop']['payment_total']); ?>&nbsp;</td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>
		</div>
			<div class="col-md-6 col-xl-6">
			    <div class="x_panel">
					<div class="table-responsive">
						<div class="title-tables mb-2 mt-2">
							<h3 class="upper text-primary d-inline"><?php echo __('Referencias del proveedor'); ?></h3>
						</div>
						<?php if (!empty($shop['ShopReference'])): ?>
							<table cellpadding = "0" cellspacing = "0" class="table">
								<thead>
									<tr>
										<th><?php echo __('Nombre'); ?></th>
										<th><?php echo __('Teléfono'); ?></th>
										<th><?php echo __('Proveedor que posee'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($shop['ShopReference'] as $shopReference): ?>
										<tr>
											<td><?php echo $shopReference['name']; ?></td>
											<td><?php echo $shopReference['phone']; ?></td>
											<td><?php echo $shopReference['commerce']; ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php endif; ?>
					</div>
				</div>
				<!--<div class="x_panel">-->
				<!--	<div class="table-responsive">-->
				<!--		<div class="title-tables mb-2 mt-2">-->
				<!--			<h3 class="upper text-primary d-inline">Información adicional de la empresa</h3>-->
				<!--		</div>-->
				<!--		<table class="table table-hover">-->
				<!--			<tbody>-->
				<!--				<tr>-->
				<!--					<td><?php echo __('Productos o servicios'); ?>:</td>-->
				<!--					<td><?php echo h($shop['Shop']['services_list']); ?>&nbsp;</td>-->
				<!--				</tr>-->

				<!--				<tr>-->
				<!--					<td><?php echo __('Cuenta con'); ?>:</td>-->
				<!--					<td>-->
				<!--						<?php $listProducts = explode(",", $shop['Shop']['products_lists']); ?>-->
				<!--						<ul class="list-unstyled list-inline mb-0">-->
				<!--							<?php foreach ($listProducts as $key => $value): ?>-->
				<!--								<li class="list-inline-item"><?php echo Configure::read("PRODUCT_LIST.$value")?>,</li>-->
				<!--							<?php endforeach ?>-->
				<!--						</ul>-->
				<!--					</td>-->
				<!--				</tr>-->
				<!--				<tr>-->
				<!--					<td><?php echo __('Asesor'); ?>:</td>-->
				<!--					<td>-->
				<!--						<?php echo h($shop['Adviser']['name']); ?>&nbsp;-->
				<!--					</td>-->
				<!--				</tr>-->
				<!--			</tbody>-->
				<!--		</table>-->
				<!--	</div>-->
				<!--</div>-->
			</div>

			<div class="col-md-6 col-xl-6">

			</div>
			<?php if (!empty($shop["ShopCommerce"])): ?>
				<div class="col-md-12">
					<div class="x_panel">
						<div class="table-responsive">
							<div class="title-tables text-center mb-2 mt-2">
								<h3 class="upper text-primary text-center d-inline"><?php echo __('SUCURSALES REGISTRADAS'); ?></h3>
							</div>
							<table cellpadding = "0" cellspacing = "0" class="table">
								<thead>
									<tr>
										<th><?php echo __('Nombre sede'); ?></th>
										<th><?php echo __('Dirección'); ?></th>
										<th><?php echo __('Teléfono'); ?></th>
										<th><?php echo __('Código de la tienda'); ?></th>
										<th><?php echo __('Enlace de la tienda'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($shop["ShopCommerce"] as $shopCommerce): ?>
										<tr>
											<td><?php echo $shopCommerce['name']; ?></td>
											<td><?php echo $shopCommerce['address']; ?></td>
											<td><?php echo $shopCommerce['phone']; ?></td>
											<td><b><?php echo $shopCommerce['code']; ?></b></td>
											<td>
												<?php
													$shopSucursal=strtolower($shop['Shop']['social_reason']);
													$shopSucursal=ucwords($shopSucursal);

													$nombreSucursal=str_replace(' ', '', $shopSucursal);
												?>
												<a href="<?php echo Router::url("/tu-credito/".$nombreSucursal.'/'.$shopCommerce['code'],true); ?>"
												target="_blank">
												<?php echo Router::url("/tu-credito/".$nombreSucursal.'/'.$shopCommerce['code'],true); ?>
												</a>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<?php endif ?>

	</div>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body">
      	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>
    </div>
  </div>
</div>


