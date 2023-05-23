<?php echo $this->Form->create('CreditsRequest', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left','id'=>"rejectRequestForm","url" =>["controller"=>"credits_requests","action"=>"reject"])); ?>

	<?php echo $this->Form->input("id",["type"=>"hidden","value"=>$request_id]) ?>
	
	<div class="form-group">
		<?php echo $this->Form->input("reason_reject",["label"=>"¿Porqué rechazas esta solicitud?","class"=>"form-control","options"=>Configure::read("REJECT_REASONS"),"empty" => "Seleccionar","required"]) ?>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-danger pull-right mt-3">Rechazar</button>
	</div>

<?php echo $this->Form->end(); ?>