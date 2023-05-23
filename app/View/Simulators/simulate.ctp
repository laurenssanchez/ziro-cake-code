<?php echo $this->Form->create('Customer', array('role' => 'form','data-parsley-validate=""',"id"=>"CustomerRegisterStepOneForm","class"=>"custom_iframe","frameBorder"=>"0")); ?>
<div id="smartwizard">
    <ul class="nav">
       <li>
           <a class="nav-link" href="#step-1">
              Simulador
           </a>
       </li>
       <li>
           <a class="nav-link" href="#step-2">
              Información de contacto
           </a>
       </li>
       <li>
           <a class="nav-link" href="#step-3">
              Fotos requeridas
           </a>
       </li>
       <li>
           <a class="nav-link" href="#step-4">
              Información adicional
           </a>
       </li>
    </ul>

    <div class="tab-content">
       <div id="step-1" class="tab-pane pb-2" role="tabpanel">
       		<div class="pt-3 pb-2 px-5">
			  <div class="content-tittles">
	            <div class="line-tittles custom_element_color">|</div>
	            <div>
	              <h1>¿CUANTO</h1>
	              <h2>DINERO NECESITAS?</h2>
	            </div>
	          </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Selecciona el monto de dinero</label>
                    <input id="moneyRange" type="range" min="<?php echo $valorMini ?>"  max="<?php echo $Valormax ?>" step="100" value="<?php echo $valorMini ?>" class="range-borrow __range custom_gradient">
                    <p class="valuemin">$ <?php echo number_format($valorMini) ?> </p>
                    <p class="valuemax pull-right">$ <?php echo number_format($Valormax) ?></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Dinero Solicitado</label>
                    <input type="number" class="form-control value-actual d-none custom_element_color_border" value="0" id="valueNumberPrice" placeholder="$200.000" name="priceValue">
                    <h2 class="value-actual form-control custom_element_color_border">$ <input type="number" id="totalSolicita2" class="custom_element_color" step="100" min="50000" ></h2>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">¿Con qué frecuencia de pagos necesitas?</label>
                    <select type="text" name="frecuency" class="form-control frecuency-payments custom_element_color_border" id="frecuency">
                    <!--<option value="">Selecciona</option>-->
                      	<option value="1">Mensual</option>
					  	<option value="3">45 días</option>
						<option value="4">60 días</option>
                      <!-- <option value="2">Quincenal</option> -->
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Cuántas cuotas?</label>
                    <select class="form-control coutas-number custom_element_color_border" id="coutas-number" name="couteValue">

                    </select>
                  </div>
                </div>
                 <div class="col-md-12">
                  <div class="cuota">
                    <p class="mb-0">Valor Cuota</p>
                    <h4><b>$ <span id="valueCalculated"></span></b></h4>
                  </div>
                </div>
              </div>

              <div class="text-center">
                <button type="button" id="btnPlaPayment" class="btn btn-primary btn-lg custom_element_bg_b">PLAN DE PAGOS</button>
              </div>
            </div>


       </div>
       <div id="step-2" class="tab-pane p-2" role="tabpanel">

          <div class="col-md-12 bg-white pd-registerblock">
	          <div class="row">
	              <div class="col-md-5 pt-2">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
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
	        <!-- Info personal -->

	        <div class="col-md-12 bg-white pd-registerblock">
	          <div class="row">
	            <div class="col-md-5 pt-3">
	              <div class="content-tittles">
	                <div class="line-tittles custom_element_color">|</div>
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
       </div>
       <div id="step-3" class="tab-pane pb-2" role="tabpanel">

       	<!--  -->

       		<div class="col-md-12 bg-white pd-registerblock">
	          <div class="row">
	              <div class="col-md-6">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
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
	                <canvas id="canvasFotoUp" style="display: none;" class="w-100 d-none"></canvas>
	                <img src="" id="imgUpFile" class="img-fluid">
	                 <?php echo $this->Form->input('document_file_up2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
	                <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn custom_element_bg_b" data-canvas="canvasFotoUp" id="fotoUpFile" data-input="CustomerDocumentFileUp2" data-img="imgUpFile">Tomar foto</button>
	              </div>
	          </div>
	        </div>

	        <div class="col-md-12 bg-white pd-registerblock">
	          <div class="row">
	              <div class="col-md-6">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
	                    <div>
	                      <h1>FOTO</h1>
	                      <h2>DOCUMENTO RESPALDO</h2>
	                    </div>
	                  </div>
	              </div>
	              <div class="col-md-6 text-center">
	                  <canvas id="canvasFotoDown" style="display: none;" class="w-100 d-none"></canvas>
	                  <img src="" class="img-fluid" id="imgDownFile">
	                   <?php echo $this->Form->input('document_file_down2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
	                  <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn custom_element_bg_b" data-canvas="canvasFotoDown" id="fotoDownFile" data-input="CustomerDocumentFileDown2" data-img="imgDownFile">Tomar foto</button>
	              </div>
	          </div>
	        </div>

	        <div class="col-md-12 bg-white pd-registerblock">
	          <div class="row">
	              <div class="col-md-6">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
	                    <div>
	                      <h1>FOTO SELFIE CON</h1>
	                      <h2>DOCUMENTO FRONTAL</h2>
	                    </div>
	                  </div>
	              </div>
	              <div class="col-md-6 text-center">
	                  <canvas id="canvasFotoUser" style="display: none;" class="w-100 d-none"></canvas>
	                  <img src="" class="img-fluid" id="imgFotoUser">
	                  <?php echo $this->Form->input('image_file2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
	                  <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn custom_element_bg_b" data-canvas="canvasFotoUser" id="fotoUserFile" data-input="CustomerImageFile2" data-img="imgFotoUser">Tomar foto</button>
	              </div>
	          </div>
	        </div>

       	<!--  -->

       </div>
       <div id="step-4" class="tab-pane pb-2" role="tabpanel">
          <!-- Adicional -->

	        <div class="col-md-12 bg-white pd-registerblock">
	          <div class="row">
	              <div class="col-md-3 pt-3">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
	                    <div>
	                      <h1 class="mb-0">INFORMACIÓN</h1>
	                      <h1>LABORAL</h1>
	                    </div>
	                  </div>
	              </div>
	              <div class="col-md-9 pt-3">
	                <div class="row">
	                    <div class="col-md-6">
	                      <div class="form-group">
	                        <?php echo $this->Form->input('profession', array('class' => 'form-control','label'=>'Profesión','div'=>false,'placeholder'=>'','required' => true,"type"=>"text")); ?>
	                      </div>
	                    </div>
	                    <div class="col-md-6">
	                      <div class="form-group">
	                        <?php echo $this->Form->input('occupation', array('class' => 'form-control','label'=>'Ocupación','div'=>false,'placeholder'=>'Selecciona una ocupación...','required' => true,"empty"=>"Selecciona la ocupación...","options" => array_combine(Configure::read("OCUPACIONES"), Configure::read("OCUPACIONES")))); ?>
	                      </div>
	                    </div>

	                  <div class="col-md-6">
	                    <div class="form-group">
	                       <?php echo $this->Form->input('monthly_income', array('class' => 'form-control','label'=>'Ingresos mensuales','div'=>false,'placeholder'=>'','required' => true,"data-parsley-validate" => "number","min"=>0,"max"=>1000000000)); ?>
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
	              <div class="col-md-3 pt-3">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
	                    <div>
	                      <h1 class="mb-0">MEDIOS DE</h1>
	                      <h1>COMUNICACIÓN</h1>
	                    </div>
	                  </div>
	              </div>
	              <div class="col-md-9 pt-3">
		                <div class="row">
		                    <div class="col-md-4">
			                  <div class="form-group">
			                    <?php echo $this->Form->input('CustomersPhone.1.phone_number', array('class' => 'form-control border-input','label'=>'Número de celular','div'=>false,"placeholder"=>"","required","value" => "",'required' => true )); ?>
			                    <?php echo $this->Form->input('CustomersPhone.1.phone_type', array('class' => 'form-control border-input',"value" => "1","type"=>"hidden",'required' => true)); ?>
			                  </div>
		                    </div>
		                    <div class="col-md-4">
			                  <div class="form-group">
			                    <?php echo $this->Form->input('email', array('class' => 'form-control','label'=>'Tu Correo Electrónico','div'=>false,'placeholder'=>'','required' => true)); ?>
			                  </div>
		                    </div>
		                    <div class="col-md-4">
			                  <div class="form-group">
			                     <?php echo $this->Form->input('password', array('class' => 'form-control','label'=>'Tu Contraseña','div'=>false,'placeholder'=>'',"data-parsley-type"=>"alphanum","data-parsley-minlength"=>"4","data-parsley-maxlength"=>"4",'required' => true)); ?>
			                  </div>
		                    </div>
		                </div>
	              </div>
	          </div>
	        </div>

	        <div class="col-md-12 bg-white pd-registerblock">
	          <div class="row">
	              <div class="col-md-3 pt-3">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
	                    <div>
	                      <h1 class="mb-0">DIRECCIONES</h1>
	                      <h1>DEL CLIENTE</h1>
	                    </div>
	                  </div>
	              </div>
	              <div class="col-md-9 pt-3">
		                <div class="row">
		                    <div class="col-md-4">
				                <div class="form-group">
				                  <?php echo $this->Form->input('CustomersAddress.address', array('class' => 'form-control border-input','label'=>'Dirección personal o laboral','div'=>false,'placeholder'=>"","required", "value" =>  "",'required' => true )); ?>
				                </div>
		                    </div>
		                    <div class="col-md-4">
				                <div class="form-group">
				                  <?php echo $this->Form->input('CustomersAddress.address_city', array('class' => 'form-control border-input','label'=>'Ciudad de residencia','div'=>false,'placeholder'=>"Ciudad","required", "value" => "",'required' => true,"options"=>Configure::read("CIUDADES") )); ?>
				                </div>
		                    </div>
		                    <div class="col-md-4">
				                <div class="form-group">
				                  <?php echo $this->Form->input('CustomersAddress.address_street', array('class' => 'form-control border-input','label'=>'Barrio de residencia','div'=>false,'placeholder'=>"","required", "value" => "",'required' => true )); ?>

				                  <?php echo $this->Form->input('CustomersAddress.address_type', array('class' => 'form-control border-input','label'=>'Ciudad','div'=>false,"required", "value" => 1,"type"=>"hidden" )); ?>
				                </div>
		                    </div>
		                </div>

	              </div>
	          </div>
	        </div>

	        <div class="col-md-12 bg-white pd-registerblock">
	          <div class="row">
	              <div class="col-md-3 pt-3">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
	                    <div>
	                      <h1 class="mb-0">REFERENCIA</h1>
	                      <h1>UNO</h1>
	                    </div>
	                  </div>
	              </div>
	              <div class="col-md-9 pt-3">
	                  <?php if (isset($customer) && !empty($customer["CustomersReference"])): ?>
	                    <?php echo $this->Form->input('CustomersReference.1.id', array('class' => 'form-control border-input',"value"=>$customer["CustomersReference"][0]["id"],"type"=>"hidden" )); ?>
	                  <?php endif ?>
	                  <div class="row">
		                    <div class="col-md-4">
			                  <div class="form-group">
			                    <?php echo $this->Form->input('CustomersReference.1.name', array('class' => 'form-control border-input','label'=>'Nombre','div'=>false,'placeholder'=>"","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["name"] : "" )); ?>
			                  </div>
		                  </div>
	                    <div class="col-md-4">
	                      <div class="form-group">
	                        <?php echo $this->Form->input('CustomersReference.1.relationship', array('class' => 'form-control border-input','label'=>'Parentesco','div'=>false,'placeholder'=>"Escribe el parentesco","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["relationship"] : "" )); ?>
	                      </div>
	                      </div>
	                      <div class="col-md-4">
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
	              <div class="col-md-3 pt-3">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
	                    <div>
	                      <h1 class="mb-0">REFERENCIA</h1>
	                      <h1>DOS</h1>
	                    </div>
	                  </div>
	              </div>
	              <div class="col-md-9 pt-3">
	                  <?php if (isset($customer) && !empty($customer["CustomersReference"])): ?>
	                    <?php echo $this->Form->input('CustomersReference.2.id', array('class' => 'form-control border-input',"value"=>$customer["CustomersReference"][0]["id"],"type"=>"hidden" )); ?>
	                  <?php endif ?>
	                  <div class="row">
		                    <div class="col-md-4">
			                  <div class="form-group">
			                    <?php echo $this->Form->input('CustomersReference.2.name', array('class' => 'form-control border-input','label'=>'Nombre','div'=>false,'placeholder'=>"Nombre completo","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["name"] : "" )); ?>
			                  </div>
		                  </div>
	                    <div class="col-md-4">
	                      <div class="form-group">
	                        <?php echo $this->Form->input('CustomersReference.2.relationship', array('class' => 'form-control border-input','label'=>'Parentesco','div'=>false,'placeholder'=>"Escribe el parentesco","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["relationship"] : "" )); ?>
	                      </div>
	                      </div>
	                      <div class="col-md-4">
	                      <div class="form-group">
	                        <?php echo $this->Form->input('CustomersReference.2.phone', array('class' => 'form-control border-input','label'=>'Número de contacto','div'=>false,'placeholder'=>"Número de Celular","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["phone"] : "" )); ?>
	                        <?php echo $this->Form->input('CustomersReference.2.reference_type', array('class' => 'form-control border-input',"value"=>"1","type"=>"hidden" )); ?>
	                      </div>
	                    </div>
	                  </div>
	              </div>
	          </div>
	        </div>

	        <div class="col-md-12 bg-white pd-registerblock">
	          <div class="row">
	              <div class="col-md-3 pt-3">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
	                    <div>
	                      <h1 class="mb-0">REFERENCIA</h1>
	                      <h1>TRES</h1>
	                    </div>
	                  </div>
	              </div>
	              <div class="col-md-9 pt-3">
	                  <?php if (isset($customer) && !empty($customer["CustomersReference"])): ?>
	                    <?php echo $this->Form->input('CustomersReference.3.id', array('class' => 'form-control border-input',"value"=>$customer["CustomersReference"][0]["id"],"type"=>"hidden" )); ?>
	                  <?php endif ?>
	                  <div class="row">
		                    <div class="col-md-4">
			                  <div class="form-group">
			                    <?php echo $this->Form->input('CustomersReference.3.name', array('class' => 'form-control border-input','label'=>'Nombre','div'=>false,'placeholder'=>"Nombre completo","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["name"] : "" )); ?>
			                  </div>
		                  </div>
	                    <div class="col-md-4">
	                      <div class="form-group">
	                        <?php echo $this->Form->input('CustomersReference.3.relationship', array('class' => 'form-control border-input','label'=>'Parentesco','div'=>false,'placeholder'=>"Escribe el parentesco","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["relationship"] : "" )); ?>
	                      </div>
	                      </div>
	                      <div class="col-md-4">
	                      <div class="form-group">
	                        <?php echo $this->Form->input('CustomersReference.3.phone', array('class' => 'form-control border-input','label'=>'Número de contacto','div'=>false,'placeholder'=>"Número de Celular","required", "value" => isset($customer) && !empty($customer["CustomersReference"]) ? $customer["CustomersReference"][0]["phone"] : "" )); ?>
	                        <?php echo $this->Form->input('CustomersReference.3.reference_type', array('class' => 'form-control border-input',"value"=>"1","type"=>"hidden" )); ?>
	                      </div>
	                    </div>
	                  </div>
	              </div>
	          </div>
	        </div>



	        <div class="col-md-12 bg-white pd-registerblock">
	          <div class="row">
	              <div class="col-md-7">
	                  <div class="content-tittles">
	                    <div class="line-tittles custom_element_color">|</div>
	                    <div>
	                      <h1 class="mb-0">AUTORIZACIÓN DE </h1>
	                      <h1>TRATAMIENTO DE DATOS</h1>
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

          <!-- End Adicional -->
       </div>
    </div>
</div>

</form>
<?php
	echo $this->Html->script("https://cdn.jsdelivr.net/npm/smartwizard@5/dist/js/jquery.smartWizard.min.js?".rand(), 						array('block' => 'AppScript'));
	echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));

?>

<div class="modal fade " id="panelPayments" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">
          <div class="content-tittles">
            <div class="line-tittles custom_element custom_element_color">|</div>
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

<?php $this->start("AppScript"); ?>
	<script>
		var btnContinue = $('<button></button>').text("Enviar solicitud").addClass('btn btn-success btn-continue d-none');
		var isdLine = "<?php echo $this->Utilidades->encrypt($simulator["Simulator"]["credits_line_id"]) ?>";
            // .on('click', function() { $('#CustomerRegisterStepOneForm').submit(); });
        IS_LAST = 0;
        toolbarSettings = { toolbarPosition: 'center', toolbarButtonPosition: 'center' };
        if (IS_LAST == 0) {
            toolbarSettings.toolbarExtraButtons = [btnContinue]
        }
		$('#smartwizard').smartWizard(
			{
				theme: 'arrows',
				enableURLhash: false,
				lang: {
	                next: "Siguiente",
	                previous: "Anterior"
	            },
	            toolbarSettings: toolbarSettings
			}
		);
		$('#smartwizard').on('showStep', function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
            console.log(stepPosition)
            if (stepPosition === 'last') {
                $('.btn-continue').removeClass('d-none');
            } else {
                $('.btn-continue').addClass('d-none');
            }
        });
	</script>
	<?php echo $this->element("home_js") ?>
<?php $this->end(); ?>
<?php echo $this->element("take_photo"); ?>
<?php
  echo $this->Html->script("ctrl/customers/simulator.js?".rand(),             array('block' => 'AppScript'));
  echo $this->Html->script("ctrl/customers/forms.js?".rand(),             array('block' => 'AppScript'));
?>

<style>
	input#totalSolicita2.custom_element_color,
	.custom_element_color{
		 color: <?php echo $simulator["Simulator"]["color_code"] ?> !important;
		}
	.custom_element_bg{
		 background-color: <?php echo $simulator["Simulator"]["color_code"] ?> !important;
		}
	.custom_element_bg_b{
		 background-color: <?php echo $simulator["Simulator"]["color_code"] ?> !important;
		 border-color: <?php echo $simulator["Simulator"]["color_code"] ?> !important;
		}
	.custom_element_color_border{
		 color: <?php echo $simulator["Simulator"]["color_code"] ?> !important;
		 border-bottom: 2px solid <?php echo $simulator["Simulator"]["color_code"] ?> !important;
	}
	.custom_gradient{
		background-image: -webkit-gradient(linear, 0% 0%, 0% 0%, color-stop(0, <?php echo $simulator["Simulator"]["color_code"] ?>), color-stop(0.37378, #ccc))  !important;
	}
	.custom_gradient.__range::-webkit-slider-thumb{
	  background: <?php echo $simulator["Simulator"]["color_code"] ?> !important;
	}
	.custom_iframe .sw>.nav .nav-link {
	    padding: 1.5rem 1rem;
	}
	.custom_iframe .sw>.nav .nav-link.active {
	    border-color: <?php echo $simulator["Simulator"]["color_code"] ?> !important;
	    background: <?php echo $simulator["Simulator"]["color_code"] ?> !important;
	}
	.custom_iframe .sw-theme-arrows>.nav .nav-link.active::after {
	    border-left-color: <?php echo $simulator["Simulator"]["color_code"] ?> !important;
	}
	.custom_iframe .sw-theme-arrows .toolbar>.btn {
	    color: #272727;
	    background-color: #dadada;
	    border: 1px solid #dadada;
	}
	.custom_iframe .sw-theme-arrows>.nav .nav-link.done {
	    color: #424242;
	    border-color: #cdcdcd;
	    background: #ccc;
	    cursor: pointer;
	}
	.custom_iframe .sw-theme-arrows>.nav .nav-link.done::after {
	    border-left-color: #ccc;
	}
	.custom_iframe #modalPhoto{
		display: none !important;
	}
	.custom_iframe #step-4 .pd-registerblock {
	    padding: 0rem 4rem;
	}
	.custom_iframe #step-4 .form-group {
	    margin-bottom: 0rem;
	}
	.custom_iframe #step-4 label {
	    margin-bottom: 0;
	}

</style>

<style>
	.tab-content{
		height: auto !important;
	}
</style>

<?php echo $this->element("/modals/tyc"); ?>

<?php echo $this->Html->css("https://cdn.jsdelivr.net/npm/smartwizard@5/dist/css/smart_wizard_all.min.css"); ?>
