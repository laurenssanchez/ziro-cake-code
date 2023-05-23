<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('código de pago online'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <?php if ($request["Request"]["state"] == 0): ?>
	            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($request['Request']['id'])));?>">
	                <i class="fa fa-edit"></i>
	                <?php echo __('Editar'); ?>
	            </a>
            <?php endif ?>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Crear nuevo'); ?>
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
										<td><?php echo __('Identificación del usuario'); ?></td>
										<td>
											<?php echo h($request['Request']['identification']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Comercio'); ?></td>
										<td>
											<?php echo $request['ShopCommerce']['name']; ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Código generado'); ?></td>
										<td>
											<?php echo h($request['Request']['code']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Valor a pagar'); ?></td>
										<td>
											<?php echo h($request['Request']['value']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Estado'); ?></td>
									<td> <?php echo $request['Request']['state'] == 1 ? __('Pagado') : __('Sin pagar') ;?> </td>									</tr>
									<tr>
										<td><?php echo __('Estado del pago'); ?></td>
										<td>
											<?php echo h($request['Request']['date_payment']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Estado de solicitud de pago'); ?></td>
										<td>
											<?php echo h($request['Request']['state_request_payment']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Usuario que solicita'); ?></td>
										<td>
											<?php echo $request['User']['name']; ?>&nbsp;
										</td>
									</tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>