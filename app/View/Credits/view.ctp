<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('Credit'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($credit['Credit']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Adicionar'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($credit['Credit']['id']))); ?>" class="btn btn-danger btn-sm changeState">
                <?php if($credit['Credit']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
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
										<td><?php echo __('Value request'); ?></td>
										<td>
											<?php echo h($credit['Credit']['value_request']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Value aprooved'); ?></td>
										<td>
											<?php echo h($credit['Credit']['value_aprooved']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Number fee'); ?></td>
										<td>
											<?php echo h($credit['Credit']['number_fee']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Creditsline'); ?></td>
										<td>
											<?php echo $this->Html->link($credit['CreditsLine']['name'], array('controller' => 'credits_lines', 'action' => 'view', $credit['CreditsLine']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Interes rate'); ?></td>
										<td>
											<?php echo h($credit['Credit']['interes_rate']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Others rate'); ?></td>
										<td>
											<?php echo h($credit['Credit']['others_rate']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Debt rate'); ?></td>
										<td>
											<?php echo h($credit['Credit']['debt_rate']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Quota value'); ?></td>
										<td>
											<?php echo h($credit['Credit']['quota_value']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Value pending'); ?></td>
										<td>
											<?php echo h($credit['Credit']['value_pending']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Deadline'); ?></td>
										<td>
											<?php echo h($credit['Credit']['deadline']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('State'); ?></td>
								<td> <?php echo $credit['Credit']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									</tr>

									<tr>
										<td><?php echo __('Customer'); ?></td>
										<td>
											<?php echo $this->Html->link($credit['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $credit['Customer']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Credits request id'); ?></td>
										<td>
											<?php echo h($credit['Credit']['credits_request_id']); ?>&nbsp;
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
    <h3><?php echo __('Related Shops Debts'); ?></h3>
    <?php if (!empty($credit['ShopsDebt'])): ?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Shop Id'); ?></th>
		<th><?php echo __('Credit Id'); ?></th>
		<th><?php echo __('Credit Payments Shop'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th><?php echo __('Reason'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
        <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
	<?php foreach ($credit['ShopsDebt'] as $shopsDebt): ?>
		<tr>
			<td><?php echo $shopsDebt['id']; ?></td>
			<td><?php echo $shopsDebt['user_id']; ?></td>
			<td><?php echo $shopsDebt['shop_id']; ?></td>
			<td><?php echo $shopsDebt['credit_id']; ?></td>
			<td><?php echo $shopsDebt['credit_payments_shop']; ?></td>
			<td><?php echo $shopsDebt['value']; ?></td>
			<td><?php echo $shopsDebt['reason']; ?></td>
			<td><?php echo $shopsDebt['state']; ?></td>
			<td><?php echo $shopsDebt['created']; ?></td>
			<td><?php echo $shopsDebt['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'shops_debts', 'action' => 'view', $shopsDebt['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'shops_debts', 'action' => 'edit', $shopsDebt['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'shops_debts', 'action' => 'delete', $shopsDebt['id']), array('confirm' => __('Are you sure you want to delete # %s?', $shopsDebt['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Shops Debt'), array('controller' => 'shops_debts', 'action' => 'add')); ?> </li>
        </ul>
    </div>
</div>
<div class="related">
    <h3><?php echo __('Related Credits Plans'); ?></h3>
    <?php if (!empty($credit['CreditsPlan'])): ?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Credit Id'); ?></th>
		<th><?php echo __('Capital Value'); ?></th>
		<th><?php echo __('Interest Value'); ?></th>
		<th><?php echo __('Others Value'); ?></th>
		<th><?php echo __('Deadline'); ?></th>
		<th><?php echo __('Value Pending'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
        <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
	<?php foreach ($credit['CreditsPlan'] as $creditsPlan): ?>
		<tr>
			<td><?php echo $creditsPlan['id']; ?></td>
			<td><?php echo $creditsPlan['credit_id']; ?></td>
			<td><?php echo $creditsPlan['capital_value']; ?></td>
			<td><?php echo $creditsPlan['interest_value']; ?></td>
			<td><?php echo $creditsPlan['others_value']; ?></td>
			<td><?php echo $creditsPlan['deadline']; ?></td>
			<td><?php echo $creditsPlan['value_pending']; ?></td>
			<td><?php echo $creditsPlan['state']; ?></td>
			<td><?php echo $creditsPlan['created']; ?></td>
			<td><?php echo $creditsPlan['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'credits_plans', 'action' => 'view', $creditsPlan['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'credits_plans', 'action' => 'edit', $creditsPlan['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'credits_plans', 'action' => 'delete', $creditsPlan['id']), array('confirm' => __('Are you sure you want to delete # %s?', $creditsPlan['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Credits Plan'), array('controller' => 'credits_plans', 'action' => 'add')); ?> </li>
        </ul>
    </div>
</div>
