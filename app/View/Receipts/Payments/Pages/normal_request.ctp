<?php echo $this->element("/menu-landings"); ?>

<div class="container-fluid p-0 bg-img-blue">
  <div class="container namelinecredit">
      <h2 class="upper"><b>Crédito tradicional</b></h2>
  </div>

      <?php echo $this->Form->create('Customer', array('role' => 'form','data-parsley-validate=""')); ?>
  <div class="container pb-3">
     <div class="row">

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>INFORMACIÓN DEL</h1>
                      <h2>PROVEEDOR</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 pt-3">
                  <div class="form-group">
                    <?php echo $this->Form->input('code', array('class' => 'form-control','label'=>'Código del proveedor','div'=>false,'placeholder'=>'Código de la tienda donde solicitas el crédito','required' => true)); ?>
                  </div>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6 pt-2">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>INFORMACIÓN</h1>
                      <h2>DE IDENTIFICACIÓN</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 pt-2">
                <p>Con esta información validamos tu identidad</p>
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
              <div class="col-md-6">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO</h1>
                      <h2>DOCUMENTO FRONTAL</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 text-center">
                <select id="select" style="display:none;">
                  <option></option>
                </select>
                <canvas id="canvasFotoUp" style="display: none;" class="w-100"></canvas>
                <img src="" id="imgUpFile" class="img-fluid">
                 <?php echo $this->Form->input('document_file_up2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
                <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn" data-canvas="canvasFotoUp" id="fotoUpFile" data-input="CustomerDocumentFileUp2" data-img="imgUpFile">Tomar foto</button>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO</h1>
                      <h2>DOCUMENTO RESPALDO</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 text-center">
                  <canvas id="canvasFotoDown" style="display: none;" class="w-100"></canvas>
                  <img src="" class="img-fluid" id="imgDownFile">
                   <?php echo $this->Form->input('document_file_down2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
                  <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn" data-canvas="canvasFotoDown" id="fotoDownFile" data-input="CustomerDocumentFileDown2" data-img="imgDownFile">Tomar foto</button>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO SELFIE CON</h1>
                      <h2>DOCUMENTO FRONTAL</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 text-center">
                  <canvas id="canvasFotoUser" style="display: none;" class="w-100"></canvas>
                  <img src="" class="img-fluid" id="imgFotoUser">
                  <?php echo $this->Form->input('image_file2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
                  <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn" data-canvas="canvasFotoUser" id="fotoUserFile" data-input="CustomerImageFile2" data-img="imgFotoUser">Tomar foto</button>
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
                <p>Diligencia tus datos como aparecen en tu cédula</p>
                   <div class="form-group">
                      <?php echo $this->Form->input('name', array('class' => 'form-control','label'=>'Nombres','div'=>false,'placeholder'=>'Nombres como están en tu cédula','required' => true)); ?>
                  </div>
                  <div class="form-group">
                      <?php echo $this->Form->input('last_name', array('class' => 'form-control','label'=>'Apellidos','div'=>false,'placeholder'=>'Apellidos como están en tu cédula','required' => true)); ?>
                  </div>
                  <div class="form-group">
                    <label for="">Tu fecha de nacimiento</label>
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
                  <div class="form-group">
                    <?php echo $this->Form->input('profession', array('class' => 'form-control','label'=>'Tu profesión','div'=>false,'placeholder'=>'Escibre tu profesión actual','required' => true,"type"=>"text")); ?>
                  </div>

                  <div class="form-group">
                    <?php echo $this->Form->input('occupation', array('class' => 'form-control','label'=>'Tu ocupación','div'=>false,'placeholder'=>'Selecciona una ocupación...','required' => true,"empty"=>"Selecciona tu ocupación...","options" => array_combine(Configure::read("OCUPACIONES"), Configure::read("OCUPACIONES")))); ?>
                  </div>

                <div class="form-group">
                   <?php echo $this->Form->input('monthly_income', array('class' => 'form-control','label'=>'Ingresos mensuales','div'=>false,'placeholder'=>'¿Cuánto dinero ganas al mes?','required' => true,"data-parsley-validate" => "number","min"=>0,"max"=>1000000000)); ?>
                </div>
                <div class="form-group">
                   <?php echo $this->Form->input('type_contract', array('class' => 'form-control','label'=>'Tipo de contrato','div'=>false,'required' => true,"empty"=>"Selecciona tu profesión...","options" => Configure::read("TIPO_DE_CONTRATO") )); ?>
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
                      <h1>TUS MEDIOS</h1>
                      <h2>DE COMUNICACIÓN</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-3">
                  <div class="form-group">
                    <?php echo $this->Form->input('CustomersPhone.1.phone_number', array('class' => 'form-control border-input','label'=>'Número de celular','div'=>false,"placeholder"=>"Ingresa tu número de celular","required","value" => "",'required' => true )); ?>
                    <?php echo $this->Form->input('CustomersPhone.1.phone_type', array('class' => 'form-control border-input',"value" => "1","type"=>"hidden",'required' => true)); ?>
                  </div>
                  <div>
                    <p class="othernum">Quiero agregar otro número de celular (opcional)</p>
                    <div class="form-group num2 controloff">
                        <?php echo $this->Form->input('CustomersPhone.2.phone_number', array('class' => 'form-control border-input','label'=>'Número de celular','div'=>false,"placeholder"=>"Ingresa tu número de celular","required" => false, "value" =>  "",'required' => false )); ?>
                        <?php echo $this->Form->input('CustomersPhone.2.phone_type', array('class' => 'form-control border-input',"value" => "1","type"=>"hidden",'required' => false)); ?>
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
                <div class="form-group">
                  <?php echo $this->Form->input('CustomersAddress.address', array('class' => 'form-control border-input','label'=>'Tu dirección personal o laboral','div'=>false,'placeholder'=>"Como aparece en tu cuenta de servicios","required", "value" =>  "",'required' => true )); ?>
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
                        <label for="">¿Cuánto dinero necesitas?</label>
                        <input id="moneyRange" type="range" min="50000" max="750000" step="10000" value="50000" class="range-borrow __range">
                        <p class="valuemin">$ 50.000</p>
                        <p class="valuemax pull-right">$ 750.000</p>
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
                          <?php for($i = 1; $i <= 4; $i++ ):  ?>
                            <option data-mes="<?php echo $i ?>" data-quince="<?php echo $i*2 ?>" value="<?php echo $i ?>"><?php echo $i ?></option>
                          <?php endfor; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="solicitado">
                        <p>Solicitado</p>
                        <input type="number" class="form-control value-actual d-none" value="0" id="valueNumberPrice" placeholder="$200.000" name="priceValue">
                        <h4>$ <span id="totalSolicita"></span></h4>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="cuota">
                        <p>Valor Cuota</p>
                        <h4>$ <span id="valueCalculated"></span></h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="text-center mt-3">
                      <button type="button" id="btnPlaPayment" class="btn btn-primary btn-lg">PLAN DE PAGOS</button>
                  </div>
                </div>

              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
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
                      <?php echo $this->Form->input('tyc', array('class' => 'form-check-input','label'=>false,'div'=>false,"required" => true,"type"=>"checkbox")); ?>
                      <label class="form-check-label big-label" for="">Si, acepto la Autorización de Tratamiento de Datos</label>
                    </div>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white text-center pt-3 pb-3">
          <div class="pb-3">
              <a href="<?php echo $this->Html->url(AuthComponent::user("id") ? ["controller"=>"credits_requests","action"=>"index"] : ["controller"=>"pages","action"=>"home"] ) ?>" class="btn btn-outline-primary btn-lg mt-2">Volver</a>
              <input type="submit" class="btn btn-primary btn-lg mt-2" value="Guardar">
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


<?php

echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));

?>
