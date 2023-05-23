<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('Customers Code'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($customersCode['CustomersCode']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Adicionar'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($customersCode['CustomersCode']['id']))); ?>" class="btn btn-danger btn-sm changeState">
                <?php if($customersCode['CustomersCode']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
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
										<td><?php echo __('Code'); ?></td>
										<td>
											<?php echo h($customersCode['CustomersCode']['code']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Customer'); ?></td>
										<td>
											<?php echo $this->Html->link($customersCode['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $customersCode['Customer']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Deadline'); ?></td>
										<td>
											<?php echo h($customersCode['CustomersCode']['deadline']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Type code'); ?></td>
										<td>
											<?php echo h($customersCode['CustomersCode']['type_code']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Credits request id'); ?></td>
										<td>
											<?php echo h($customersCode['CustomersCode']['credits_request_id']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('State'); ?></td>
								<td> <?php echo $customersCode['CustomersCode']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									</tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


