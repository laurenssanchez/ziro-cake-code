<div class="page-title">
	<div class="title_left">
		<h3><?php echo __('Adicionar').' '.__('Credits Request'); ?></h3>
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
				<?php echo $this->Form->create('CreditsRequest', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>

													<div class='item form-group'>
								<?php echo $this->Form->label('CreditsRequest.customer_id',__('Customer'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('customer_id', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('CreditsRequest.request_value',__('Request Value'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('request_value', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('CreditsRequest.request_number',__('Request Number'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('request_number', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('CreditsRequest.credits_line_id',__('Credits Line'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('credits_line_id', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
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


