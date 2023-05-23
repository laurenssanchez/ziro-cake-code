<?php echo $this->Form->create('CreditsRequest', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left','id'=>"approveRequestForm","url" =>["controller"=>"credits_requests","action"=>"reject"])); ?>

	<?php echo $this->Form->input("id",["type"=>"hidden","value"=>$request_id]) ?>

	<div class="form-group mb-2">
		<?php echo $this->Form->input("value_approve",["label"=>"¿Cuánto dinero le aprobaron?","class"=>"form-control","required","placeholder" => "Escribe el monto aprobado", "min" => 100000, "max" => 10000000, "value" => $request["CreditsRequest"]["request_value"],"step" => 100, "required",  ]) ?>
    </div>
	<div class="form-group mb-2">
		<?php echo $this->Form->input("type_request",["disabled", "type"=>"hidden","label"=>"¿Que frecuencia de pago tendrá?","class"=>"form-control","required","options" =>["1" => "Mensual"], "value" => $request["CreditsRequest"]["request_type"], "required",  ]) ?>
    </div>
    <div class="form-group">
    	<?php $numMax = $request["CreditsRequest"]["shop_commerce_id"] == Configure::read("COMMERCE") ? 8 : 4 ?>
		<?php echo $this->Form->input("number_approve",["disabled", "type"=>"hidden","label"=>"¿A cuántas cuotas?","class"=>"form-control","required","placeholder" => "Escribe el número de cuotas", "min" => $request["CreditsRequest"]["request_type"], "max" => $request["CreditsRequest"]["request_type"]*$numMax, "step" => $request["CreditsRequest"]["request_type"], "value" => $request["CreditsRequest"]["request_number"],"required", "data-id" => $this->Utilidades->encrypt($request["CreditsRequest"]["shop_commerce_id"])  ]) ?>
    </div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary pull-right mt-3">Aprobar</button>
	</div>

<?php echo $this->Form->end(); ?>
