<?php echo $this->Form->create('Commitment', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>

<div class='item form-group'>
	
			<?php echo $this->Form->input('credits_plan_id', array('type' => 'hidden','label'=>false,'div'=>false,"value" => $id)); ?>


			<?php echo $this->Form->input('user_id', array('type' => 'hidden','label'=>false,'div'=>false,"value"=>AuthComponent::user("id"))); ?>
			<?php echo $this->Form->input('type', array('type' => 'hidden','label'=>false,'div'=>false,"value"=> AuthComponent::user("role") == 11 ? 1 : 0 )); ?>
	</div>
	<div class='item form-group'>
			<?php echo $this->Form->label('Commitment.commitment',__('Compromiso'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
			<div class='col-md-6 col-sm-6'>
			<?php echo $this->Form->input('commitment', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
			</div>
	</div>
	<div class='item form-group'>
			<?php echo $this->Form->label('Commitment.deadline',__('Fecha límite'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
			<div class='col-md-6 col-sm-6'>
			<?php echo $this->Form->text('deadline', array('class' => 'form-control border-input','label'=>false,'div'=>false, "min" => date("Y-m-d"),"type"=>"date")); ?>
			</div>
	</div>
		<div class="ln_solid"></div>
		<div class="item form-group">
			<div class="col-md-6 col-sm-6 offset-md-4">
				<button type="submit" class="btn btn-success">Guardar</button>
			</div>								
		</div>
<?php echo $this->Form->end(); ?>