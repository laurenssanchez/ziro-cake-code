<div class="page-title">
	<div class="title_left">
		<h3><?php echo __('Adicionar').' '.__('Credits Plan'); ?></h3>
	</div>

	<div class="title_right">
		<div class="col-md-5 col-sm-5  form-group pull-right top_search">
			<a class="btn btn-sm btn-default shiny green"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
            <i class="glyphicon glyphicon-th-list"></i>
            <?php echo __('Listar'); ?>        </a>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
				<br />
				<?php echo $this->Form->create('CreditsPlan', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>

													<div class='item form-group'>
								<?php echo $this->Form->label('CreditsPlan.credit_id',__('Credit'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('credit_id', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('CreditsPlan.capital_value',__('Capital Value'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('capital_value', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('CreditsPlan.interest_value',__('Interest Value'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('interest_value', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('CreditsPlan.others_value',__('Others Value'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('others_value', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('CreditsPlan.deadline',__('Deadline'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('deadline', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('CreditsPlan.value_pending',__('Value Pending'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('value_pending', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
							<div class="ln_solid"></div>
							<div class="item form-group">
								<div class="col-md-6 col-sm-6 offset-md-4">
									<button type="submit" class="btn btn-success">Guardar</button>
								</div>								
							</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>



