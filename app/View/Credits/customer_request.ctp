<?php echo $this->Form->create('Customer', array('role' => 'form','data-parsley-validate=""',"type"=>"file")); ?>

<div class="page-title">
  <div class="row">
    <div class="col-md-6">
      <h3><?php echo __('Formulario de Registro de Proveedores'); ?></h3>
    </div>
    <div class="col-md-6 text-right">
      <a class="btn btn-secondary"  href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index"]) ?>">
              <i class="glyphicon glyphicon-th-list"></i>
              <?php echo __('Ver solicitudes realizadas'); ?>
            </a>
    </div>

  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_content" id="registercomerce">
           <div class="row">
              <div class="col-md-12 bg-white pd-registerblock">
                <div class="row">
                    <div class="col-md-5 pt-2">
                        <div class="content-tittles">
                          <div class="line-tittles">|</div>
                          <div>
                            <h1>INFORMACIÓN</h1>
                            <h2>DE IDENTIFICACIÓN</h2>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-7 pt-2">
                        <div class="form-group">
                          <?php echo $this->Form->input('identification_type', array('class' => 'form-control','label'=>'Tipo de identificación','div'=>false,"options" => Configure::read("Identification_TYPE"),'required' => true)); ?>
                          <?php echo $this->Form->input('empresa_id', array('class' => 'form-control','label'=>'Código del proveedor','div'=>false,"type"=>"hidden","value"=>AuthComponent::user("empresa_id"))); ?>
                          <?php echo $this->Form->input('type', array('class' => 'form-control','label'=>'Código del proveedor','div'=>false,"type"=>"hidden","value"=>0)); ?>
                        </div>
                        <div class="form-group">
                          <?php echo $this->Form->input('identification', array('class' => 'form-control','label'=>'Número de identificación','div'=>false,'placeholder'=>'Número de identificación','data-parsley-type'=>"number","data-parsley-minlength"=>"5",'required' => true)); ?>
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
                          <h1>INFORMACIÓN</h1>
                          <h2>PERSONAL</h2>
                        </div>
                      </div>
                  </div>
                    <div class="col-md-7 pt-3">
                         <div class="form-group">
                            <?php echo $this->Form->input('name', array('class' => 'form-control','label'=>'Nombre Completo','div'=>false,'placeholder'=>'Nombre completo como en la cédula','required' => true)); ?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('last_name', array('class' => 'form-control','label'=>'Apellidos','div'=>false,'placeholder'=>'Apellidos como están en la cédula','required' => true)); ?>
                        </div>
                        <div class="form-group">
                          <label for="">Fecha de nacimiento</label>
                          <?php echo $this->Form->text('date_birth', array('class' => 'form-control','label'=>'la fecha de nacimiento','div'=>false,"type" => "date",'required' => true)); ?>
                        </div>
                        <div class="form-group">
                          <?php echo $this->Form->input('city_birth', array('class' => 'form-control','label'=>'Lugar de Nacimiento','div'=>false,'placeholder'=>'Lugar de Nacimiento','required' => true,"options"=>Configure::read("CIUDADES"))); ?>
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
                            <h1>INFORMACIÓN</h1>
                            <h2>LABORAL</h2>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-7 pt-3">
                        <div class="form-group">
                          <?php echo $this->Form->input('profession', array('class' => 'form-control','label'=>'Profesión','div'=>false,'placeholder'=>'Escibre la profesión actual','required' => true,"type"=>"text")); ?>
                        </div>

                        <div class="form-group">
                          <?php echo $this->Form->input('occupation', array('class' => 'form-control','label'=>'Ocupación','div'=>false,'placeholder'=>'Selecciona una ocupación...','required' => true,"empty"=>"Selecciona ocupación...","options" => array_combine(Configure::read("OCUPACIONES"), Configure::read("OCUPACIONES")))); ?>
                        </div>

                      <div class="form-group">
                         <?php echo $this->Form->input('monthly_income', array('class' => 'form-control','label'=>'Ingresos mensuales','div'=>false,'placeholder'=>'¿Cuánto dinero ganas al mes?','required' => true,"data-parsley-validate" => "number","min"=>0,"max"=>1000000000)); ?>
                      </div>
                      <div class="form-group">
                         <?php echo $this->Form->input('type_contract', array('class' => 'form-control','label'=>'Tipo de contrato','div'=>false,'required' => true,"empty"=>"Selecciona profesión...","options" => Configure::read("TIPO_DE_CONTRATO") )); ?>
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
                            <h1>MEDIOS</h1>
                            <h2>DE COMUNICACIÓN</h2>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-7 pt-3">
                        <div class="form-group">
                          <?php echo $this->Form->input('CustomersPhone.1.phone_number', array('class' => 'form-control border-input','label'=>'Número de celular','div'=>false,"placeholder"=>"Ingresa el número de celular","required","value" => "",'required' => true )); ?>
                          <?php echo $this->Form->input('CustomersPhone.1.phone_type', array('class' => 'form-control border-input',"value" => "1","type"=>"hidden",'required' => true)); ?>
                        </div>
                        <div class="form-group">
                          <?php echo $this->Form->input('email', array('class' => 'form-control','label'=>'Correo Electrónico','div'=>false,'placeholder'=>'Escribe el correo Electrónico','required' => false)); ?>
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
                            <h1>UBICACIÓN</h1>
                            <h2>DEL CLIENTE</h2>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-7 pt-3">
                      <div class="form-group">
                        <?php echo $this->Form->input('CustomersAddress.address', array('class' => 'form-control border-input','label'=>'Dirección personal o laboral','div'=>false,'placeholder'=>"Como aparece en la cuenta de servicios","required", "value" =>  "",'required' => true )); ?>
                      </div>
                      <div class="form-group">
                        <?php echo $this->Form->input('CustomersAddress.address_city', array('class' => 'form-control border-input','label'=>'Ciudad de residencia','div'=>false,'placeholder'=>"Ciudad","required", "value" => "",'required' => true,"options"=>Configure::read("CIUDADES") )); ?>
                      </div>
                      <div class="form-group">
                        <?php echo $this->Form->input('CustomersAddress.address_street', array('class' => 'form-control border-input','label'=>'Barrio de residencia','div'=>false,'placeholder'=>"Barrio","required", "value" => "",'required' => true )); ?>

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
                      <?php echo $this->Form->input('tyc', array('class' => 'form-check-input','label'=>false,'div'=>false,"required" => true,"type"=>"checkbox","value"=>1,"type"=>"hidden")); ?>
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
                        <div class="form-group">
                          <?php echo $this->Form->input('CustomersReference.3.name', array('class' => 'form-control border-input','label'=>'Nombre','div'=>false,'placeholder'=>"Ingresa el nombre completo","required", "value" => "",'required' => true)); ?>
                        </div>
                        <div class="form-group">
                          <?php echo $this->Form->input('CustomersReference.3.relationship', array('class' => 'form-control border-input','label'=>'Parentesco','div'=>false,"required","placeholder" => "Escribe el parentesco", "value" => "",'required' => true )); ?>
                        </div>
                        <div class="form-group">
                          <?php echo $this->Form->input('CustomersReference.3.phone', array('class' => 'form-control border-input','label'=>'Número de contacto','div'=>false,"required",'placeholder'=>"Ingresa su número de Celular o Telefónico","type" => "number", "value" => "",'required' => true )); ?>

                          <?php echo $this->Form->input('CustomersReference.3.reference_type', array('class' => 'form-control border-input',"value"=>"3","type"=>"hidden" )); ?>
                        </div>
                    </div>
                </div>
              </div>


              <div class="col-md-12">
                <hr>
              </div>

              <div class="col-md-12 bg-white pd-registerblock">
                <div class="row">
                    <div class="col-md-5">
                        <div class="content-tittles">
                          <div class="line-tittles">|</div>
                          <div>
                            <h1>Documentos </h1>
                            <h2>adicionales</h2>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                          <?php echo $this->Form->input('Document.1.file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"label"=>"Selecciona el documento adicional 1","data-parsley-fileall" => 1,"type" => "file","required"=>false)); ?>
                        </div>
                        <div class="form-group">
                          <?php echo $this->Form->input('Document.2.file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"label"=>"Selecciona el documento adicional 2","data-parsley-fileall" => 1,"type" => "file","required"=>false)); ?>
                        </div>
                        <div class="form-group">
                          <?php echo $this->Form->input('Document.3.file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"label"=>"Selecciona el documento adicional 3","data-parsley-fileall" => 1,"type" => "file","required"=>false)); ?>
                        </div>
                        <div class="form-group">
                          <?php echo $this->Form->input('Document.4.file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"label"=>"Selecciona el documento adicional 4","data-parsley-fileall" => 1,"type" => "file","required"=>false)); ?>
                        </div>
                    </div>
                </div>
              </div>


              <div class="col-md-12 bg-white text-center pd-registerblock mb-5 pb-5">
                <div class="pb-3">
                    <input type="submit" class="btn btn-primary btn-lg mt-2" value="Enviar solicitud de crédito">
                </div>
              </div>

           </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
  .nav-md .container.body .right_col {
    overflow: hidden !important;
  }
</style>

<?php $this->start("AppScript") ?>

<script type="text/javascript">

  $("#CustomerCustomerRequestForm").parsley();

</script>

<?php $this->end() ?>
