<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('Requests Detail'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($requestsDetail['RequestsDetail']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Adicionar'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($requestsDetail['RequestsDetail']['id']))); ?>" class="btn btn-danger btn-sm changeState">
                <?php if($requestsDetail['RequestsDetail']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
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
										<td><?php echo __('Request'); ?></td>
										<td>
											<?php echo $this->Html->link($requestsDetail['Request']['id'], array('controller' => 'requests', 'action' => 'view', $requestsDetail['Request']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('State payment'); ?></td>
										<td>
											<?php echo h($requestsDetail['RequestsDetail']['state_payment']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Value'); ?></td>
										<td>
											<?php echo h($requestsDetail['RequestsDetail']['value']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Response'); ?></td>
										<td>
											<?php echo h($requestsDetail['RequestsDetail']['response']); ?>&nbsp;
										</td>
									</tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="related">
    <h3><?php echo __('Related Requests'); ?></h3>
    <?php if (!empty($requestsDetail['Request'])): ?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Identification'); ?></th>
		<th><?php echo __('Shop Commerce Id'); ?></th>
		<th><?php echo __('Code'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('State Request Payment'); ?></th>
		<th><?php echo __('Requests Detail Id'); ?></th>
		<th><?php echo __('Requests Payment Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Date Payment'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
        <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
	<?php foreach ($requestsDetail['Request'] as $request): ?>
		<tr>
			<td><?php echo $request['id']; ?></td>
			<td><?php echo $request['identification']; ?></td>
			<td><?php echo $request['shop_commerce_id']; ?></td>
			<td><?php echo $request['code']; ?></td>
			<td><?php echo $request['value']; ?></td>
			<td><?php echo $request['state']; ?></td>
			<td><?php echo $request['state_request_payment']; ?></td>
			<td><?php echo $request['requests_detail_id']; ?></td>
			<td><?php echo $request['requests_payment_id']; ?></td>
			<td><?php echo $request['user_id']; ?></td>
			<td><?php echo $request['date_payment']; ?></td>
			<td><?php echo $request['created']; ?></td>
			<td><?php echo $request['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'requests', 'action' => 'view', $request['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'requests', 'action' => 'edit', $request['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'requests', 'action' => 'delete', $request['id']), array('confirm' => __('Are you sure you want to delete # %s?', $request['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Request'), array('controller' => 'requests', 'action' => 'add')); ?> </li>
        </ul>
    </div>
</div>
