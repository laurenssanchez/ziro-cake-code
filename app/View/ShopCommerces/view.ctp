<div class="page-title">
    <div class="row">
        <div class="col-md-6">
            <h3>Detalle de la sucursal</h3>
        </div>
        <div class="col-md-6 text-right">
             <a class="btn btn-primary"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                    <i class="fa fa-list-alt"></i>
                    <?php echo __('Ver todos los proveedores'); ?>
                </a>

                <a class="btn btn-secondary" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($shopCommerce['ShopCommerce']['id'])));?>">
                    <i class="fa fa-edit"></i>
                    <?php echo __('Editar'); ?>
                </a>
                <a class="btn btn-success"  href="<?php echo $this->Html->url(array('action'=>'add_user_commerce'));?>">
                    <i class="fa fa-plus-circle"></i>
                    <?php echo __('Registar empleado'); ?>
                </a>
        </div>

    </div>
</div>

<div class="clearfix"></div>
    <div class="row">
        <div class="col-md-5 col-sm-5 mt-3">
            <div class="x_panel">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>

                            <tr>
                                <td><?php echo __('Estado'); ?></td>
                                <td> <?php echo $shopCommerce['ShopCommerce']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>
                            </tr>
							<tr>
								<td><?php echo __('Nombre'); ?></td>
								<td>
									<?php echo h($shopCommerce['ShopCommerce']['name']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Dirección'); ?></td>
								<td>
									<?php echo h($shopCommerce['ShopCommerce']['address']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Teléfono'); ?></td>
								<td>
									<?php echo h($shopCommerce['ShopCommerce']['phone']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Foto'); ?></td>
								<td>
									<img src="<?php echo $this->Html->url("/files/shop_commerces/".$shopCommerce['ShopCommerce']['image']) ?>" alt="<?php echo $shopCommerce['ShopCommerce']['name'] ?>" class="img-fluid">
									&nbsp;
								</td>
							</tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-sm-7 mt-3">
            <div class="x_panel">
                <div class="table-responsive">
                	<h3>Usuarios registrados</h3>
                    <table class="table table-striped">
                    	<thead>
                    		<tr>
                    			<th>Nombre</th>
                    			<th>Correo eléctronico</th>
                    			<th>Rol</th>
                    			<th>Estado</th>
                    			<th>Acciones</th>
                    		</tr>
                    	</thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                            	<tr>
                            		<td colspan="5" class="text-center">
                            			No hay usuarios registrados
                            		</td>
                            	</tr>
                            <?php else: ?>
                            	<?php foreach ($users as $key => $value): ?>
                            		<tr>
                            			<td>
                            				<?php echo $value["User"]["name"] ?>
                            			</td>
                            			<td>
                            				<?php echo $value["User"]["email"] ?>
                            			</td>
                            			<td>
                            				<?php echo Configure::read("ROLES.".$value["User"]["role"]) ?>
                            			</td>
                            			<td>
                            				<?php echo $value["User"]["state"] == 1 ? "Activo" : "Inactivo" ?>
                            			</td>
                            			<td>
                            				<a rel="tooltip" href="<?php echo $this->Html->url(array('controller' => 'shop_commerces', 'action' => 'users_disable',$this->Utilidades->encrypt($value['User']['id']))); ?>" title="<?php echo $value['User']['state'] == 1 ? __('Deshabilitar') : __('Habilitar'); ?>" class="btn btn-danger btn-xs changeState">
										    	<?php if($value['User']['state'] == 1): ?>									        <i class="fa fa-times-circle-o"></i>
										        <?php else:  ?>
										        	<i class="fa fa-check-circle"></i>
										        <?php endif;  ?>
										    </a>
                            			</td>
                            		</tr>
                            	<?php endforeach ?>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


