<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('Shop Reference'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($shopReference['ShopReference']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Adicionar'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($shopReference['ShopReference']['id']))); ?>" class="btn btn-danger btn-sm changeState">
                <?php if($shopReference['ShopReference']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
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
                    <table class="table table-condensed">
                        <tbody>
                            
									<tr>
										<td><?php echo __('Name'); ?></td>
										<td>
											<?php echo h($shopReference['ShopReference']['name']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Phone'); ?></td>
										<td>
											<?php echo h($shopReference['ShopReference']['phone']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Commerce'); ?></td>
										<td>
											<?php echo h($shopReference['ShopReference']['commerce']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Shop'); ?></td>
										<td>
											<?php echo $this->Html->link($shopReference['Shop']['id'], array('controller' => 'shops', 'action' => 'view', $shopReference['Shop']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('State'); ?></td>
								<td> <?php echo $shopReference['ShopReference']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									</tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


