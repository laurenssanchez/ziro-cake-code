<?php echo $this->element("/menu-landings"); ?>  

<div class="container-fluid p-0 bg-img-blue">
  <div class="control-absolute">
  <div class="content-steps container">

    <div class="relative">
        <div class="circle-content circle-content-1">
          <h2>1</h2>
        </div>
        <div class="steps step1">
          <p>Capturas</p>
        </div>
    </div>

    <div class="relative">
        <div class="circle-content circle-content-2">
          <h2>2</h2>
        </div>
        <div class="steps step2">
          <p>Personal</p>
        </div>
    </div>

    <div class="relative">
        <div class="circle-content circle-content-3 active">
          <h2>3</h2>
        </div>
        <div class="steps step3 active">
          <p>Contactos</p>
        </div>
    </div>

  </div>
</div>

  <div class="container pb-3">
     <div class="row">
      <?php echo $this->Form->create('Customer', array('role' => 'form','data-parsley-validate=""',"class" => "w-100")); ?>
        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>  
                      <h1>TUS MEDIOS</h1>
                      <h2>DE COMUNICACIÓN</h2>
                    </div>
                  </div>                
              </div>
              <div class="col-md-7 pt-3">
                <p>En este número de celular recibirás tus código de seguridad</p>
					        <div class="form-group">
                    <?php if (isset($customer) && !empty($customer["CustomersPhone"])): ?>
                      <?php echo $this->Form->input('CustomersPhone.1.id', array('class' => 'form-control border-input',"value" => $customer["CustomersPhone"][0]["id"],"type"=>"hidden")); ?>
                    <?php endif ?>
                    <?php echo $this->Form->input('CustomersPhone.1.phone_number', array('class' => 'form-control border-input','label'=>'Número de celular','div'=>false,"placeholder"=>"Ingresa tu número de celular","required","value" => isset($customer) && !empty($customer["CustomersPhone"]) ? $customer["CustomersPhone"][0]["phone_number"] : "" )); ?>
                    <?php echo $this->Form->input('CustomersPhone.1.phone_type', array('class' => 'form-control border-input',"value" => isset($customer) && !empty($customer["CustomersPhone"]) ? $customer["CustomersPhone"][0]["phone_type"] : "1","type"=>"hidden")); ?>
	                </div>
	                <div>
	                	<p class="othernum">Quiero agregar otro número de celular (opcional)</p>
						        <div class="form-group num2 controloff">
                      <?php if ( isset($customer) && count($customer["CustomersPhone"]) > 1): ?>
                        <?php echo $this->Form->input('CustomersPhone.2.id', array('class' => 'form-control border-input',"value" => $customer["CustomersPhone"][1]["id"],"type"=>"hidden")); ?>
                      <?php endif ?>
                        <?php echo $this->Form->input('CustomersPhone.2.phone_number', array('class' => 'form-control border-input','label'=>'Número de celular','div'=>false,"placeholder"=>"Ingresa tu número de celular","required" => false, "value" => isset($customer) && count($customer["CustomersPhone"]) > 1 ? $customer["CustomersPhone"][1]["phone_number"] : "" )); ?>
                        <?php echo $this->Form->input('CustomersPhone.2.phone_type', array('class' => 'form-control border-input',"value" => isset($customer) && count($customer["CustomersPhone"]) > 1 ? $customer["CustomersPhone"][1]["phone_type"] : "1","type"=>"hidden")); ?>
		                </div>	                	
	                </div>
              			
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>  
                      <h1>TUS</h1>
                      <h2>DIRECCIONES</h2>
                    </div>
                  </div>                
              </div>
              <div class="col-md-7 pt-3">
                <?php if (isset($customer) && !empty($customer["CustomersAddress"]) ): ?>
                  <?php echo $this->Form->input('CustomersAddress.id', array('class' => 'form-control border-input','label'=>false,'div'=>false,'placeholder'=>"Como aparece en tu cuenta de servicios","required", "value" => $customer["CustomersAddress"]["0"]["id"],"type" => "hidden" )); ?>
                <?php endif ?>
      				  <div class="form-group">
                  <?php echo $this->Form->input('CustomersAddress.address', array('class' => 'form-control border-input','label'=>'Tu dirección personal o laboral','div'=>false,'placeholder'=>"Como aparece en tu cuenta de servicios","required", "value" => isset($customer) && !empty($customer["CustomersAddress"]) ? $customer["CustomersAddress"]["0"]["address"] : "" )); ?>
      				  </div>                  
	              <div class="form-group">
                  <?php echo $this->Form->input('CustomersAddress.address_city', array('class' => 'form-control border-input','label'=>'Ciudad de residencia','div'=>false,'placeholder'=>"Ciudad","required", "value" => isset($customer) && !empty($customer["CustomersAddress"]) ? $customer["CustomersAddress"]["0"]["address_city"] : "", "options" => Configure::read("CIUDADES"),"default" => "MEDELLIN" )); ?>
	              </div>
                <div class="form-group">
                  <?php echo $this->Form->input('CustomersAddress.address_street', array('class' => 'form-control border-input','label'=>'Barrio de residencia','div'=>false,'placeholder'=>"Barrio","required", "value" => isset($customer) && !empty($customer["CustomersAddress"]) ? $customer["CustomersAddress"]["0"]["address_street"] : "" )); ?>

                  <?php echo $this->Form->input('CustomersAddress.address_type', array('class' => 'form-control border-input','label'=>'Ciudad','div'=>false,"required", "value" => 1,"type"=>"hidden" )); ?>
                </div>  		         
              </div>
          </div>
        </div>


        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>  
                      <h1>REFERENCIA</h1>
                      <h2>PERSONAL O FAMILIAR 1</h2>
                    </div>
                  </div>                
              </div>
              <div class="col-md-7 pt-3">
                  <?php if (isset($customer) && !empty($customer["CustomersReference"])): ?>
                    <?php echo $this->Form->input('CustomersReference.1.id', array('class' => 'form-control border-input',"value"=>$customer["CustomersReference"][0]["id"],"type"=>"hidden" )); ?>
                  <?php endif ?>
        				  <div class="form-group">
                    <?php echo $this->Form->input('CustomersReference.1.name', array('class' => 'form-control border-input','label'=>'Nombre','div'=>false,'placeholder'=>"Ingresa el nombre completo","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["name"] : "" )); ?>
        				  </div>                  
        				  <div class="form-group">
                    <?php echo $this->Form->input('CustomersReference.1.relationship', array('class' => 'form-control border-input','label'=>'Parentesco','div'=>false,'placeholder'=>"Escribe el parentesco","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["relationship"] : "" )); ?>
        				  </div>    
        				  <div class="form-group">
                    <?php echo $this->Form->input('CustomersReference.1.phone', array('class' => 'form-control border-input','label'=>'Número de contacto','div'=>false,'placeholder'=>"Ingresa su número de Celular o Telefónico","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["phone"] : "" )); ?>
                    <?php echo $this->Form->input('CustomersReference.1.reference_type', array('class' => 'form-control border-input',"value"=>"1","type"=>"hidden" )); ?>
        				  </div>               
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>  
                      <h1>REFERENCIA</h1>
                      <h2>PERSONAL O FAMILIAR 2</h2>
                    </div>
                  </div>                
              </div>
              <div class="col-md-7 pt-3">
                  <?php if (isset($customer) && !empty($customer["CustomersReference"])): ?>
                    <?php echo $this->Form->input('CustomersReference.2.id', array('class' => 'form-control border-input',"value"=>$customer["CustomersReference"][1]["id"],"type"=>"hidden" )); ?>
                  <?php endif ?>
        				  <div class="form-group">
                    <?php echo $this->Form->input('CustomersReference.2.name', array('class' => 'form-control border-input','label'=>'Nombre','div'=>false,'placeholder'=>"Ingresa el nombre completo","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][1]["name"] : "")); ?>
        				  </div>                  
                  <div class="form-group">
                    <?php echo $this->Form->input('CustomersReference.2.relationship', array('class' => 'form-control border-input','label'=>'Parentesco','div'=>false,"required",'placeholder'=>"Escribe el parentesco", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][1]["relationship"] : "" )); ?>
                  </div>  
        				  <div class="form-group">
                    <?php echo $this->Form->input('CustomersReference.2.phone', array('class' => 'form-control border-input','label'=>'Número de contacto','div'=>false,'placeholder'=>"Ingresa su número de Celular o Telefónico","required" , "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][1]["phone"] : "")); ?>
                    <?php echo $this->Form->input('CustomersReference.2.reference_type', array('class' => 'form-control border-input',"value"=>"2","type"=>"hidden")); ?>
				          </div>                
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>  
                      <h1>REFERENCIA</h1>
                      <h2>PERSONAL O FAMILIAR 3</h2>
                    </div>
                  </div>                
              </div>
              <div class="col-md-7 pt-3">
                  <?php if (isset($customer) && !empty($customer["CustomersReference"])): ?>
                    <?php echo $this->Form->input('CustomersReference.3.id', array('class' => 'form-control border-input',"value"=>$customer["CustomersReference"][2]["id"],"type"=>"hidden" )); ?>
                  <?php endif ?>
        				  <div class="form-group">
                    <?php echo $this->Form->input('CustomersReference.3.name', array('class' => 'form-control border-input','label'=>'Nombre','div'=>false,'placeholder'=>"Ingresa el nombre completo","required", "value" => isset($customer) && !empty($customer["CustomersReference"])? $customer["CustomersReference"][2]["name"] : "")); ?>
        				  </div>                  
                  <div class="form-group">
                    <?php echo $this->Form->input('CustomersReference.3.relationship', array('class' => 'form-control border-input','label'=>'Parentesco','div'=>false,"required","placeholder" => "Escribe el parentesco", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][2]["relationship"] : "" )); ?>
                  </div>                 
        				  <div class="form-group">
                    <?php echo $this->Form->input('CustomersReference.3.phone', array('class' => 'form-control border-input','label'=>'Número de contacto','div'=>false,"required",'placeholder'=>"Ingresa su número de Celular o Telefónico","type" => "number", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][2]["phone"] : "" )); ?>
                    <?php echo $this->Form->input('CustomersReference.3.reference_type', array('class' => 'form-control border-input',"value"=>"3","type"=>"hidden" )); ?>
        				  </div>    				  					          
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white text-center pt-3 pb-3">
          <div class="pb-3">
              <input type="submit" class="btn btn-primary btn-lg mt-2" value="Guardar y Finalizar Registro">
          </div>
        </div>

     </div>
   </form>
  </div>
</div>

<?php 
  
   echo $this->Html->script("ctrl/customers/forms.js?".rand(),             array('block' => 'AppScript'));

 ?>