<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('Automatic'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($automatic['Automatic']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Adicionar'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($automatic['Automatic']['id']))); ?>" class="btn btn-danger btn-sm changeState">
                <?php if($automatic['Automatic']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
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
										<td><?php echo __('Min value'); ?></td>
										<td>
											<?php echo h($automatic['Automatic']['min_value']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Max value'); ?></td>
										<td>
											<?php echo h($automatic['Automatic']['max_value']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Type value'); ?></td>
										<td>
											<?php echo h($automatic['Automatic']['type_value']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Score min'); ?></td>
										<td>
											<?php echo h($automatic['Automatic']['score_min']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Aplica cap'); ?></td>
										<td>
											<?php echo h($automatic['Automatic']['aplica_cap']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Cap'); ?></td>
										<td>
											<?php echo h($automatic['Automatic']['cap']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Min oblig'); ?></td>
										<td>
											<?php echo h($automatic['Automatic']['min_oblig']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Aplica min value oblig'); ?></td>
										<td>
											<?php echo h($automatic['Automatic']['aplica_min_value_oblig']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Min value oblig'); ?></td>
										<td>
											<?php echo h($automatic['Automatic']['min_value_oblig']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Min mora'); ?></td>
										<td>
											<?php echo h($automatic['Automatic']['min_mora']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('State'); ?></td>
								<td> <?php echo $automatic['Automatic']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									</tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


