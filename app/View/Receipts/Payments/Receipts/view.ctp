<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('Receipt'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($receipt['Receipt']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Adicionar'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($receipt['Receipt']['id']))); ?>" class="btn btn-danger btn-sm changeState">
                <?php if($receipt['Receipt']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
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
											<?php echo h($receipt['Receipt']['value']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Creditsplan'); ?></td>
										<td>
											<?php echo $this->Html->link($receipt['CreditsPlan']['id'], array('controller' => 'credits_plans', 'action' => 'view', $receipt['CreditsPlan']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('User'); ?></td>
										<td>
											<?php echo $this->Html->link($receipt['User']['name'], array('controller' => 'users', 'action' => 'view', $receipt['User']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Shopcommerce'); ?></td>
										<td>
											<?php echo $this->Html->link($receipt['ShopCommerce']['name'], array('controller' => 'shop_commerces', 'action' => 'view', $receipt['ShopCommerce']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('State'); ?></td>
								<td> <?php echo $receipt['Receipt']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									</tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="related">
    <h3><?php echo __('Related Payments'); ?></h3>
    <?php if (!empty($receipt['Payment'])): ?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Credits Plan Id'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Shop Commerce Id'); ?></th>
		<th><?php echo __('Shop Payment Request Id'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Juridic'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('State Credishop'); ?></th>
		<th><?php echo __('Date Credishop'); ?></th>
		<th><?php echo __('Receipt Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
        <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
	<?php foreach ($receipt['Payment'] as $payment): ?>
		<tr>
			<td><?php echo $payment['id']; ?></td>
			<td><?php echo $payment['credits_plan_id']; ?></td>
			<td><?php echo $payment['value']; ?></td>
			<td><?php echo $payment['user_id']; ?></td>
			<td><?php echo $payment['shop_commerce_id']; ?></td>
			<td><?php echo $payment['shop_payment_request_id']; ?></td>
			<td><?php echo $payment['type']; ?></td>
			<td><?php echo $payment['juridic']; ?></td>
			<td><?php echo $payment['state']; ?></td>
			<td><?php echo $payment['state_credishop']; ?></td>
			<td><?php echo $payment['date_credishop']; ?></td>
			<td><?php echo $payment['receipt_id']; ?></td>
			<td><?php echo $payment['created']; ?></td>
			<td><?php echo $payment['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'payments', 'action' => 'view', $payment['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'payments', 'action' => 'edit', $payment['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'payments', 'action' => 'delete', $payment['id']), array('confirm' => __('Are you sure you want to delete # %s?', $payment['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Payment'), array('controller' => 'payments', 'action' => 'add')); ?> </li>
        </ul>
    </div>
</div>
