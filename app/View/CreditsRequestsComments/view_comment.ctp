<?php if (in_array(AuthComponent::user("role"),[2,3]) && empty($rqData["CreditsRequest"]["empresa_id"])): ?>
<div class="row">
  <div class="col-md-12">
    <h2><b>¿Qué sucede con esta solicitud?</b></h2>
  </div>        
</div>       
   
  <?php echo $this->Form->create('CreditsRequestsComment', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>
  <div class="form-group">
    <?php echo $this->Form->input('type', array('class' => 'form-control border-input','label'=>'Selecciona un motivo','div'=>false,"options"=>Configure::read("TYPE_COMMENT"))); ?>
  </div>
  <div class="form-group">
    <?php echo $this->Form->input('comment', array('class' => 'form-control border-input','label'=>'Comentarios','div'=>false,"placeholder"=>"Describe tu observación para esta solicitud","rows" => 3)); ?>
    <?php echo $this->Form->input('credits_request_id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type" => "hidden","value" => $request)); ?>
    <?php echo $this->Form->input('user_id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type" => "hidden","value" => AuthComponent::user("id"))); ?>
  </div>          
  <div class="form-group mt-3">
    <input type="submit" class="btn btn-primary pull-right" value="Guardar Observaciones">
  </div>
  <?php echo $this->Form->end(); ?>
 <?php endif ?> 
<hr>
<h2><b>Observaciones <?php echo empty($rqData["CreditsRequest"]["empresa_id"]) ? "previas" : "de la resolución." ?> </b></h2>
<?php if (!empty($allComments)): ?>
  <?php foreach ($allComments as $key => $value): ?>
    <div class="card comments-list mb-3">
      <div class="card-header">
        <?php echo $value["CreditsRequestsComment"]["type"] ?> <span class="pull-right">
          <?php echo date("d-m-Y h:i A",strtotime($value['CreditsRequestsComment']['created'])); ?>
            
          </span>
      </div>
      <div class="card-body">
        <p class="card-text">
          <?php echo $value["CreditsRequestsComment"]["comment"] ?>
        </p>
      </div>
  </div>
  <?php endforeach ?>
<?php else: ?>
  <h3 class="text-center mt-3">
    No hay comentarios registrados
  </h3>
<?php endif ?>
