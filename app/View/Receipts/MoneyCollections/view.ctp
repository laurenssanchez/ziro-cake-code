<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('Money Collection'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($moneyCollection['MoneyCollection']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Adicionar'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($moneyCollection['MoneyCollection']['id']))); ?>" class="btn btn-danger btn-sm changeState">
                <?php if($moneyCollection['MoneyCollection']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
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
										<td><?php echo __('Value'); ?></td>
										<td>
											<?php echo h($moneyCollection['MoneyCollection']['value']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Shopcommerce'); ?></td>
										<td>
											<?php echo $this->Html->link($moneyCollection['ShopCommerce']['name'], array('controller' => 'shop_commerces', 'action' => 'view', $moneyCollection['ShopCommerce']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('User'); ?></td>
										<td>
											<?php echo $this->Html->link($moneyCollection['User']['name'], array('controller' => 'users', 'action' => 'view', $moneyCollection['User']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('State'); ?></td>
								<td> <?php echo $moneyCollection['MoneyCollection']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									</tr>

									<tr>
										<td><?php echo __('Payment credit'); ?></td>
										<td>
											<?php echo h($moneyCollection['MoneyCollection']['payment_credit']); ?>&nbsp;
										</td>
									</tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


