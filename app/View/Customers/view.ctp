<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('Customer'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($customer['Customer']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Adicionar'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($customer['Customer']['id']))); ?>" class="btn btn-danger btn-sm changeState">
                <?php if($customer['Customer']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
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
											<?php echo h($customer['Customer']['name']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Identification type'); ?></td>
										<td>
											<?php echo h($customer['Customer']['identification_type']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Identification'); ?></td>
										<td>
											<?php echo h($customer['Customer']['identification']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Email'); ?></td>
										<td>
											<?php echo h($customer['Customer']['email']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Document file up'); ?></td>
										<td>
											<?php echo h($customer['Customer']['document_file_up']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Document file down'); ?></td>
										<td>
											<?php echo h($customer['Customer']['document_file_down']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Image file'); ?></td>
										<td>
											<?php echo h($customer['Customer']['image_file']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Tyc'); ?></td>
										<td>
											<?php echo h($customer['Customer']['tyc']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Gender'); ?></td>
										<td>
											<?php echo h($customer['Customer']['gender']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Date birth'); ?></td>
										<td>
											<?php echo h($customer['Customer']['date_birth']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('City birth'); ?></td>
										<td>
											<?php echo h($customer['Customer']['city_birth']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Identification date'); ?></td>
										<td>
											<?php echo h($customer['Customer']['identification_date']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Occupation'); ?></td>
										<td>
											<?php echo h($customer['Customer']['occupation']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Monthly income'); ?></td>
										<td>
											<?php echo h($customer['Customer']['monthly_income']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Politics'); ?></td>
										<td>
											<?php echo h($customer['Customer']['politics']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('State'); ?></td>
								<td> <?php echo $customer['Customer']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									</tr>

									<tr>
										<td><?php echo __('Data full'); ?></td>
										<td>
											<?php echo h($customer['Customer']['data_full']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('User'); ?></td>
										<td>
											<?php echo $this->Html->link($customer['User']['name'], array('controller' => 'users', 'action' => 'view', $customer['User']['id']), array('class' => '')); ?>&nbsp;
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
    <h3><?php echo __('Related Credits'); ?></h3>
    <?php if (!empty($customer['Credit'])): ?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Value Request'); ?></th>
		<th><?php echo __('Value Aprooved'); ?></th>
		<th><?php echo __('Type Payment'); ?></th>
		<th><?php echo __('Deadlines'); ?></th>
		<th><?php echo __('Credits Line Id'); ?></th>
		<th><?php echo __('Interes Rate'); ?></th>
		<th><?php echo __('Others Rate'); ?></th>
		<th><?php echo __('Debt Rate'); ?></th>
		<th><?php echo __('Quota Value'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('Customer Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Value Pending'); ?></th>
        <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
	<?php foreach ($customer['Credit'] as $credit): ?>
		<tr>
			<td><?php echo $credit['id']; ?></td>
			<td><?php echo $credit['value_request']; ?></td>
			<td><?php echo $credit['value_aprooved']; ?></td>
			<td><?php echo $credit['type_payment']; ?></td>
			<td><?php echo $credit['deadlines']; ?></td>
			<td><?php echo $credit['credits_line_id']; ?></td>
			<td><?php echo $credit['interes_rate']; ?></td>
			<td><?php echo $credit['others_rate']; ?></td>
			<td><?php echo $credit['debt_rate']; ?></td>
			<td><?php echo $credit['quota_value']; ?></td>
			<td><?php echo $credit['state']; ?></td>
			<td><?php echo $credit['customer_id']; ?></td>
			<td><?php echo $credit['created']; ?></td>
			<td><?php echo $credit['modified']; ?></td>
			<td><?php echo $credit['value_pending']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'credits', 'action' => 'view', $credit['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'credits', 'action' => 'edit', $credit['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'credits', 'action' => 'delete', $credit['id']), array('confirm' => __('Are you sure you want to delete # %s?', $credit['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Credit'), array('controller' => 'credits', 'action' => 'add')); ?> </li>
        </ul>
    </div>
</div>
