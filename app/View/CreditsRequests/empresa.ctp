<div class="row">
  <div class="col-md-12">
    <h2>
      Resolución a la solicitud de estudio de la empresa: <b> <?php echo $request["Empresa"]["social_reason"] ?> </b> 
      <br>
      Para el cliente: <b><?php echo $request["Customer"]["name"] ?> <?php echo $request["Customer"]["last_name"] ?> </b> | CC:  <b><?php echo $request["Customer"]["identification"] ?></b>
    </h2>
  </div>        
</div>       
<?php if (in_array(AuthComponent::user("role"),[2,3])): ?>
   
  <?php echo $this->Form->create('CreditsRequestsComment', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left',"type"=>"file","url"=>["action"=>"empresas","controller" => "credits_requests" ])); ?>
  <div class="form-group">
    <?php echo $this->Form->input('type', array('class' => 'form-control border-input','label'=>'Selecciona un motivo','div'=>false,"type" => "hidden","value"=>"Resolución a la solicitud")); ?>
  </div>
  <div class="form-group">
    <?php echo $this->Form->input('comment', array('class' => 'form-control border-input','label'=>'Comentarios','div'=>false,"placeholder"=>"Describe tu observación para esta solicitud","rows" => 10)); ?>
    <?php echo $this->Form->input('credits_request_id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type" => "hidden","value" => $request["CreditsRequest"]["id"])); ?>
    <?php echo $this->Form->input('CreditsRequest.id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type" => "hidden","value" => $request["CreditsRequest"]["id"])); ?>
    <?php echo $this->Form->input('user_id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type" => "hidden","value" => AuthComponent::user("id"))); ?>
  </div>          
  <div class="row">
    <div class="col-md-6">
        <?php echo $this->Form->input('CreditsRequest.state', array('class' => 'form-control border-input','label'=>"Estado final de la solicitud",'div'=>false,"options"=>["3"=>"Aprobarle cupo","4" => "Negarle la solicitud"])); ?>
    </div>
    <div class="col-md-6">
      <?php echo $this->Form->input('CreditsRequest.value_approve', array('class' => 'form-control border-input','label'=>"Valor recomendado",'div'=>false,"min" => 0,"value"=>0)); ?>
    </div>
    <div class="col-md-6">
      <?php echo $this->Form->input('Document.1.file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"label"=>"Selecciona el documento 1 adional a tener encuenta en solicitud de crédito","data-parsley-fileall" => 1,"type" => "file","required"=>false)); ?>
    </div>
    <div class="col-md-6">
      <?php echo $this->Form->input('Document.2.file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"label"=>"Selecciona el documento 2 adional a tener encuenta en solicitud de crédito","data-parsley-fileall" => 1,"type" => "file","required"=>false)); ?>
    </div>
    <div class="col-md-6">
      <?php echo $this->Form->input('Document.3.file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"label"=>"Selecciona el documento 3 adional a tener encuenta en solicitud de crédito","data-parsley-fileall" => 1,"type" => "file","required"=>false)); ?>
    </div>
    <div class="col-md-6">
      <?php echo $this->Form->input('Document.4.file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"label"=>"Selecciona el documento 4 adional a tener encuenta en solicitud de crédito","data-parsley-fileall" => 1,"type" => "file","required"=>false)); ?>
    </div>
  </div>
  <div class="form-group mt-3">
    <input type="submit" class="btn btn-primary pull-right" value="Enviar Resolución">
  </div>
  <?php echo $this->Form->end(); ?>
 <?php endif ?> 
<hr>