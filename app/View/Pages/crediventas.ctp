<?php echo $this->element("/menu-landings"); ?>

<div class="container-fluid p-0 bg-img-blue formcrediventas">
  <div class="container namelinecredit">
      <h2 class="upper"><b>Crédito Online crediventas</b></h2>
  </div>

      <?php echo $this->Form->create('Customer', array('role' => 'form','data-parsley-validate=""')); ?>
  <div class="container pb-3">
     <div class="row">

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>INFORMACIÓN DEL</h1>
                      <h2>PROVEEDOR</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-3">
                  <div class="form-group">
                    <?php echo $this->Form->input('code', array('class' => 'form-control','readonly' => 'readonly','label'=>'Código del proveedor','value'=>'73221084','div'=>false,'placeholder'=>'Código de la tienda donde solicitas el crédito','required' => true)); ?>
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
                      <h1>INFORMACIÓN DEL</h1>
                      <h2>CRÉDITO</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-3">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <input id="moneyRange" type="range" min="50000" max="850000" step="100" value="50000" class="range-borrow ">
                        <p class="valuemin">$ 50.000</p>
                        <p class="valuemax pull-right">$ 850.000</p>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Frecuencia de pago</label>
                        <select type="text" class="form-control frecuency-payments" name="frecuency" id="frecuency">
                          <option value="1">Mensual</option>
                          <option value="2">Quincenal</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Cuotas</label>
                        <select class="form-control coutas-number" name="couteValue">
                          <?php for($i = 1; $i <= 8; $i++ ):  ?>
                            <option data-mes="<?php echo $i ?>" data-quince="<?php echo $i*2 ?>" value="<?php echo $i ?>"><?php echo $i ?></option>
                          <?php endfor; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6 pb-3">
                      <div class="solicitado">
                        <p class="mb-0">Solicitado</p>
                        <input type="number" class="form-control value-actual d-none" value="0" id="valueNumberPrice" placeholder="$200.000" name="priceValue">
                        <h4>$ <input type="number" id="totalSolicita2" step="100" min="50000" ></h4>
                      </div>
                    </div>
                    <div class="col-md-6 pb-3">
                      <div class="cuota">
                        <p class="mb-0">Valor Cuota</p>
                        <h4>$ <span id="valueCalculated"></span></h4>
                      </div>
                    </div>
                  </div>


              </div>
          </div>
        </div>
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
                  </div>

                  <div class="form-group">
                    <?php echo $this->Form->input('identification', array('class' => 'form-control','label'=>'Tu número de identificación','div'=>false,'placeholder'=>'Escribe tu Número de identificación','data-parsley-type'=>"number","data-parsley-minlength"=>"5",'required' => true)); ?>
                  </div>

              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO</h1>
                      <h2>DOCUMENTO FRONTAL</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 text-center">
                 <?php echo $this->Form->input('document_file_up', array('class' => 'btn btn-outline-secondary mt-2 w-100 pd-resetbtn','label'=>false,'div'=>false,"required" => true,"type"=>"file","data-parsley-imageextension"=>"3")); ?>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO</h1>
                      <h2>DOCUMENTO RESPALDO</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 text-center">
                <?php echo $this->Form->input('document_file_down', array('class' => 'btn btn-outline-secondary mt-2 w-100 pd-resetbtn','label'=>false,'div'=>false,"required" => true,"type"=>"file","data-parsley-imageextension"=>"3")); ?>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO SELFIE CON</h1>
                      <h2>DOCUMENTO FRONTAL</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 text-center">
                  <?php echo $this->Form->input('image_file', array('class' => 'btn btn-outline-secondary mt-2 w-100 pd-resetbtn','label'=>false,'div'=>false,"required" => true,"type"=>"file","data-parsley-imageextension"=>"3")); ?>
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
              <div class="row">
                <div class="col-md-6">
                   <div class="form-group">
                    <?php echo $this->Form->input('name', array('class' => 'form-control','label'=>'Nombres','div'=>false,'placeholder'=>'Nombres','required' => true)); ?>
                  </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                      <?php echo $this->Form->input('last_name', array('class' => 'form-control','label'=>'Apellidos','div'=>false,'placeholder'=>'Apellidos','required' => true)); ?>
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label for="">Fecha de nacimiento</label>
                <?php echo $this->Form->text('date_birth', array('class' => 'form-control','label'=>'Tu fecha de nacimiento','div'=>false,"type" => "date",'required' => true)); ?>
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
                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <?php echo $this->Form->input('profession', array('class' => 'form-control','label'=>'Profesión','div'=>false,'placeholder'=>'Profesión actual','required' => true,"type"=>"text")); ?>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <?php echo $this->Form->input('occupation', array('class' => 'form-control','label'=>'Ocupación','div'=>false,'placeholder'=>'Selecciona una ocupación...','required' => true,"empty"=>"Selecciona la ocupación...","options" => array_combine(Configure::read("OCUPACIONES"), Configure::read("OCUPACIONES")))); ?>
                      </div>
                    </div>

                  <div class="col-md-6">
                    <div class="form-group">
                       <?php echo $this->Form->input('monthly_income', array('class' => 'form-control','label'=>'Ingresos mensuales','div'=>false,'placeholder'=>'Ingresos mensuales','required' => true,"data-parsley-validate" => "number","min"=>0,"max"=>1000000000)); ?>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                       <?php echo $this->Form->input('type_contract', array('class' => 'form-control','label'=>'Tipo de contrato','div'=>false,'required' => true,"empty"=>"Selecciona la profesión...","options" => Configure::read("TIPO_DE_CONTRATO") )); ?>
                    </div>
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
                      <h1>MEDIOS DE</h1>
                      <h2>COMUNICACIÓN</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-3">
                  <div class="form-group">
                    <?php echo $this->Form->input('CustomersPhone.1.phone_number', array('class' => 'form-control border-input','label'=>'Número de celular','div'=>false,"placeholder"=>"Ingresa el número de celular","required","value" => "",'required' => true )); ?>
                    <?php echo $this->Form->input('CustomersPhone.1.phone_type', array('class' => 'form-control border-input',"value" => "1","type"=>"hidden",'required' => true)); ?>
                  </div>
                  <div class="form-group">
                    <?php echo $this->Form->input('email', array('class' => 'form-control','label'=>'Tu Correo Electrónico','div'=>false,'placeholder'=>'Escribe tu correo Electrónico','required' => true)); ?>
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
                      <h1>DIRECCIONES</h1>
                      <h2>DEL CLIENTE</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-3">
                <div class="form-group">
                  <?php echo $this->Form->input('CustomersAddress.address', array('class' => 'form-control border-input','label'=>'Dirección personal o laboral','div'=>false,'placeholder'=>"Ingresa la dirección","required", "value" =>  "",'required' => true )); ?>
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
                    <?php echo $this->Form->input('CustomersReference.1.name', array('class' => 'form-control border-input','label'=>'Nombre','div'=>false,'placeholder'=>"Nombre completo","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["name"] : "" )); ?>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <?php echo $this->Form->input('CustomersReference.1.relationship', array('class' => 'form-control border-input','label'=>'Parentesco','div'=>false,'placeholder'=>"Escribe el parentesco","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["relationship"] : "" )); ?>
                      </div>
                      </div>
                      <div class="col-md-6">
                      <div class="form-group">
                        <?php echo $this->Form->input('CustomersReference.1.phone', array('class' => 'form-control border-input','label'=>'Número de contacto','div'=>false,'placeholder'=>"Número de Celular","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["phone"] : "" )); ?>
                        <?php echo $this->Form->input('CustomersReference.1.reference_type', array('class' => 'form-control border-input',"value"=>"1","type"=>"hidden" )); ?>
                      </div>
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
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <?php echo $this->Form->input('CustomersReference.2.relationship', array('class' => 'form-control border-input','label'=>'Parentesco','div'=>false,"required",'placeholder'=>"Escribe el parentesco", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][1]["relationship"] : "" )); ?>
                      </div>
                      </div>
                       <div class="col-md-6">
                      <div class="form-group">
                        <?php echo $this->Form->input('CustomersReference.2.phone', array('class' => 'form-control border-input','label'=>'Número de contacto','div'=>false,'placeholder'=>"Número de Celular","required" , "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][1]["phone"] : "")); ?>
                        <?php echo $this->Form->input('CustomersReference.2.reference_type', array('class' => 'form-control border-input',"value"=>"2","type"=>"hidden")); ?>
                      </div>
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
                      <h1>REFERENCIA</h1>
                      <h2>PERSONAL O FAMILIAR 3</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-3">
                  <div class="form-group">
                    <?php echo $this->Form->input('CustomersReference.3.name', array('class' => 'form-control border-input','label'=>'Nombre','div'=>false,'placeholder'=>"Ingresa el nombre completo","required", "value" => "",'required' => true)); ?>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <?php echo $this->Form->input('CustomersReference.3.relationship', array('class' => 'form-control border-input','label'=>'Parentesco','div'=>false,"required","placeholder" => "Escribe el parentesco", "value" => "",'required' => true )); ?>
                      </div>
                    </div>
                    <div class="col-md-6">

                      <div class="form-group">
                        <?php echo $this->Form->input('CustomersReference.3.phone', array('class' => 'form-control border-input','label'=>'Número de contacto','div'=>false,"required",'placeholder'=>"Número de Celular","type" => "number", "value" => "",'required' => true )); ?>

                        <?php echo $this->Form->input('CustomersReference.3.reference_type', array('class' => 'form-control border-input',"value"=>"3","type"=>"hidden" )); ?>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <a href="javascript::void(0)"  class="validateCodesData btn btn-block btn-warning">
                        Enviár códigos de validación
                      </a>
                    </div>
                  </div>
              </div>
          </div>
        </div>



        <div class="col-md-12 bg-white pd-registerblock d-none">
          <div class="row">
              <div class="col-md-7">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>AUTORIZACIÓN DE </h1>
                      <h2>TRATAMIENTO DE DATOS</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-5">
                    <div class="form-check p-2">
                      <?php echo $this->Form->input('tyc', array('class' => 'form-check-input','label'=>false,'div'=>false,"checked" => "checked","required" => true,"type"=>"checkbox")); ?>
                      <label class="form-check-label big-label" for="">Si, acepto la Autorización de Tratamiento de Datos</label>
                    </div>
              </div>
          </div>
        </div>
        <div class="col-md-12 bg-white pd-registerblock codesSend" style="display:none">
          <div class="row">
              <div class="col-md-5"></div>
              <div class="col-md-7">
                      <div class="form-group">
                          <?php echo $this->Form->input('code_email', array('class' => 'form-control','label'=>'Código enviado al email','div'=>false,'placeholder'=>'Escribe el código que enviamos a tu email','required' => false,'type'=>'number')); ?>
                      </div>
                        <?php echo $this->Form->input('code_email_verify', array('class' => 'form-control','label'=>'Código enviado al email','div'=>false,'required' => false,"type" => "hidden")); ?>

                      <div class="form-group">
                         <?php echo $this->Form->input('code_phone', array('class' => 'form-control','label'=>'Código enviado al celular','div'=>false,'placeholder'=>'Escribe el código que enviamos a tu celular','required' => false,'type'=>'number')); ?>
                      </div>
                      <?php echo $this->Form->input('code_phone_verify', array('class' => 'form-control','div'=>false,'required' => false,"type" => "hidden")); ?>
                      <div class="row">
                        <div class="col-md-6">
                          <button id="validarCodigosCrediventas" class="btn btn-success mt-2">
                            Confirmar códigos
                          </button>
                        </div>
                        <div class="col-md-6">
                          <a type="submit" class="btn btn-secondary mt-2 " id="reenvioCrediventas"> Reenviar códigos </a>
                        </div>
                      </div>


              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white text-center pt-3 pb-5">
          <div class="pb-3">
              <a href="<?php echo $this->Html->url(AuthComponent::user("id") ? ["controller"=>"credits_requests","action"=>"index"] : ["controller"=>"pages","action"=>"home"] ) ?>" class="btn btn-outline-primary btn-lg mt-2">Volver</a>
              <input type="submit" class="btn btn-success btn-lg mt-2" value="Guardar" id="guadarCrediventas" style="display:none">
          </div>
        </div>

     </div>
  </div>
  </form>
</div>

<div class="modal fade " id="panelPayments" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">
          <div class="content-tittles">
            <div class="line-tittles">|</div>
            <div>
              <h1>PLAN</h1>
              <h2>DE PAGOS</h2>
            </div>
          </div>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="planPaymentBody">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php echo $this->element("take_photo"); ?>
<?php
  echo $this->Html->script("ctrl/customers/add.js?".rand(),             array('block' => 'AppScript'));
  echo $this->Html->script("ctrl/customers/forms.js?".rand(),             array('block' => 'AppScript'));
?>

<?php echo $this->element("/modals/tyc"); ?>

<script type="text/javascript">
  var CODES_VALID = false;
</script>

<?php

echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));

?>
