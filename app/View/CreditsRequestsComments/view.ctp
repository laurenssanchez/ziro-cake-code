<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('Credits Requests Comment'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($creditsRequestsComment['CreditsRequestsComment']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Adicionar'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($creditsRequestsComment['CreditsRequestsComment']['id']))); ?>" class="btn btn-danger btn-sm changeState">
                <?php if($creditsRequestsComment['CreditsRequestsComment']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
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
										<td><?php echo __('Type'); ?></td>
										<td>
											<?php echo h($creditsRequestsComment['CreditsRequestsComment']['type']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Comment'); ?></td>
										<td>
											<?php echo h($creditsRequestsComment['CreditsRequestsComment']['comment']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Creditsrequest'); ?></td>
										<td>
											<?php echo $this->Html->link($creditsRequestsComment['CreditsRequest']['id'], array('controller' => 'credits_requests', 'action' => 'view', $creditsRequestsComment['CreditsRequest']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


