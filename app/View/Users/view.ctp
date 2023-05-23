<div class="page-title">
    <div class="row">
        <div class="col-md-6">
            <h3>Ver detalle del Usuario</h3>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-secondary" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($user['User']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-primary"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Registrar otro Usuario'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($user['User']['id']))); ?>" class="btn btn-danger changeState">
                <?php if($user['User']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
                <?php  else: ?> 
                    <i class="fa fa-check-circle"></i> Habilitar
                 <?php endif;  ?>                                      
            </a>
        </div>
    </div>
</div>

<div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>                            
							<tr>
								<td><?php echo __('Nombre'); ?></td>
								<td>
									<?php echo h($user['User']['name']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Correo electrónico'); ?></td>
								<td>
									<?php echo h($user['User']['email']); ?>&nbsp;
								</td>
							</tr>

                            <tr>
                                <td><?php echo __('Celular'); ?></td>
                                <td>
                                    <?php echo h($user['User']['phone']); ?>&nbsp;
                                </td>
                            </tr>

							<tr>
								<td><?php echo __('Rol del usuario'); ?></td>
								<td>
									<?php echo Configure::read("ROLES.".$user['User']['role']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Estado'); ?></td>
								<td> <?php echo $user['User']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									
							</tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
